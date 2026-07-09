<?php
    include('../base_de_datos/conexion.php');
    
    $usuario = mysqli_real_escape_string($conexionDB, $_POST["usuario"]);
    $password = $_POST["password"];
    
    $consulta = "SELECT * FROM usuario WHERE rut_usuario='$usuario' LIMIT 1";
    $resultado = mysqli_query($conexionDB, $consulta);

    
    if(mysqli_num_rows($resultado) > 0){
        $datosUsuario = mysqli_fetch_assoc($resultado);

        if (password_verify($password, $datosUsuario["contraseña"])) {
            
            session_start();
            $_SESSION["rut_usuario"] = $datosUsuario["rut_usuario"];
            $_SESSION["ID_usuario"] = $datosUsuario["ID_usuario"];

            $id_user = $_SESSION["ID_usuario"];

            $query_trabajador = "SELECT * FROM trabajador WHERE ID_usuario='$id_user' LIMIT 1";
            $res_trabajador = mysqli_query($conexionDB, $query_trabajador);

            if(mysqli_num_rows($res_trabajador) > 0) {
                $datosTrabajador = mysqli_fetch_assoc($res_trabajador);
                
                $_SESSION["tipo"] = $datosTrabajador["tipo_trabajador"]; 
                $_SESSION["ID_departamento"] = $datosTrabajador["ID_departamento"];
            } else {
                if($datosUsuario["is_admin"] == 1) {
                    $_SESSION["tipo"] = "Administrador";
                } else {
                    $_SESSION["tipo"] = "Invitado";
                }
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