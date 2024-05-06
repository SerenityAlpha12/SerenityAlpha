<?php
session_start();

require_once 'vendor/autoload.php'; // Assuming you are using Twig for templating
require 'db/config.php'; // Your database configuration file

$loader = new \Twig\Loader\FilesystemLoader('Templates'); // Assuming your templates are stored in the 'Templates' directory
$twig = new \Twig\Environment($loader);

// Check if the user is already logged in, redirect to dashboard if true
if (isset($_SESSION['user_id'])) {
    header("Location: userdashboard.php");
    exit();
}

// Initialize an array to store error messages
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
    exit("Email not provided in the URL");
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve email and password from the form
    $password = ($_POST["user-password"]); // Assuming your input name is 'user-password'
    $confirmPassword = $_POST["confirm-password"]; // Assuming your input name is 'confirm-password'

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

// Load and display the main template (assuming 'newpassword.html' is your template file)
$template = $twig->load('newpassword.html');
echo $template->render([
    'email' => $email,
    'errors' => $errors,
]);
?>
