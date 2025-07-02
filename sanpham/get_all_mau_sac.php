<?php
header("Content-Type: application/json; charset=UTF-8");
require __DIR__ . '/../includes/connect.php';

$data = callAPI("getAllMauSac");
echo json_encode($data);
