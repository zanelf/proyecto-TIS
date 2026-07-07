<?php

include_once('../base_de_datos/conexion.php');

$modelo = $Tipo_modelo;

$atributos = mysqli_query($conexionDB, "DESCRIBE " . $modelo);
$campos = [];

while ($fila = mysqli_fetch_assoc($atributos)) {
    $campos[] = $fila;
}

foreach ($campos as &$campo) {

    if (isset($campo['Type']) && $campo['Type'] !== null) {
        if (str_contains((string)$campo['Type'], "int")) {
            $campo['Type'] = "number";
        }
        if (str_contains((string)$campo['Type'], "varchar")) {
            $campo['Type'] = "text";
        }
    }
}
unset($campo);



echo '<form action="../consultas/Insertar' . $modelo . '.php" method="POST">';

foreach ($campos as $campo) {
    if ($campo['Key'] != "PRI") {

        $nombreLabel = ucfirst(strtolower(str_replace("_", " ", $campo['Field'])));


        echo '<div class="mb-3">';
        echo '  <label class="form-label fw-bold">' . $nombreLabel . '</label>';
        echo '  <input type="' . $campo['Type'] . '" name="' . $campo['Field'] . '" class="form-control" required>';
        echo '</div>';
    }
}


echo '<button type="submit" class="btn btn-sm btn-success fw-medium px-3 shadow-sm w-100 py-2">
    Guardar
      </button>';
echo '</form>';
