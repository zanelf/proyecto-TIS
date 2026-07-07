<?php
include_once('../../base_de_datos/conexion.php');

// 1. Validar que vengan los datos necesarios por la URL
if (!isset($_GET['id_enviado']) || !isset($_GET['tipomod'])) {
    echo "Faltan parámetros para editar.";
    exit;
}

$id_recibido = $_GET["id_enviado"];
$modelo = $_GET["tipomod"]; // Ejemplo: "tipo_solicitud"

// 2. Descubrir dinámicamente cuál es la Clave Primaria (PK) de la tabla
$atributos = mysqli_query($conexionDB, "DESCRIBE " . $modelo);
$PKmodelo = "";
while ($fila = mysqli_fetch_assoc($atributos)) {
    if ($fila['Key'] == "PRI") {
        $PKmodelo = $fila['Field'];
    }
}

// 3. Buscar los datos actuales de ese registro específico
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
                <div class="card shadow">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-bottom border-primary py-3 text-center">
                            <h6 class="mb-0 fw-bold text-primary text-uppercase small">Actualizar Registro (ID: <?php echo $id_recibido; ?>)</h6>
                        </div>
                        <div class="card-body">
                            <form action="../../consultas/Actualizartipo_solicitud.php" method="POST">

                                <input type="hidden" name="Id_tipo_solicitud" value="<?php echo $id_recibido; ?>">

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Nombre</label>
                                    <input type="text" class="form-control" name="Nombre" value="<?php echo htmlspecialchars($registro['Nombre'] ?? $registro['nombre'] ?? ''); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Descripción</label>
                                    <textarea class="form-control" name="Descripcion" rows="3" required><?php echo htmlspecialchars($registro['Descripcion'] ?? $registro['descripcion'] ?? ''); ?></textarea>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <a href="../../ventanas/<?php echo $modelo; ?>.php" class="btn btn-sm btn-secondary fw-medium px-4 shadow-sm py-2">
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
</body>

</html>