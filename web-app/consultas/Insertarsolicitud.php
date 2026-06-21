<?php
    include('../base_de_datos/conexion.php');



    $consulta="";

    $correo=$_POST["correo"];
    $ID_departamento=$_POST["departamento"];
    $Asunto=$_POST["asunto"];
    $Descripcion=$_POST["descripcion"];
    $categoria="Agua";

    $ID_tipo_solicitud=$_POST["tipo"];
    $EstadoSol="Recibida";
    $ciudadanoQuery= mysqli_query($conexionDB,"SELECT RUT_ciudadano FROM ciudadano WHERE correo_electronico='$correo'");
    $ciudadano = mysqli_fetch_assoc($ciudadanoQuery);
    $rut_ciudadano = 0;
    if(mysqli_num_rows($ciudadanoQuery)==0){
        echo "PEDIR RUT";
    }
    if(mysqli_num_rows($ciudadanoQuery)==1){
                echo "".$ciudadano["RUT_ciudadano"];
                $rut_ciudadano = $ciudadano["RUT_ciudadano"];
                $consulta = "INSERT INTO solicitud (Asunto,Tipo_estado, Descripcion, Categoria, RUT_ciudadano, ID_departamento, ID_tipo_solicitud) VALUES ('$Asunto','$EstadoSol','$Descripcion','$categoria','$rut_ciudadano','$ID_departamento','$ID_tipo_solicitud')";// EN LA BASE DE DATOS CATEGORIA TIENE OTRO DOM
                $resultado = mysqli_query($conexionDB,$consulta);
                $ID_solicitud = mysqli_insert_id($conexionDB);
                $InsertComprobante="INSERT INTO comprobante (Fecha, Hora, solicitud_ID) VALUES (CURDATE(), CURTIME(), $ID_solicitud);";
                $resultado = mysqli_query($conexionDB,$InsertComprobante);
                header('Location: ../Ventanas/solicitud.php');
    }
    if(mysqli_num_rows($ciudadanoQuery) > 1){
                echo "RUUUUUUUUUUUUUUUUT 12";
    }

?>