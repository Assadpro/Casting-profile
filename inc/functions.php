<?php
// filepath: c:\xampp\htdocs\saadanfilm\inc\functions.php

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function sendConfirmationEmail($to, $nomineeName) {
    $subject = "Thank You for Voting!";
    $message = "Thank you for voting for " . $nomineeName . " in the Saadan Film Company's Best Actor/Actress of the Year!";
    $headers = "From: no-reply@saadanfilms.com\r\n";
    mail($to, $subject, $message, $headers);
}

function sendAppreciationEmail($to, $nomineeName) {
    $subject = "Thank You for Sharing!";
    $message = "Thank you for sharing your vote for " . $nomineeName . "! Your support means a lot to us.";
    $headers = "From: no-reply@saadanfilms.com\r\n";
    mail($to, $subject, $message, $headers);
}

function subscribeUser($email) {
    // Logic to save the email to the subscription list in the database
    // This is a placeholder function and should be implemented with actual database logic
}

function getNominees($dbConnection) {
    $query = "SELECT * FROM nominees ORDER BY votes DESC";
    $result = $dbConnection->query($query);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function countVotes($nomineeId, $dbConnection) {
    $query = "UPDATE nominees SET votes = votes + 1 WHERE id = ?";
    $stmt = $dbConnection->prepare($query);
    $stmt->bind_param("i", $nomineeId);
    $stmt->execute();
    $stmt->close();
}
?>