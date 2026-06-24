<?php

    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: http://miproyecto.local");
    header("Access-Control-Allow-Headers: Content-Type");
    header("Access-Control-Allow-Methods: POST, OPTIONS");

    include('../base_de_datos/conexion.php');

    // Manejo CORS preflight
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }

    // Leer JSON
    $data = json_decode(file_get_contents("php://input"), true);

    $nombre = $data["nombre"] ?? null;

    if (!$nombre) {
        http_response_code(400);

        echo json_encode([
            "success" => false,
            "message" => "Nombre vacío"
        ]);
        exit();
    }

    // Insertar (MEJOR: preparado)
    $stmt = $conexionDB->prepare("INSERT INTO departamento (nombre) VALUES (?)");
    $stmt->bind_param("s", $nombre);

    if ($stmt->execute()) {

        http_response_code(201);

        echo json_encode([
            "success" => true,
            "message" => "Departamento creado"
        ]);

    } else {

        http_response_code(500);

        echo json_encode([
            "success" => false,
            "message" => "Error al insertar"
        ]);
    }
?>