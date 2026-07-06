<?php
    include('../base_de_datos/conexion.php');
    session_start();

    $Comp = null;
    $busqueda = false;

    if (isset($_POST["ID_comprobante"]) && $_POST["ID_comprobante"] != "") {
        $busqueda = true;
        $ID_comprobante = intval($_POST["ID_comprobante"]);

        $consultaCompr = mysqli_query(
            $conexionDB,
            "SELECT comprobante.ID_comprobante, comprobante.Fecha, comprobante.Hora,
                    solicitud.solicitud_ID, solicitud.Tipo_estado, solicitud.Asunto,
                    solicitud.Descripcion, solicitud.Categoria, solicitud.RUT_ciudadano,
                    departamento.nombre AS departamento,
                    tipo_solicitud.nombre AS tipo_solicitud
            FROM comprobante
            INNER JOIN solicitud ON comprobante.solicitud_ID = solicitud.solicitud_ID
            LEFT JOIN departamento ON solicitud.ID_departamento = departamento.ID_departamento
            LEFT JOIN tipo_solicitud ON solicitud.ID_tipo_solicitud = tipo_solicitud.ID_tipo_solicitud
            WHERE comprobante.ID_comprobante = $ID_comprobante"
        );

        $Comp = mysqli_fetch_assoc($consultaCompr);
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguimiento de Solicitud - SGISC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../recursos/css/style_enviar_solicitud_formulario.css">
</head>
<body>

<?php include("../recursos/componentes/navbar1.php"); ?>

<div class="cuerpo-pag">
    <div class="container" style="max-width:780px;">

        <div class="tarjeta-formulario">

            <h1 class="formulario-tarjeta-titulo">Seguimiento de Solicitud</h1>
            <p class="formulario-tarjeta-subtitulo">Ingrese el codigo de comprobante para consultar el estado de su solicitud.</p>
            <hr class="division">

            <form action="Seguimiento.php" method="POST">
                <div class="row g-3 align-items-end">
                    <div class="col-md-8">
                        <label class="form-label">
                            Codigo de comprobante <span class="rojito">*</span>
                        </label>
                        <input type="number" name="ID_comprobante" class="form-control" placeholder="Ej: 1" required>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn-enviar w-100">Consultar</button>
                    </div>
                </div>
            </form>

            <?php if ($busqueda && $Comp != null): ?>
                <div class="mt-4 pt-4 border-top" style="border-color: var(--card-border) !important;">
                    <div class="alert alert-success mb-4">
                        Solicitud encontrada correctamente.
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Comprobante</label>
                            <input type="text" class="form-control" value="<?php echo $Comp["ID_comprobante"]; ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Solicitud</label>
                            <input type="text" class="form-control" value="<?php echo $Comp["solicitud_ID"]; ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha</label>
                            <input type="text" class="form-control" value="<?php echo $Comp["Fecha"]; ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Hora</label>
                            <input type="text" class="form-control" value="<?php echo $Comp["Hora"]; ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estado</label>
                            <input type="text" class="form-control" value="<?php echo $Comp["Tipo_estado"]; ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Categoria</label>
                            <input type="text" class="form-control" value="<?php echo $Comp["Categoria"]; ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tipo de solicitud</label>
                            <input type="text" class="form-control" value="<?php echo $Comp["tipo_solicitud"]; ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Departamento</label>
                            <input type="text" class="form-control" value="<?php echo $Comp["departamento"]; ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">RUT ciudadano</label>
                            <input type="text" class="form-control" value="<?php echo $Comp["RUT_ciudadano"]; ?>" readonly>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Asunto</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($Comp["Asunto"]); ?>" readonly>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Descripcion</label>
                            <textarea class="form-control" readonly><?php echo htmlspecialchars($Comp["Descripcion"]); ?></textarea>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($busqueda && $Comp == null): ?>
                <div class="alert alert-warning mt-4 mb-0">
                    No se encontro una solicitud asociada al codigo ingresado.
                </div>
            <?php endif; ?>

            <div class="d-flex justify-content-end align-items-center gap-2 pt-4 mt-4 border-top"
                 style="border-color: var(--card-border) !important;">
                <a href="../index.php" class="btn-cancelar">Volver</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
