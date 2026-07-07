<?php
include_once('../base_de_datos/conexion.php');

$modelo = $Tipo_modelo;
$atributos = mysqli_query($conexionDB, "DESCRIBE " . $modelo);

$campos = [];
$PKmodelo = "";
$PKValue = "";

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

echo '<tbody>';
while ($row = mysqli_fetch_assoc($resultado)) {
    echo "<tr>";
    foreach ($campos as $campo) {
        if ($campo['Key'] == "PRI") {
            $PKValue = $row[$campo['Field']];
        }
        $aux = $row[$campo['Field']];
        echo '<td>' . $aux . '</td>';
    }


    echo '<td class="text-center text-nowrap">
                            <a href="../recursos/componentes/Editar.php?id_enviado=' . $PKValue . '&tipomod=' . $modelo . '" class="btn btn-sm btn-warning text-dark fw-medium px-3 shadow-sm"">Editar</a>
                            <a href="../recursos/componentes/Eliminar.php?id_enviado=' . $PKValue . '&tipomod=' . $modelo . '" class="btn btn-sm btn-danger fw-medium px-3 shadow-sm">Eliminar</a>
                          </td>';
    echo "</tr>";
}
echo '</tbody>';
echo '</table>';
