<?php
// Database configuration 
$servername = "localhost";
$username = "root";
$password = "";
$database = "resister-user";

// Create uploads directory
$uploadDir = "uploads/";
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
    echo "Created uploads directory<br>";
}

// Check upload directory permissions
echo "Upload directory permissions: " . substr(sprintf('%o', fileperms($uploadDir)), -4) . "<br>";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected to database successfully<br>";

// Check for form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h2>Form Submitted</h2>";
    
    echo "<h3>POST Data:</h3>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    
    echo "<h3>FILES Data:</h3>";
    echo "<pre>";
    print_r($_FILES);
    echo "</pre>";
    
    // Test file upload functionality
    if (!empty($_FILES['document']['name'])) {
        $fileExt = strtolower(pathinfo($_FILES['document']['name'], PATHINFO_EXTENSION));
        $fileName = "test_" . time() . "." . $fileExt;
        
        if (move_uploaded_file($_FILES['document']['tmp_name'], $uploadDir . $fileName)) {
            echo "<div style='color:green'>File uploaded successfully to: " . $uploadDir . $fileName . "</div>";
        } else {
            echo "<div style='color:red'>File upload failed</div>";
            echo "Error code: " . $_FILES['document']['error'] . "<br>";
            echo "Tmp name: " . $_FILES['document']['tmp_name'] . "<br>";
            echo "Target: " . $uploadDir . $fileName . "<br>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Form Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1>Test Form Submission</h1>
    
    <div class="card">
        <div class="card-header">
            <h2>Standard Form</h2>
        </div>
        <div class="card-body">
            <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="Test User">
                </div>
                <div class="mb-3">
                    <label for="document" class="form-label">Upload Document</label>
                    <input type="file" class="form-control" id="document" name="document">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
    
    <hr class="my-5">
    
    <div class="card">
        <div class="card-header">
            <h2>JavaScript Form</h2>
        </div>
        <div class="card-body">
            <form id="jsForm" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="jsName" class="form-label">Name</label>
                    <input type="text" class="form-control" id="jsName" name="name" value="Test User">
                </div>
                <div class="mb-3">
                    <label for="jsDocument" class="form-label">Upload Document</label>
                    <input type="file" class="form-control" id="jsDocument" name="document">
                </div>
                <div id="formOutput" class="alert alert-info d-none"></div>
            </form>
            <button id="submitButton" class="btn btn-primary">Submit with JavaScript</button>
        </div>
    </div>
    
    <script>
        document.getElementById('submitButton').addEventListener('click', function() {
            const form = document.getElementById('jsForm');
            const formData = new FormData(form);
            
            document.getElementById('formOutput').classList.remove('d-none');
            document.getElementById('formOutput').textContent = 'Submitting form...';
            
            // Send data directly to this same page
            fetch('test_form.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(data => {
                document.getElementById('formOutput').innerHTML = 'Form submitted successfully!<br>Reload page to see results.';
                document.getElementById('formOutput').className = 'alert alert-success';
            })
            .catch(error => {
                document.getElementById('formOutput').textContent = 'Error: ' + error.message;
                document.getElementById('formOutput').className = 'alert alert-danger';
            });
        });
    </script>
</body>
</html> 