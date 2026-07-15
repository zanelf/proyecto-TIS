<?php

require_once __DIR__ . '/../config/mail_config.php';

require_once __DIR__ . '/../libs/PHPMailer-master/src/Exception.php';
require_once __DIR__ . '/../libs/PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../libs/PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function enviarCorreoEncuesta($correoCiudadano, $asuntoSolicitud, $tokenEncuesta) {
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
        $mail->addAddress($correoCiudadano);

        $linkEncuesta = "http://localhost/xampp/proyecto-TIS/web-app/ventanas/Encuesta.php?token=" . $tokenEncuesta;

        $mail->isHTML(true);
        $mail->Subject = "Encuesta de satisfacción - SGISC";

        $mail->Body = "
            <h2>Su solicitud ha sido respondida</h2>
            <p>Estimado/a ciudadano/a:</p>
            <p>La solicitud asociada al asunto <strong>$asuntoSolicitud</strong> fue respondida por la municipalidad.</p>
            <p>Le invitamos a responder una breve encuesta de satisfacción.</p>
            <p>
                <a href='$linkEncuesta' style='background:#112d57;color:white;padding:10px 16px;text-decoration:none;border-radius:6px;'>
                    Responder encuesta
                </a>
            </p>
            <p>Muchas gracias por ayudarnos a mejorar.</p>
        ";

        $mail->send();
        return true;

    } catch (Exception $e) {
    echo "Error PHPMailer: " . $mail->ErrorInfo;
    return false;
}
}
?>