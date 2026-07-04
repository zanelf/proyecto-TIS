<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include('../base_de_datos/conexion.php');

// Manejo OPTIONS (CORS preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Solo permitir GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);

    echo json_encode([
        "success" => false,
        "message" => "Método no permitido"
    ]);
    exit();
}

$sql = "SELECT * FROM departamento";
$result = $conexionDB->query($sql);

$departamentos = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $departamentos[] = $row;
    }
}

echo json_encode([
    "success" => true,
    "data" => $departamentos
]);

exit();
?>