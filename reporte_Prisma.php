<?php
/*Realizando conexion a la BD*/

$mysqli = new mysqli("localhost", "root", "", "SiscopevW2");
if ($mysqli->connect_errno) {
	echo "Falló la conexión con MySQL: (" . $mysqli->connect_errno . ")" . $mysqli->connect_error;
}

//Solicitar el archivo desde el lado del cliente
$nombre = $_FILES['archivo']['name'];
$guardado = $_FILES['archivo']['tmp_name'];

if (!file_exists('archivos')) {
	mkdir('archivos', 0777, true);
	if (file_exists('archivos')) {
		if (move_uploaded_file($guardado, 'archivos/' . $nombre)) {
			echo "";
		} else {
			echo "Ocurrio un error con la carga del archivo";
		}
	}
} else {
	if (move_uploaded_file($guardado, 'archivos/' . $nombre)) {
	} else {
		echo "Ocurrio un error con la carga del archivo";
	}
}

//Aumentando el tiempo de oconsultas SQL
set_time_limit(600);
//Borrando contenido de la tabla temporal
$mysqli->query("DELETE FROM tmpConPrisma");

$arc2 = fopen('archivos/' . $nombre, "r");
//Obteniendo una linea completa del archivo txt
$linea2 = fgets($arc2);
//Recuperando las dos primeras posiciones de cada linea
$bandera2 = substr($linea2, 0, 4);

if ($bandera2 != "0101") {
	header("Location: alertas/prisma.php");
}
fclose($arc2);




//Abriendo el archivo
$arc = fopen('archivos/' . $nombre, "r");
//Ciclo que indica lee el archivo hasta la ultima linea
while (!feof($arc)) {
	//Obteniendo una linea completa del archivo txt
	$linea = fgets($arc);
	//Recuperando las dos primeras posiciones de cada linea
	$bandera = substr($linea, 0, 2);


	//Condicion para validar que solo se recuperan datos que inicien con 02 
	if ($bandera == "02") {
		//Recuperando los datos segun las posiciones
		$strCURP = substr($linea, 2, 18);
		$strPaterno = substr($linea, 41, 50);
		$strMaterno = substr($linea, 91, 50);
		$strNombre = substr($linea, 141, 50);
		$strAnio = substr($linea, 419, 4);
		$strQuincena = substr($linea, 423, 2);
		$strImporte = substr($linea, 232, 11);
		$nomina = substr($linea, 419, 6);

		//Ciclo para recuperar las posiciones del archivo PRISMA
		for ($i = 232; $i < 418; $i = $i + 11) {
			$intPos = $i + 1;
			$strImporte = substr($linea, $i, 11);
			$final = $strImporte / 100;

			if ($final != 0) {
				$mysqli->query("INSERT INTO tmpConPrisma 
							(CURP,Paterno,Materno,Nombre,Pos,Importe,Anio,Quincena) 
							VALUES ('$strCURP','$strPaterno','$strMaterno','$strNombre',
							'$intPos',$final,'$strAnio','$strQuincena')");
			}
		}
	}
}
//Obteniendo nomina
$nom = $nomina . " 10094";

//Cerrando el archivo
fclose($arc);

//Ajusta las diferencias de un centavo en las Aportaciones de ISSEMyM
$mysqli->query("UPDATE ajuapoiss SET Importe_DetNomina=Importe_tmpConPrisma WHERE CveNomina IN ('$nom')");

//Borrando la tabla temporal
$mysqli->query("DELETE FROM tmpDifISS");
//Inserciones en tmpdifISS
$mysqli->query("INSERT INTO tmpDifISS SELECT DetNomina.CvePersonal, CURP, 
			CONCAT(Paterno,' ',Materno,' ',Nombre), DetNomina.Clave, 
			SUM(Importe), PosPrisma FROM EmpGral INNER JOIN DetNomina 
			ON EmpGral.CvePersonal = DetNomina.CvePersonal INNER JOIN PerDedApo ON 
			DetNomina.Clave = PerDedApo.Clave WHERE PosPRISMA<>0 AND 
			CveNomina IN ('$nom') GROUP BY DetNomina.CvePersonal,CURP, 
			CONCAT(Paterno,' ',Materno,' ',Nombre), DetNomina.Clave, PosPrisma");



// ******GENERANDO REPORTES CON EL REPORTEADOR FPDF******
//Solicitando los archivos de FPDF
require('fpdf/fpdf.php');
class PDF extends FPDF
{
	// Cabecera de página
	function Header()
	{
		global $nom;
		// Logos
		$this->Image('img/reportes/armas_horizontal.png', 7, 7, 70); //esp.izquierda-abajo-tamaño
		$this->Image('img/reportes/edomex_horizontal.png', 230, 11, 60);
		// Estilos de letra
		$this->SetFont('Arial', 'B', 15);
		// Movernos a la derecha
		$this->Cell(80);
		// Título
		$this->Cell(100, 15, utf8_decode('Conciliación con Prisma'), 10, 10, 'C'); //derecha abajo Salto de línea
		//Saltos de linea
		$this->Ln(0);
		$this->Cell(258, 10, utf8_decode('Quincena ' . $nom), 10, 10, 'C');
		//Saltos de linea
		$this->Ln(0);
		$this->Cell(255, 12, utf8_decode('Personal de Apoyo a la Coordinación Administrativa'), 10, 10, 'C');
	}

	// Pie de página
	function Footer()
	{
		// Posición: a 1,5 cm del final
		$this->SetY(-15);
		// Arial italic 8
		$this->SetFont('Arial', 'I', 6);
		// Número de página
		$this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
	}
}
//Solicitando la conexion con la BD
require 'conexion.php';
//Efectuando la consulta de las vista
$consulta = "SELECT * FROM conprisma";
//Almaceando el resultado de la consulta en una variable
$resultado = $mysqli->query($consulta);

// Creación del objeto de la clase heredada
$pdf = new PDF('L', 'mm', 'A4'); //Indicando formato horizontal del reporte
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Helvetica', '', 9);

//Encabezados de la tabla
$pdf->SetFont('Helvetica', 'B', 9); //Colocando letras en negritas
$pdf->Cell(25, 10, 'Periodo', 1, 0, 'C', 0);
$pdf->Cell(50, 10, 'CURP', 1, 0, 'C', 0);
$pdf->Cell(90, 10, 'Empleado', 1, 0, 'C', 0);
$pdf->Cell(15, 10, 'Clave', 1, 0, 'C', 0);
$pdf->Cell(20, 10, 'PRISMA', 1, 0, 'C', 0);
$pdf->Cell(20, 10, 'SISCOPEV', 1, 0, 'C', 0);
$pdf->Cell(60, 10, 'Falla', 1, 1, 'C', 0);
$pdf->SetFont('Helvetica', '', 9); //Devolviendo valores de letra

//Ciclo para recorrer la tabla e insertar registros en la tabla
$a = 0;
while ($row = $resultado->fetch_assoc()) {
	//Ancho alto,borde,salto de linea justificacion relleno
	$pdf->Cell(25, 10, utf8_decode($row['Periodo']), 1, 0, 'C', 0);
	$pdf->Cell(50, 10, utf8_decode($row['CURP']), 1, 0, 'C', 0);
	$pdf->Cell(90, 10, utf8_decode($row['Empleado']), 1, 0, 'C', 0);
	$pdf->Cell(15, 10, utf8_decode($row['Clave']), 1, 0, 'C', 0);
	$pdf->Cell(20, 10, utf8_decode($row['Imp_PRISMA']), 1, 0, 'C', 0);
	$pdf->Cell(20, 10, utf8_decode($row['Imp_SISCOPEV']), 1, 0, 'C', 0);
	$pdf->Cell(60, 10, utf8_decode($row['Falla']), 1, 1, 'C', 0);
	$a = $a + 1;
}
$pdf->Cell(43, 10, utf8_decode(""), 0, 1, 'C', 0);
$pdf->Cell(25, 10, 'EXISTEN ' . $a . ' DIFERENCIAS', 0, 0, 0);
//Indicar salida del archivo pdf
$pdf->Output();
