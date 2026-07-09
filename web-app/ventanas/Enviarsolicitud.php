<?php
    session_start();
    include('../base_de_datos/conexion.php');
    $consulta = "SELECT * FROM solicitud";
    $resultado = mysqli_query($conexionDB,$consulta);

    $atributos = mysqli_query($conexionDB,"DESCRIBE solicitud");

    $tipos_solicitudes=mysqli_query($conexionDB,"SELECT * FROM tipo_solicitud");
    $departamentos=mysqli_query($conexionDB,"SELECT * FROM departamento");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Solicitud — SGISC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../recursos/css/style_enviar_solicitud_formulario.css">
</head>
<body>


<?php include("../recursos/componentes/navbar1.php"); ?>

<?php
    $campos = [];
    while ($fila = mysqli_fetch_assoc($atributos)) {
        $campos[] = $fila;
    }
    foreach ($campos as &$campo) {
        if (str_contains($campo['Type'], "int"))     $campo['Type'] = "number";
        if (str_contains($campo['Type'], "varchar")) $campo['Type'] = "text";
    }
    unset($campo);
?>

<div class="cuerpo-pag">
    <div class="container" style="max-width:780px;">

       
        <div class="formulario-tarjeta">

            <h1 class="formulario-tarjeta-titulo">Nueva Solicitud Ciudadana</h1>
            <p class="formulario-tarjeta-subtitulo">Complete el formulario para registrar su solicitud.</p>
            <hr class="division">

            <form action="../consultas/Insertarsolicitud.php" method="post" enctype="multipart/form-data">

                
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">
                            Correo electrónico <span class="rojito">*</span>
                        </label>
                        <input type="email" name="correo" class="form-control"
                               placeholder="ejemplo@correo.cl" required>
                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Tipo de solicitud <span class="rojito">*</span>
                        </label>

                        <select id="tipo_solicitud" name="tipo" class="form-select" required>
                            <option value="" disabled selected>Seleccione un tipo…</option>
                            <?php while ($tipo_sol = mysqli_fetch_assoc($tipos_solicitudes)): ?>
                                <option value="<?php echo $tipo_sol["ID_tipo_solicitud"]; ?>">
                                    <?php echo htmlspecialchars($tipo_sol["nombre"]); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">
                            Departamento destino <span class="rojito">*</span>
                        </label>
                        <select id="departamento" name="departamento" class="form-select" required>
                            <option value="" disabled selected>Seleccione un departamento…</option>
                            <?php while ($dpto = mysqli_fetch_assoc($departamentos)): ?>
                                <option value="<?php echo $dpto["ID_departamento"]; ?>">
                                    <?php echo htmlspecialchars($dpto["nombre"]); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">
                            Categoría <span class="rojito">*</span>
                        </label>
                        <select id="categoria" name="categoria" class="form-select" required>
                            <option value="" disabled selected>Seleccione una categoría…</option>
                            <option value="Agua">Agua</option>
                            <option value="Eléctrico">Eléctrico</option>
                            <option value="Transito">Transito</option>
                        </select>
                    </div>
                </div>

             
                <div class="mb-3">
                    <label class="form-label">
                        Asunto <span class="rojito">*</span>
                    </label>
                    <input type="text" name="asunto" class="form-control"
                           placeholder="Describa brevemente el asunto" required>
                </div>

                
                <div class="mb-3">
                    <label class="form-label">
                        Descripción <span class="rojito">*</span>
                    </label>
                    <textarea name="descripcion" class="form-control"
                              placeholder="Detalle su solicitud con la mayor información posible…"
                              required></textarea>
                </div>

             
                <div class="mb-4">
                    <label class="form-label">Adjuntar archivo</label>
                    <input type="file" id="archivo" name="archivo" class="form-control">
                   <p class="form-text text-muted mb-0">Formatos permitidos: PDF, JPG, PNG. Tamaño máximo: 5 MB.</p>
                </div>

                
                <div class="d-flex justify-content-end align-items-center gap-2 pt-2 border-top"
                     style="border-color: var(--card-border) !important;">
                    <a href="../index.php" class="btn-cancelar">Volver</a>
                    <button type="submit" class="btn-enviar">Enviar Solicitud</button>
                </div>

            </form>
        </div>
</div>

</body>
</html>
