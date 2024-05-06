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

// User insertion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extracting form data
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $phonenumber = $_POST["phonenumber"];
    $email = $_POST["email"];
    $age = $_POST["age"]; // Extract age from the form
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hashing the password
    $confirmPassword = $_POST["confirmpassword"];

    // Validation for First Name
    if (empty($firstname)) {
        $errors['firstname'] = "First Name is required.";
    }

    // Validation for Last Name (similar validation for other fields)
    if (empty($lastname)) {
        $errors['lastname'] = "Last Name is required.";
    }
        // Validation for Age
        $age = $_POST["age"];
        if (empty($age)) {
            $errors['age'] = "Age is required.";
        } elseif (!is_numeric($age) || $age <= 0) {
            $errors['age'] = "Please enter a valid age.";
        }

       // Validation for Phone Number
       if (empty($phonenumber)) {
        $errors['phonenumber'] = "Phone Number is required.";
    } else {
        // Check if the phone number already exists
        $checkPhoneSql = "SELECT COUNT(*) FROM users WHERE phonenumber = :phonenumber";
        $checkPhoneStmt = $conn->prepare($checkPhoneSql);
        $checkPhoneStmt->bindParam(':phonenumber', $phonenumber);
        $checkPhoneStmt->execute();

        if ($checkPhoneStmt->fetchColumn() > 0) {
            $errors['phonenumber'] = "Phone Number already exists. Please choose a different one.";
        }
    }

     // Validation for Email Address (similar validation for other fields)
     if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Please provide a valid email address.";
    } else {
        // Check if the email already exists
        $checkEmailSql = "SELECT COUNT(*) FROM users WHERE email = :email";
        $checkEmailStmt = $conn->prepare($checkEmailSql);
        $checkEmailStmt->bindParam(':email', $email);
        $checkEmailStmt->execute();

        if ($checkEmailStmt->fetchColumn() > 0) {
            $errors['email'] = "Email already exists. Please choose a different one.";
        }
    }

    // Validation for Password and Confirm Password
    if (empty($password)) {
        $errors['password'] = "Password is required.";
    } elseif (strlen($_POST["password"]) < 6) {
        $errors['password'] = "Password must be at least 6 characters long.";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', $_POST["password"])) {
        $errors['password'] = "Password must contain at least one lowercase letter, one uppercase letter, and one digit.";
    }elseif (!password_verify($confirmPassword, $password)) {
        $errors['password'] = "Passwords do not match.";
    }

  
    

    // If there are no errors, proceed with user insertion
    if (empty($errors)) {
        // SQL query to insert user data into the 'users' table
        $sql = "INSERT INTO users (firstname, lastname, phonenumber, email, password, age) 
                VALUES (:firstname, :lastname, :phonenumber, :email, :password, :age)";
    
        $stmt = $conn->prepare($sql);
    
        // Binding parameters including age
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':phonenumber', $phonenumber);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':age', $age); // Bind age parameter
    

        // Execute the statement
        try {
            $stmt->execute();
            echo "User registered successfully!";
            
            // Redirect to login.php after successful signup
            header("Location: login.php");
            exit(); // Ensure that no further code is executed after the redirect
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}


// Close the connection
$conn = null;

// Load and display the main template
$template = $twig->load('signup.html');
echo $template->render([
    'errors' => $errors, // Pass errors to the template
    'values' => [
        'firstname' => isset($firstname) ? $firstname : '',
        'lastname' => isset($lastname) ? $lastname : '',
        'phonenumber' => isset($phonenumber) ? $phonenumber : '',
        'email' => isset($email) ? $email : '',
        'age' => isset($age) ? $age : '',
    ],
]);

?>
