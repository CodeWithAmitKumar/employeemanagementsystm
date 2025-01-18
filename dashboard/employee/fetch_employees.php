<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "resister-user";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch employees
$sql = "SELECT id, name, employee_id, department, email, phone, shift_time FROM employees";
$result = $conn->query($sql);

$employees = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($employees);

$conn->close();
?>
