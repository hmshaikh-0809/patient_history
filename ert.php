<?php
// submit_patient_info.php

// Display all errors for debugging (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database credentials
$host = 'localhost';
$db   = 'hospital';
$user = 'root'; // Default username for XAMPP/WAMP
$pass = '';     // Default password for XAMPP/WAMP is empty
$charset = 'utf8mb4';

// Data Source Name
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// PDO options for better error handling and security
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Enable exceptions
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Set default fetch mode
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Disable emulation
];

try {
    // Create PDO instance
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Handle connection errors
    die("Database connection failed: " . $e->getMessage());
}

// Check if form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $medical_history = $_POST['history'];

    // Prepare the SQL statement with placeholders
    $sql = "INSERT INTO `patients` (`name`, `address`, `phone`, `email`, `medical_history`) 
            VALUES (:name, :address, :phone, :email, :medical_history)";

    try {
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':medical_history', $medical_history);

        // Execute the statement
        $stmt->execute();

        // Success message
        echo "<h3>Patient information submitted successfully.</h3>";
        echo "<a href='index.html'>Submit Another Entry</a>";

    } catch (PDOException $e) {
        // Handle SQL errors
        echo "<h3>There was an error submitting the information.</h3>";
        echo "<p>Error: " . $e->getMessage() . "</p>";
        echo "<a href='index.html'>Go Back to the Form</a>";
    }

} else {
    // If the request method is not POST, deny access
    echo "<h3>Invalid request method.</h3>";
    echo "<a href='index.html'>Go Back to the Form</a>";
}
?>
