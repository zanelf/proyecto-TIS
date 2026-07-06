<?php
session_start();

$tipoUsuario = $_SESSION["tipo_usuario"] ?? $_SESSION["tipo"] ?? $_SESSION["rol"] ?? "";


$tipoUsuario = "Administrador"; 


if (empty($tipoUsuario) || $tipoUsuario !== "Administrador") {
    header("Location: ../index.php"); 
    exit();
}


include("../base_de_datos/conexion.php"); 

if (isset($conexion)) { $conexionDB = $conexion; }
if (isset($conn)) { $conexionDB = $conn; }


$kpi_tiempo_promedio = "48 Horas"; 
$kpi_tasa_sla = "85%"; 
$kpi_vencidas = 12; 


$datos_tipo = [ ["tipo" => "Reclamo", "cantidad" => 45], ["tipo" => "Sugerencia", "cantidad" => 20] ];
$datos_dpto = [ ["dpto" => "Aseo", "cantidad" => 35], ["dpto" => "Tránsito", "cantidad" => 30] ];
$datos_estados = [ ["estado" => "Activos", "cantidad" => 40], ["estado" => "Cerrados", "cantidad" => 25] ];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SGISC - Panel Analítico y SLA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../recursos/css/style_index.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../recursos/css/style_metricas.css?v=<?php echo time(); ?>">
</head>


<body style="background-color: #f8fafc; margin: 0;">

    <?php include("../recursos/componentes/navbar1.php"); ?>

    <div class="contenedor-metricas">
        <h1 class="titulo-seccion">Panel Analítico y Desempeño (SLA)</h1>

        <form method="GET" class="panel-filtros">
            <div class="grupo-filtro">
                <label>Fecha Inicio</label>
                <input type="date" name="fecha_inicio" value="<?php echo htmlspecialchars($filtro_fecha_inicio); ?>">
            </div>
            <div class="grupo-filtro">
                <label>Fecha Fin</label>
                <input type="date" name="fecha_fin" value="<?php echo htmlspecialchars($filtro_fecha_fin); ?>">
            </div>
            <div class="grupo-filtro">
                <label>Departamento</label>
                <select name="id_departamento">
                    <option value="">Todos</option>
                    </select>
            </div>
            <div class="grupo-filtro">
                <label>Tipo Solicitud</label>
                <select name="id_tipo_solicitud">
                    <option value="">Todas</option>
                    </select>
            </div>
            <button type="submit" class="btn-filtrar">Aplicar Filtros</button>
        </form>

        <div class="grid-kpi">
            <div class="tarjeta-kpi">
                <h3>Tiempo Promedio de Resolución</h3>
                <div class="numero"><?php echo $kpi_tiempo_promedio; ?></div>
            </div>
            <div class="tarjeta-kpi kpi-verde">
                <h3>Tasa de Cumplimiento de SLA</h3>
                <div class="numero"><?php echo $kpi_tasa_sla; ?></div>
            </div>
            <div class="tarjeta-kpi kpi-rojo">
                <h3>Solicitudes Vencidas Activas</h3>
                <div class="numero"><?php echo $kpi_vencidas; ?></div>
            </div>
        </div>

        <div class="grid-graficos">
            
            <div class="bloque-grafico">
                <h3>Distribución por Tipo</h3>
                <?php foreach($datos_tipo as $d): ?>
                    <div class="barra-fila">
                        <div class="barra-texto"><span><?php echo $d['tipo']; ?></span><span><?php echo $d['cantidad']; ?></span></div>
                        <div class="barra-fondo"><div class="barra-color" style="width: 70%;"></div></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="bloque-grafico">
                <h3>Solicitudes por Departamento</h3>
                <?php foreach($datos_dpto as $d): ?>
                    <div class="barra-fila">
                        <div class="barra-texto"><span><?php echo $d['dpto']; ?></span><span><?php echo $d['cantidad']; ?></span></div>
                        <div class="barra-fondo"><div class="barra-color" style="width: 50%; background: #8b5cf6;"></div></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="bloque-grafico">
                <h3>Estados de Gestión (Activos vs Finalizados)</h3>
                <?php foreach($datos_estados as $d): ?>
                    <div class="barra-fila">
                        <div class="barra-texto"><span><?php echo $d['estado']; ?></span><span><?php echo $d['cantidad']; ?></span></div>
                        <div class="barra-fondo"><div class="barra-color" style="width: 80%; background: #10b981;"></div></div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="bloque-grafico">
                <h3>Tendencia Histórica</h3>
                <p style="color: #94a3b8; font-size: 13px;">(Aquí integraremos la línea de tiempo más adelante)</p>
            </div>

        </div>
    </div>
</body>
</html>