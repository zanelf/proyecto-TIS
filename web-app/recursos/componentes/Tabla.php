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

// qué columnas mostrar por tabla, y su ancho
$columnasVisibles = [
    "usuario" => [
        "rut_usuario"     => ["RUT", "13%"],
        "correo_usuario"  => ["Correo", "20%"],
        "tipo_trabajador" => ["Tipo de trabajador", "14%"],
        "nombre_departamento" => ["Departamento", "17%"],
        "Fecha_creacion"  => ["Creado", "12%"],
        "activo"          => ["Estado", "8%"],
    ],
    "solicitud" => [
        "solicitud_ID"  => ["ID", "8%"],
        "Asunto"        => ["Asunto", "27%"],
        "Tipo_estado"   => ["Estado", "15%"],
        "ID_departamento" => ["Depto.", "15%"],
    ],
    "departamento" => [
        "nombre" => ["Nombre", "70%"],
    ],
    "ciudadano" => [
        "RUT_ciudadano"       => ["RUT", "30%"],
        "correo_electronico"  => ["Correo", "45%"],
    ],
    "tipo_solicitud" => [
        "nombre"      => ["Nombre", "30%"],
        "descripcion" => ["Descripción", "45%"],
    ],
    "prioridad" => [
        "Nombre" => ["Nombre", "70%"],
    ],
];

// si el modelo no está en la lista, se muestran todas las columnas
$camposAMostrar = isset($columnasVisibles[$modelo]) ? $columnasVisibles[$modelo] : null;

echo '<table class="table table-hover align-middle mb-0">';
echo '<thead class="table-light">';
echo '<tr>';
if ($camposAMostrar !== null) {
    foreach ($camposAMostrar as $campoNombre => $meta) {
        echo '<th scope="col" style="width: ' . $meta[1] . ';">' . htmlspecialchars($meta[0]) . '</th>';
    }
} else {
    foreach ($campos as $campo) {
        $nombreColumna = ucfirst(strtolower(str_replace("_", " ", $campo['Field'])));
        echo '<th scope="col">' . $nombreColumna . '</th>';
    }
}
echo '<th scope="col" class="text-center" style="width: 18%;">Acciones</th>';
echo '</tr>';
echo '</thead>';

$tipoUsuario = isset($_SESSION['tipo']) ? $_SESSION['tipo'] : "";
$dpto = isset($_SESSION['ID_departamento']) && $_SESSION['ID_departamento'] !== "" ? $_SESSION['ID_departamento'] : null;

$buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : "";
$buscarEsc = mysqli_real_escape_string($conexionDB, $buscar);

$resultado = mysqli_query($conexionDB, "SELECT * FROM " . $modelo);
if ($modelo == "usuario") {
    $selectUsuario = "SELECT usuario.*,
        (
            CASE
                WHEN usuario.is_admin = 1 THEN 'Administrador'
                WHEN EXISTS (SELECT 1 FROM desarrollador dev WHERE dev.ID_usuario = usuario.ID_usuario) THEN 'Desarrollador'
                WHEN EXISTS (SELECT 1 FROM trabajador t WHERE t.ID_usuario = usuario.ID_usuario AND t.tipo_trabajador = 'funcionario') THEN 'Funcionario'
                WHEN EXISTS (SELECT 1 FROM trabajador t WHERE t.ID_usuario = usuario.ID_usuario AND t.tipo_trabajador = 'director') THEN 'Director'
                ELSE 'Sin rol'
            END
        ) AS tipo_trabajador,
        departamento.nombre AS nombre_departamento
        FROM usuario
        LEFT JOIN trabajador ON trabajador.ID_usuario = usuario.ID_usuario
        LEFT JOIN departamento ON departamento.ID_departamento = trabajador.ID_departamento";
    $resultado = mysqli_query($conexionDB, $selectUsuario);
}

$where = [];

if ($modelo == "solicitud") {
    if (($tipoUsuario == "Funcionario" || $tipoUsuario == "Director") && $dpto !== null) {
        $where[] = "ID_departamento = '" . mysqli_real_escape_string($conexionDB, $dpto) . "'";
    }
    if ($buscar !== "") {
        $where[] = "(Asunto LIKE '%$buscarEsc%' OR Descripcion LIKE '%$buscarEsc%' OR solicitud_ID LIKE '%$buscarEsc%')";
    }
} elseif ($modelo == "usuario") {
    // usuario no tiene departamento propio, se busca en trabajador
    if ($tipoUsuario == "Director" && $dpto !== null) {
        $dptoEsc = mysqli_real_escape_string($conexionDB, $dpto);
        $where[] = "EXISTS (SELECT 1 FROM trabajador t WHERE t.ID_usuario = usuario.ID_usuario AND t.tipo_trabajador = 'director' AND t.ID_departamento = '$dptoEsc')";
    }
    if ($buscar !== "") {
        $where[] = "usuario.rut_usuario LIKE '%$buscarEsc%'";
    }
} elseif ($modelo == "departamento") {
    if ($buscar !== "") {
        $where[] = "nombre LIKE '%$buscarEsc%'";
    }
} elseif ($modelo == "ciudadano") {
    if ($buscar !== "") {
        $where[] = "(correo_electronico LIKE '%$buscarEsc%' OR RUT_ciudadano LIKE '%$buscarEsc%')";
    }
} elseif ($modelo == "tipo_solicitud") {
    if ($buscar !== "") {
        $where[] = "(nombre LIKE '%$buscarEsc%' OR descripcion LIKE '%$buscarEsc%')";
    }
} elseif ($modelo == "prioridad") {
    if ($buscar !== "") {
        $where[] = "Nombre LIKE '%$buscarEsc%'";
    }
}

if (count($where) > 0) {
    $baseSelect = ($modelo == "usuario") ? $selectUsuario : "SELECT * FROM " . $modelo;
    $consulta = $baseSelect . " WHERE " . implode(" AND ", $where);
    $resultado = mysqli_query($conexionDB, $consulta);
}

echo '<tbody id="tabla-cuerpo">';
if (mysqli_num_rows($resultado) === 0) {
    $colspan = ($camposAMostrar !== null ? count($camposAMostrar) : count($campos)) + 1;
    echo '<tr><td colspan="' . $colspan . '" class="text-center text-muted py-3">No se encontraron registros.</td></tr>';
}
$filasPorPagina = 5;
$numeroFila = 0;
while ($row = mysqli_fetch_assoc($resultado)) {
    $numeroFila++;
    $paginaFila = (int)ceil($numeroFila / $filasPorPagina);
    echo '<tr data-pagina="' . $paginaFila . '">';

    $activoUsuario = null;
    foreach ($campos as $campo) {
        if ($campo['Key'] == "PRI") {
            $PKValue = $row[$campo['Field']];
        }
        if ($campo['Field'] == "Tipo_estado") {
            $estado = $row[$campo['Field']];
        }
        if ($modelo == "usuario" && $campo['Field'] == "activo") {
            $activoUsuario = (int)$row[$campo['Field']];
        }
    }

    if ($camposAMostrar !== null) {
        foreach ($camposAMostrar as $campoNombre => $meta) {
            $valor = $row[$campoNombre] ?? '';

            if ($campoNombre == "activo") {
                echo (int)$valor === 1
                    ? '<td><span class="badge bg-success-subtle text-success">Activo</span></td>'
                    : '<td><span class="badge bg-danger-subtle text-danger">Inactivo</span></td>';
            } elseif ($campoNombre == "Fecha_creacion" && $valor !== '') {
                echo '<td>' . htmlspecialchars(date('d-m-Y', strtotime($valor))) . '</td>';
            } else {
                echo '<td>' . htmlspecialchars($valor) . '</td>';
            }
        }
    } else {
        foreach ($campos as $campo) {
            echo '<td>' . htmlspecialchars($row[$campo['Field']] ?? '') . '</td>';
        }
    }

    echo '<td class="text-center">';
    echo '<div class="d-flex gap-1 justify-content-center flex-wrap">';

    if ($modelo == "usuario" && $tipoUsuario == "Administrador") {
        if ($activoUsuario === 1) {
            echo '<a href="EditarUsuario.php?id_enviado=' . $PKValue . '" class="btn btn-sm btn-warning text-dark fw-medium shadow-sm px-2">Editar</a>';
            echo '<a href="../recursos/componentes/Dardebajausuario.php?id_enviado=' . $PKValue . '"
                    class="btn btn-sm btn-danger fw-medium shadow-sm px-2 text-nowrap"
                    onclick="return confirm(\'¿Confirma dar de baja a este usuario? Esta acción es irreversible.\');">
                    Baja
                  </a>';
        } else {
            echo '<span class="badge bg-secondary-subtle text-secondary fw-semibold px-3 py-2">Sin acciones</span>';
        }
    } elseif ($modelo != "usuario" && $tipoUsuario == "Administrador") {
        echo '<a href="../recursos/componentes/Editar.php?id_enviado=' . $PKValue . '&tipomod=' . $modelo . '" class="btn btn-sm btn-warning text-dark fw-medium shadow-sm px-2">Editar</a>';
        echo '<a href="../recursos/componentes/Eliminar.php?id_enviado=' . $PKValue . '&tipomod=' . $modelo . '" class="btn btn-sm btn-danger fw-medium shadow-sm px-2">Eliminar</a>';
    }

    $gestionaSolicitudes = ($tipoUsuario == "Funcionario" || $tipoUsuario == "Director");

    if ($modelo == "solicitud" && $estado == "Recibida" && $gestionaSolicitudes) {
        echo '<a href="Revisar.php?id_enviado=' . $PKValue . '&tipomod=' . $modelo . '" class="btn btn-sm btn-success fw-medium shadow-sm px-2">Revisar</a>';
    }
    if ($modelo == "solicitud" && $estado == "Derivada" && $gestionaSolicitudes) {
        echo '<a href="Tomarsolicitud.php?id_enviado=' . $PKValue . '" class="btn btn-sm btn-success fw-medium shadow-sm px-2">Tomar</a>';
    }
    if ($modelo == "solicitud" && $estado == "En proceso" && $gestionaSolicitudes) {
        echo '<a href="Responder.php?id_enviado=' . $PKValue . '" class="btn btn-sm btn-success fw-medium shadow-sm px-2">Responder</a>';
    }
    // solo el director puede anular una solicitud recibida
    if ($modelo == "solicitud" && $estado == "Recibida" && $tipoUsuario == "Director") {
        echo '<a href="Anular.php?id_enviado=' . $PKValue . '" class="btn btn-sm btn-outline-danger fw-medium shadow-sm px-2">Anular</a>';
    }
    if ($modelo == "solicitud" && $estado != "Recibida" && $estado != "Derivada" && $estado != "En proceso" && $gestionaSolicitudes) {
        echo '<span class="text-muted small">Sin acciones</span>';
    }
    echo '</div>';
    echo '</td>';
    echo "</tr>";
}
echo '</tbody>';
echo '</table>';

$totalPaginas = (int)ceil($numeroFila / $filasPorPagina);
if ($totalPaginas > 1) {
    echo '<div id="tabla-paginacion" class="d-flex justify-content-center align-items-center gap-2 py-3 flex-wrap"></div>';
?>
<script>
(function() {
    const filas = document.querySelectorAll('#tabla-cuerpo tr[data-pagina]');
    const totalPaginas = <?php echo $totalPaginas; ?>;
    const contenedorPaginacion = document.getElementById('tabla-paginacion');
    let paginaActual = 1;

    function mostrarPagina(pagina) {
        paginaActual = pagina;
        filas.forEach(function(fila) {
            fila.style.display = (parseInt(fila.dataset.pagina) === pagina) ? '' : 'none';
        });
        renderizarControles();
    }

    function renderizarControles() {
        contenedorPaginacion.innerHTML = '';

        const btnAnterior = document.createElement('button');
        btnAnterior.className = 'btn btn-sm btn-outline-secondary';
        btnAnterior.textContent = 'Anterior';
        btnAnterior.disabled = (paginaActual === 1);
        btnAnterior.addEventListener('click', function() { mostrarPagina(paginaActual - 1); });
        contenedorPaginacion.appendChild(btnAnterior);

        for (let i = 1; i <= totalPaginas; i++) {
            const btnPagina = document.createElement('button');
            btnPagina.className = 'btn btn-sm ' + (i === paginaActual ? 'btn-primary text-white' : 'btn-outline-secondary');
            btnPagina.textContent = i;
            btnPagina.addEventListener('click', function() { mostrarPagina(i); });
            contenedorPaginacion.appendChild(btnPagina);
        }

        const btnSiguiente = document.createElement('button');
        btnSiguiente.className = 'btn btn-sm btn-outline-secondary';
        btnSiguiente.textContent = 'Siguiente';
        btnSiguiente.disabled = (paginaActual === totalPaginas);
        btnSiguiente.addEventListener('click', function() { mostrarPagina(paginaActual + 1); });
        contenedorPaginacion.appendChild(btnSiguiente);
    }

    mostrarPagina(1);
})();
</script>
<?php
}