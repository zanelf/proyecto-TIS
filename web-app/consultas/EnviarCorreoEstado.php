<?php

require_once __DIR__ . '/../config/mail_config.php';

require_once __DIR__ . '/../libs/PHPMailer-master/src/Exception.php';
require_once __DIR__ . '/../libs/PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../libs/PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function enviarCorreoEstado($correo, $asunto, $id_comprobante, $estado) {
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
        $mail->Subject = "Actualización de su solicitud - SGISC";
        $mail->Body = "
            <h2>Actualización de su solicitud</h2>
            <p>Su solicitud <strong>$asunto</strong> cambió al estado: <strong>$estado</strong>.</p>
            <p>Puede consultar el estado con su código de comprobante: <strong>#$id_comprobante</strong>.</p>
            <p>Muchas gracias por comunicarse con la Municipalidad.</p>
        ";

        $mail->send();
        return true;

    } catch (Exception $e) {
        return false;
    }
}
?>