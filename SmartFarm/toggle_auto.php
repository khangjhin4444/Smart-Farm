<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$device = $data['device'];
$state = $data['state'];

// Kết nối MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "smart_farm";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

// Ghi vào DB
$sql = "INSERT INTO auto_modes (device, enabled) VALUES (?, ?) ON DUPLICATE KEY UPDATE enabled = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $device, $state, $state);
$stmt->execute();
$stmt->close();
$conn->close();

// Gửi lệnh tới Python qua socket
$cmd = '';
if ($device === "light") {
    $cmd = $state == 1 ? "auto_light_on" : "auto_light_off";
} elseif ($device === "water") {
    $cmd = $state == 1 ? "auto_water_on" : "auto_water_off";
}

$socket = stream_socket_client("tcp://127.0.0.1:65432", $errno, $errstr, 2);
if (!$socket) {
    echo json_encode(['error' => "Không thể kết nối tới Python: $errstr ($errno)"]);
    exit;
}
fwrite($socket, $cmd);
fclose($socket);

echo json_encode(['message' => 'Lệnh đã gửi tới Python thành công']);
?>
