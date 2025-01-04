<?php
include '../../private/databaseinteract.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

if (isset($_GET['first']) && isset($_GET['second']) && isset($_GET['result'])) {
    $valid = findRecipe($_GET['first'],$_GET['second'], $_GET['result']);
    $emoji = "";
    if ($valid) {
        $emoji = findResults("emoji", $_GET['result']);
        $emoji = $emoji ?: "";
    }
    echo json_encode([
        'valid' => $valid,
        'emoji' => $emoji
    ], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(['valid' => 'false', 'emoji' => '']);
}
?>