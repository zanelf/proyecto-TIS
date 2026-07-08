<?php
    include('../base_de_datos/conexion.php');

    $nombre_usuario = mysqli_real_escape_string($conexionDB, $_POST["nombre_usuario"]);
    $password_plana = $_POST["password"];
    $tipo = $_POST["tipo"];
    $id_departamento = isset($_POST["id_departamento"]) ? $_POST["id_departamento"] : null;

    $contraseñahash = password_hash($password_plana, PASSWORD_BCRYPT);

    $consulta_base = "INSERT INTO usuario (nombre_usuario, contraseña) VALUES ('$nombre_usuario', '$contraseñahash')";
    $resultado_base = mysqli_query($conexionDB, $consulta_base);

    if ($resultado_base) {
        $id_nuevo_usuario = mysqli_insert_id($conexionDB);

        if ($tipo == "Administrador") {
            $query_admin = "INSERT INTO administrador (ID_usuario) VALUES ('$id_nuevo_usuario')";
            mysqli_query($conexionDB, $query_admin);
        } 
        
        elseif ($tipo == "Funcionario") {
            $query_func = "INSERT INTO funcionario (ID_usuario, ID_departamento) VALUES ('$id_nuevo_usuario', '$id_departamento')";
            mysqli_query($conexionDB, $query_func);
        } 
        
        elseif ($tipo == "Director") {
            $query_dir = "INSERT INTO director (ID_usuario, ID_departamento) VALUES ('$id_nuevo_usuario', '$id_departamento')";
            mysqli_query($conexionDB, $query_dir);
        }

        header('Location: ../ventanas/Usuario.php?registro=ok');
        exit;

    } else {
        echo "Error al registrar en la tabla usuario: " . mysqli_error($conexionDB);
    }
?>