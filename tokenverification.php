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

// Check if the code is provided in the query parameter
if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // Validate the code
    if (empty($code)) {
        $errors['code'] = "Verification code cannot be blank.";
    } else {
        $sql = "SELECT * FROM users WHERE reset_token = :code AND reset_expiry > NOW()";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':code', $code);

        try {
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Code is correct, show success message and redirect
                $redirectUrl = "newpassword.php?email=" . urlencode($user['email']); // Pass email as a query parameter
                header("Location: $redirectUrl");
                exit;
            } else {
                // Code is incorrect or expired, show error message
                $errors['code'] = "Invalid or expired code.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

// Load and display the main template
$template = $twig->load('tokenverification.html');
echo $template->render([
    'errors' => $errors,
]);
?>
