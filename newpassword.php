<?php
session_start();

require_once 'vendor/autoload.php';
require 'db/config.php';

$loader = new \Twig\Loader\FilesystemLoader('Templates');
$twig = new \Twig\Environment($loader);

if (isset($_SESSION['user_id'])) {
    header("Location: userdashboard.php");
    exit();
}


// Define an array to store error messages
$errors = [];

// Database connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$email = isset($_GET['email']) ? $_GET['email'] : null;

if ($email === null) {
    // Handle the case where email is not provided in the URL
    // You may want to redirect the user to an error page or take appropriate action
    exit("Email not provided in the URL");
}


// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve email and password from the form
    $email = isset($_GET['email']) ? $_GET['email'] : '';
    $password = ($_POST["password"]); // Hashing the password
    $confirmPassword = $_POST["confirmpassword"];

    // Validation for Password and Confirm Password
    if (empty($password)) {
        $errors['password'] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors['password'] = "Password must be at least 6 characters long.";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', $password)) {
        $errors['password'] = "Password must contain at least one lowercase letter, one uppercase letter, and one digit.";
    } elseif ($password !== $confirmPassword) {
        $errors['password'] = "Passwords do not match.";
    }

     // Check if the new password is the same as the old password
     $sqlCheckPassword = "SELECT password FROM users WHERE email = :email";
     $stmtCheckPassword = $conn->prepare($sqlCheckPassword);
     $stmtCheckPassword->bindParam(':email', $email);
     $stmtCheckPassword->execute();
     $oldPasswordHash = $stmtCheckPassword->fetchColumn();
 
     if (password_verify($password, $oldPasswordHash)) {
         $errors['password'] = "Sorry, you can't use the old password as the new password.";
     }
 
    
    // If no errors, update the password in the database
    if (empty($errors)) {
        // Hash the password before storing it in the database
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Update the password in the database
        $sql = "UPDATE users SET password = :password WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':email', $email);

        try {
            $stmt->execute();

            // Redirect to a success page or login page
            header("Location: login.php");
            exit;
        } catch (PDOException $e) {
            $errors['general'] = "Error updating password: " . $e->getMessage();
        }
    }
}

// Load and display the main template
$template = $twig->load('newpassword.html');
echo $template->render([
    'email' => $email,
    'errors' => $errors,
]);
?>
