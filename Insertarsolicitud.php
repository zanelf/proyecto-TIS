<?php
    include('conexion.php');
    $Tipo_estado=$_POST["Tipo_estado"];
    $Asunto=$_POST["Asunto"];
    $Descripcion=$_POST["Descripcion"];
    $RUT_ciudadano=$_POST["RUT_ciudadano"];
    $ID_departamento=$_POST["ID_departamento"];
    $ID_tipo_solicitud=$_POST["ID_tipo_solicitud"];

    $consulta = "INSERT INTO solicitud (Tipo_estado, Asunto,Descripcion,RUT_ciudadano,ID_departamento,ID_tipo_solicitud)  VALUES ('$Tipo_estado', '$Asunto','$Descripcion','$RUT_ciudadano','$ID_departamento','$ID_tipo_solicitud')";
    $resultado = mysqli_query($conexionDB,$consulta);

    header('Location: solicitud.php');
?>