<?php
    session_start();
    include('../base_de_datos/conexion.php');

    if (!isset($_SESSION["usuario"])) {
        header("Location: ../index.php");
        exit;
    }

    $ID_prioridad = mysqli_real_escape_string($conexionDB, $_POST["ID_prioridad"]);
    $ID_tipo_solicitud = mysqli_real_escape_string($conexionDB, $_POST["ID_tipo_solicitud"]);
    $ID_departamento = $_POST["ID_departamento"];
    $tiempo = mysqli_real_escape_string($conexionDB, $_POST["tiempo"]);

    // condicion para el director
    if ($_SESSION["tipo"] == "Director") {
        $ID_departamento = mysqli_real_escape_string($conexionDB, $_SESSION["ID_departamento"]);
    }

    // uso solo para el admin 
    if ($ID_departamento === "TODOS" && $_SESSION["tipo"] == "Administrador") {
        $deps = mysqli_query($conexionDB, "SELECT ID_departamento FROM departamento");
        while ($d = mysqli_fetch_assoc($deps)) {
            $idDep = $d['ID_departamento'];
            // inserta o sobrescribe dependiendo
            mysqli_query($conexionDB,
                "INSERT INTO tiempo (ID_prioridad, ID_tipo_solicitud, ID_departamento, tiempo)
                 VALUES ('$ID_prioridad', '$ID_tipo_solicitud', '$idDep', '$tiempo')
                 ON DUPLICATE KEY UPDATE tiempo = '$tiempo'");
        }
        header("Location: ../ventanas/tiempo.php");
        exit;
    }

    
    $ID_departamento = mysqli_real_escape_string($conexionDB, $ID_departamento);

    
    $existe = mysqli_query($conexionDB,
        "SELECT * FROM tiempo
         WHERE ID_prioridad = '$ID_prioridad'
           AND ID_tipo_solicitud = '$ID_tipo_solicitud'
           AND ID_departamento = '$ID_departamento'
         LIMIT 1");

    if (mysqli_num_rows($existe) > 0) {
        echo "<script>
                alert('Ya existe esa regla de prioridad para el tipo de solicitud en el departamento.');
                window.location.href = '../ventanas/tiempo.php';
              </script>";
        exit;
    }

    $consulta = "INSERT INTO tiempo (ID_prioridad, ID_tipo_solicitud, ID_departamento, tiempo)
                 VALUES ('$ID_prioridad', '$ID_tipo_solicitud', '$ID_departamento', '$tiempo')";
    mysqli_query($conexionDB, $consulta);

    header("Location: ../ventanas/tiempo.php");
    exit;
?>