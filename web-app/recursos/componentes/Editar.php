<?php
include_once('../../base_de_datos/conexion.php');

if (!isset($_GET['id_enviado']) || !isset($_GET['tipomod'])) {
    echo "Faltan parámetros para editar.";
    exit;
}

$id_recibido = mysqli_real_escape_string($conexionDB, $_GET["id_enviado"]);
$modelo = $_GET["tipomod"];

// pk
$atributos = mysqli_query($conexionDB, "DESCRIBE " . $modelo);
$PKmodelo = "";
$campos = [];
while ($fila = mysqli_fetch_assoc($atributos)) {
    $campos[] = $fila;
    if ($fila['Key'] == "PRI") {
        $PKmodelo = $fila['Field'];
    }
}

$consulta = "SELECT * FROM $modelo WHERE $PKmodelo = '$id_recibido'";
$resultado = mysqli_query($conexionDB, $consulta);
$registro = mysqli_fetch_assoc($resultado);

if (!$registro) {
    echo "Registro no encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar <?php echo ucfirst(str_replace('_', ' ', $modelo)); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom border-primary py-3 text-center">
                        <h6 class="mb-0 fw-bold text-primary text-uppercase small">Actualizar Registro (ID: <?php echo htmlspecialchars($id_recibido); ?>)</h6>
                    </div>
                    <div class="card-body">
                        <form action="../../consultas/Actualizar.php" method="POST">

                            <input type="hidden" name="id_enviado" value="<?php echo htmlspecialchars($id_recibido); ?>">
                            <input type="hidden" name="tipomod" value="<?php echo htmlspecialchars($modelo); ?>">

                            <?php foreach ($campos as $campo):
                                if ($campo['Key'] == "PRI") continue;

                                $nombreCampo = $campo['Field'];
                                $label = ucfirst(strtolower(str_replace("_", " ", $nombreCampo)));
                                $valor = htmlspecialchars($registro[$nombreCampo] ?? '');
                                $esTexto = str_contains($campo['Type'], "varchar") && !str_contains($campo['Type'], "255");
                                $esLargo = str_contains($campo['Type'], "255") || str_contains($campo['Type'], "text");
                            ?>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold"><?php echo $label; ?></label>
                                    <?php if ($esLargo): ?>
                                        <textarea class="form-control" name="<?php echo $nombreCampo; ?>" rows="3" required><?php echo $valor; ?></textarea>
                                    <?php else: ?>
                                        <input type="text" class="form-control" name="<?php echo $nombreCampo; ?>" value="<?php echo $valor; ?>" required>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="../../ventanas/<?php echo htmlspecialchars($modelo); ?>.php" class="btn btn-sm btn-secondary fw-medium px-4 shadow-sm py-2">Cancelar</a>
                                <button type="submit" class="btn btn-sm btn-success fw-medium px-4 shadow-sm py-2">Guardar Cambios</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>