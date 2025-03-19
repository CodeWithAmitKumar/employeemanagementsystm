<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-5">
        <?php
        // Include the database connection file
        include 'db_connection.php';

        // Enable error reporting
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        // Check if the form is submitted
        if (isset($_POST['submit'])) {
            $fullname = htmlspecialchars($_POST['fullname']); // Prevent XSS
            $email = htmlspecialchars($_POST['email']);       // Prevent XSS
            $password = htmlspecialchars($_POST['password']); // Prevent XSS
            $repeatPassword = htmlspecialchars($_POST['confirm-password']); // Prevent XSS

            $errors = array();

            // Validation
            if (empty($fullname) || empty($email) || empty($password) || empty($repeatPassword)) {
                array_push($errors, "Please fill in all fields.");
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                array_push($errors, "Please enter a valid email address.");
            }

            if (strlen($password) < 8) {
                array_push($errors, "Password must be at least 8 characters long.");
            }

            if ($password !== $repeatPassword) {
                array_push($errors, "Passwords do not match.");
            }

            // Check for duplicate email
            $checkEmailQuery = "SELECT * FROM users WHERE email = ?";
            $stmt = $conn->prepare($checkEmailQuery);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                array_push($errors, "This email is already registered.");
            }

            // If there are errors, display them
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            ' . $error . '
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                          </div>';
                }
            } else {
                // Insert data into the database
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password
                
                $sql = "INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $fullname, $email, $hashedPassword);
                
                if ($stmt->execute()) {
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                            Registration successful! Redirecting to login page...
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                          </div>';
                } else {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            Error: ' . $conn->error . '
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                          </div>';
                }
            }
        }

        // Close the database connection
        $conn->close();
        ?>

        <div class="card shadow-lg form-container">
            <div class="card-header text-center bg-primary text-white">
                <h3>Registration Form</h3>
            </div>
            <div class="card-body">
                <form action="/employeedashboard/register/registration.php" method="post">
                    <div class="mb-3">
                        <label for="fullname" class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="fullname" placeholder="Enter your full name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Enter your password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm-password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" name="confirm-password" placeholder="Re-enter your password" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary w-100" name="submit">Register</button>
                    </div>
                    <div class="text-center mt-3">
                        <p>Already registered? 
                            <a href="/employeedashboard/login/login.php" class="text-primary">Click here</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
