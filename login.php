<?php
session_start();

require_once 'vendor/autoload.php';
require 'db/config.php';

$loader = new \Twig\Loader\FilesystemLoader('Templates');
$twig = new \Twig\Environment($loader);

     // If the user is already logged in, redirect to userdashboard.php
     if (isset($_SESSION['user_id'])) {
        header("Location: userdashboard.php");
        exit();
    }


// Handle AJAX requests here (if needed)

// Define an array to store error messages
$errors = [];

// Database connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
// User login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extracting form data
    $email = $_POST["email"];
    $password = $_POST["password"];
    $rememberMe = isset($_POST["rememberMe"]) && $_POST["rememberMe"] == 'on';

    // Validation for Email Address
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Please provide a valid email address.";
    }

    // Validation for Password
    if (empty($password)) {
        $errors['password'] = "Password is required.";
    }

   

    // If there are no errors, proceed with user authentication
    if (empty($errors)) {
        // Check user credentials in the 'users' table
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);

        try {
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id']; // Assuming your user table has an 'id' column
                $_SESSION['user_first_name'] = $user['firstname'];
                $_SESSION['user_last_name'] = $user['lastname'];
                $_SESSION['user_age'] = $user['age']; // Assuming 'age' is the column name in your database
                $_SESSION['user_weight'] = $user['weight']; // Assuming 'age' is the column name in your database
                $_SESSION['user_height'] = $user['height']; // Assuming 'age' is the column name in your database


                // Set cookie if "Remember Me" is checked
                if ($rememberMe) {
                    setcookie('remembered_email', $email, time() + (30 * 24 * 60 * 60)); // 30 days expiration
                } else {
                    // Clear the cookie if "Remember Me" is not checked
                    setcookie('remembered_email', '', time() - 3600);
                }

                // Redirect to index.php upon successful login
                header("Location: userdashboard.php");
                exit();
            } else {
                $errors['login'] = "Invalid email or password.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

// Check if the cookie exists and pre-fill the email input field
$rememberedEmail = isset($_COOKIE['remembered_email']) ? $_COOKIE['remembered_email'] : '';

// Load and display the main template
$template = $twig->load('login.html');
echo $template->render([
    'errors' => $errors, // Pass errors to the template
    'values' => [
        'email' => isset($email) ? $email : $rememberedEmail,
    ],
]);
