<?php
include_once('../base_de_datos/conexion.php');

$Nombre = $_POST["Nombre"];

$consulta = "INSERT INTO prioridad (Nombre) VALUES ('$Nombre')";
$resultado = mysqli_query($conexionDB, $consulta);

header("Location: ../ventanas/prioridad.php");
exit;