<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "serenity_alpha";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch appointments for the selected date
    $day = $_GET['day'];
    $month = $_GET['month'];
    $year = $_GET['year'];
    $selectedDate = sprintf('%04d-%02d-%02d', $year, $month, $day); // Ensure proper formatting

    $stmt = $conn->prepare("SELECT * FROM Appointments WHERE AppointmentDate = :selectedDate");
    $stmt->bindParam(':selectedDate', $selectedDate);
    $stmt->execute();
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($appointments);
} catch (PDOException $e) {
    $error_message = "ERROR: Could not able to execute " . $e->getMessage();
    echo json_encode(array('error' => $error_message));
}
?>
