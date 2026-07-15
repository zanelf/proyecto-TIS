<?php
include_once('../base_de_datos/conexion.php');

if (!isset($_POST['id_enviado']) || !isset($_POST['tipomod'])) {
    echo "Error: faltan parámetros para actualizar.";
    exit;
}

$id = mysqli_real_escape_string($conexionDB, $_POST['id_enviado']);
$modelo = $_POST['tipomod'];

// pk
$atributos = mysqli_query($conexionDB, "DESCRIBE " . $modelo);
$PKmodelo = "";
$campos = [];
while ($fila = mysqli_fetch_assoc($atributos)) {
    if ($fila['Key'] == "PRI") {
        $PKmodelo = $fila['Field'];
    } else {
        $campos[] = $fila['Field'];
    }
}


$asignaciones = [];
foreach ($campos as $campo) {
    if (isset($_POST[$campo])) {
        $valor = mysqli_real_escape_string($conexionDB, $_POST[$campo]);
        $asignaciones[] = "$campo = '$valor'";
    }
}

if (count($asignaciones) === 0) {
    echo "No hay datos para actualizar.";
    exit;
}

$consulta = "UPDATE $modelo SET " . implode(", ", $asignaciones) . " WHERE $PKmodelo = '$id'";
$resultado = mysqli_query($conexionDB, $consulta);

if ($resultado) {
    header("Location: ../ventanas/$modelo.php");
    exit;
} else {
    echo "Error al actualizar el registro: " . mysqli_error($conexionDB);
}
?>