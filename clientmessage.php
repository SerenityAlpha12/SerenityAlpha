<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Path to PHPMailer autoload file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $firstName = $_POST['first-name'];
    $lastName = $_POST['last-name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];

    // Email address where you want to receive the message
    $to = "serenityalpha2024@gmail.com";

    // Subject of the email
    $subject = "New message from $firstName $lastName";

    // Compose the email message
    $email_message = "First Name: $firstName\n";
    $email_message .= "Last Name: $lastName\n";
    $email_message .= "Email: $email\n";
    $email_message .= "Phone: $phone\n";
    $email_message .= "Message:\n$message\n";

    // Send email using PHPMailer
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;
        $mail->Username = 'serenityalpha2024@gmail.com'; // Your SMTP username
        $mail->Password = ''; // Your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;

        //Recipients
        $mail->setFrom($email);
        $mail->addAddress($to);

        // Content
        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body = $email_message;

        $mail->send();
        echo "Your message has been sent successfully.";
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    // If it's not a POST request, redirect or handle the situation accordingly
    echo "Method not allowed.";
}
?>
