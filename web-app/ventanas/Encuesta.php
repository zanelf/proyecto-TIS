<?php
session_start();
include('../base_de_datos/conexion.php');

$token = $_GET["token"] ?? "";
$solicitud = null;
$encuestaRespondida = false;

if ($token != "") {
    $tokenSeguro = mysqli_real_escape_string($conexionDB, $token);

    $consulta = "
        SELECT solicitud.solicitud_ID, solicitud.Asunto
        FROM solicitud
        WHERE solicitud.token_encuesta = '$tokenSeguro'
        LIMIT 1
    ";

    $resultado = mysqli_query($conexionDB, $consulta);
    $solicitud = mysqli_fetch_assoc($resultado);

    if ($solicitud) {
        $idSolicitud = $solicitud["solicitud_ID"];

        $consultaEncuesta = "SELECT ID_encuesta FROM encuesta WHERE solicitud_ID = $idSolicitud LIMIT 1";
        $resultadoEncuesta = mysqli_query($conexionDB, $consultaEncuesta);

        if (mysqli_num_rows($resultadoEncuesta) > 0) {
            $encuestaRespondida = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encuesta de Satisfacción - SGISC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../recursos/css/style_enviar_solicitud_formulario.css">
</head>

<body>
<?php include("../recursos/componentes/navbar1.php"); ?>

<div class="cuerpo-pag">
    <div class="container" style="max-width:780px;">

        <div class="formulario-tarjeta">

            <h1 class="formulario-tarjeta-titulo">Encuesta de Satisfacción</h1>
            <p class="formulario-tarjeta-subtitulo">
                Su opinión nos ayuda a mejorar la atención municipal.
            </p>
            <hr class="division">
            
            <?php if (isset($_GET["gracias"]) && $_GET["gracias"] == "1"): ?>

    <div class="alert alert-success">
        <h5 class="mb-2">¡Muchas gracias por responder la encuesta!</h5>
        <p class="mb-0">
            Su opinión es muy importante para seguir mejorando la atención de la Municipalidad.
        </p>
    </div>

    <a href="../index.php" class="btn-enviar">Volver al inicio</a>

<?php elseif ($token == "" || !$solicitud): ?>
    
            <?php elseif ($token == "" || !$solicitud): ?>

                <div class="alert alert-danger">
                    El enlace de encuesta no es válido o ya no se encuentra disponible.
                </div>

                <a href="../index.php" class="btn-cancelar">Volver al inicio</a>

            <?php elseif ($encuestaRespondida): ?>

                <div class="alert alert-info">
                    Esta encuesta ya fue respondida. Muchas gracias por su participación.
                </div>

                <a href="../index.php" class="btn-cancelar">Volver al inicio</a>

            <?php else: ?>

                <div class="alert alert-primary">
                    Solicitud asociada: <strong><?php echo htmlspecialchars($solicitud["Asunto"]); ?></strong>
                </div>

                <form action="../consultas/Insertarencuesta.php" method="POST">

                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            1. ¿Cómo evalúa la atención recibida?
                        </label>
                        <select name="respuesta_p1" class="form-select" required>
                            <option value="" disabled selected>Seleccione una calificación</option>
                            <option value="1">1 - Muy insatisfecho/a</option>
                            <option value="2">2 - Insatisfecho/a</option>
                            <option value="3">3 - Neutral</option>
                            <option value="4">4 - Satisfecho/a</option>
                            <option value="5">5 - Muy satisfecho/a</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            2. ¿La respuesta entregada resolvió su solicitud?
                        </label>
                        <select name="respuesta_p2" class="form-select" required>
                            <option value="" disabled selected>Seleccione una calificación</option>
                            <option value="1">1 - No resolvió nada</option>
                            <option value="2">2 - Resolución insuficiente</option>
                            <option value="3">3 - Resolución parcial</option>
                            <option value="4">4 - Resolución adecuada</option>
                            <option value="5">5 - Resolución completa</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            3. ¿Qué tan clara y comprensible fue la respuesta entregada?
                        </label>
                        <select name="respuesta_p3" class="form-select" required>
                            <option value="" disabled selected>Seleccione una calificación</option>
                            <option value="1">1 - Muy confusa</option>
                            <option value="2">2 - Poco clara</option>
                            <option value="3">3 - Medianamente clara</option>
                            <option value="4">4 - Clara</option>
                            <option value="5">5 - Muy clara</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                        <a href="../index.php" class="btn-cancelar">Cancelar</a>
                        <button type="submit" class="btn-enviar">Enviar encuesta</button>
                    </div>

                </form>

            <?php endif; ?>

        </div>
    </div>
</div>

</body>
</html>