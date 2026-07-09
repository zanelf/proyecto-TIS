<?php
    include('../base_de_datos/conexion.php');

    $modelo = $Tipo_modelo;

    $query_deptos = "SELECT * FROM departamento";
    $res_deptos = mysqli_query($conexionDB, $query_deptos);

    echo '<form action="../consultas/Insertar'.strtolower($modelo).'.php" method="POST" class="mt-3">';

        echo '<div class="mb-3">';
            echo '<label class="form-label fw-semibold">RUT</label>';
            echo '<input type="text" name="rut_usuario" class="form-control" required>';
        echo '</div>';

        echo '<div class="mb-3">';
            echo '<label class="form-label fw-semibold">CORREO</label>';
            echo '<input type="email" name="correo_usuario" class="form-control" required>';
        echo '</div>';

        echo '<div class="mb-3">';
            echo '<label class="form-label fw-semibold">ROL DE ACCESO</label>';
            echo '<select id="tipo" name="tipo" class="form-select" onchange="toggleFichaTrabajador(this.value)" required>';
                echo '<option value="" disabled selected>-- Seleccione un Rol --</option>';
                echo '<option value="Director">Director</option>';
                echo '<option value="Administrador">Administrador</option>';
                echo '<option value="Funcionario">Funcionario</option>';
                echo '<option value="Desarrollador">Desarrollador</option>';
            echo '</select>';
        echo '</div>';

        
        echo '<div id="div_ficha_trabajador" style="display:none;">';

            echo '<div class="mb-3">';
                echo '<label class="form-label fw-semibold">NOMBRE</label>';
                echo '<input type="text" name="nombre" class="form-control">';
            echo '</div>';

            echo '<div class="mb-3">';
                echo '<label class="form-label fw-semibold">APELLIDO</label>';
                echo '<input type="text" name="apellido" class="form-control">';
            echo '</div>';

            echo '<div class="mb-3">';
                echo '<label class="form-label fw-semibold">DEPARTAMENTO / UNIDAD MUNICIPAL</label>';
                echo '<select name="id_departamento" class="form-select">';
                    while($depto = mysqli_fetch_assoc($res_deptos)) {
                        echo '<option value="'.$depto['ID_departamento'].'">'.$depto['nombre'].'</option>';
                    }
                echo '</select>';
            echo '</div>';

            echo '<div class="mb-3">';
                echo '<label class="form-label fw-semibold">TIPO DE TRABAJADOR</label>';
                echo '<input type="text" name="tipo_trabajador" class="form-control">';
            echo '</div>';

            echo '<div class="mb-3">';
                echo '<label class="form-label fw-semibold">PREVISIÓN</label>';
                echo '<input type="text" name="prevision" class="form-control">';
            echo '</div>';

            echo '<div class="mb-3">';
                echo '<label class="form-label fw-semibold">AFP</label>';
                echo '<input type="text" name="afp" class="form-control">';
            echo '</div>';

        echo '</div>';

        echo '<button type="submit" class="btn btn-success mt-2 w-100">Registrar Usuario</button>';
    echo '</form>';
?>

<script>
function toggleFichaTrabajador(rol) {
    const divFicha = document.getElementById('div_ficha_trabajador');
    const camposFicha = divFicha.querySelectorAll('input, select');

    if (rol === 'Funcionario' || rol === 'Director') {
        divFicha.style.display = 'block';
        camposFicha.forEach(campo => {
            if (campo.name === 'nombre' || campo.name === 'apellido' || campo.name === 'id_departamento') {
                campo.setAttribute('required', 'required');
            }
        });
    } else {
        divFicha.style.display = 'none';
        camposFicha.forEach(campo => campo.removeAttribute('required'));
    }
}
</script>