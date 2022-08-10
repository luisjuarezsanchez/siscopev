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
		echo "Tablas borradas con exito";
	} else {
		$mysqli->query("CALL sp_GeneraNomNor ('$CveNomina','$Del','$Al','$GenHon')");

		echo "Clave de nómina:" . $CveNomina;
		echo "<br>";
		echo "Fecha de inicio: " . $Del;
		echo "<br>";
		echo "Fecha de finalizacion: " . $Al;
		echo "<br>";
		echo "Indicador de honorarios: " . $GenHon;
	}
	?>

	<?php
	/*$mysqli->query("DELETE FROM tmpDifISS");
	$mysqli->query("INSERT INTO tmpDifISS SELECT DetNomina.CvePersonal, CURP, CONCAT(Paterno,' ',Materno,' ',Nombre), DetNomina.Clave, SUM(Importe), PosPrisma FROM EmpGral INNER JOIN DetNomina ON EmpGral.CvePersonal = DetNomina.CvePersonal INNER JOIN PerDedApo ON DetNomina.Clave = PerDedApo.Clave WHERE PosPRISMA<>0 AND CveNomina IN ('$CveNomina') GROUP BY DetNomina.CvePersonal,CURP, CONCAT(Paterno,' ',Materno,' ',Nombre), DetNomina.Clave, PosPrisma");*/ ?>




</body>

</html>