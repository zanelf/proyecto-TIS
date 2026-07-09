<?php
session_start();
include('../base_de_datos/conexion.php');

$tipos_solicitudes = mysqli_query($conexionDB, "SELECT * FROM tipo_solicitud");
$departamentos = mysqli_query($conexionDB, "SELECT * FROM departamento");
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Solicitud — SGISC</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
        crossorigin="anonymous">

    <link rel="stylesheet" href="../recursos/css/style_enviar_solicitud_formulario.css">
</head>

<body>

    <?php include("../recursos/componentes/navbar1.php"); ?>

    <div class="cuerpo-pag">
        <div class="container" style="max-width:780px;">

            <div class="formulario-tarjeta">

                <h1 class="formulario-tarjeta-titulo">Nueva Solicitud Ciudadana</h1>

                <p class="formulario-tarjeta-subtitulo">
                    Complete el siguiente formulario para registrar una nueva solicitud.
                </p>

                <hr class="division">

                <form action="../consultas/Insertarsolicitud.php" method="post">

                    <!-- Correo y Tipo -->
                    <div class="row g-3 mb-3">

                        <div class="col-md-6">
                            <label class="form-label">
                                Correo electrónico
                                <span class="rojito">*</span>
                            </label>

                            <input
                                type="email"
                                name="correo"
                                class="form-control"
                                maxlength="100"
                                placeholder="ejemplo@correo.cl"
                                required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Tipo de solicitud
                                <span class="rojito">*</span>
                            </label>

                            <select
                                name="tipo"
                                class="form-select"
                                required>

                                <option value="" selected disabled>
                                    Seleccione un tipo...
                                </option>

                                <?php while ($tipo = mysqli_fetch_assoc($tipos_solicitudes)) : ?>

                                    <option value="<?= $tipo["ID_tipo_solicitud"] ?>">
                                        <?= htmlspecialchars($tipo["nombre"]) ?>
                                    </option>

                                <?php endwhile; ?>

                            </select>
                        </div>

                    </div>

                    <!-- Departamento y Categoría -->
                    <div class="row g-3 mb-3">

                        <div class="col-md-6">

                            <label class="form-label">
                                Departamento
                                <span class="rojito">*</span>
                            </label>

                            <select
                                name="departamento"
                                class="form-select"
                                required>

                                <option value="" selected disabled>
                                    Seleccione un departamento...
                                </option>

                                <?php while ($departamento = mysqli_fetch_assoc($departamentos)) : ?>

                                    <option value="<?= $departamento["ID_departamento"] ?>">
                                        <?= htmlspecialchars($departamento["nombre"]) ?>
                                    </option>

                                <?php endwhile; ?>

                            </select>

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                Categoría
                                <span class="rojito">*</span>
                            </label>

                            <select
                                name="categoria"
                                class="form-select"
                                required>

                                <option value="" selected disabled>
                                    Seleccione una categoría...
                                </option>

                                <option value="Agua">Agua</option>
                                <option value="Electrico">Eléctrico</option>
                                <option value="Transito">Tránsito</option>

                            </select>

                        </div>

                    </div>

                    <!-- Asunto -->
                    <div class="mb-3">

                        <label class="form-label">
                            Asunto
                            <span class="rojito">*</span>
                        </label>

                        <input
                            type="text"
                            name="asunto"
                            class="form-control"
                            maxlength="100"
                            placeholder="Ingrese el asunto de la solicitud"
                            required>

                    </div>

                    <!-- Descripción -->
                    <div class="mb-4">

                        <label class="form-label">
                            Descripción
                            <span class="rojito">*</span>
                        </label>

                        <textarea
                            name="descripcion"
                            class="form-control"
                            rows="5"
                            maxlength="100"
                            placeholder="Describa detalladamente la solicitud..."
                            required></textarea>

                    </div>

                    <!-- Botones -->
                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">

                        <a href="../index.php" class="btn btn-secondary">
                            Cancelar
                        </a>

                        <button type="submit" class="btn btn-primary">
                            Enviar solicitud
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>