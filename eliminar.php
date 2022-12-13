<?php
	include("db.php");
	$id_empleado = $_REQUEST['id_empleado'];


	$query = "DELETE FROM login WHERE id_empleado = '$id_empleado'";

	$resultado = $conexion->query($query);

	if($resultado){
	?>
	<?php
	include("crud_admin.php");
	?>
		<script>
			alert("Registro eliminado");
		</script>
		<?php
	}
		
	