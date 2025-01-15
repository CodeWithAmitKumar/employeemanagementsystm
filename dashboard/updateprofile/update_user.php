<?php
// Include database connection
include('db_connection.php');

// Initialize variables
$error_message = null;
$success_message = null;

// Handle form submission for updating user details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id']; // Unique identifier
    $job_designation = $_POST['job_designation'];
    $highest_qualification = $_POST['highest_qualification'];
    $marital_status = $_POST['marital_status'];
    $mobile_no = $_POST['mobile_no'];
    $date_of_birth = $_POST['date_of_birth'];

    // Update query
    $sql = "UPDATE updateuser SET 
                job_designation = ?, 
                highest_qualification = ?, 
                marital_status = ?, 
                mobile_no = ?, 
                date_of_birth = ?
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $job_designation, $highest_qualification, $marital_status, $mobile_no, $date_of_birth, $id);

    if ($stmt->execute()) {
        $success_message = "Details updated successfully!";
    } else {
        $error_message = "Error updating details: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User Details</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        // Function to hide messages after 4 seconds
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                const messages = document.querySelectorAll('.success-message, .error-message');
                messages.forEach(message => message.style.display = 'none');
            }, 4000);
        });
    </script>
</head>
<body>
    <div class="card">
        <h2>Update User Details</h2>
        <?php if ($error_message) echo "<div class='error-message'>$error_message</div>"; ?>
        <?php if ($success_message) echo "<div class='success-message'>$success_message</div>"; ?>

        <form method="POST" action="">
            <input type="hidden" name="id" value="<!-- Pass the user's ID here dynamically -->" />
            <input type="text" name="job_designation" placeholder="Job Designation" required />
            <input type="text" name="highest_qualification" placeholder="Highest Qualification" required />
            <select name="marital_status" required>
                <option value="">Select Marital Status</option>
                <option value="Single">Single</option>
                <option value="Married">Married</option>
            </select>
            <input type="text" name="mobile_no" placeholder="Mobile Number" required />
            <input type="date" name="date_of_birth" required />
            <button type="submit">Update</button>
        </form>
    </div>
</body>
</html>
