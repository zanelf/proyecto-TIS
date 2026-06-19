<?php
    include('../base_de_datos/conexion.php');
require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // Configuración SMTP de Gmail
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = '98l.acuna.m@gmail.com';
    $mail->Password = 'apdd wvrb zway resq';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Remitente
    $mail->setFrom('98l.acuna.m@gmail.com', 'Sistema Municipalidad');

    // Destinatario
    $mail->addAddress('fvidela@ing.ucsc.cl');

    // Contenido
    $mail->Subject = 'Comprobante de Solicitud';
    $mail->Body = 'Gracias por utilizar nuestro sistema.';

    $mail->send();

    echo "Correo enviado con éxito.";
    header('Location: ../index.php');
}
catch (Exception $e) {
    echo "Error al enviar correo: " . $mail->ErrorInfo;
}





    $consulta="";

    $correo=$_POST["correo"];
    $ID_departamento=$_POST["departamento"];
    $Asunto=$_POST["asunto"];
    $Descripcion=$_POST["descripcion"];
    $categoria="Agua";

    $ID_tipo_solicitud=$_POST["tipo"];

    $ciudadanoQuery= mysqli_query($conexionDB,"SELECT RUT_ciudadano FROM ciudadano WHERE correo_electronico='$correo'");
    $ciudadano = mysqli_fetch_assoc($ciudadanoQuery);
    $rut_ciudadano = 0;
    if(mysqli_num_rows($ciudadanoQuery)==0){
        echo "PEDIR RUT";
    }
    if(mysqli_num_rows($ciudadanoQuery)==1){
                echo "".$ciudadano["RUT_ciudadano"];
                $rut_ciudadano = $ciudadano["RUT_ciudadano"];
                $consulta = "INSERT INTO solicitud (Asunto, Descripcion, Categoria, RUT_ciudadano, ID_departamento, ID_tipo_solicitud) VALUES ('$Asunto','$Descripcion','$categoria','$rut_ciudadano','$ID_departamento','$ID_tipo_solicitud')";// EN LA BASE DE DATOS CATEGORIA TIENE OTRO DOM
                $resultado = mysqli_query($conexionDB,$consulta);
                $ID_solicitud = mysqli_insert_id($conexionDB);
                $InsertComprobante="INSERT INTO comprobante (Fecha, Hora, solicitud_ID) VALUES (CURDATE(), CURTIME(), $ID_solicitud);";
                $resultado = mysqli_query($conexionDB,$InsertComprobante);
                //header('Location: ../index.php');
    }
    if(mysqli_num_rows($ciudadanoQuery) > 1){
                echo "RUUUUUUUUUUUUUUUUT 12";
    }

?>