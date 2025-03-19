<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";

// Create connection without specifying a database
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS `resister-user`";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully or already exists<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Switch to the created database
$conn->select_db("resister-user");

// Create users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Users table created successfully or already exists<br>";
} else {
    echo "Error creating users table: " . $conn->error . "<br>";
}

// Create employees table
$sql = "CREATE TABLE IF NOT EXISTS employees (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    employee_id VARCHAR(50) NOT NULL UNIQUE,
    department VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    shift_time VARCHAR(50) NOT NULL,
    document VARCHAR(255) NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Employees table created successfully or already exists<br>";
} else {
    echo "Error creating employees table: " . $conn->error . "<br>";
}

// Insert a test user
$hashedPassword = password_hash("password123", PASSWORD_DEFAULT);
$sql = "INSERT IGNORE INTO users (fullname, email, password) VALUES ('Test User', 'test@example.com', '$hashedPassword')";

if ($conn->query($sql) === TRUE) {
    echo "Test user created or already exists<br>";
    
    // Verify the test user exists
    $verifySql = "SELECT * FROM users WHERE email = 'test@example.com'";
    $result = $conn->query($verifySql);
    if ($result->num_rows > 0) {
        echo "Test user verified in database<br>";
    } else {
        echo "Warning: Test user not found in database after creation<br>";
    }
} else {
    echo "Error creating test user: " . $conn->error . "<br>";
}

// Show all users in the database
echo "<br>Current users in database:<br>";
$usersSql = "SELECT id, fullname, email FROM users";
$usersResult = $conn->query($usersSql);
if ($usersResult->num_rows > 0) {
    while($row = $usersResult->fetch_assoc()) {
        echo "ID: " . $row["id"] . " - Name: " . $row["fullname"] . " - Email: " . $row["email"] . "<br>";
    }
} else {
    echo "No users found in database<br>";
}

echo "<br>Database setup completed.<br>";
echo "<a href='login/login.php'>Go to login page</a><br>";
echo "<a href='merge_databases.php'>Merge databases (if you have existing data in register-user)</a>";

$conn->close();
?> 