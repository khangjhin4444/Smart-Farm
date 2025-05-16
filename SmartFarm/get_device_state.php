<?php 
  if ($_GET['device']) {
    $device = $_GET['device'];
    $conn = mysqli_connect("localhost", "root", "", "smart_farm");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $sql = "select Status from device_status where Name = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $device);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $res = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    echo json_encode(['status' => $res['Status']]);
  }
  else {
    echo json_encode(['error' => 'No device specified']);
  }
  
?>