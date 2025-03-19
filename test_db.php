<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$database = "resister-user";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected to database successfully<br>";

// Check 'employees' table
$employeesTableSql = "SHOW TABLES LIKE 'employees'";
$result = $conn->query($employeesTableSql);
if ($result->num_rows > 0) {
    echo "Employees table exists<br>";
    
    // Show table structure
    $tableSql = "DESCRIBE employees";
    $tableResult = $conn->query($tableSql);
    echo "<h3>Employees Table Structure:</h3>";
    echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $tableResult->fetch_assoc()) {
        echo "<tr>";
        foreach ($row as $key => $value) {
            echo "<td>" . htmlspecialchars($value ?? 'NULL') . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
    
    // Count employees
    $countSql = "SELECT COUNT(*) as count FROM employees";
    $countResult = $conn->query($countSql);
    $count = $countResult->fetch_assoc()['count'];
    echo "<br>Total employees in database: $count<br>";
} else {
    echo "Employees table does not exist. Creating it now...<br>";
    
    // Create employees table
    $createTableSql = "CREATE TABLE employees (
        id INT(11) PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        employee_id VARCHAR(50) NOT NULL UNIQUE,
        department VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        shift_time VARCHAR(50) NOT NULL,
        document VARCHAR(255) NULL
    )";
    
    if ($conn->query($createTableSql) === TRUE) {
        echo "Employees table created successfully<br>";
    } else {
        echo "Error creating employees table: " . $conn->error . "<br>";
    }
}

$conn->close();
?> 