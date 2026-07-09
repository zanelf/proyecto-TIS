<?php
include_once('../base_de_datos/conexion.php');

$modelo = $Tipo_modelo;
$atributos = mysqli_query($conexionDB, "DESCRIBE " . $modelo);

$campos = [];
$PKmodelo = "";
$PKValue = "";

$estado="";

while ($fila = mysqli_fetch_assoc($atributos)) {
    $campos[] = $fila;
    if ($fila['Key'] == "PRI") {
        $PKmodelo = $fila['Field'];
    }
}

?>