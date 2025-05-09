<?php
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "smart_farm";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

// Get latest sensor data
$sql = "SELECT temperature, humidity, light FROM sensor_data ORDER BY timestamp DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode([
        'temp' => $row['temperature'],
        'humidity' => $row['humidity'],
        'light' => $row['light']
    ]);
} else {
    echo json_encode(['temp' => 0, 'humidity' => 0, 'light' => 0]);
}

$conn->close();
?>