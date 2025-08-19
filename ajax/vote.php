<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../inc/mailer.php';

function out($data, $status = 200) {
    http_response_code($status);
    echo json_encode($data);
    exit;
}

// Validate CSRF and input
$csrf = $_POST['csrf'] ?? '';
if (!hash_equals($_SESSION['csrf'] ?? '', $csrf)) out(['success'=>false,'error'=>'Invalid CSRF'], 400);

$nomineeId = (int)($_POST['nominee_id'] ?? 0);
$token = trim((string)($_POST['subscriber_token'] ?? ''));
if (!$nomineeId || $token === '') out(['success'=>false,'error'=>'Missing data'], 400);

$ip = $_SERVER['REMOTE_ADDR'] ?? '';
$ua = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 200);

// get nominee name for response/email
$stmtNm = $conn->prepare("SELECT id, name FROM nominees WHERE id = ? LIMIT 1");
if (!$stmtNm) out(['success'=>false,'error'=>'DB error (nominee lookup)'], 500);
$stmtNm->bind_param('i', $nomineeId);
$stmtNm->execute();
$nmRes = $stmtNm->get_result()->fetch_assoc();
$stmtNm->close();
if (!$nmRes) out(['success'=>false,'error'=>'Nominee not found'], 400);
$nomineeName = $nmRes['name'];

// Ensure required tables exist before any INSERTs
$createVotes = "CREATE TABLE IF NOT EXISTS votes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nominee_id INT NOT NULL,
  subscriber_id INT DEFAULT NULL,
  subscriber_token VARCHAR(128) NOT NULL,
  share_token VARCHAR(128) DEFAULT NULL,
  ip VARBINARY(16) DEFAULT NULL,
  user_agent VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_vote (subscriber_token),
  KEY idx_nominee (nominee_id),
  KEY idx_subtok (subscriber_token)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
if (!$conn->query($createVotes)) out(['success'=>false,'error'=>'DB error (votes table)'], 500);

$createShares = "CREATE TABLE IF NOT EXISTS shares (
  id INT AUTO_INCREMENT PRIMARY KEY,
  share_token VARCHAR(128) NOT NULL UNIQUE,
  nominee_id INT NOT NULL,
  subscriber_id INT DEFAULT NULL,
  channel VARCHAR(50) DEFAULT '',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY idx_share_nom (nominee_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
if (!$conn->query($createShares)) out(['success'=>false,'error'=>'DB error (shares table)'], 500);

// transaction
$conn->begin_transaction();
try {
    // find subscriber
    $stmt = $conn->prepare("SELECT id, email FROM subscribers WHERE token = ? LIMIT 1");
    if (!$stmt) throw new Exception('DB error');
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $res = $stmt->get_result();
    $sub = $res->fetch_assoc();
    $stmt->close();
    if (!$sub) throw new Exception('Subscriber not found — please subscribe first');

    $subscriber_id = (int)$sub['id'];

    // Prevent any previous vote by this subscriber (global single vote)
    $stmt = $conn->prepare("SELECT id, nominee_id FROM votes WHERE subscriber_token = ? LIMIT 1");
    if (!$stmt) throw new Exception('DB error');
    $stmt->bind_param('s', $token);
    $stmt->execute();
    if ($stmt->get_result()->fetch_assoc()) {
        $stmt->close();
        throw new Exception('You have already voted');
    }
    $stmt->close();

    // generate share token
    try { $share_token = bin2hex(random_bytes(12)); } catch (Exception $e) { $share_token = bin2hex(openssl_random_pseudo_bytes(12)); }

    // Insert vote (use the incoming $token as subscriber_token)
    $stmt = $conn->prepare("INSERT INTO votes (nominee_id, subscriber_id, subscriber_token, share_token, ip, user_agent) VALUES (?, ?, ?, ?, INET6_ATON(?), ?)");
    if (!$stmt) throw new Exception('DB error');
    $ipVal = $ip ?: null;
    $userAgent = substr($ua, 0, 255);
    $stmt->bind_param('iissss', $nomineeId, $subscriber_id, $token, $share_token, $ipVal, $userAgent);
    if (!$stmt->execute()) {
        // duplicate key -> already voted (DB-level protection)
        if ($conn->errno === 1062) {
            $stmt->close();
            throw new Exception('You have already voted');
        }
        $stmt->close();
        throw new Exception('Database error saving vote');
    }
    $stmt->close();

    // Increment cached counter (best-effort)
    $stmt = $conn->prepare("UPDATE nominees SET votes_count = IFNULL(votes_count,0) + 1 WHERE id = ?");
    if ($stmt) { $stmt->bind_param('i', $nomineeId); $stmt->execute(); $stmt->close(); }

    // create share record
    $stmt = $conn->prepare("INSERT INTO shares (share_token, nominee_id, subscriber_id, channel) VALUES (?, ?, ?, '')");
    if ($stmt) {
        $stmt->bind_param('sii', $share_token, $nomineeId, $subscriber_id);
        $stmt->execute();
        $stmt->close();
    }

    $conn->commit();

    // send confirmation / congratulation email (best-effort)
    $to = $sub['email'];
    $subject = "Your vote is recorded — Saadan Film";
    $plain = "Congratulations!\n\nYour vote for \"{$nomineeName}\" has been recorded. Thank you for participating.\n\nShare your vote: ".
             (isset($_SERVER['HTTP_HOST']) ? ( (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] ) : '') .
             "/vote_for_award.php?shared={$share_token}\n\nSaadan Film";
    $html = "<p>Congratulations!</p><p>Your vote for <strong>" . htmlspecialchars($nomineeName, ENT_QUOTES, 'UTF-8') . "</strong> has been recorded. Thank you for participating.</p>" .
            "<p>Share your vote: <a href=\"/vote_for_award.php?shared={$share_token}\">Share</a></p>";

    if (function_exists('sf_send_mail')) {
        // sf_send_mail($to, $subject, $html) expected to handle headers/html
        @sf_send_mail($to, $subject, $html);
    } else {
        $headers = "From: Saadan Film <no-reply@" . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ">\r\n";
        $headers .= "Reply-To: no-reply@" . ($_SERVER['HTTP_HOST'] ?? 'localhost') . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        @mail($to, $subject, $html, $headers);
    }

    // return success + friendly message
    out([
        'success' => true,
        'share_token' => $share_token,
        'message' => "Congratulations — your vote for \"{$nomineeName}\" is recorded. A confirmation email has been sent."
    ]);
} catch (Exception $e) {
    $conn->rollback();
    out(['success'=>false,'error'=>$e->getMessage()], 400);
}
?>