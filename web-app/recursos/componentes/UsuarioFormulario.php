<?php
    include('../base_de_datos/conexion.php');

    $modelo = $Tipo_modelo;

    // Traemos los departamentos activos para mostrarlos en el selector
    $query_deptos = "SELECT * FROM departamento";
    $res_deptos = mysqli_query($conexionDB, $query_deptos);

    echo '<form action="../consultas/Insertar'.strtolower($modelo).'.php" method="POST" class="mt-3">';
        echo '<div class="mb-3">';
            echo '<label class="form-label fw-semibold">USUARIO</label>';
            echo '<input type="text" name="nombre_usuario" class="form-control" required>';
        echo '</div>';
        
        echo '<div class="mb-3">';
            echo '<label class="form-label fw-semibold">CONTRASEÑA</label>';
            echo '<input type="password" name="password" class="form-control" required>';
        echo '</div>';
        
        echo '<div class="mb-3">';
            echo '<label class="form-label fw-semibold">ROL DE ACCESO</label>';
            echo '<select id="tipo" name="tipo" class="form-select" onchange="toggleDeptoSelector(this.value)" required>';
                echo '<option value="" disabled selected>-- Seleccione un Rol --</option>'; // Opción inicial de control
                echo '<option value="Director">Director</option>';
                echo '<option value="Administrador">Administrador</option>';
                echo '<option value="Funcionario">Funcionario</option>';
            echo '</select>';
        echo '</div>';

        // CORREGIDO: Se quita el display none inicial para que esté listo ante Director o Funcionario
        echo '<div class="mb-3" id="div_departamento">';
            echo '<label class="form-label fw-semibold">DEPARTAMENTO / UNIDAD MUNICIPAL</label>';
            echo '<select name="id_departamento" class="form-select" required>';
                while($depto = mysqli_fetch_assoc($res_deptos)) {
                    echo '<option value="'.$depto['ID_departamento'].'">'.$depto['nombre'].'</option>';
                }
            echo '</select>';
        echo '</div>';
        
        echo '<button type="submit" class="btn btn-success mt-2 w-100">Registrar Usuario</button>';
    echo '</form>';
?>

<script>
function toggleDeptoSelector(rol) {
    const divDepto = document.getElementById('div_departamento');
    const selectDepto = divDepto.querySelector('select');
    
    if (rol === 'Administrador' || rol === '') {
        divDepto.style.display = 'none';
        selectDepto.removeAttribute('required'); // No se exige para administradores
    } else {
        divDepto.style.display = 'block';
        selectDepto.setAttribute('required', 'required'); // Obligatorio para funcionarios y directores
    }
}
</script>