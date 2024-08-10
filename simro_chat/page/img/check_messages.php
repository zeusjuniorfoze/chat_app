<?php 
// Replace these credentials with your own
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bd";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to check for new messages
$sql = "SELECT * FROM messages WHERE timestamp > DATE_SUB(NOW(), INTERVAL 5 SECOND)";
$result = $conn->query($sql);

$newMessages = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $newMessages[] = $row['sender'] . ': ' . $row['message'];
    }
}

$response = [
    'newMessages' => $newMessages,
];

echo json_encode($response);

$conn->close();
?>