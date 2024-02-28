<?php
session_start();

require_once 'vendor/autoload.php';
require 'db/config.php';




$loader = new \Twig\Loader\FilesystemLoader('Templates');
$twig = new \Twig\Environment($loader);




// Handle AJAX requests here (if needed)

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}


// Fetch user data from the session or database, depending on your implementation
$userFirstName = $_SESSION['user_first_name'];
$userLastName = $_SESSION['user_last_name'];
$userAge = $_SESSION['user_age'];
$userWeight = $_SESSION['user_weight'];
$userHeight = $_SESSION['user_height'];

// Fetch additional user data from the database if needed

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update the user's height and weight in the session
    $_SESSION['user_weight'] = $_POST['user-weight'];
    $_SESSION['user_height'] = $_POST['user-height'];

    // Update the user's height and weight in the database (you may need to modify this based on your database schema)
    $userId = $_SESSION['user_id'];
    $newWeight = $_POST['user-weight'];
    $newHeight = $_POST['user-height'];

    // Perform the database update (replace this with your actual database update query)
    $updateQuery = "UPDATE users SET weight = :weight, height = :height WHERE id = :id";
    try {
        // Perform the database update
        $stmt = $conn->prepare($updateQuery);
        $stmt->bindParam(':weight', $newWeight);
        $stmt->bindParam(':height', $newHeight);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
    

    // Redirect to the profile page
    header("Location: userdashboard.php");
    exit();
 }

// Load and display the main template
$template = $twig->load('userdashboard.html');
echo $template->render([
    'userFirstName' => $userFirstName,
    'userLastName' => $userLastName,
    'userAge' => $userAge,
    'userHeight' => $userHeight,
    'userWeight' => $userWeight,
]);
?>
