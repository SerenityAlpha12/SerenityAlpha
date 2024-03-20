<?php

require_once 'vendor/autoload.php';
require 'db/config.php';

$loader = new \Twig\Loader\FilesystemLoader('Templates');
$twig = new \Twig\Environment($loader);


// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Retrieve form data
        $date = $_POST['appointmentDate'];
        $time = $_POST['appointmentTime'];
        $reason = $_POST['reason'];

        // Insert data into the database
        $stmt = $conn->prepare("INSERT INTO Appointments (AppointmentDate, AppointmentTime, Reason) VALUES (:date, :time, :reason)");
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':time', $time);
        $stmt->bindParam(':reason', $reason);

        $stmt->execute();
        $success_message = "Appointment created successfully";
    } catch (PDOException $e) {
        $error_message = "ERROR: Could not able to execute " . $e->getMessage();
    }
}


// Load and display the main template
$template = $twig->load('dashboard.html');
echo $template->render([
  
]);
?>