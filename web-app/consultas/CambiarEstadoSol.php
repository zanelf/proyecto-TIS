<?php
    session_start();
    include('../base_de_datos/conexion.php');
    require_once('GenerarTokenEncuesta.php');
    require_once('EnviarCorreoEstado.php');

    if (!isset($_SESSION["usuario"])) {
        header("Location: ../index.php");
        exit;
    }

    if (!isset($_POST['ID_cambio']) || !isset($_POST['estadoSiguiente'])) {
        echo "Faltan parámetros para cambiar el estado.";
        exit;
    }

    $idSolicitud = mysqli_real_escape_string($conexionDB, $_POST['ID_cambio']);
    $estadoSiguiente = mysqli_real_escape_string($conexionDB, $_POST['estadoSiguiente']);

    $estadosValidos = ['En revision', 'Derivada', 'En proceso', 'Respondida', 'Anulada'];
    if (!in_array($estadoSiguiente, $estadosValidos)) {
        echo "Estado no válido.";
        exit;
    }

    // datos para el correo
    $datosSol = mysqli_fetch_assoc(mysqli_query($conexionDB,
        "SELECT solicitud.Asunto, solicitud.correo_electronico, comprobante.ID_comprobante
         FROM solicitud
         LEFT JOIN comprobante ON comprobante.solicitud_ID = solicitud.solicitud_ID
         WHERE solicitud.solicitud_ID = '$idSolicitud'
         LIMIT 1"
    ));

    if ($estadoSiguiente == 'Respondida') {
        $respuesta = isset($_POST['respuesta']) ? mysqli_real_escape_string($conexionDB, trim($_POST['respuesta'])) : '';
        if ($respuesta === '') {
            echo "Debe ingresar una respuesta antes de marcar la solicitud como respondida.";
            exit;
        }

        $consulta = "UPDATE solicitud
                     SET Tipo_estado = 'Respondida', respuesta = '$respuesta', fecha_respondida = NOW()
                     WHERE solicitud_ID = '$idSolicitud'";
        mysqli_query($conexionDB, $consulta);

        generarTokenEncuesta($conexionDB, $idSolicitud);

    } elseif ($estadoSiguiente == 'Anulada') {
        $motivo = isset($_POST['motivo_anulacion']) ? mysqli_real_escape_string($conexionDB, trim($_POST['motivo_anulacion'])) : '';
        if ($motivo === '') {
            echo "Por favor, ingrese un motivo de anulación.";
            exit;
        }

        $consulta = "UPDATE solicitud
                     SET Tipo_estado = 'Anulada', motivo_anulacion = '$motivo'
                     WHERE solicitud_ID = '$idSolicitud'";
        mysqli_query($conexionDB, $consulta);

        if ($datosSol && $datosSol['correo_electronico']) {
            enviarCorreoEstado(
                $datosSol['correo_electronico'],
                $datosSol['Asunto'],
                $datosSol['ID_comprobante'],
                'Anulada',
                $motivo
            );
        }

    } else {
        $consulta = "UPDATE solicitud SET Tipo_estado = '$estadoSiguiente' WHERE solicitud_ID = '$idSolicitud'";
        mysqli_query($conexionDB, $consulta);

        if ($datosSol && $datosSol['correo_electronico']) {
            enviarCorreoEstado(
                $datosSol['correo_electronico'],
                $datosSol['Asunto'],
                $datosSol['ID_comprobante'],
                $estadoSiguiente
            );
        }
    }

    if ($_SESSION["tipo"] == "Funcionario") {
        header("Location: ../ventanas/solicitud.php?actualizado=ok");
    } else {
        header("Location: ../ventanas/director.php?actualizado=ok");
    }
    exit;
?>