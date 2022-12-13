<?php
	include("db.php");
	$id_empleado = $_REQUEST['id_empleado'];

	if (isset($id_empleado) || isset($nombre) || isset($apellidos) || isset($telefono) || isset($contrasena)){
	$id_empleado=$_POST['id_empleado'];
	$nombre = $_POST['nombre'];
	$apellidos = $_POST['apellidos'];
	$telefono = $_POST['telefono'];
	$contrasena = $_POST['contrasena'];
	}


	$query = "UPDATE login SET id_empleado='$id_empleado', nombre='$nombre',apellidos='$apellidos', telefono='$telefono', contrasena='$contrasena' WHERE id_empleado='$id_empleado'";

	$resultado = $conexion->query($query);

	if($resultado){
	?>
	<?php
	include("crud_admin.php");
	?>
		<script>
			alert("Modificacion exitosa");
		</script>
		<?php	
	}
	
	