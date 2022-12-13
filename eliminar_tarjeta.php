<?php
	include("db.php");
	$id_tarjeta = $_REQUEST['id_tarjeta'];

	$query = "DELETE FROM tarjetas WHERE id_tarjeta = '$id_tarjeta'";

	$resultado = $conexion->query($query);

	if($resultado){
	?>
	<?php
	include("gestion_tarjetas.php");
	?>
		<script>
			alert("Registro eliminado");
		</script>
		<?php
	}
		
	