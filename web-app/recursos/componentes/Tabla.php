<?php
include_once('../base_de_datos/conexion.php');

$modelo = $Tipo_modelo;
$atributos = mysqli_query($conexionDB, "DESCRIBE " . $modelo);

$campos = [];
$PKmodelo = "";
$PKValue = "";

$estado = "";
while ($fila = mysqli_fetch_assoc($atributos)) {
    $campos[] = $fila;
    if ($fila['Key'] == "PRI") {
        $PKmodelo = $fila['Field'];
    }
}

echo '<table class="table table-hover table-bordered align-middle">';
echo '<thead class="table-light">';
echo '<tr>';
foreach ($campos as $campo) {
    $nombreColumna = ucfirst(strtolower(str_replace("_", " ", $campo['Field'])));
    echo '<th scope="col">' . $nombreColumna . '</th>';
}

echo '<th scope="col" class="text-center" style="width: 15%;">Acciones</th>';
echo '</tr>';
echo '</thead>';

// Rol y departamento del usuario en sesión (pueden no existir según el rol, por eso se validan)
$tipoUsuario = isset($_SESSION['tipo']) ? $_SESSION['tipo'] : "";
$dpto = isset($_SESSION['ID_departamento']) && $_SESSION['ID_departamento'] !== "" ? $_SESSION['ID_departamento'] : null;

// Término de búsqueda opcional (ver solicitud.php)
$buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : "";

// Consulta base: todas las filas del modelo
$resultado = mysqli_query($conexionDB, "SELECT * FROM " . $modelo);

if ($modelo == "solicitud") {
    $where = [];

    // Funcionario y Director solo ven las solicitudes de su propio departamento
    if (($tipoUsuario == "Funcionario" || $tipoUsuario == "Director") && $dpto !== null) {
        $where[] = "ID_departamento = '" . mysqli_real_escape_string($conexionDB, $dpto) . "'";
    }

    // Búsqueda por asunto, descripción o ID de solicitud
    if ($buscar !== "") {
        $buscarEsc = mysqli_real_escape_string($conexionDB, $buscar);
        $where[] = "(Asunto LIKE '%$buscarEsc%' OR Descripcion LIKE '%$buscarEsc%' OR solicitud_ID LIKE '%$buscarEsc%')";
    }

    if (count($where) > 0) {
        $consulta = "SELECT * FROM solicitud WHERE " . implode(" AND ", $where);
        $resultado = mysqli_query($conexionDB, $consulta);
    }
}

echo '<tbody>';
if (mysqli_num_rows($resultado) === 0) {
    $colspan = count($campos) + 1;
    echo '<tr><td colspan="' . $colspan . '" class="text-center text-muted py-3">No se encontraron solicitudes.</td></tr>';
}
while ($row = mysqli_fetch_assoc($resultado)) {
    echo "<tr>";
    foreach ($campos as $campo) {
        if ($campo['Key'] == "PRI") {
            $PKValue = $row[$campo['Field']];
        }
        $aux = $row[$campo['Field']];
        echo '<td>' . htmlspecialchars($aux ?? '') . '</td>';
        if ($campo['Field'] == "Tipo_estado") {
            $estado = $aux;
        }
    }

    echo '<td class="text-center text-nowrap">';
    if ($tipoUsuario == "Administrador") {
        echo '<a href="../recursos/componentes/Editar.php?id_enviado=' . $PKValue . '&tipomod=' . $modelo . '" class="btn btn-sm btn-warning text-dark fw-medium px-3 shadow-sm">Editar</a> ';
        echo '<a href="../recursos/componentes/Eliminar.php?id_enviado=' . $PKValue . '&tipomod=' . $modelo . '" class="btn btn-sm btn-danger fw-medium px-3 shadow-sm">Eliminar</a>';
    }

    if ($modelo == "solicitud" && $estado == "Recibida" && $tipoUsuario == "Funcionario") {
        echo '<a href="Revisar.php?id_enviado=' . $PKValue . '&tipomod=' . $modelo . '" class="btn btn-sm btn-success fw-medium px-3 shadow-sm">Revisar</a>';
    }
    if ($modelo == "solicitud" && $estado == "Derivada" && $tipoUsuario == "Funcionario") {
        echo '<a href="Responder.php?id_enviado=' . $PKValue . '&tipomod=' . $modelo . '" class="btn btn-sm btn-success fw-medium px-3 shadow-sm">Responder</a>';
    }
    if ($modelo == "solicitud" && $estado != "Recibida" && $estado != "Derivada" && $tipoUsuario == "Funcionario") {
        echo '<span class="text-muted small">Sin acciones</span>';
    }

    echo '</td>';
    echo "</tr>";
}
echo '</tbody>';
echo '</table>';