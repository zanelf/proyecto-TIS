<?php
    include('../base_de_datos/conexion.php');
    require_once('RegistrarLogEstado.php');
    require_once __DIR__ . '/../config/mail_config.php';
    require_once __DIR__ . '/../libs/PHPMailer-master/src/Exception.php';
    require_once __DIR__ . '/../libs/PHPMailer-master/src/PHPMailer.php';
    require_once __DIR__ . '/../libs/PHPMailer-master/src/SMTP.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    $correo = mysqli_real_escape_string($conexionDB, $_POST["correo"]);
    $Asunto = mysqli_real_escape_string($conexionDB, $_POST["asunto"]);
    $Descripcion = mysqli_real_escape_string($conexionDB, $_POST["descripcion"]);
    $ID_categoria = mysqli_real_escape_string($conexionDB, $_POST["categoria"]);
    $ID_tipo_solicitud = mysqli_real_escape_string($conexionDB, $_POST["tipo"]);

    $existe = mysqli_query($conexionDB, "SELECT correo_electronico FROM ciudadano WHERE correo_electronico = '$correo' LIMIT 1");
    if (mysqli_num_rows($existe) == 0) {
        $fila = mysqli_fetch_assoc(mysqli_query($conexionDB, "SELECT IFNULL(MAX(RUT_ciudadano),0)+1 AS nuevo FROM ciudadano"));
        $nuevoRut = $fila["nuevo"];
        if (!mysqli_query($conexionDB, "INSERT INTO ciudadano (RUT_ciudadano, correo_electronico) VALUES ($nuevoRut, '$correo')")) {
            echo "Error al registrar el correo del ciudadano: " . mysqli_error($conexionDB);
            exit;
        }
    }

    $categoriaFila = mysqli_fetch_assoc(mysqli_query($conexionDB, "SELECT ID_departamento FROM categoria WHERE ID_categoria = '$ID_categoria'"));
    if (!$categoriaFila) {
        echo "Categoría no válida.";
        exit;
    }
    $ID_departamento = $categoriaFila["ID_departamento"];

    $consulta = "INSERT INTO solicitud (Asunto, Descripcion, ID_categoria, correo_electronico, ID_departamento, ID_tipo_solicitud, fecha_creacion)
                 VALUES ('$Asunto', '$Descripcion', '$ID_categoria', '$correo', '$ID_departamento', '$ID_tipo_solicitud', CURDATE())";

    if (!mysqli_query($conexionDB, $consulta)) {
        echo "Error al registrar la solicitud: " . mysqli_error($conexionDB);
        exit;
    }

    $ID_solicitud = mysqli_insert_id($conexionDB);

    mysqli_query($conexionDB, "INSERT INTO comprobante (Fecha, Hora, solicitud_ID) VALUES (CURDATE(), CURTIME(), $ID_solicitud)");

    $comp = mysqli_fetch_assoc(mysqli_query($conexionDB, "SELECT ID_comprobante FROM comprobante WHERE solicitud_ID = $ID_solicitud LIMIT 1"));
    $ID_comprobante = $comp['ID_comprobante'];

    // llamamos al log
    registrarLogEstado($conexionDB, $ID_solicitud, null, 'Recibida', null);

    // envío de correo con comprobante
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
        $mail->addAddress($correo);

        $mail->isHTML(true);
        $mail->Subject = "Solicitud recibida - SGISC";
        $mail->Body = "
            <h2>Su solicitud ha sido recibida</h2>
            <p>Le confirmamos que su solicitud <strong>$Asunto</strong> fue registrada exitosamente.</p>
            <p><strong>Código de comprobante:</strong> #$ID_comprobante</p>
            <p>Guarde este código para consultar el estado de su solicitud.</p>
            <p>Muchas gracias por comunicarse con la Municipalidad.</p>
        ";

        $mail->send();
    } catch (Exception $e) {
        // si falla el correo igual se redirige
    }

    header('Location: ../index.php');
    exit;
?>