<?php
include('../base_de_datos/conexion.php');

$correo = $_POST["correo"];
$ID_departamento = $_POST["departamento"];
$Asunto = $_POST["asunto"];
$Descripcion = $_POST["descripcion"];
$categoria = $_POST["categoria"];
$ID_tipo_solicitud = $_POST["tipo"];

$consulta = "INSERT INTO solicitud (
                Asunto,
                Descripcion,
                Categoria,
                correo_electronico,
                ID_departamento,
                ID_tipo_solicitud,
                fecha_creacion
            ) VALUES (
                '$Asunto',
                '$Descripcion',
                '$categoria',
                '$correo',
                '$ID_departamento',
                '$ID_tipo_solicitud',
                CURDATE()
            )";

$resultado = mysqli_query($conexionDB, $consulta);

if ($resultado) {

    $ID_solicitud = mysqli_insert_id($conexionDB);

    $InsertComprobante = "INSERT INTO comprobante
                            (Fecha, Hora, solicitud_ID)
                          VALUES
                            (CURDATE(), CURTIME(), $ID_solicitud)";

    mysqli_query($conexionDB, $InsertComprobante);
}

header("Location: ../index.php");
exit;
?>