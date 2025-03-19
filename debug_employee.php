<?php
// Display all PHP errors and warnings for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

// Function to display form & handle submission
function handleEmployeeForm() {
    global $conn;
    
    // Check if form submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        echo "<h2>Form Data Received:</h2>";
        echo "<pre>";
        print_r($_POST);
        echo "</pre>";
        
        echo "<h2>Files Information:</h2>";
        echo "<pre>";
        print_r($_FILES);
        echo "</pre>";
        
        // Insert employee directly without validation for testing
        try {
            $sql = "INSERT INTO employees (name, employee_id, department, email, phone, shift_time) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", 
                $_POST['employeeName'],
                $_POST['employeeID'],
                $_POST['department'],
                $_POST['email'],
                $_POST['phone'],
                $_POST['shiftTime']
            );
            
            if ($stmt->execute()) {
                echo "<div style='color:green;font-weight:bold;'>Employee inserted successfully!</div>";
            } else {
                echo "<div style='color:red;font-weight:bold;'>Error inserting employee: " . $stmt->error . "</div>";
            }
        } catch (Exception $e) {
            echo "<div style='color:red;font-weight:bold;'>Exception: " . $e->getMessage() . "</div>";
        }
    }
    
    // Display test form
    ?>
    <h2>Test Employee Form</h2>
    <form method="post" enctype="multipart/form-data">
        <div>
            <label>Name: <input type="text" name="employeeName" value="Test Employee"></label>
        </div>
        <div>
            <label>Employee ID: <input type="text" name="employeeID" value="EMP<?php echo rand(1000, 9999); ?>"></label>
        </div>
        <div>
            <label>Department: 
                <select name="department">
                    <option value="Software">Software Development</option>
                    <option value="QA">Quality Assurance</option>
                    <option value="HR">Human Resources</option>
                </select>
            </label>
        </div>
        <div>
            <label>Email: <input type="email" name="email" value="test<?php echo rand(100, 999); ?>@example.com"></label>
        </div>
        <div>
            <label>Phone: <input type="text" name="phone" value="555-<?php echo rand(1000, 9999); ?>"></label>
        </div>
        <div>
            <label>Shift Time: 
                <select name="shiftTime">
                    <option value="8 am - 2 pm">8 am - 2 pm</option>
                    <option value="2 pm - 8 pm">2 pm - 8 pm</option>
                </select>
            </label>
        </div>
        <div>
            <label>Document: <input type="file" name="document"></label>
        </div>
        <div>
            <button type="submit">Test Add Employee</button>
        </div>
    </form>
    <?php
}

// Display database tables and their content
function showDatabaseContent() {
    global $conn;
    
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
        
        // List employees
        $listSql = "SELECT * FROM employees";
        $listResult = $conn->query($listSql);
        
        if ($listResult->num_rows > 0) {
            echo "<h3>Current Employees:</h3>";
            echo "<table border='1'><tr>";
            
            // Get field names
            $fieldInfo = $listResult->fetch_fields();
            foreach ($fieldInfo as $field) {
                echo "<th>" . htmlspecialchars($field->name) . "</th>";
            }
            echo "</tr>";
            
            // Output data
            while ($row = $listResult->fetch_assoc()) {
                echo "<tr>";
                foreach ($row as $key => $value) {
                    echo "<td>" . htmlspecialchars($value ?? 'NULL') . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<h3>No employees found in database</h3>";
        }
    } else {
        echo "<div style='color:red;font-weight:bold;'>Employees table does not exist!</div>";
    }
}

// Display HTML page
echo "<!DOCTYPE html>
<html>
<head>
    <title>Employee System Debug</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        div { margin-bottom: 10px; }
        pre { background: #f4f4f4; padding: 10px; border-radius: 5px; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Employee System Diagnostic</h1>";

// Handle form and show database content
handleEmployeeForm();
echo "<hr>";
showDatabaseContent();

echo "</body></html>";

$conn->close();
?> 