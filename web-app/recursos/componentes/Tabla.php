<?php
include_once('../base_de_datos/conexion.php');

$modelo = $Tipo_modelo;
$atributos = mysqli_query($conexionDB, "DESCRIBE " . $modelo);

$campos = [];
$PKmodelo = "";
$PKValue = "";

$estado="";
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

$dpto=$_SESSION['ID_departamento'];
echo $dpto;

if($modelo=="solicitud" && $_SESSION['tipo']=="Funcionario"){
    $consulta="SELECT * FROM solicitud WHERE ID_departamento='$dpto'";
    $resultado=mysqli_query($conexionDB,$consulta);
}

echo '<tbody>';
while ($row = mysqli_fetch_assoc($resultado)) {
    echo "<tr>";
    foreach ($campos as $campo) {
        if ($campo['Key'] == "PRI") {
            $PKValue = $row[$campo['Field']];
        }
        $aux = $row[$campo['Field']];
        echo '<td>' . $aux . '</td>';
        if($campo['Field']=="Tipo_estado"){
            $estado=$aux;
        }
    }

    echo '<td class="text-center text-nowrap">';
    if($modelo=="solicitud" && $_SESSION["tipo"]=="Administrador"){
    echo    '<a href="../recursos/componentes/Editar.php?id_enviado=' . $PKValue . '&tipomod=' . $modelo . '" class="btn btn-sm btn-warning text-dark fw-medium px-3 shadow-sm"">Editar</a>';
    echo    '<a href="../recursos/componentes/Eliminar.php?id_enviado=' . $PKValue . '&tipomod=' . $modelo . '" class="btn btn-sm btn-danger fw-medium px-3 shadow-sm">Eliminar</a>';
    }
    
    if($modelo=="solicitud" && $estado=="Recibida" && $_SESSION["tipo"]=="Funcionario"){
        echo    '<a href="revisar.php?id_enviado=' . $PKValue . '&tipomod=' . $modelo . '" class="btn btn-sm btn-success fw-medium px-3 shadow-sm">Revisar</a>';
    }
    echo    '</td>;';
    echo "</tr>";
}
echo '</tbody>';
echo '</table>';
