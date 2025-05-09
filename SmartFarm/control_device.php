<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$device = $data['device'];
$state = $data['state'];

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "smart_farm";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

// Log device control action
$sql = "INSERT INTO device_logs (device, action, timestamp) VALUES (?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $device, $state);
$stmt->execute();

$stmt->close();
$conn->close();

$cmd = '';
if ($device === "light") {
    $cmd = $state == 1 ? "light_on" : "light_off";
} elseif ($device === "water") {
    $cmd = $state == 1 ? "water_on" : "water_off";
}

$socket = stream_socket_client("tcp://127.0.0.1:65432", $errno, $errstr, 2);
if (!$socket) {
    echo json_encode(['error' => "Không thể kết nối tới Python: $errstr ($errno)"]);
    exit;
}
fwrite($socket, $cmd);
fclose($socket);

?>