<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "resister-user"; // Change to your actual database name

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to send JSON response
function sendResponse($data) {
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Insert or update employee (POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['employeeName'])) {
    $name = $_POST['employeeName'];
    $employeeID = $_POST['employeeID'];
    $department = $_POST['department'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $shiftTime = $_POST['shiftTime'];

    // Check if the employee already exists
    $checkStmt = $conn->prepare("SELECT id FROM employees WHERE employee_id = ?");
    $checkStmt->bind_param("s", $employeeID);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        // Update existing employee
        $updateStmt = $conn->prepare("UPDATE employees SET name = ?, department = ?, email = ?, phone = ?, shift_time = ? WHERE employee_id = ?");
        $updateStmt->bind_param("ssssss", $name, $department, $email, $phone, $shiftTime, $employeeID);
        $response = $updateStmt->execute() ? ['success' => true, 'message' => 'Employee updated successfully.'] : ['success' => false, 'message' => 'Failed to update employee.'];
        $updateStmt->close();
    } else {
        // Insert new employee
        $insertStmt = $conn->prepare("INSERT INTO employees (name, employee_id, department, email, phone, shift_time) VALUES (?, ?, ?, ?, ?, ?)");
        $insertStmt->bind_param("ssssss", $name, $employeeID, $department, $email, $phone, $shiftTime);
        $response = $insertStmt->execute() ? ['success' => true, 'message' => 'Employee added successfully.'] : ['success' => false, 'message' => 'Failed to add employee.'];
        $insertStmt->close();
    }

    $checkStmt->close();
    sendResponse($response);
}

// Delete employee (POST request with action)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['action']) && $data['action'] === 'delete' && isset($data['employeeID'])) {
        $employeeID = $data['employeeID'];
        
        // Step 1: Delete the employee
        $deleteStmt = $conn->prepare("DELETE FROM employees WHERE employee_id = ?");
        $deleteStmt->bind_param("s", $employeeID);

        $deleteSuccess = $deleteStmt->execute();
        $deleteStmt->close();

        // Step 2: Reindex the employee IDs of remaining employees
        if ($deleteSuccess) {
            $reindexStmt = $conn->prepare("UPDATE employees SET employee_id = employee_id - 1 WHERE employee_id > ?");
            $reindexStmt->bind_param("s", $employeeID);
            $reindexStmt->execute();
            $reindexStmt->close();

            sendResponse(['success' => true, 'message' => 'Employee deleted and reindexed successfully.']);
        } else {
            sendResponse(['success' => false, 'message' => 'Failed to delete employee.']);
        }
    } else {
        sendResponse(['success' => false, 'message' => 'Invalid request for deletion.']);
    }
}

// Fetch employees (GET request)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT id, name, employee_id, department, email, phone, shift_time FROM employees";
    $result = $conn->query($sql);

    $employees = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $employees[] = $row;
        }
    }

    sendResponse($employees);
}

$conn->close();
?>
