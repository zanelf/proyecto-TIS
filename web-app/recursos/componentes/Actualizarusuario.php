<?php
    include_once('../../base_de_datos/conexion.php');

    if (isset($_POST['ID_usuario']) && isset($_POST['correo_usuario'])) {

        $id_usuario = mysqli_real_escape_string($conexionDB, $_POST['ID_usuario']);
        $correo_usuario = mysqli_real_escape_string($conexionDB, $_POST['correo_usuario']);

        if (empty(trim($correo_usuario))) {
            echo "Error: Por favor, ingrese un correo.";
            exit;
        }

        $consulta_usuario = "UPDATE usuario SET correo_usuario = '$correo_usuario' WHERE ID_usuario = '$id_usuario'";
        $resultado_usuario = mysqli_query($conexionDB, $consulta_usuario);

        if (!$resultado_usuario) {
            echo "Error crítico al intentar actualizar el usuario: " . mysqli_error($conexionDB);
            exit;
        }

        if (isset($_POST['nombre']) && isset($_POST['apellido']) && isset($_POST['id_departamento'])) {
            $nombre = mysqli_real_escape_string($conexionDB, $_POST['nombre']);
            $apellido = mysqli_real_escape_string($conexionDB, $_POST['apellido']);
            $id_departamento = mysqli_real_escape_string($conexionDB, $_POST['id_departamento']);
            $prevision = isset($_POST['prevision']) ? mysqli_real_escape_string($conexionDB, $_POST['prevision']) : null;
            $afp = isset($_POST['afp']) ? mysqli_real_escape_string($conexionDB, $_POST['afp']) : null;

            $consulta_trabajador = "UPDATE trabajador
                                     SET nombre = '$nombre', apellido = '$apellido', ID_departamento = '$id_departamento', prevision = '$prevision', AFP = '$afp'
                                     WHERE ID_usuario = '$id_usuario'";
            mysqli_query($conexionDB, $consulta_trabajador);
        }

        header("Location: ../../ventanas/Usuario.php?actualizado=ok");
        exit;

    } else {
        echo "Error: No se recibieron los parámetros necesarios para procesar la actualización.";
    }
?>