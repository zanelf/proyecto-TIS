<?php
include_once('../base_de_datos/conexion.php');

$nombre = $_POST["nombre"];
$descripcion = $_POST["descripcion"];

$consulta = "INSERT INTO tipo_solicitud (nombre, descripcion)  VALUES ('$nombre','$descripcion')";
$resultado = mysqli_query($conexionDB, $consulta);

header("Location: ../ventanas/tipo_solicitud.php");
exit;
