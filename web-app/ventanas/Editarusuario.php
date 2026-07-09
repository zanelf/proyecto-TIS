<?php
    session_start();
    include('../base_de_datos/conexion.php');

    if (!isset($_SESSION["usuario"])) {
        header("Location: ../index.php");
        exit;
    }

    if ($_SESSION["tipo"] !== "Administrador") {
        header("Location: Inicio.php?error=NoAutorizado");
        exit;
    }

    if (!isset($_GET['id_enviado'])) {
        echo "Faltan parámetros para editar.";
        exit;
    }

    $id_usuario = $_GET['id_enviado'];

    $consulta_usuario = "SELECT * FROM usuario WHERE ID_usuario = '$id_usuario'";
    $resultado_usuario = mysqli_query($conexionDB, $consulta_usuario);
    $usuarioActual = mysqli_fetch_assoc($resultado_usuario);

    if (!$usuarioActual) {
        echo "Usuario no encontrado.";
        exit;
    }

    $rolActual = "";
    if ((int)$usuarioActual["is_admin"] === 1) {
        $rolActual = "Administrador";
    } elseif (mysqli_num_rows(mysqli_query($conexionDB, "SELECT ID_usuario FROM desarrollador WHERE ID_usuario = '$id_usuario'")) > 0) {
        $rolActual = "Desarrollador";
    } else {
        $trabajadorRol = mysqli_fetch_assoc(mysqli_query($conexionDB, "SELECT tipo_trabajador FROM trabajador WHERE ID_usuario = '$id_usuario'"));
        if ($trabajadorRol) {
            $rolActual = ucfirst($trabajadorRol["tipo_trabajador"]);
        }
    }

    $requiereFicha = ($rolActual == "Funcionario" || $rolActual == "Director");

    $trabajadorActual = null;
    if ($requiereFicha) {
        $consulta_trabajador = "SELECT * FROM trabajador WHERE ID_usuario = '$id_usuario'";
        $resultado_trabajador = mysqli_query($conexionDB, $consulta_trabajador);
        $trabajadorActual = mysqli_fetch_assoc($resultado_trabajador);
    }

    $query_deptos = "SELECT * FROM departamento";
    $res_deptos = mysqli_query($conexionDB, $query_deptos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario - SGISC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../recursos/css/style_index.css">
    <link rel="stylesheet" href="../recursos/css/style_mantenedores.css">
</head>
<body class="bg-light">
    <?php include('../recursos/componentes/navbar1.php'); ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm border" style="border-radius: 8px; overflow: hidden;">
                    <div class="card-header custom-card-header py-3">
                        <h6 class="mb-0 fw-bold text-primary text-uppercase small tracking-wider">
                            Editar Usuario (RUT: <?php echo htmlspecialchars($usuarioActual['rut_usuario']); ?>)
                        </h6>
                    </div>
                    <div class="card-body p-4 bg-white">
                        <form action="../recursos/componentes/ActualizarUsuario.php" method="POST">
                            <input type="hidden" name="ID_usuario" value="<?php echo $id_usuario; ?>">

                            <div class="mb-3">
                                <label class="form-label fw-semibold">RUT</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($usuarioActual['rut_usuario']); ?>" disabled>
                                <div class="form-text">El RUT no puede modificarse.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tipo de trabajador</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($rolActual); ?>" disabled>
                                <div class="form-text">El tipo de trabajador no puede modificarse.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Correo</label>
                                <input type="email" class="form-control" name="correo_usuario" value="<?php echo htmlspecialchars($usuarioActual['correo_usuario']); ?>" required>
                            </div>

                            <?php if ($requiereFicha && $trabajadorActual): ?>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Nombre</label>
                                    <input type="text" class="form-control" name="nombre" value="<?php echo htmlspecialchars($trabajadorActual['nombre']); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Apellido</label>
                                    <input type="text" class="form-control" name="apellido" value="<?php echo htmlspecialchars($trabajadorActual['apellido']); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Departamento / Unidad Municipal</label>
                                    <select name="id_departamento" class="form-select" required>
                                        <?php while ($depto = mysqli_fetch_assoc($res_deptos)): ?>
                                            <option value="<?php echo $depto['ID_departamento']; ?>"
                                                <?php echo ($depto['ID_departamento'] == $trabajadorActual['ID_departamento']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($depto['nombre']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Previsión</label>
                                    <select name="prevision" class="form-select">
                                        <option value="Fonasa" <?php echo ($trabajadorActual['prevision'] == 'Fonasa') ? 'selected' : ''; ?>>Fonasa</option>
                                        <option value="Isapres" <?php echo ($trabajadorActual['prevision'] == 'Isapres') ? 'selected' : ''; ?>>Isapre</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">AFP</label>
                                    <select name="afp" class="form-select">
                                        <?php
                                            $afps = ["AFP Capital" => "Capital", "AFP Cuprum" => "Cuprum", "AFP Habitat" => "Habitat", "AFP Modelo" => "Modelo", "AFP Planvital" => "PlanVital", "AFP Provida" => "Provida", "AFPUno" => "Uno"];
                                            foreach ($afps as $valor => $etiqueta) {
                                                $sel = ($trabajadorActual['AFP'] == $valor) ? 'selected' : '';
                                                echo "<option value=\"$valor\" $sel>$etiqueta</option>";
                                            }
                                        ?>
                                    </select>
                                </div>

                            <?php endif; ?>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="Usuario.php" class="btn btn-sm btn-secondary fw-medium px-4 shadow-sm py-2">
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-sm btn-success fw-medium px-4 shadow-sm py-2">
                                    Guardar Cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('../recursos/componentes/footer.php'); ?>
</body>
</html>