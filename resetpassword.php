<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: userdashboard.php");
    exit();
}


require_once 'vendor/autoload.php';
require 'db/config.php';

$loader = new \Twig\Loader\FilesystemLoader('Templates');
$twig = new \Twig\Environment($loader);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Define an array to store error messages
$errors = [];

// Database connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Password reset
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extracting form data
    $email = $_POST["email"];

    // Validation for Email Address
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Please provide a valid email address.";
    }

    // If there are no errors, proceed with password reset
    if (empty($errors)) {
        // Check if the email exists in the 'users' table
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);

        try {
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Generate a random 6-digit token
                $resetToken = sprintf("%06d", mt_rand(1, 999999));
                $resetExpiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

                // Store the token and expiry in the database for verification
                $updateTokenSQL = "UPDATE users SET reset_token = :reset_token, reset_expiry = :reset_expiry, created_at = NOW() WHERE email = :email";
                $updateTokenStmt = $conn->prepare($updateTokenSQL);
                $updateTokenStmt->bindParam(':reset_token', $resetToken);
                $updateTokenStmt->bindParam(':reset_expiry', $resetExpiry);
                $updateTokenStmt->bindParam(':email', $email);
                $updateTokenStmt->execute();



                $mail = new PHPMailer(true);

                try {
                    //Server settings
                    $mail->SMTPDebug = SMTP::DEBUG_OFF; // Enable verbose debug output
                    $mail->isSMTP(); // Set mailer to use SMTP
                    $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'serenityalpha2024@gmail.com'; // Replace with your SMTP username
                    $mail->Password   = 'qrpjiunrqhrohvpj'; // Replace with your SMTP password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption, `ssl` also accepted
                    $mail->Port       = 587; // You may use 465 instead for 'ssl'

                    //Recipients
                    $mail->setFrom('serenityalpha2024@gmail.com', 'admin'); // Replace with your name and email address
                    $mail->addAddress($email); // User's email

                    // Content
                    $mail->isHTML(true); // Set email format to HTML
                    $mail->Subject = 'Password Reset Token';
                    $mail->Body    = 'Your password reset token is: ' . $resetToken;

                    $mail->send();
                    echo 'Password reset email sent successfully.';
                } catch (Exception $e) {
                    echo "Error sending email: {$mail->ErrorInfo}";
                }

                // Redirect to tokenverification.php
                header("Location: tokenverification.php?email=" . urlencode($user['email']));
                exit;
            } else {
                $errors['email'] = "Email not found. Please enter a valid email address.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

// After setting the CSRF token in your PHP script
$csrfToken = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrfToken;

// Load and display the main template
$template = $twig->load('resetpassword.html');
echo $template->render([
    'errors' => $errors,
    'values' => [
        'email' => isset($email) ? $email : '',
    ],
    'csrf_token' => $csrfToken, // Pass CSRF token to the template
]);
?>
