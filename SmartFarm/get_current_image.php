<?php
$dir = 'images/';
$normal = $dir . 'normal.jpg';
$disease = $dir . 'disease.jpg';

if (file_exists($disease)) {
    echo json_encode([
        'filename' => $disease,
        'status' => 'disease'
    ]);
} else if (file_exists($normal)) {
    echo json_encode([
        'filename' => $normal,
        'status' => 'normal'
    ]);
} else {
    echo json_encode([
        'error' => 'No image found'
    ]);
}
?>
