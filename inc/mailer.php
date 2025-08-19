<?php
// mailer.php - Handles email sending functionality

function sf_send_mail($to, $subject, $htmlBody, $from = 'no-reply@saadanfilm.com') {
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: Saadan Film <{$from}>\r\n";
    // NOTE: use PHPMailer in production; this wrapper uses mail()
    return mail($to, $subject, $htmlBody, $headers);
}

function sendConfirmationEmail($to, $nomineeName) {
    $subject = "Thank You for Voting!";
    $message = "Dear Voter,<br><br>Thank you for casting your vote for " . $nomineeName . " in the Saadan Film Company's Best Actor/Actress of the Year award!<br><br>Best regards,<br>Saadan Film Company";
    $headers = "From: no-reply@saadanfilms.com\r\n";

    return sf_send_mail($to, $subject, $message, $headers);
}

function sendAppreciationEmail($to, $nomineeName) {
    $subject = "Thank You for Sharing!";
    $message = "Dear Voter,<br><br>Thank you for sharing your vote for " . $nomineeName . " in the Saadan Film Company's Best Actor/Actress of the Year award! Your support means a lot to us.<br><br>Best regards,<br>Saadan Film Company";
    $headers = "From: no-reply@saadanfilms.com\r\n";

    return sf_send_mail($to, $subject, $message, $headers);
}

function sendSubscriptionEmail($to) {
    $subject = "Welcome to Saadan Film Company!";
    $message = "Dear Subscriber,<br><br>Thank you for subscribing to our newsletter! You will now receive updates about our films, nominees, and events.<br><br>Best regards,<br>Saadan Film Company";
    $headers = "From: no-reply@saadanfilms.com\r\n";

    return sf_send_mail($to, $subject, $message, $headers);
}
?>