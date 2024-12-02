<?php
// Database configuration
$host = 'localhost'; // Database host
$db = 'trend_company'; // Database name
$user = 'root'; // Database username
$password = ''; // Database password

// Create a connection
$conn = new mysqli($host, $user, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Basic validation
    if (empty($name) || empty($email) || empty($password)) {
        echo "All fields are required!";
        exit;
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $hashedPassword);

    if ($stmt->execute()) {
        echo "Registration successful!";
        header("Location: /login"); // Redirect to login page
        exit;
    } else {
        if ($stmt->errno === 1062) {
            echo "Email already exists!";
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    $stmt->close();
}

$conn->close();
?>
