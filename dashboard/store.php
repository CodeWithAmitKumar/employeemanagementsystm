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

// Insert or update employee (POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['employeeName'])) {
    // Get the form data from the POST request
    $name = $_POST['employeeName'];
    $employeeID = $_POST['employeeID'];
    $department = $_POST['department'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $shiftTime = $_POST['shiftTime'];

    $stmt = $conn->prepare("INSERT INTO employees (name, employee_id, department, email, phone, shift_time) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $employeeID, $department, $email, $phone, $shiftTime);

    if ($stmt->execute()) {
        $response = ['success' => true];
    } else {
        $response = ['success' => false];
    }

    $stmt->close();
    echo json_encode($response);
    exit;
}

// Delete employee (POST request with action)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['HTTP_CONTENT_TYPE']) && strpos($_SERVER['HTTP_CONTENT_TYPE'], 'application/json') !== false) {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if ($data['action'] == 'delete' && isset($data['employeeID'])) {
        $employeeID = $data['employeeID'];
        $stmt = $conn->prepare("DELETE FROM employees WHERE employee_id = ?");
        $stmt->bind_param("s", $employeeID);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }

        $stmt->close();
        exit;
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

    header('Content-Type: application/json');
    echo json_encode($employees);
}

$conn->close();
?>
