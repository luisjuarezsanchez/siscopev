<!DOCTYPE html>
<html>

<head>
	<title> </title>
</head>

<body>
	<?php
	/*Realizando conexion a la BD*/
	$mysqli = new mysqli("localhost", "root", "", "SiscopevW2");
	if ($mysqli->connect_errno) {
		echo "Falló la conexión con MySQL: (" . $mysqli->connect_errno . ")" . $mysqli->connect_error;
	}

	$CveNomina = $_POST['CveNomina'];
	$Del = $_POST['Del'];
	$Al = $_POST['Al'];
	$GenHon = $_POST['GenHon'];


	if ($GenHon == 1) {
		$mysqli->query("TRUNCATE TABLE DetNomina");
		$mysqli->query("DELETE FROM Nominas");
		$mysqli->query("DELETE FROM tmpDetNomina");
	} else {
		$mysqli->query("CALL sp_GeneraNomNor ('$CveNomina','$Del','$Al','$GenHon')");
	}
	?>
	<?php
	header("location: ../procesar_Nomina.php");
	?>

</body>

</html>