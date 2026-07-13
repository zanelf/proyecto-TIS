<?php
    session_start();
    include('../base_de_datos/conexion.php');
    require_once('../consultas/RegistrarLogEstado.php');

    $Comp = null;
    $busqueda = false;

    if (isset($_POST["ID_comprobante"]) && $_POST["ID_comprobante"] != "") {
        $busqueda = true;
        $ID_comprobante = intval($_POST["ID_comprobante"]);

        $consultaCompr = mysqli_query(
            $conexionDB,
            "SELECT comprobante.ID_comprobante, comprobante.Fecha, comprobante.Hora,
                    solicitud.solicitud_ID, solicitud.Tipo_estado, solicitud.Asunto,
                    solicitud.Descripcion, solicitud.correo_electronico,
                    solicitud.fecha_creacion,
                    categoria.nombre AS categoria,
                    departamento.nombre AS departamento,
                    tipo_solicitud.nombre AS tipo_solicitud
            FROM comprobante
            INNER JOIN solicitud ON comprobante.solicitud_ID = solicitud.solicitud_ID
            LEFT JOIN categoria ON solicitud.ID_categoria = categoria.ID_categoria
            LEFT JOIN departamento ON solicitud.ID_departamento = departamento.ID_departamento
            LEFT JOIN tipo_solicitud ON solicitud.ID_tipo_solicitud = tipo_solicitud.ID_tipo_solicitud
            WHERE comprobante.ID_comprobante = $ID_comprobante"
        );

        $Comp = mysqli_fetch_assoc($consultaCompr);
    }

    $flujoEstados = ['Recibida', 'En revision', 'Derivada', 'En proceso', 'Respondida', 'Cerrada'];
    $etiquetas = [
        'Recibida'    => 'Recibida',
        'En revision' => 'En revisión',
        'Derivada'    => 'Derivada a departamento',
        'En proceso'  => 'En proceso',
        'Respondida'  => 'Respondida',
        'Cerrada'     => 'Cerrada',
        'Anulada'     => 'Anulada',
    ];

    $estadoActual = $Comp['Tipo_estado'] ?? '';
    $indiceActual = array_search($estadoActual, $flujoEstados);

    // historial de cambios (estados)
    $fechasPorEstado = [];
    if ($Comp) {
        $historial = obtenerLogEstado($conexionDB, $Comp['solicitud_ID']);
        foreach ($historial as $evento) {
            $fechasPorEstado[$evento['estado_nuevo']] = $evento['fecha_hora_cambio'];
        }
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
    <link rel="stylesheet" href="../recursos/css/style_seguimiento.css?v=1">
</head>
<body>

<?php include("../recursos/componentes/navbar1.php"); ?>

<div class="cuerpo-pag">
    <div class="container" style="max-width:780px;">

        <div class="mb-3">
            <a href="../index.php" class="text-decoration-none text-muted small">← Volver al inicio</a>
        </div>

        <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius:12px;">
            <h1 class="formulario-tarjeta-titulo">Seguimiento de Solicitud</h1>
            <p class="formulario-tarjeta-subtitulo">Ingrese su código de comprobante para consultar el estado.</p>

            <form action="Seguimiento.php" method="POST" class="mt-3">
                <div class="d-flex gap-2">
                    <input type="number" name="ID_comprobante" class="form-control" placeholder="Ej: 1" required
                           value="<?php echo isset($_POST['ID_comprobante']) ? intval($_POST['ID_comprobante']) : ''; ?>">
                    <button type="submit" class="btn-enviar" style="white-space:nowrap;">Consultar</button>
                </div>
            </form>
        </div>

        <?php if ($busqueda && $Comp == null): ?>
            <div class="alert alert-warning">
                No se encontró una solicitud asociada al código ingresado.
            </div>
        <?php endif; ?>

        <?php if ($busqueda && $Comp != null): ?>

            <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius:12px;">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h5 class="fw-bold mb-0">Solicitud #<?php echo htmlspecialchars($Comp['ID_comprobante']); ?></h5>
                    <span class="badge bg-primary-subtle text-primary fw-semibold px-3 py-2" style="border-radius:20px;">
                        <?php echo htmlspecialchars($etiquetas[$estadoActual] ?? $estadoActual); ?>
                    </span>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <span class="text-muted small">Tipo de solicitud: </span>
                        <strong class="small"><?php echo htmlspecialchars($Comp['tipo_solicitud'] ?? '—'); ?></strong>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted small">Departamento: </span>
                        <strong class="small"><?php echo htmlspecialchars($Comp['departamento'] ?? '—'); ?></strong>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted small">Fecha de creación: </span>
                        <strong class="small"><?php echo htmlspecialchars(date('d-m-Y', strtotime($Comp['fecha_creacion']))); ?></strong>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted small">Categoría: </span>
                        <strong class="small"><?php echo htmlspecialchars($Comp['categoria'] ?? '—'); ?></strong>
                    </div>
                </div>

                <hr>

                <p class="mb-1 text-muted small">Asunto</p>
                <p class="fw-semibold"><?php echo htmlspecialchars($Comp['Asunto']); ?></p>

                <p class="mb-1 text-muted small">Descripción</p>
                <p class="mb-0"><?php echo nl2br(htmlspecialchars($Comp['Descripcion'])); ?></p>
            </div>

            <div class="card border-0 shadow-sm p-4" style="border-radius:12px;">
                <h6 class="fw-bold mb-4">Seguimiento de la solicitud</h6>

                <?php if ($estadoActual === 'Anulada'): ?>
                    <div class="tracker-anulada">
                        <div class="tracker-circulo circulo-anulada">✕</div>
                        <div>
                            <div class="nombre-actual">Solicitud anulada</div>
                            <div class="fecha-estado"><?php echo isset($fechasPorEstado['Anulada']) ? date('d-m-Y H:i', strtotime($fechasPorEstado['Anulada'])) : '—'; ?></div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="tracker">
                        <?php foreach ($flujoEstados as $clave):
                            $indice = array_search($clave, $flujoEstados);
                            $fechaEstado = isset($fechasPorEstado[$clave])
                                ? date('d-m-Y', strtotime($fechasPorEstado[$clave]))
                                : '';
                            $horaEstado = isset($fechasPorEstado[$clave])
                                ? date('H:i', strtotime($fechasPorEstado[$clave]))
                                : '';

                            if ($clave === $estadoActual) {
                                $clasePaso = 'paso-actual'; $icono = '●';
                            } elseif ($indice < $indiceActual) {
                                $clasePaso = 'paso-ok'; $icono = '✓';
                            } else {
                                $clasePaso = 'paso-pendiente'; $icono = '';
                            }
                        ?>
                            <div class="tracker-paso <?php echo $clasePaso; ?>">
                                <div class="tracker-circulo"><?php echo $icono; ?></div>
                                <div class="tracker-etiqueta"><?php echo $etiquetas[$clave] ?? $clave; ?></div>
                                <div class="tracker-fecha">
                                    <?php if ($fechaEstado): ?>
                                        <?php echo $fechaEstado; ?><br><span class="tracker-hora"><?php echo $horaEstado; ?></span>
                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

        <?php endif; ?>

    </div>
</div>

</body>
</html>