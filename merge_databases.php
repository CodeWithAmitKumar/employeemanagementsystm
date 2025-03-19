<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Database Merge Utility</h1>";

// Connect to both databases
$source_db = "register-user";
$target_db = "resister-user";

$conn_source = new mysqli("localhost", "root", "", $source_db);
$conn_target = new mysqli("localhost", "root", "", $target_db);

// Check connections
if ($conn_source->connect_error) {
    die("Source database connection failed: " . $conn_source->connect_error);
}

if ($conn_target->connect_error) {
    die("Target database connection failed: " . $conn_target->connect_error);
}

echo "<p>Successfully connected to both databases.</p>";

// Get users from source database
$source_users = [];
$sql = "SELECT * FROM users";
$result = $conn_source->query($sql);

if ($result === false) {
    die("Error fetching users from source: " . $conn_source->error);
}

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $source_users[] = $row;
    }
    echo "<p>Found " . count($source_users) . " users in source database.</p>";
} else {
    echo "<p>No users found in source database.</p>";
}

// Get existing emails from target database to avoid duplicates
$existing_emails = [];
$sql = "SELECT email FROM users";
$result = $conn_target->query($sql);

if ($result === false) {
    die("Error fetching emails from target: " . $conn_target->error);
}

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $existing_emails[] = strtolower($row['email']);
    }
    echo "<p>Found " . count($existing_emails) . " existing emails in target database.</p>";
}

// Transfer users that don't exist in the target database
$transferred = 0;
$skipped = 0;

foreach ($source_users as $user) {
    if (!in_array(strtolower($user['email']), $existing_emails)) {
        // User doesn't exist in target, insert them
        $stmt = $conn_target->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $user['name'], $user['email'], $user['password']);
        
        if ($stmt->execute()) {
            $transferred++;
            echo "<p>Transferred user: " . htmlspecialchars($user['email']) . "</p>";
        } else {
            echo "<p>Error transferring user " . htmlspecialchars($user['email']) . ": " . $conn_target->error . "</p>";
        }
    } else {
        $skipped++;
        echo "<p>Skipped duplicate user: " . htmlspecialchars($user['email']) . "</p>";
    }
}

echo "<h2>Transfer Summary</h2>";
echo "<p>Total users in source: " . count($source_users) . "</p>";
echo "<p>Users transferred: " . $transferred . "</p>";
echo "<p>Users skipped (duplicates): " . $skipped . "</p>";

// Only drop the database if explicitly confirmed
if (isset($_GET['confirm_drop']) && $_GET['confirm_drop'] === 'yes') {
    // Close the source connection first
    $conn_source->close();
    
    // Create new connection to default database
    $conn_drop = new mysqli("localhost", "root", "");
    
    if ($conn_drop->connect_error) {
        die("Connection failed for drop operation: " . $conn_drop->connect_error);
    }
    
    // Drop the source database
    $sql = "DROP DATABASE `$source_db`";
    if ($conn_drop->query($sql) === TRUE) {
        echo "<p style='color:green'>Successfully dropped source database '$source_db'.</p>";
    } else {
        echo "<p style='color:red'>Error dropping database: " . $conn_drop->error . "</p>";
    }
    
    $conn_drop->close();
} else {
    echo "<p><a href='?confirm_drop=yes' onclick='return confirm(\"Are you sure you want to drop the $source_db database? This cannot be undone!\");'>Click here to drop the source database</a></p>";
    echo "<p style='color:red'><strong>Warning:</strong> This will permanently delete the database. Make sure all data has been transferred correctly.</p>";
}

// Close connections
$conn_target->close();
if (isset($conn_source) && $conn_source) {
    $conn_source->close();
}

echo "<p><a href='/employeedashboard/'>Return to Dashboard</a></p>";
?> 