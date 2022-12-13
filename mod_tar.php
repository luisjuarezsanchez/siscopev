<?php
	include("db.php");
	$id_tarjeta = $_REQUEST['id_tarjeta'];

	if (isset($id_tarjeta) || isset($nombre) || isset($apellidos) || isset($telefono)){
	$id_tarjeta=$_POST['id_tarjeta'];
	$nombre = $_POST['nombre'];
	$apellidos = $_POST['apellidos'];
	$telefono = $_POST['telefono'];
	}


	$query = "UPDATE tarjetas SET id_tarjeta='$id_tarjeta', nombre='$nombre',apellidos='$apellidos', telefono='$telefono' WHERE id_tarjeta='$id_tarjeta'";

	$resultado = $conexion->query($query);

	if($resultado){
	?>
	<?php
	include("gestion_tarjetas.php");
	?>
		<script>
			alert("Modificacion exitosa");
		</script>
		<?php	
	}
	
	