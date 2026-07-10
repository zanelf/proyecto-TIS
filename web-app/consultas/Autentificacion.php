<?php
    include('../base_de_datos/conexion.php');
    
    $usuario = mysqli_real_escape_string($conexionDB, $_POST["usuario"]);
    $password = $_POST["password"];
    

    $consulta = "SELECT * FROM usuario WHERE rut_usuario='$usuario' LIMIT 1";
    $resultado = mysqli_query($conexionDB, $consulta);

    if(mysqli_num_rows($resultado) > 0){
        $datosUsuario = mysqli_fetch_assoc($resultado);

        if ((int)$datosUsuario["activo"] === 0) {
            header('Location: ../ventanas/Inicio.php?error=inactivo');
            exit;
        }

        if (password_verify($password, $datosUsuario["contraseña"])) {
            
            session_start();
            $_SESSION["usuario"] = $datosUsuario["rut_usuario"];
            $_SESSION["ID_usuario"] = $datosUsuario["ID_usuario"];
            
            $var = $_SESSION["ID_usuario"];

            $dev = "SELECT * FROM desarrollador WHERE ID_usuario='$var';";
            $esDev = mysqli_query($conexionDB, $dev);

            $trab = "SELECT * FROM trabajador WHERE ID_usuario='$var';";
            $esTrab = mysqli_fetch_assoc(mysqli_query($conexionDB, $trab));

            if ((int)$datosUsuario["is_admin"] === 1) {
                $_SESSION["ID_departamento"] = "";
                $_SESSION["tipo"] = "Administrador";
            }
            elseif ($esTrab && $esTrab["tipo_trabajador"] == "funcionario") {
                $_SESSION["ID_departamento"] = $esTrab["ID_departamento"];
                $_SESSION["tipo"] = "Funcionario";
            }
            elseif ($esTrab && $esTrab["tipo_trabajador"] == "director") {
                $_SESSION["ID_departamento"] = $esTrab["ID_departamento"];
                $_SESSION["tipo"] = "Director";
            }
            elseif (mysqli_num_rows($esDev) > 0) {
                $_SESSION["tipo"] = "Desarrollador";
            }

            if (isset($_SESSION["tipo"])) {
                switch ($_SESSION["tipo"]) {
                    case "Administrador":
                        header('Location: ../ventanas/Usuario.php');
                        break;
                    case "Director":
                        header('Location: ../ventanas/director.php');
                        break;
                    case "Funcionario":
                        header('Location: ../ventanas/solicitud.php');
                        break;
                    default:
                        header('Location: ../ventanas/Inicio.php?error=RolInvalido');
                        break;
                }
                exit;
            }
            exit;

        } else {
            header('Location: ../ventanas/Inicio.php?error=clave');
            exit;
        }
    } else {
        header('Location: ../ventanas/Inicio.php?error=usuario');
        exit;
    }
?>