<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        if (isset($_POST['login'])) {
            $email = htmlspecialchars($_POST['email']); 
            $password = htmlspecialchars($_POST['password']); 

            $errors = array();

            // Validation
            if (empty($email) || empty($password)) {
                array_push($errors, "Please fill in all fields.");
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                array_push($errors, "Please enter a valid email address.");
            }

            // If no errors, proceed to authenticate user
            if (count($errors) === 0) {
                $checkUserQuery = "SELECT * FROM users WHERE email = '$email'";
                $result = $conn->query($checkUserQuery);

                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    if (password_verify($password, $user['password'])) {
                        // Successful login, redirect to dashboard
                        header("Location: /employeedashboard/dashboard.php");
                        exit;
                    } else {
                        array_push($errors, "Invalid password. Please try again.");
                    }
                } else {
                    array_push($errors, "No account found with this email.");
                }
            }

            // Display errors if any
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            ' . $error . '
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
                <h3>Login</h3>
            </div>
            <div class="card-body">
                <form action="/employeedashboard/login/login.php" method="post">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Enter your password" required>
                    </div>
                    <div class="text-center">
                        <!-- <button type="submit" class="btn btn-primary w-100" name="login">Login</button> -->
                        <a href="/employeedashboard/dashboard/dashboard.php" type="submit" class="btn btn-primary w-100" name="login">Login</a>
                    </div>
                    <div class="text-center mt-3">
                        <p>Don't have an account? 
                            <a href="/employeedashboard/register/registration.php" class="text-primary">Register here</a>
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
