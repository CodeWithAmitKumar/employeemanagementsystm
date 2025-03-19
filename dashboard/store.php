<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$database = "resister-user";
$uploadDir = __DIR__ . "/uploads/";

// Establish database connection
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    sendResponse(['success' => false, 'message' => 'Database connection failed'], 500);
}

// Create uploads directory if not exists
if (!file_exists($uploadDir)) {
    if (!mkdir($uploadDir, 0777, true)) {
        error_log("Failed to create upload directory: " . $uploadDir);
        sendResponse(['success' => false, 'message' => 'Failed to create upload directory'], 500);
    } else {
        error_log("Created upload directory: " . $uploadDir);
        // Ensure permissions are set
        chmod($uploadDir, 0777);
    }
}

// Check if directory is writable
if (!is_writable($uploadDir)) {
    error_log("Upload directory is not writable: " . $uploadDir);
    sendResponse(['success' => false, 'message' => 'Upload directory is not writable'], 500);
}

// Unified response handler
function sendResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Secure file upload handler
function handleDocumentUpload($employeeID) {
    global $uploadDir;

    // If no file was uploaded or there was an error, return null
    if (!isset($_FILES['document']) || $_FILES['document']['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    
    // If there was a different upload error
    if ($_FILES['document']['error'] !== UPLOAD_ERR_OK) {
        sendResponse(['success' => false, 'message' => 'Document upload error: ' . $_FILES['document']['error']], 400);
    }

    // File validation
    $allowedTypes = [
        'application/pdf' => 'pdf',
        'application/msword' => 'doc',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
        'image/jpeg' => 'jpg',
        'image/pjpeg' => 'jpg', 
        'image/png' => 'png',
        'image/gif' => 'gif',
        'text/plain' => 'txt'
    ];

    // Allow files that may not have correct MIME type detection
    $fileExt = strtolower(pathinfo($_FILES['document']['name'], PATHINFO_EXTENSION));
    $allowedExt = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'gif', 'txt'];

    if (in_array($fileExt, $allowedExt)) {
        // Check file size
        if ($_FILES['document']['size'] > 2 * 1024 * 1024) {
            sendResponse(['success' => false, 'message' => 'File size exceeds 2MB limit'], 400);
        }
        
        // Proceed with file upload even if MIME type detection fails
        $fileName = sprintf("doc_%s_%d.%s", 
            preg_replace('/[^A-Za-z0-9]/', '', $employeeID),
            time(),
            $fileExt
        );
        
        if (!move_uploaded_file($_FILES['document']['tmp_name'], $uploadDir . $fileName)) {
            error_log("Failed to move uploaded file from temp to uploads dir");
            sendResponse(['success' => false, 'message' => 'File upload failed: Could not move file'], 500);
        }
        
        return $fileName;
    } else {
        sendResponse(['success' => false, 'message' => 'Invalid file type. Allowed types: pdf, doc, docx, jpg, png, gif, txt'], 400);
    }
}

// Handle POST requests (Create/Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debug information
    error_log("POST request received: " . json_encode($_POST));
    error_log("FILES: " . json_encode($_FILES));
    
    $requiredFields = ['employeeName', 'employeeID', 'department', 'email', 'phone', 'shiftTime'];
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            error_log("Missing required field: $field");
            sendResponse(['success' => false, 'message' => "Missing required field: $field"], 400);
        }
    }

    // Check if this is an update or new record
    $isUpdate = false;
    
    // Check existing employee
    $checkStmt = $conn->prepare("SELECT id, document FROM employees WHERE employee_id = ?");
    $checkStmt->bind_param("s", $_POST['employeeID']);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    
    // Set update flag if employee exists
    if ($result->num_rows > 0) {
        $isUpdate = true;
    }
    
    // Document is required for new entries but optional for updates
    if (!$isUpdate && empty($_FILES['document']['name'])) {
        sendResponse(['success' => false, 'message' => "Document is required for new employees"], 400);
    }

    // Process document upload
    $document = null;
    try {
        if (isset($_FILES['document']) && $_FILES['document']['error'] !== UPLOAD_ERR_NO_FILE) {
            $document = handleDocumentUpload($_POST['employeeID']);
            error_log("Document uploaded: " . $document);
        } else {
            error_log("No document uploaded or document field empty");
        }
    } catch (Exception $e) {
        error_log("Document upload error: " . $e->getMessage());
        sendResponse(['success' => false, 'message' => 'Document upload error: ' . $e->getMessage()], 400);
    }
    
    // Reset $result position
    $result->data_seek(0);
    
    if ($isUpdate) {
        // Update existing record
        $row = $result->fetch_assoc();
        $currentDoc = $row['document'];
        
        // Remove old document if new one uploaded
        if ($document && $currentDoc && file_exists($uploadDir . $currentDoc)) {
            unlink($uploadDir . $currentDoc);
        }

        $stmt = $conn->prepare("UPDATE employees SET 
            name = ?, department = ?, email = ?, phone = ?, shift_time = ?, document = COALESCE(?, document)
            WHERE employee_id = ?");
        $stmt->bind_param("sssssss", 
            $_POST['employeeName'],
            $_POST['department'],
            $_POST['email'],
            $_POST['phone'],
            $_POST['shiftTime'],
            $document,
            $_POST['employeeID']
        );
    } else {
        // Insert new record
        $stmt = $conn->prepare("INSERT INTO employees 
            (name, employee_id, department, email, phone, shift_time, document)
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", 
            $_POST['employeeName'],
            $_POST['employeeID'],
            $_POST['department'],
            $_POST['email'],
            $_POST['phone'],
            $_POST['shiftTime'],
            $document
        );
    }

    if ($stmt->execute()) {
        sendResponse(['success' => true, 'message' => 'Employee saved successfully']);
    } else {
        sendResponse(['success' => false, 'message' => 'Database operation failed'], 500);
    }
}

// Handle DELETE requests
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        sendResponse(['success' => false, 'message' => 'Invalid employee ID'], 400);
    }

    $id = (int)$_GET['id'];

    // Get document path
    $stmt = $conn->prepare("SELECT document FROM employees WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        sendResponse(['success' => false, 'message' => 'Employee not found'], 404);
    }

    $document = $result->fetch_assoc()['document'];
    $stmt->close();

    // Delete record
    $deleteStmt = $conn->prepare("DELETE FROM employees WHERE id = ?");
    $deleteStmt->bind_param("i", $id);
    
    if (!$deleteStmt->execute()) {
        sendResponse(['success' => false, 'message' => 'Deletion failed'], 500);
    }

    // Remove associated file
    if ($document && file_exists($uploadDir . $document)) {
        unlink($uploadDir . $document);
    }

    sendResponse(['success' => true, 'message' => 'Employee deleted successfully']);
}

// Handle GET requests (Read)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Check if the employees table exists
        $checkTable = $conn->query("SHOW TABLES LIKE 'employees'");
        if ($checkTable->num_rows === 0) {
            // Table doesn't exist, create it
            $createTable = "CREATE TABLE employees (
                id INT(11) PRIMARY KEY AUTO_INCREMENT,
                name VARCHAR(100) NOT NULL,
                employee_id VARCHAR(50) NOT NULL UNIQUE,
                department VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL,
                phone VARCHAR(20) NOT NULL,
                shift_time VARCHAR(50) NOT NULL,
                document VARCHAR(255) NULL
            )";
            
            if (!$conn->query($createTable)) {
                throw new Exception("Failed to create employees table: " . $conn->error);
            }
            
            // Return empty array if table was just created
            sendResponse([]);
            exit;
        }
        
        $stmt = $conn->prepare("SELECT 
            id, name, employee_id, department, email, phone, shift_time, document 
            FROM employees");
            
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }
        
        if (!$stmt->execute()) {
            throw new Exception("Execute statement failed: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        $employees = [];
        
        while ($row = $result->fetch_assoc()) {
            // Sanitize output and add path for document
            $document = $row['document'] ? htmlspecialchars($row['document']) : null;
            $employees[] = [
                'id' => (int)$row['id'],
                'name' => htmlspecialchars($row['name']),
                'employee_id' => htmlspecialchars($row['employee_id']),
                'department' => htmlspecialchars($row['department']),
                'email' => htmlspecialchars($row['email']),
                'phone' => htmlspecialchars($row['phone']),
                'shift_time' => htmlspecialchars($row['shift_time']),
                'document' => $document
            ];
        }
        
        sendResponse($employees);
    } catch (Exception $e) {
        sendResponse(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

// Close connection and exit
$conn->close();
exit;
