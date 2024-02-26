<?php
require_once 'vendor/autoload.php';
require 'db/config.php';

$loader = new \Twig\Loader\FilesystemLoader('Templates');
$twig = new \Twig\Environment($loader);

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

    // Validation for Email Address
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Please provide a valid email address.";
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
                // Redirect to index.php upon successful login
                header("Location: index.php");
                exit();
            } else {
                $errors['login'] = "Invalid email or password.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

// Close the connection
$conn = null;

// Load and display the main template
$template = $twig->load('login.html');
echo $template->render([
    'errors' => $errors, // Pass errors to the template
    'values' => [
        'email' => isset($email) ? $email : '',
    ],
]);
?>
