<?php
$dir = 'images/';
$normal = array_merge(
    glob($dir . '*normal*.jpg'),
    glob($dir . '*normal*.jpeg')
);

$disease = array_merge(
    glob($dir . '*disease*.jpg'),
    glob($dir . '*disease*.jpeg')
);

// Ưu tiên hiển thị ảnh bệnh nếu có
if (!empty($disease)) {
    echo json_encode([
        'filename' => $disease[0],  // hoặc $disease để trả nhiều file
        'status' => 'disease'
    ]);
} else if (!empty($normal)) {
    echo json_encode([
        'filename' => $normal[0],
        'status' => 'normal'
    ]);
} else {
    echo json_encode([
        'error' => 'No image found'
    ]);
}
?>