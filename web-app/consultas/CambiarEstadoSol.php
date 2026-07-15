<?php
    session_start();
    include('../base_de_datos/conexion.php');
    require_once('GenerarTokenEncuesta.php');
    require_once('EnviarCorreoEstado.php');
    require_once('RegistrarLogEstado.php');
    require_once('CalcularVencimiento.php');

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
    $idUsuario = isset($_SESSION['ID_usuario']) ? $_SESSION['ID_usuario'] : null;

    $estadosValidos = ['En revision', 'Derivada', 'En proceso', 'Respondida', 'Anulada'];
    if (!in_array($estadoSiguiente, $estadosValidos)) {
        echo "Estado no válido.";
        exit;
    }

    
    $datosSol = mysqli_fetch_assoc(mysqli_query($conexionDB,
        "SELECT solicitud.Tipo_estado, solicitud.Asunto, solicitud.correo_electronico,
                solicitud.ID_tipo_solicitud, solicitud.ID_departamento, comprobante.ID_comprobante
         FROM solicitud
         LEFT JOIN comprobante ON comprobante.solicitud_ID = solicitud.solicitud_ID
         WHERE solicitud.solicitud_ID = '$idSolicitud'
         LIMIT 1"
    ));
    $estadoAnterior = $datosSol ? $datosSol['Tipo_estado'] : null;

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

        registrarLogEstado($conexionDB, $idSolicitud, $estadoAnterior, 'Respondida', $idUsuario);

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

        registrarLogEstado($conexionDB, $idSolicitud, $estadoAnterior, 'Anulada', $idUsuario);

        if ($datosSol && $datosSol['correo_electronico']) {
            enviarCorreoEstado(
                $datosSol['correo_electronico'],
                $datosSol['Asunto'],
                $datosSol['ID_comprobante'],
                'Anulada',
                $motivo
            );
        }

    } elseif ($estadoSiguiente == 'Derivada' && isset($_POST['ID_prioridad'])) {
        
        $idPrioridad = mysqli_real_escape_string($conexionDB, $_POST['ID_prioridad']);

        $diasSLA = obtenerTiempoSLA(
            $conexionDB,
            $idPrioridad,
            $datosSol['ID_tipo_solicitud'],
            $datosSol['ID_departamento']
        );

        if ($diasSLA !== null) {
            $fechaVencimiento = sumarDiasHabiles($diasSLA);
            $consulta = "UPDATE solicitud
                         SET Tipo_estado = 'Derivada',
                             ID_prioridad = '$idPrioridad',
                             tiempo_asignado = '$diasSLA',
                             fecha_vencimiento = '$fechaVencimiento'
                         WHERE solicitud_ID = '$idSolicitud'";
        } else {
            //si no hay regla solametne derivamos sin regla
            $consulta = "UPDATE solicitud
                         SET Tipo_estado = 'Derivada', ID_prioridad = '$idPrioridad'
                         WHERE solicitud_ID = '$idSolicitud'";
        }
        mysqli_query($conexionDB, $consulta);

        registrarLogEstado($conexionDB, $idSolicitud, $estadoAnterior, 'Derivada', $idUsuario);

        if ($datosSol && $datosSol['correo_electronico']) {
            enviarCorreoEstado(
                $datosSol['correo_electronico'],
                $datosSol['Asunto'],
                $datosSol['ID_comprobante'],
                'Derivada'
            );
        }

    } else {
        $consulta = "UPDATE solicitud SET Tipo_estado = '$estadoSiguiente' WHERE solicitud_ID = '$idSolicitud'";
        mysqli_query($conexionDB, $consulta);

        registrarLogEstado($conexionDB, $idSolicitud, $estadoAnterior, $estadoSiguiente, $idUsuario);

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