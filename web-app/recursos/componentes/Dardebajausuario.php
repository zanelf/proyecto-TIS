<?php
    include_once('../../base_de_datos/conexion.php');
 
    if (!isset($_GET['id_enviado'])) {
        echo "Faltan parámetros para dar de baja.";
        exit;
    }
 
    $id_usuario = mysqli_real_escape_string($conexionDB, $_GET['id_enviado']);
 
    //  no elimina fisicamente, solo da de baja
    $consulta = "UPDATE usuario SET activo = 0 WHERE ID_usuario = '$id_usuario'";
    $resultado = mysqli_query($conexionDB, $consulta);
 
    if (!$resultado) {
        echo "Error al dar de baja al usuario: " . mysqli_error($conexionDB);
        exit;
    }
 
    header("Location: ../../ventanas/Usuario.php?baja=ok");
    exit;
?>