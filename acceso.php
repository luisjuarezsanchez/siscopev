<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

	<?php
	date_default_timezone_set('America/Mexico_City');
	$fecha_actual=date("Y-m-d H:i:s");
	?>

	<form action="ingresar.php" method="post" accept-charset="utf-8">
		<label>ID tarjeta</label><input type="text" name="id_tar" value="0001"> <br>
		<label>Num empleado</label><input type="text" name="num_empleado" value="1020"><br>
		<label>Nombre</label><input type="text" name="nombre" value="Adriana Karen Vilchis Garcia"><br>
		<label>Fecha</label><input type="datetime" name="fecha" value="<?=$fecha_actual?>"><br>

		<input type="submit" name="alta" value="Alta">
		
	</form>

</body>
</html>