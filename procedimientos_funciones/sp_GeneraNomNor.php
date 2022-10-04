<!DOCTYPE html>
<html>

<head>
	<title> </title>
	<script src="sweetalert2.all.min.js"></script>
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
		//$mysqli->query("CALL sp_GeneraNomNor ('$CveNomina','$Del','$Al','$GenHon')");
		$mysqli->query("CALL sp_GeneraEventuales ('$CveNomina','$Del','$Al','$GenHon')");
		$mysqli->query("CALL sp_GeneraClavesPerDedApo()");
		$mysqli->query("CALL sp_GeneraExcentosDedApo()");
		$mysqli->query("CALL sp_GeneraSubsidioISR()");
	}
	?>
	<?php
	//header("location: ../procesar_Nomina.php");
	echo '<script>
	alert("Proceso de nómina ejecutado correctamente")
	</script>
	<meta http-equiv="refresh" content="0.1;url=../procesar_Nomina.php" />';

	//header("location: ../procesar_Nomina.php");
	?>

</body>

</html>