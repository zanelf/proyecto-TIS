<?php
    include('../base_de_datos/conexion.php');
    require_once __DIR__ . '/../config/mail_config.php';
    require_once __DIR__ . '/../libs/PHPMailer-master/src/Exception.php';
    require_once __DIR__ . '/../libs/PHPMailer-master/src/PHPMailer.php';
    require_once __DIR__ . '/../libs/PHPMailer-master/src/SMTP.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    $rut_usuario = mysqli_real_escape_string($conexionDB, $_POST["rut_usuario"]);
    $correo_usuario = mysqli_real_escape_string($conexionDB, $_POST["correo_usuario"]);
    $tipo = $_POST["tipo"];
    $id_departamento = isset($_POST["id_departamento"]) ? $_POST["id_departamento"] : null;
    $nombre = isset($_POST["nombre"]) ? mysqli_real_escape_string($conexionDB, $_POST["nombre"]) : null;
    $apellido = isset($_POST["apellido"]) ? mysqli_real_escape_string($conexionDB, $_POST["apellido"]) : null;
    $tipo_trabajador = isset($_POST["tipo_trabajador"]) ? mysqli_real_escape_string($conexionDB, $_POST["tipo_trabajador"]) : null;
    $prevision = isset($_POST["prevision"]) ? mysqli_real_escape_string($conexionDB, $_POST["prevision"]) : null;
    $afp = isset($_POST["afp"]) ? mysqli_real_escape_string($conexionDB, $_POST["afp"]) : null;

    // D3.1: rut_usuario ya existe
    $consulta_existe = "SELECT ID_usuario FROM usuario WHERE rut_usuario = '$rut_usuario'";
    $resultado_existe = mysqli_query($conexionDB, $consulta_existe);
    if (mysqli_num_rows($resultado_existe) > 0) {
        header('Location: ../ventanas/Usuario.php?error=usuarioExistente');
        exit;
    }

    // D3.3: si el rol requiere ficha de trabajador, deben venir nombre, apellido y departamento
    $requiereFicha = ($tipo == "Funcionario" || $tipo == "Director");
    if ($requiereFicha && (empty($nombre) || empty($apellido) || empty($id_departamento))) {
        header('Location: ../ventanas/Usuario.php?error=datosTrabajadorIncompletos');
        exit;
    }

    // D5: el sistema genera automáticamente la contraseña
    $password_plana = substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 8);
    $contraseñahash = password_hash($password_plana, PASSWORD_BCRYPT);

    // D4.1: registrar en tabla usuario
    $consulta_base = "INSERT INTO usuario (rut_usuario, correo_usuario, contraseña, Fecha_creacion, Activo, cambioContra)
                       VALUES ('$rut_usuario', '$correo_usuario', '$contraseñahash', NOW(), 1, 1)";
    $resultado_base = mysqli_query($conexionDB, $consulta_base);

    if (!$resultado_base) {
        echo "Error al registrar en la tabla usuario: " . mysqli_error($conexionDB);
        exit;
    }

    $id_nuevo_usuario = mysqli_insert_id($conexionDB);

    // D4.2: ficha de trabajador, solo si el rol la requiere
    if ($requiereFicha) {
        $consulta_trabajador = "INSERT INTO trabajador (ID_usuario, nombre, apellido, ID_departamento, tipo_trabajador, prevision, afp)
                                 VALUES ('$id_nuevo_usuario', '$nombre', '$apellido', '$id_departamento', '$tipo_trabajador', '$prevision', '$afp')";
        mysqli_query($conexionDB, $consulta_trabajador);
    }

    // D4.3: registrar en la tabla de rol correspondiente
    if ($tipo == "Administrador") {
        mysqli_query($conexionDB, "INSERT INTO administrador (ID_usuario) VALUES ('$id_nuevo_usuario')");
    } elseif ($tipo == "Desarrollador") {
        mysqli_query($conexionDB, "INSERT INTO desarrollador (ID_usuario) VALUES ('$id_nuevo_usuario')");
    } elseif ($tipo == "Funcionario") {
        mysqli_query($conexionDB, "INSERT INTO funcionario (ID_usuario, ID_departamento) VALUES ('$id_nuevo_usuario', '$id_departamento')");
    } elseif ($tipo == "Director") {
        mysqli_query($conexionDB, "INSERT INTO director (ID_usuario, ID_departamento) VALUES ('$id_nuevo_usuario', '$id_departamento')");
    }

    // D5.2: enviar correo con las credenciales (mismo patrón que EnviarCorreoEncuesta.php)
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];
        $mail->Host = MAIL_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = MAIL_USER;
        $mail->Password = MAIL_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = MAIL_PORT;

        $mail->CharSet = 'UTF-8';
        $mail->setFrom(MAIL_USER, MAIL_FROM_NAME);
        $mail->addAddress($correo_usuario);

        $mail->isHTML(true);
        $mail->Subject = "Credenciales de acceso - SGISC";
        $mail->Body = "
            <h2>Cuenta creada</h2>
            <p>Se ha creado una cuenta de acceso al sistema SGISC con los siguientes datos:</p>
            <p><strong>Usuario (RUT):</strong> $rut_usuario</p>
            <p><strong>Contraseña:</strong> $password_plana</p>
            <p>Se recomienda cambiar la contraseña en el primer inicio de sesión.</p>
        ";

        $mail->send();
    } catch (Exception $e) {
        // D6: si el correo falla, el usuario ya quedó registrado; se avisa pero no se revierte el alta
        header('Location: ../ventanas/Usuario.php?registro=ok&correo=fallo');
        exit;
    }

    header('Location: ../ventanas/Usuario.php?registro=ok');
    exit;
?>