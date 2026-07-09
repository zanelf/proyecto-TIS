<?php

include_once('../base_de_datos/conexion.php');


if (isset($_POST['Id_tipo_solicitud']) && isset($_POST['Nombre']) && isset($_POST['Descripcion'])) {
    
    
    $id = mysqli_real_escape_string($conexionDB, $_POST['Id_tipo_solicitud']);
    $nombre = mysqli_real_escape_string($conexionDB, $_POST['Nombre']);
    $descripcion = mysqli_real_escape_string($conexionDB, $_POST['Descripcion']);

   
    $consulta = "UPDATE tipo_solicitud SET Nombre = '$nombre', Descripcion = '$descripcion' WHERE Id_tipo_solicitud = '$id'";
    $resultado = mysqli_query($conexionDB, $consulta);

    if ($resultado) {
        
        header("Location: ../ventanas/tipo_solicitud.php");
        exit;
    } else {
   
        echo "Error crítico al intentar actualizar el registro: " . mysqli_error($conexionDB);
    }
} else {
  
    echo "Error: No se recibieron los parámetros necesarios para procesar la actualización.";
}
?> 