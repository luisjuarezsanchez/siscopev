<?php
$CveNomina = $_POST['CveNomina'];
$a=0;
$b=0;
$c=0;
//Solicitando los archivos de FPDF
require('fpdf/fpdf.php');
class PDF extends FPDF
{
	// Cabecera de página
	function Header()
	{
		// Logos
		$this->Image('img/reportes/gob.jpg', 10, 2, 35); //esp.izquierda-abajo-tamaño
		$this->Image('img/reportes/logo_vertical.png', 240, 2, 25);
		// Arial bold 15
		$this->SetFont('Arial', 'B', 15);
		// Movernos a la derecha
		$this->Cell(80);
		// Título
		$this->Cell(100,5, utf8_decode(""), 0, 1, 'C', 0);

		$this->Cell(250, 15, utf8_decode('SECRETARIA DE CULTURA Y TURISMO'), 10, 10, 'C'); //derecha abajo Salto de línea
		//Saltos de linea
		$this->Ln(0);
		$this->Cell(250, 14, utf8_decode('RESUMEN DE PERCEPCIONES Y DEDUCCIONES DE LA QUINCENA '.$GLOBALS["CveNomina"]), 10, 10, 'C');
		$this->Ln(0);

		
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
//Exxtaccion de claves de contrato
$consulta = "SELECT * FROM `Contratos` WHERE CveContrato='DEPOR Ene-Jun 2022'";
$resultado = $mysqli->query($consulta);
//COMEM********************************************************************************************************************************
//Extraccion de totales
//Percepciones(Sueldos eventuales)
$consulta2 = "SELECT Clave as clave, COUNT(*) as cuenta, SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='DEPOR Ene-Jun 2022') AND Clave =0202 AND CveNomina='$CveNomina'";
$resultado2 = $mysqli->query($consulta2);
//Aportaciones(Subsidio al empleo)
$consulta3 = "SELECT Clave as clave, COUNT(*) as cuenta, SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='DEPOR Ene-Jun 2022') AND Clave =0325 AND CveNomina='$CveNomina'";
$resultado3 = $mysqli->query($consulta3);
//TOTALES DE PERCEPCIONES
$consulta4 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN( SELECT CvePersonal FROM EmpCont WHERE cveContrato = 'DEPOR Ene-Jun 2022') AND Clave IN (0202,0325) AND CveNomina = '$CveNomina';";
$resultado4 = $mysqli->query($consulta4);

//Deducciones
//Deducciones(SERVICIOS DE SALUD 4.625%)
$consulta5 = "SELECT Clave as clave, COUNT(*) as cuenta, SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='DEPOR Ene-Jun 2022') AND Clave =5540 AND CveNomina='$CveNomina'";
$resultado5 = $mysqli->query($consulta5);
//Deducciones(SISTEMA SOLIDARIO DE REPARTO 6.1%)
$consulta6 = "SELECT Clave as clave, COUNT(*) as cuenta, SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='DEPOR Ene-Jun 2022') AND Clave =5541 AND CveNomina='$CveNomina'";
$resultado6 = $mysqli->query($consulta6);
//Deducciones(CAPITALIZACION INDIVIDUAL 1.4%)
$consulta7 = "SELECT Clave as clave, COUNT(*) as cuenta, SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='DEPOR Ene-Jun 2022') AND Clave =5542 AND CveNomina='$CveNomina'";
$resultado7 = $mysqli->query($consulta7);
//Deducciones(IMPUESTO SOBRE LA RENTA)
$consulta8 = "SELECT Clave as clave, COUNT(*) as cuenta, SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='DEPOR Ene-Jun 2022') AND Clave =5408 AND CveNomina='$CveNomina'";
$resultado8 = $mysqli->query($consulta8);
//TOTAL DE DEDUCCIONES
$consulta9 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN( SELECT CvePersonal FROM EmpCont WHERE cveContrato = 'DEPOR Ene-Jun 2022') AND Clave IN (5540,5541,5542,5408) AND CveNomina = '$CveNomina';";
$resultado9 = $mysqli->query($consulta9);
//TOTAL NETO A PAGAR DE DEPORTE
$consulta10 = "
SELECT SUM(Importe)AS total1,(SELECT SUM(Importe) FROM DetNomina WHERE CvePersonal IN( SELECT CvePersonal FROM EmpCont WHERE cveContrato = 'DEPOR Ene-Jun 2022') AND Clave IN (5540,5541,5542,5408) AND CveNomina = '$CveNomina') AS total2 FROM DetNomina WHERE CvePersonal IN( SELECT CvePersonal FROM EmpCont WHERE cveContrato = 'DEPOR Ene-Jun 2022') AND Clave IN (0202,0325) AND CveNomina = '$CveNomina'";
$resultado10 = $mysqli->query($consulta10);

$consulta11 = "SELECT Clave as clave, COUNT(*) as cuenta, SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='DEPOR Ene-Jun 2022') AND Clave =0202 AND CveNomina='$CveNomina'";
$resultado11 = $mysqli->query($consulta2);

//10.0% SERVICIOS DE SALUD
$consulta12 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='DEPOR Ene-Jun 2022') AND Clave =5640 AND CveNomina='$CveNomina'";
$resultado12 = $mysqli->query($consulta12);
// 7.42% FONDO DEL SISTEMA SOLIDARIO D
$consulta13 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='DEPOR Ene-Jun 2022') AND Clave =5641 AND CveNomina='$CveNomina'";
$resultado13 = $mysqli->query($consulta13);
//1.85% SISTEMA DE CAPITALI. INDIVI.
$consulta14 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='DEPOR Ene-Jun 2022') AND Clave =5642 AND CveNomina='$CveNomina'";
$resultado14 = $mysqli->query($consulta14);
 //0.875% GASTOS DE ADMINISTRACION 
$consulta15 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='DEPOR Ene-Jun 2022') AND Clave =5643 AND CveNomina='$CveNomina'";
$resultado15 = $mysqli->query($consulta15);
 //1.0% PRIMA BASICA 
$consulta16 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='DEPOR Ene-Jun 2022') AND Clave =5644 AND CveNomina='$CveNomina'";
$resultado16 = $mysqli->query($consulta16);
 //1.989% PRIMA DE SINIESTRALIDAD	
$consulta17 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='DEPOR Ene-Jun 2022') AND Clave =5645 AND CveNomina='$CveNomina'";
$resultado17 = $mysqli->query($consulta17);
  //0.104% PRIMA RIESGO NO CONTROLADO 	
$consulta18 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='DEPOR Ene-Jun 2022') AND Clave =5647 AND CveNomina='$CveNomina'";
$resultado18 = $mysqli->query($consulta18);
  //3.0% TOTAL IMPUESTO SOBRE REMUNERACIONES
$consulta19 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='DEPOR Ene-Jun 2022') AND Clave =5641 AND CveNomina='$CveNomina'";
$resultado19 = $mysqli->query($consulta19);


//COMEM************************************************************************************************************************************
$consulta20 = "SELECT * FROM `Contratos` WHERE CveContrato='COMEM Ene-Jun 2022'";
$resultado20 = $mysqli->query($consulta20);
//Percepciones(Sueldos eventuales)
$consulta21 = "SELECT Clave as clave, COUNT(*) as cuenta, SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='COMEM Ene-Jun 2022') AND Clave =0202 AND CveNomina='$CveNomina'";
$resultado21 = $mysqli->query($consulta21);
//Aportaciones(Subsidio al empleo)
$consulta22 = "SELECT Clave as clave, COUNT(*) as cuenta, SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='COMEM Ene-Jun 2022') AND Clave =0325 AND CveNomina='$CveNomina'";
$resultado22 = $mysqli->query($consulta22);
//TOTALES DE PERCEPCIONES
$consulta23 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN( SELECT CvePersonal FROM EmpCont WHERE cveContrato = 'COMEM Ene-Jun 2022') AND Clave IN (0202,0325) AND CveNomina = '$CveNomina';";
$resultado23 = $mysqli->query($consulta23);
//Deducciones
//Deducciones(SERVICIOS DE SALUD 4.625%)
$consulta24 = "SELECT Clave as clave, COUNT(*) as cuenta, SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='COMEM Ene-Jun 2022') AND Clave =5540 AND CveNomina='$CveNomina'";
$resultado24 = $mysqli->query($consulta24);
//Deducciones(SISTEMA SOLIDARIO DE REPARTO 6.1%)
$consulta25 = "SELECT Clave as clave, COUNT(*) as cuenta, SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='COMEM Ene-Jun 2022') AND Clave =5541 AND CveNomina='$CveNomina'";
$resultado25 = $mysqli->query($consulta25);
//Deducciones(CAPITALIZACION INDIVIDUAL 1.4%)
$consulta26 = "SELECT Clave as clave, COUNT(*) as cuenta, SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='COMEM Ene-Jun 2022') AND Clave =5542 AND CveNomina='$CveNomina'";
$resultado26 = $mysqli->query($consulta26);
//Deducciones(IMPUESTO SOBRE LA RENTA)
$consulta27 = "SELECT Clave as clave, COUNT(*) as cuenta, SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='COMEM Ene-Jun 2022') AND Clave =5408 AND CveNomina='$CveNomina'";
$resultado27 = $mysqli->query($consulta27);
//TOTAL DE DEDUCCIONES
$consulta28 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN( SELECT CvePersonal FROM EmpCont WHERE cveContrato = 'COMEM Ene-Jun 2022') AND Clave IN (5540,5541,5542,5408) AND CveNomina = '$CveNomina';";
$resultado28 = $mysqli->query($consulta28);
//TOTAL NETO A PAGAR DE DEPORTE
$consulta29 = "
SELECT SUM(Importe)AS total1,(SELECT SUM(Importe) FROM DetNomina WHERE CvePersonal IN( SELECT CvePersonal FROM EmpCont WHERE cveContrato = 'COMEM Ene-Jun 2022') AND Clave IN (5540,5541,5542,5408) AND CveNomina = '$CveNomina') AS total2 FROM DetNomina WHERE CvePersonal IN( SELECT CvePersonal FROM EmpCont WHERE cveContrato = 'COMEM Ene-Jun 2022') AND Clave IN (0202,0325) AND CveNomina = '$CveNomina'";
$resultado29 = $mysqli->query($consulta29);

$consulta30 = "SELECT Clave as clave, COUNT(*) as cuenta, SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='COMEM Ene-Jun 2022') AND Clave =0202 AND CveNomina='$CveNomina'";
$resultado30 = $mysqli->query($consulta30);

//10.0% SERVICIOS DE SALUD
$consulta31 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='COMEM Ene-Jun 2022') AND Clave =5640 AND CveNomina='$CveNomina'";
$resultado31 = $mysqli->query($consulta31);
// 7.42% FONDO DEL SISTEMA SOLIDARIO D
$consulta32 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='COMEM Ene-Jun 2022') AND Clave =5641 AND CveNomina='$CveNomina'";
$resultado32 = $mysqli->query($consulta32);
//1.85% SISTEMA DE CAPITALI. INDIVI.
$consulta33 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='COMEM Ene-Jun 2022') AND Clave =5642 AND CveNomina='$CveNomina'";
$resultado33 = $mysqli->query($consulta33);
 //0.875% GASTOS DE ADMINISTRACION 
$consulta34 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='COMEM Ene-Jun 2022') AND Clave =5643 AND CveNomina='$CveNomina'";
$resultado34 = $mysqli->query($consulta34);
 //1.0% PRIMA BASICA 
$consulta35 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='COMEM Ene-Jun 2022') AND Clave =5644 AND CveNomina='$CveNomina'";
$resultado35 = $mysqli->query($consulta35);
 //1.989% PRIMA DE SINIESTRALIDAD	
$consulta36 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='COMEM Ene-Jun 2022') AND Clave =5645 AND CveNomina='$CveNomina'";
$resultado36 = $mysqli->query($consulta36);
  //0.104% PRIMA RIESGO NO CONTROLADO 	
$consulta37 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='COMEM Ene-Jun 2022') AND Clave =5647 AND CveNomina='$CveNomina'";
$resultado37 = $mysqli->query($consulta37);
  //3.0% TOTAL IMPUESTO SOBRE REMUNERACIONES
$consulta38 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='COMEM Ene-Jun 2022') AND Clave =5641 AND CveNomina='$CveNomina'";
$resultado38 = $mysqli->query($consulta38);
//COMEM************************************************************************************************************************************
$consulta20 = "SELECT * FROM `Contratos` WHERE CveContrato='COMEM Ene-Jun 2022'";
$resultado20 = $mysqli->query($consulta20);
//Percepciones(Sueldos eventuales)
$consulta21 = "SELECT Clave as clave, COUNT(*) as cuenta, SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='COMEM Ene-Jun 2022') AND Clave =0202 AND CveNomina='$CveNomina'";
$resultado21 = $mysqli->query($consulta21);
//Aportaciones(Subsidio al empleo)
$consulta22 = "SELECT Clave as clave, COUNT(*) as cuenta, SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='COMEM Ene-Jun 2022') AND Clave =0325 AND CveNomina='$CveNomina'";
$resultado22 = $mysqli->query($consulta22);
//TOTALES DE PERCEPCIONES
$consulta23 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN( SELECT CvePersonal FROM EmpCont WHERE cveContrato = 'COMEM Ene-Jun 2022') AND Clave IN (0202,0325) AND CveNomina = '$CveNomina';";
$resultado23 = $mysqli->query($consulta23);
//Deducciones
//Deducciones(SERVICIOS DE SALUD 4.625%)
$consulta24 = "SELECT Clave as clave, COUNT(*) as cuenta, SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='COMEM Ene-Jun 2022') AND Clave =5540 AND CveNomina='$CveNomina'";
$resultado24 = $mysqli->query($consulta24);
//Deducciones(SISTEMA SOLIDARIO DE REPARTO 6.1%)
$consulta25 = "SELECT Clave as clave, COUNT(*) as cuenta, SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='COMEM Ene-Jun 2022') AND Clave =5541 AND CveNomina='$CveNomina'";
$resultado25 = $mysqli->query($consulta25);
//Deducciones(CAPITALIZACION INDIVIDUAL 1.4%)
$consulta26 = "SELECT Clave as clave, COUNT(*) as cuenta, SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='COMEM Ene-Jun 2022') AND Clave =5542 AND CveNomina='$CveNomina'";
$resultado26 = $mysqli->query($consulta26);
//Deducciones(IMPUESTO SOBRE LA RENTA)
$consulta27 = "SELECT Clave as clave, COUNT(*) as cuenta, SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='COMEM Ene-Jun 2022') AND Clave =5408 AND CveNomina='$CveNomina'";
$resultado27 = $mysqli->query($consulta27);
//TOTAL DE DEDUCCIONES
$consulta28 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN( SELECT CvePersonal FROM EmpCont WHERE cveContrato = 'COMEM Ene-Jun 2022') AND Clave IN (5540,5541,5542,5408) AND CveNomina = '$CveNomina';";
$resultado28 = $mysqli->query($consulta28);
//TOTAL NETO A PAGAR DE DEPORTE
$consulta29 = "
SELECT SUM(Importe)AS total1,(SELECT SUM(Importe) FROM DetNomina WHERE CvePersonal IN( SELECT CvePersonal FROM EmpCont WHERE cveContrato = 'COMEM Ene-Jun 2022') AND Clave IN (5540,5541,5542,5408) AND CveNomina = '$CveNomina') AS total2 FROM DetNomina WHERE CvePersonal IN( SELECT CvePersonal FROM EmpCont WHERE cveContrato = 'COMEM Ene-Jun 2022') AND Clave IN (0202,0325) AND CveNomina = '$CveNomina'";
$resultado29 = $mysqli->query($consulta29);

$consulta30 = "SELECT Clave as clave, COUNT(*) as cuenta, SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='COMEM Ene-Jun 2022') AND Clave =0202 AND CveNomina='$CveNomina'";
$resultado30 = $mysqli->query($consulta30);

//10.0% SERVICIOS DE SALUD
$consulta31 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='COMEM Ene-Jun 2022') AND Clave =5640 AND CveNomina='$CveNomina'";
$resultado31 = $mysqli->query($consulta31);
// 7.42% FONDO DEL SISTEMA SOLIDARIO D
$consulta32 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='COMEM Ene-Jun 2022') AND Clave =5641 AND CveNomina='$CveNomina'";
$resultado32 = $mysqli->query($consulta32);
//1.85% SISTEMA DE CAPITALI. INDIVI.
$consulta33 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='COMEM Ene-Jun 2022') AND Clave =5642 AND CveNomina='$CveNomina'";
$resultado33 = $mysqli->query($consulta33);
 //0.875% GASTOS DE ADMINISTRACION 
$consulta34 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='COMEM Ene-Jun 2022') AND Clave =5643 AND CveNomina='$CveNomina'";
$resultado34 = $mysqli->query($consulta34);
 //1.0% PRIMA BASICA 
$consulta35 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='COMEM Ene-Jun 2022') AND Clave =5644 AND CveNomina='$CveNomina'";
$resultado35 = $mysqli->query($consulta35);
 //1.989% PRIMA DE SINIESTRALIDAD	
$consulta36 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='COMEM Ene-Jun 2022') AND Clave =5645 AND CveNomina='$CveNomina'";
$resultado36 = $mysqli->query($consulta36);
  //0.104% PRIMA RIESGO NO CONTROLADO 	
$consulta37 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='COMEM Ene-Jun 2022') AND Clave =5647 AND CveNomina='$CveNomina'";
$resultado37 = $mysqli->query($consulta37);
  //3.0% TOTAL IMPUESTO SOBRE REMUNERACIONES
$consulta38 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='COMEM Ene-Jun 2022') AND Clave =5641 AND CveNomina='$CveNomina'";
$resultado38 = $mysqli->query($consulta38);
//PATRIMONIO**************************************************************************************************************************
$consulta39 = "SELECT * FROM `Contratos` WHERE CveContrato='PATRI Ene-Jun 2022'";
$resultado39 = $mysqli->query($consulta39);
//Percepciones(Sueldos eventuales)
$consulta40 = "SELECT Clave as clave, COUNT(*) as cuenta, SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='PATRI Ene-Jun 2022') AND Clave =0202 AND CveNomina='$CveNomina'";
$resultado40 = $mysqli->query($consulta40);
//Aportaciones(Subsidio al empleo)
$consulta41 = "SELECT Clave as clave, COUNT(*) as cuenta, SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='PATRI Ene-Jun 2022') AND Clave =0325 AND CveNomina='$CveNomina'";
$resultado41 = $mysqli->query($consulta41);
//TOTALES DE PERCEPCIONES
$consulta42 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN( SELECT CvePersonal FROM EmpCont WHERE cveContrato = 'PATRI Ene-Jun 2022') AND Clave IN (0202,0325) AND CveNomina = '$CveNomina';";
$resultado42 = $mysqli->query($consulta42);
//Deducciones
//Deducciones(SERVICIOS DE SALUD 4.625%)
$consulta43 = "SELECT Clave as clave, COUNT(*) as cuenta, SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='PATRI Ene-Jun 2022') AND Clave =5540 AND CveNomina='$CveNomina'";
$resultado43 = $mysqli->query($consulta43);
//Deducciones(SISTEMA SOLIDARIO DE REPARTO 6.1%)
$consulta44 = "SELECT Clave as clave, COUNT(*) as cuenta, SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='PATRI Ene-Jun 2022') AND Clave =5541 AND CveNomina='$CveNomina'";
$resultado44 = $mysqli->query($consulta44);
//Deducciones(CAPITALIZACION INDIVIDUAL 1.4%)
$consulta45 = "SELECT Clave as clave, COUNT(*) as cuenta, SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='PATRI Ene-Jun 2022') AND Clave =5542 AND CveNomina='$CveNomina'";
$resultado45 = $mysqli->query($consulta45);
//Deducciones(IMPUESTO SOBRE LA RENTA)
$consulta46 = "SELECT Clave as clave, COUNT(*) as cuenta, SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='PATRI Ene-Jun 2022') AND Clave =5408 AND CveNomina='$CveNomina'";
$resultado46 = $mysqli->query($consulta46);
//TOTAL DE DEDUCCIONES
$consulta47 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN( SELECT CvePersonal FROM EmpCont WHERE cveContrato = 'PATRI Ene-Jun 2022') AND Clave IN (5540,5541,5542,5408) AND CveNomina = '$CveNomina';";
$resultado47 = $mysqli->query($consulta47);
//TOTAL NETO A PAGAR DE DEPORTE
$consulta48 = "
SELECT SUM(Importe)AS total1,(SELECT SUM(Importe) FROM DetNomina WHERE CvePersonal IN( SELECT CvePersonal FROM EmpCont WHERE cveContrato = 'PATRI Ene-Jun 2022') AND Clave IN (5540,5541,5542,5408) AND CveNomina = '$CveNomina') AS total2 FROM DetNomina WHERE CvePersonal IN( SELECT CvePersonal FROM EmpCont WHERE cveContrato = 'PATRI Ene-Jun 2022') AND Clave IN (0202,0325) AND CveNomina = '$CveNomina'";
$resultado48 = $mysqli->query($consulta48);

$consulta49 = "SELECT Clave as clave, COUNT(*) as cuenta, SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='PATRI Ene-Jun 2022') AND Clave =0202 AND CveNomina='$CveNomina'";
$resultado49 = $mysqli->query($consulta49);

//10.0% SERVICIOS DE SALUD
$consulta50 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='PATRI Ene-Jun 2022') AND Clave =5640 AND CveNomina='$CveNomina'";
$resultado50 = $mysqli->query($consulta50);
// 7.42% FONDO DEL SISTEMA SOLIDARIO D
$consulta51 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='PATRI Ene-Jun 2022') AND Clave =5641 AND CveNomina='$CveNomina'";
$resultado51 = $mysqli->query($consulta51);
//1.85% SISTEMA DE CAPITALI. INDIVI.
$consulta52 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='PATRI Ene-Jun 2022') AND Clave =5642 AND CveNomina='$CveNomina'";
$resultado52 = $mysqli->query($consulta52);
 //0.875% GASTOS DE ADMINISTRACION 
$consulta53 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='PATRI Ene-Jun 2022') AND Clave =5643 AND CveNomina='$CveNomina'";
$resultado53 = $mysqli->query($consulta53);
 //1.0% PRIMA BASICA 
$consulta54 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='PATRI Ene-Jun 2022') AND Clave =5644 AND CveNomina='$CveNomina'";
$resultado54 = $mysqli->query($consulta54);
 //1.989% PRIMA DE SINIESTRALIDAD	
$consulta55 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='PATRI Ene-Jun 2022') AND Clave =5645 AND CveNomina='$CveNomina'";
$resultado55 = $mysqli->query($consulta55);
  //0.104% PRIMA RIESGO NO CONTROLADO 	
$consulta56 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='PATRI Ene-Jun 2022') AND Clave =5647 AND CveNomina='$CveNomina'";
$resultado56 = $mysqli->query($consulta56);
  //3.0% TOTAL IMPUESTO SOBRE REMUNERACIONES
$consulta57 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='PATRI Ene-Jun 2022') AND Clave =5641 AND CveNomina='$CveNomina'";
$resultado57 = $mysqli->query($consulta57);
//TOTALES**************************************************************************************************************************

//Percepciones(Sueldos eventuales)
$consulta58 = "SELECT clave,COUNT(*) as cuenta, SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont) AND Clave =0202 AND CveNomina='$CveNomina'";

$resultado58 = $mysqli->query($consulta58);
//Aportaciones(Subsidio al empleo)
$consulta59 = "SELECT clave,COUNT(*) as cuenta, SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont) AND Clave =0325 AND CveNomina='$CveNomina'";
$resultado59 = $mysqli->query($consulta59);
//TOTALES DE PERCEPCIONES
$consulta60 = "SELECT SUM(Importe)AS total1,(SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont) AND Clave =0202 AND CveNomina='$CveNomina') AS total2 FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont) AND Clave =0325 AND CveNomina='$CveNomina'";
$resultado60 = $mysqli->query($consulta60);
////////////////////////
//Deducciones
//Deducciones(SERVICIOS DE SALUD 4.625%)
$consulta61 = "SELECT Clave as clave, COUNT(*) as cuenta, SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont) AND Clave =5540 AND CveNomina='$CveNomina'";
$resultado61 = $mysqli->query($consulta61);
//Deducciones(SISTEMA SOLIDARIO DE REPARTO 6.1%)
$consulta62 = "SELECT Clave as clave, COUNT(*) as cuenta, SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont) AND Clave =5541 AND CveNomina='$CveNomina'";
$resultado62 = $mysqli->query($consulta62);
//Deducciones(CAPITALIZACION INDIVIDUAL 1.4%)
$consulta63 = "SELECT Clave as clave, COUNT(*) as cuenta, SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont) AND Clave =5542 AND CveNomina='$CveNomina'";
$resultado63 = $mysqli->query($consulta63);
//Deducciones(IMPUESTO SOBRE LA RENTA)
$consulta64 = "SELECT Clave as clave, COUNT(*) as cuenta, SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont) AND Clave =5408 AND CveNomina='$CveNomina'";
$resultado64 = $mysqli->query($consulta64);
//TOTAL DE DEDUCCIONES
$consulta65 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN( SELECT CvePersonal FROM EmpCont) AND Clave IN (5540,5541,5542,5408) AND CveNomina = '$CveNomina';";
$resultado65 = $mysqli->query($consulta65);
//TOTAL NETO A PAGAR DE DEPORTE
$consulta66 = "
SELECT SUM(Importe)AS total1,(SELECT SUM(Importe) FROM DetNomina WHERE CvePersonal IN( SELECT CvePersonal FROM EmpCont ) AND Clave IN (5540,5541,5542,5408) AND CveNomina = '$CveNomina') AS total2 FROM DetNomina WHERE CvePersonal IN( SELECT CvePersonal FROM EmpCont) AND Clave IN (0202,0325) AND CveNomina = '$CveNomina'";
$resultado66 = $mysqli->query($consulta66);

//////////////
$consulta67 = "SELECT Clave as clave, COUNT(*) as cuenta, SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont) AND Clave =0202 AND CveNomina='$CveNomina'";
$resultado67 = $mysqli->query($consulta67);

//10.0% SERVICIOS DE SALUD
$consulta68 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont) AND Clave =5640 AND CveNomina='$CveNomina'";
$resultado68 = $mysqli->query($consulta68);
// 7.42% FONDO DEL SISTEMA SOLIDARIO D
$consulta69 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont ) AND Clave =5641 AND CveNomina='$CveNomina'";
$resultado69 = $mysqli->query($consulta69);
//1.85% SISTEMA DE CAPITALI. INDIVI.
$consulta70 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont) AND Clave =5642 AND CveNomina='$CveNomina'";
$resultado70 = $mysqli->query($consulta70);
 //0.875% GASTOS DE ADMINISTRACION 
$consulta71 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont) AND Clave =5643 AND CveNomina='$CveNomina'";
$resultado71 = $mysqli->query($consulta71);
 //1.0% PRIMA BASICA 
$consulta72 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont) AND Clave =5644 AND CveNomina='$CveNomina'";
$resultado72 = $mysqli->query($consulta72);
 //1.989% PRIMA DE SINIESTRALIDAD	
$consulta73 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont ) AND Clave =5645 AND CveNomina='$CveNomina'";
$resultado73 = $mysqli->query($consulta73);
  //0.104% PRIMA RIESGO NO CONTROLADO 	
$consulta74 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont) AND Clave =5647 AND CveNomina='$CveNomina'";
$resultado74 = $mysqli->query($consulta74);
  //3.0% TOTAL IMPUESTO SOBRE REMUNERACIONES
$consulta75 = "SELECT SUM(Importe) suma FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont) AND Clave =5641 AND CveNomina='$CveNomina'";
$resultado75 = $mysqli->query($consulta75);

////VALIDACIONES
//DEPORTE
$consulta76 = "SELECT COUNT(*) as cuenta FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='DEPOR Ene-Jun 2022') AND Clave =0202 AND CveNomina='$CveNomina'";
$resultado76 = $mysqli->query($consulta76);
//COMEM
$consulta77 = "SELECT COUNT(*) as cuenta FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='COMEM Ene-Jun 2022') AND Clave =0202 AND CveNomina='$CveNomina'";
$resultado77 = $mysqli->query($consulta77);
//PATRIMONIO
$consulta78 = "SELECT COUNT(*) as cuenta FROM DetNomina WHERE CvePersonal IN (SELECT CvePersonal FROM EmpCont WHERE cveContrato='PATRI Ene-Jun 2022') AND Clave =0202 AND CveNomina='$CveNomina'";
$resultado78 = $mysqli->query($consulta78);



// Creación del objeto de la clase heredada
$pdf = new PDF('L', 'mm', 'letter');//Indicando formato horizontal del reporte
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Helvetica', '', 12);
//DEPORTE*******************************************************************************************************************************


//                                    margem - salto de linea  ALIGN // tachar
while ($row = $resultado76->fetch_assoc()) {

	if($row['cuenta']>0){
		$a=$a+1;
		while ($row = $resultado->fetch_assoc()) {
	//Ancho alto,borde,salto de linea justificacion relleno
			
			$pdf->Cell(135,10, utf8_decode('Clave de contrato: '.$row['CveContrato']), 0, 0, 'C', 0);
			$pdf->Cell(100,10, utf8_decode($row['Descripcion']), 0, 0, 'C', 0);
		}
//Indicar salida del archivo pdf
		$pdf->Cell(43,10, utf8_decode(""), 0, 1, 'C', 0);
		$pdf->Cell(150,15, utf8_decode('PERCEPCIONES'), 0, 0, 'C', 0);
		$pdf->Cell(43,10, utf8_decode(""), 0, 1, 'C', 0);
		$pdf->Cell(100,16, utf8_decode('Clave'), 0, 0, 'C', 0);
		$pdf->Cell(20,16, utf8_decode('Concepto'), 0, 0, 'C', 0);
		$pdf->Cell(150,16, utf8_decode('Importe'), 0, 0, 'C', 0);
		$pdf->Cell(43,10, utf8_decode(''), 0, 1, 'C', 0);
		$pdf->Cell(43,10, utf8_decode(''), 0, 1, 'C', 0);
		$pdf->SetFont('Helvetica', '', 12);

		while ($row = $resultado2->fetch_assoc()) {
			$pdf->Cell(100,7, utf8_decode($row['clave']), 0, 0, 'C', 0);
			$pdf->Cell(22,7, utf8_decode('SUELDOS EVENTUALES'), 0, 0, 'C', 0);
			$pdf->Cell(72,7, utf8_decode($row['cuenta']), 0, 0, 'C', 0);
			$pdf->Cell(10,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);

		}
		while ($row = $resultado3->fetch_assoc()) {
			$pdf->Cell(100,7, utf8_decode($row['clave']), 0, 0, 'C', 0);
			$pdf->Cell(22,7, utf8_decode('SUBSIDIO AL EMPLEO'), 0, 0, 'C', 0);
			$pdf->Cell(72,7, utf8_decode($row['cuenta']), 0, 0, 'C', 0);
			$pdf->Cell(10,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
		}

		$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
		while ($row = $resultado4->fetch_assoc()) {
			$pdf->Cell(150,10, utf8_decode('TOTAL DE PERCEPCIONES'), 0, 0, 'C', 0);
			$pdf->Cell(54,10, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
		}

		$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
		$pdf->Cell(150,10, utf8_decode('DEDUCCIONES'), 0, 0, 'C', 0);
		$pdf->Cell(43,8, utf8_decode(""), 0, 1, 'C', 0);
		$pdf->Cell(100,16, utf8_decode('Clave'), 0, 0, 'C', 0);
		$pdf->Cell(20,16, utf8_decode('Concepto'), 0, 0, 'C', 0);
		$pdf->Cell(150,16, utf8_decode('Importe'), 0, 0, 'C', 0);
		$pdf->Cell(43,10, utf8_decode(''), 0, 1, 'C', 0);
		$pdf->Cell(43,10, utf8_decode(''), 0, 1, 'C', 0);

		while ($row = $resultado5->fetch_assoc()) {
			$pdf->Cell(100,7, utf8_decode($row['clave']), 0, 0, 'C', 0);
			$pdf->Cell(22,7, utf8_decode('SERVICIOS DE SALUD 4.625% '), 0, 0, 'C', 0);
			$pdf->Cell(72,7, utf8_decode($row['cuenta']), 0, 0, 'C', 0);
			$pdf->Cell(10,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);

		}
		while ($row = $resultado6->fetch_assoc()) {
			$pdf->Cell(100,7, utf8_decode($row['clave']), 0, 0, 'C', 0);
			$pdf->Cell(22,7, utf8_decode('SISTEMA SOLIDARIO DE REPARTO 6.1%'), 0, 0, 'C', 0);
			$pdf->Cell(72,7, utf8_decode($row['cuenta']), 0, 0, 'C', 0);
			$pdf->Cell(10,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);

		}

		while ($row = $resultado7->fetch_assoc()) {
			$pdf->Cell(100,7, utf8_decode($row['clave']), 0, 0, 'C', 0);
			$pdf->Cell(22,7, utf8_decode('CAPITALIZACION INDIVIDUAL 1.4%'), 0, 0, 'C', 0);
			$pdf->Cell(72,7, utf8_decode($row['cuenta']), 0, 0, 'C', 0);
			$pdf->Cell(10,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);

		}
		while ($row = $resultado8->fetch_assoc()) {
			$pdf->Cell(100,7, utf8_decode($row['clave']), 0, 0, 'C', 0);
			$pdf->Cell(22,7, utf8_decode('IMPUESTO SOBRE LA RENTA '), 0, 0, 'C', 0);
			$pdf->Cell(72,7, utf8_decode($row['cuenta']), 0, 0, 'C', 0);
			$pdf->Cell(10,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);

		}
		$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
		while ($row = $resultado9->fetch_assoc()) {
			$pdf->Cell(150,10, utf8_decode('TOTAL DE DEDUCCIONES'), 0, 0, 'C', 0);
			$pdf->Cell(54,10,utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
		}

		$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
		while ($row = $resultado10->fetch_assoc()) {
			$pdf->Cell(150,10, utf8_decode('TOTAL NETO A PAGAR'), 0, 0, 'C', 0);
			$pdf->Cell(54,10, utf8_decode("$".number_format($row['total1']-$row['total2'],2,".",",")), 0, 1, 'R', 0);
			$pdf->Cell(54,5, utf8_decode(""), 0, 1, 'R', 0);
		}
//$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
		while ($row = $resultado11->fetch_assoc()) {
			$pdf->Cell(150,10, utf8_decode('TOTAL SUELDOS EVENTUALES'), 0, 0, 'C', 0);
			$pdf->Cell(10,12, utf8_decode($row['cuenta']), 0, 1, 'C', 0);

		}
		while ($row = $resultado12->fetch_assoc()) {
			$pdf->Cell(150,10, utf8_decode('10.0% SERVICIOS DE SALUD'), 0, 0, 'C', 0);
			$pdf->Cell(54,6, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
		}
		while ($row = $resultado13->fetch_assoc()) {
			$pdf->Cell(150,10, utf8_decode('7.42% FONDO DEL SISTEMA SOLIDARIO D'), 0, 0, 'C', 0);
			$pdf->Cell(54,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
		}

		while ($row = $resultado14->fetch_assoc()) {
			$pdf->Cell(150,10, utf8_decode('1.85% SISTEMA DE CAPITALI. INDIVI.'), 0, 0, 'C', 0);
			$pdf->Cell(54,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
		}

		while ($row = $resultado15->fetch_assoc()) {
			$pdf->Cell(150,10, utf8_decode('0.875% GASTOS DE ADMINISTRACION'), 0, 0, 'C', 0);
			$pdf->Cell(54,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
		}

		while ($row = $resultado16->fetch_assoc()) {
			$pdf->Cell(150,10, utf8_decode('1.0% PRIMA BASICA'), 0, 0, 'C', 0);
			$pdf->Cell(54,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
		}

		while ($row = $resultado17->fetch_assoc()) {
			$pdf->Cell(150,10, utf8_decode('1.989% PRIMA DE SINIESTRALIDAD'), 0, 0, 'C', 0);
			$pdf->Cell(54,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
		}

		while ($row = $resultado18->fetch_assoc()) {
			$pdf->Cell(150,10, utf8_decode('0.104% PRIMA RIESGO NO CONTROLADO'), 0, 0, 'C', 0);
			$pdf->Cell(54,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
		}

		while ($row = $resultado19->fetch_assoc()) {
			$pdf->Cell(150,10, utf8_decode('3.0% TOTAL IMPUESTO SOBRE REMUNERACIONES'), 0, 0, 'C', 0);
			$pdf->Cell(54,7, utf8_decode('PENDIENTE'), 0, 1, 'R', 0);
		}

	}
}

//COMEM******************************************************************************************************

while ($row = $resultado77->fetch_assoc()) {
	if ($row['cuenta']>0) {
		$b=$b+1;
		$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
		$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
		$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
		$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
		$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
		$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
		$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
		$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
		$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
		$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
		$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
		$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
		$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
		while ($row = $resultado20->fetch_assoc()) {
	//Ancho alto,borde,salto de linea justificacion relleno
			$pdf->Cell(135,10, utf8_decode('Clave de contrato: '.$row['CveContrato']), 0, 0, 'C', 0);
			$pdf->Cell(100,10, utf8_decode($row['Descripcion']), 0, 0, 'C', 0);
		}
		$pdf->Cell(43,10, utf8_decode(""), 0, 1, 'C', 0);
		$pdf->Cell(150,15, utf8_decode('PERCEPCIONES'), 0, 0, 'C', 0);
		$pdf->Cell(43,10, utf8_decode(""), 0, 1, 'C', 0);
		$pdf->Cell(100,16, utf8_decode('Clave'), 0, 0, 'C', 0);
		$pdf->Cell(20,16, utf8_decode('Concepto'), 0, 0, 'C', 0);
		$pdf->Cell(150,16, utf8_decode('Importe'), 0, 0, 'C', 0);
		$pdf->Cell(43,10, utf8_decode(''), 0, 1, 'C', 0);
		$pdf->Cell(43,10, utf8_decode(''), 0, 1, 'C', 0);

		while ($row = $resultado21->fetch_assoc()) {
			$pdf->Cell(100,7, utf8_decode($row['clave']), 0, 0, 'C', 0);
			$pdf->Cell(22,7, utf8_decode('SUELDOS EVENTUALES'), 0, 0, 'C', 0);
			$pdf->Cell(72,7, utf8_decode($row['cuenta']), 0, 0, 'C', 0);
			$pdf->Cell(10,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
		}
		while ($row = $resultado22->fetch_assoc()) {
			$pdf->Cell(100,7, utf8_decode($row['clave']), 0, 0, 'C', 0);
			$pdf->Cell(22,7, utf8_decode('SUBSIDIO AL EMPLEO'), 0, 0, 'C', 0);
			$pdf->Cell(72,7, utf8_decode($row['cuenta']), 0, 0, 'C', 0);
			$pdf->Cell(10,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
		}

		$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
		while ($row = $resultado23->fetch_assoc()) {
			$pdf->Cell(150,10, utf8_decode('TOTAL DE PERCEPCIONES'), 0, 0, 'C', 0);
			$pdf->Cell(54,10, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
		}
		$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
		$pdf->Cell(150,10, utf8_decode('DEDUCCIONES'), 0, 0, 'C', 0);
		$pdf->Cell(43,8, utf8_decode(""), 0, 1, 'C', 0);
		$pdf->Cell(100,16, utf8_decode('Clave'), 0, 0, 'C', 0);
		$pdf->Cell(20,16, utf8_decode('Concepto'), 0, 0, 'C', 0);
		$pdf->Cell(150,16, utf8_decode('Importe'), 0, 0, 'C', 0);
		$pdf->Cell(43,10, utf8_decode(''), 0, 1, 'C', 0);
		$pdf->Cell(43,10, utf8_decode(''), 0, 1, 'C', 0);
		while ($row = $resultado24->fetch_assoc()) {
			$pdf->Cell(100,7, utf8_decode($row['clave']), 0, 0, 'C', 0);
			$pdf->Cell(22,7, utf8_decode('SERVICIOS DE SALUD 4.625% '), 0, 0, 'C', 0);
			$pdf->Cell(72,7, utf8_decode($row['cuenta']), 0, 0, 'C', 0);
			$pdf->Cell(10,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
		}
		while ($row = $resultado25->fetch_assoc()) {
			$pdf->Cell(100,7, utf8_decode($row['clave']), 0, 0, 'C', 0);
			$pdf->Cell(22,7, utf8_decode('SISTEMA SOLIDARIO DE REPARTO 6.1%'), 0, 0, 'C', 0);
			$pdf->Cell(72,7, utf8_decode($row['cuenta']), 0, 0, 'C', 0);
			$pdf->Cell(10,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
		}
		while ($row = $resultado26->fetch_assoc()) {
			$pdf->Cell(100,7, utf8_decode($row['clave']), 0, 0, 'C', 0);
			$pdf->Cell(22,7, utf8_decode('CAPITALIZACION INDIVIDUAL 1.4%'), 0, 0, 'C', 0);
			$pdf->Cell(72,7, utf8_decode($row['cuenta']), 0, 0, 'C', 0);
			$pdf->Cell(10,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);

		}
		while ($row = $resultado27->fetch_assoc()) {
			$pdf->Cell(100,7, utf8_decode($row['clave']), 0, 0, 'C', 0);
			$pdf->Cell(22,7, utf8_decode('IMPUESTO SOBRE LA RENTA '), 0, 0, 'C', 0);
			$pdf->Cell(72,7, utf8_decode($row['cuenta']), 0, 0, 'C', 0);
			$pdf->Cell(10,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);

		}
		$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
		while ($row = $resultado28->fetch_assoc()) {
			$pdf->Cell(150,10, utf8_decode('TOTAL DE DEDUCCIONES'), 0, 0, 'C', 0);
			$pdf->Cell(54,10, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
		}

		$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
		while ($row = $resultado29->fetch_assoc()) {
			$pdf->Cell(150,10, utf8_decode('TOTAL NETO A PAGAR'), 0, 0, 'C', 0);
			$pdf->Cell(54,10, utf8_decode("$".number_format($row['total1']-$row['total2'],2,".",",")), 0, 1, 'R', 0);
			$pdf->Cell(54,5, utf8_decode(""), 0, 1, 'C', 0);
		}
		while ($row = $resultado30->fetch_assoc()) {
			$pdf->Cell(150,10, utf8_decode('TOTAL SUELDOS EVENTUALES'), 0, 0, 'C', 0);
			$pdf->Cell(10,12, utf8_decode($row['cuenta']), 0, 1, 'C', 0);
		}
		while ($row = $resultado31->fetch_assoc()) {
			$pdf->Cell(150,10, utf8_decode('10.0% SERVICIOS DE SALUD'), 0, 0, 'C', 0);
			$pdf->Cell(54,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
		}
		while ($row = $resultado32->fetch_assoc()) {
			$pdf->Cell(150,10, utf8_decode('7.42% FONDO DEL SISTEMA SOLIDARIO D'), 0, 0, 'C', 0);
			$pdf->Cell(54,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
		}

		while ($row = $resultado33->fetch_assoc()) {
			$pdf->Cell(150,10, utf8_decode('1.85% SISTEMA DE CAPITALI. INDIVI.'), 0, 0, 'C', 0);
			$pdf->Cell(54,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
		}

		while ($row = $resultado34->fetch_assoc()) {
			$pdf->Cell(150,10, utf8_decode('0.875% GASTOS DE ADMINISTRACION'), 0, 0, 'C', 0);
			$pdf->Cell(54,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
		}

		while ($row = $resultado35->fetch_assoc()) {
			$pdf->Cell(150,10, utf8_decode('1.0% PRIMA BASICA'), 0, 0, 'C', 0);
			$pdf->Cell(54,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
		}

		while ($row = $resultado36->fetch_assoc()) {
			$pdf->Cell(150,10, utf8_decode('1.989% PRIMA DE SINIESTRALIDAD'), 0, 0, 'C', 0);
			$pdf->Cell(54,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
		}

		while ($row = $resultado37->fetch_assoc()) {
			$pdf->Cell(150,10, utf8_decode('0.104% PRIMA RIESGO NO CONTROLADO'), 0, 0, 'C', 0);
			$pdf->Cell(54,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
		}

		while ($row = $resultado38->fetch_assoc()) {
			$pdf->Cell(150,10, utf8_decode('3.0% TOTAL IMPUESTO SOBRE REMUNERACIONES'), 0, 0, 'C', 0);
			$pdf->Cell(54,7, utf8_decode('PENDIENTE'), 0, 1, 'R', 0);
		}
	}
}

//PATRIMONIO******************************************************************************************************
while ($row = $resultado78->fetch_assoc()) {
	if ($row['cuenta']>0) {
		$c=$c+1;
		$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
		$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
		$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
		$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
		$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
		$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
		$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
		$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
		$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
		$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
		$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
		$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
		$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
		while ($row = $resultado39->fetch_assoc()) {
	//Ancho alto,borde,salto de linea justificacion relleno
			$pdf->Cell(135,10, utf8_decode('Clave de contrato: '.$row['CveContrato']), 0, 0, 'C', 0);
			$pdf->Cell(100,10, utf8_decode($row['Descripcion']), 0, 0, 'C', 0);
		}
		$pdf->Cell(43,10, utf8_decode(""), 0, 1, 'C', 0);
		$pdf->Cell(150,15, utf8_decode('PERCEPCIONES'), 0, 0, 'C', 0);
		$pdf->Cell(43,10, utf8_decode(""), 0, 1, 'C', 0);
		$pdf->Cell(100,16, utf8_decode('Clave'), 0, 0, 'C', 0);
		$pdf->Cell(20,16, utf8_decode('Concepto'), 0, 0, 'C', 0);
		$pdf->Cell(150,16, utf8_decode('Importe'), 0, 0, 'C', 0);
		$pdf->Cell(43,10, utf8_decode(''), 0, 1, 'C', 0);
		$pdf->Cell(43,10, utf8_decode(''), 0, 1, 'C', 0);

		while ($row = $resultado40->fetch_assoc()) {
			$pdf->Cell(100,7, utf8_decode($row['clave']), 0, 0, 'C', 0);
			$pdf->Cell(22,7, utf8_decode('SUELDOS EVENTUALES'), 0, 0, 'C', 0);
			$pdf->Cell(72,7, utf8_decode($row['cuenta']), 0, 0, 'C', 0);
			$pdf->Cell(10,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
		}
		while ($row = $resultado41->fetch_assoc()) {
			$pdf->Cell(100,7, utf8_decode($row['clave']), 0, 0, 'C', 0);
			$pdf->Cell(22,7, utf8_decode('SUBSIDIO AL EMPLEO'), 0, 0, 'C', 0);
			$pdf->Cell(72,7, utf8_decode($row['cuenta']), 0, 0, 'C', 0);
			$pdf->Cell(10,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
		}

		$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
		while ($row = $resultado42->fetch_assoc()) {
			$pdf->Cell(150,10, utf8_decode('TOTAL DE PERCEPCIONES'), 0, 0, 'C', 0);
			$pdf->Cell(54,10, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
		}
		$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
		$pdf->Cell(150,10, utf8_decode('DEDUCCIONES'), 0, 0, 'C', 0);
		$pdf->Cell(43,8, utf8_decode(""), 0, 1, 'C', 0);
		$pdf->Cell(100,16, utf8_decode('Clave'), 0, 0, 'C', 0);
		$pdf->Cell(20,16, utf8_decode('Concepto'), 0, 0, 'C', 0);
		$pdf->Cell(150,16, utf8_decode('Importe'), 0, 0, 'C', 0);
		$pdf->Cell(43,10, utf8_decode(''), 0, 1, 'C', 0);
		$pdf->Cell(43,10, utf8_decode(''), 0, 1, 'C', 0);
		while ($row = $resultado43->fetch_assoc()) {
			$pdf->Cell(100,7, utf8_decode($row['clave']), 0, 0, 'C', 0);
			$pdf->Cell(22,7, utf8_decode('SERVICIOS DE SALUD 4.625% '), 0, 0, 'C', 0);
			$pdf->Cell(72,7, utf8_decode($row['cuenta']), 0, 0, 'C', 0);
			$pdf->Cell(10,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
		}
		while ($row = $resultado44->fetch_assoc()) {
			$pdf->Cell(100,7, utf8_decode($row['clave']), 0, 0, 'C', 0);
			$pdf->Cell(22,7, utf8_decode('SISTEMA SOLIDARIO DE REPARTO 6.1%'), 0, 0, 'C', 0);
			$pdf->Cell(72,7, utf8_decode($row['cuenta']), 0, 0, 'C', 0);
			$pdf->Cell(10,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
		}
		while ($row = $resultado45->fetch_assoc()) {
			$pdf->Cell(100,7, utf8_decode($row['clave']), 0, 0, 'C', 0);
			$pdf->Cell(22,7, utf8_decode('CAPITALIZACION INDIVIDUAL 1.4%'), 0, 0, 'C', 0);
			$pdf->Cell(72,7, utf8_decode($row['cuenta']), 0, 0, 'C', 0);
			$pdf->Cell(10,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);

		}
		while ($row = $resultado46->fetch_assoc()) {
			$pdf->Cell(100,7, utf8_decode($row['clave']), 0, 0, 'C', 0);
			$pdf->Cell(22,7, utf8_decode('IMPUESTO SOBRE LA RENTA '), 0, 0, 'C', 0);
			$pdf->Cell(72,7, utf8_decode($row['cuenta']), 0, 0, 'C', 0);
			$pdf->Cell(10,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);

		}
		$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
		while ($row = $resultado47->fetch_assoc()) {
			$pdf->Cell(150,10, utf8_decode('TOTAL DE DEDUCCIONES'), 0, 0, 'C', 0);
			$pdf->Cell(54,10, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
		}

		$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
		while ($row = $resultado48->fetch_assoc()) {
			$pdf->Cell(150,10, utf8_decode('TOTAL NETO A PAGAR'), 0, 0, 'C', 0);
			$pdf->Cell(87,10, utf8_decode("$".number_format($row['total1']-$row['total2'],2,".",",")), 0, 1, 'C', 0);
			$pdf->Cell(54,5, utf8_decode(""), 0, 1, 'R', 0);
		}
		while ($row = $resultado49->fetch_assoc()) {
			$pdf->Cell(150,10, utf8_decode('TOTAL SUELDOS EVENTUALES'), 0, 0, 'C', 0);
			$pdf->Cell(10,12, utf8_decode($row['cuenta']), 0, 1, 'C', 0);
		}
		while ($row = $resultado50->fetch_assoc()) {
			$pdf->Cell(150,10, utf8_decode('10.0% SERVICIOS DE SALUD'), 0, 0, 'C', 0);
			$pdf->Cell(54,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
		}
		while ($row = $resultado51->fetch_assoc()) {
			$pdf->Cell(150,10, utf8_decode('7.42% FONDO DEL SISTEMA SOLIDARIO D'), 0, 0, 'C', 0);
			$pdf->Cell(54,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
		}

		while ($row = $resultado52->fetch_assoc()) {
			$pdf->Cell(150,10, utf8_decode('1.85% SISTEMA DE CAPITALI. INDIVI.'), 0, 0, 'C', 0);
			$pdf->Cell(54,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
		}

		while ($row = $resultado53->fetch_assoc()) {
			$pdf->Cell(150,10, utf8_decode('0.875% GASTOS DE ADMINISTRACION'), 0, 0, 'C', 0);
			$pdf->Cell(54,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
		}

		while ($row = $resultado54->fetch_assoc()) {
			$pdf->Cell(150,10, utf8_decode('1.0% PRIMA BASICA'), 0, 0, 'C', 0);
			$pdf->Cell(54,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
		}

		while ($row = $resultado55->fetch_assoc()) {
			$pdf->Cell(150,10, utf8_decode('1.989% PRIMA DE SINIESTRALIDAD'), 0, 0, 'C', 0);
			$pdf->Cell(54,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
		}

		while ($row = $resultado56->fetch_assoc()) {
			$pdf->Cell(150,10, utf8_decode('0.104% PRIMA RIESGO NO CONTROLADO'), 0, 0, 'C', 0);
			$pdf->Cell(54,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
		}

		while ($row = $resultado57->fetch_assoc()) {
			$pdf->Cell(150,10, utf8_decode('3.0% TOTAL IMPUESTO SOBRE REMUNERACIONES'), 0, 0, 'C', 0);
			$pdf->Cell(54,7, utf8_decode('PENDIENTE'), 0, 1, 'R', 0);
		}
	}
}

//TOLALES******************************************************************************************************
$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);	
$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);


if ($a+$b+$c<3) {
	$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);


}


	//Ancho alto,borde,salto de linea justificacion relleno
$pdf->Cell(250,10, utf8_decode('SECRETARIA DE CULTURA Y TURISMO'), 0, 1, 'C', 0);
$pdf->Cell(250,10, utf8_decode('RESUMEN GENERAL DE LA NOMINA '.$CveNomina), 0, 0, 'C', 0);

$pdf->Cell(43,10, utf8_decode(""), 0, 1, 'C', 0);
$pdf->Cell(150,15, utf8_decode('PERCEPCIONES'), 0, 0, 'C', 0);
$pdf->Cell(43,10, utf8_decode(""), 0, 1, 'C', 0);
$pdf->Cell(100,16, utf8_decode('Clave'), 0, 0, 'C', 0);
$pdf->Cell(20,16, utf8_decode('Concepto'), 0, 0, 'C', 0);
$pdf->Cell(150,16, utf8_decode('Importe'), 0, 0, 'C', 0);
$pdf->Cell(43,10, utf8_decode(''), 0, 1, 'C', 0);
$pdf->Cell(43,10, utf8_decode(''), 0, 1, 'C', 0);

while ($row = $resultado58->fetch_assoc()) {
	$pdf->Cell(100,7, utf8_decode($row['clave']), 0, 0, 'C', 0);
	$pdf->Cell(22,7, utf8_decode('SUELDOS EVENTUALES'), 0, 0, 'C', 0);
	$pdf->Cell(72,7, utf8_decode($row['cuenta']), 0, 0, 'C', 0);
	$pdf->Cell(10,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
}
while ($row = $resultado59->fetch_assoc()) {
	$pdf->Cell(100,7, utf8_decode($row['clave']), 0, 0, 'C', 0);
	$pdf->Cell(22,7, utf8_decode('SUBSIDIO AL EMPLEO'), 0, 0, 'C', 0);
	$pdf->Cell(72,7, utf8_decode($row['cuenta']), 0, 0, 'C', 0);
	$pdf->Cell(10,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
}

$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
while ($row = $resultado60->fetch_assoc()) {
	$pdf->Cell(150,10, utf8_decode('TOTAL DE PERCEPCIONES'), 0, 0, 'C', 0);
	$pdf->Cell(54,10, utf8_decode("$".number_format($row['total2']+$row['total1'],2,".",",")), 0, 1, 'R', 0);
}
$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
$pdf->Cell(150,10, utf8_decode('DEDUCCIONES'), 0, 0, 'C', 0);
$pdf->Cell(43,8, utf8_decode(""), 0, 1, 'C', 0);
$pdf->Cell(100,16, utf8_decode('Clave'), 0, 0, 'C', 0);
$pdf->Cell(20,16, utf8_decode('Concepto'), 0, 0, 'C', 0);
$pdf->Cell(150,16, utf8_decode('Importe'), 0, 0, 'C', 0);
$pdf->Cell(43,10, utf8_decode(''), 0, 1, 'C', 0);
$pdf->Cell(43,10, utf8_decode(''), 0, 1, 'C', 0);
while ($row = $resultado61->fetch_assoc()) {
	$pdf->Cell(100,7, utf8_decode($row['clave']), 0, 0, 'C', 0);
	$pdf->Cell(22,7, utf8_decode('SERVICIOS DE SALUD 4.625% '), 0, 0, 'C', 0);
	$pdf->Cell(72,7, utf8_decode($row['cuenta']), 0, 0, 'C', 0);
	$pdf->Cell(10,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
}
while ($row = $resultado62->fetch_assoc()) {
	$pdf->Cell(100,7, utf8_decode($row['clave']), 0, 0, 'C', 0);
	$pdf->Cell(22,7, utf8_decode('SISTEMA SOLIDARIO DE REPARTO 6.1%'), 0, 0, 'C', 0);
	$pdf->Cell(72,7, utf8_decode($row['cuenta']), 0, 0, 'C', 0);
	$pdf->Cell(10,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
}
while ($row = $resultado63->fetch_assoc()) {
	$pdf->Cell(100,7, utf8_decode($row['clave']), 0, 0, 'C', 0);
	$pdf->Cell(22,7, utf8_decode('CAPITALIZACION INDIVIDUAL 1.4%'), 0, 0, 'C', 0);
	$pdf->Cell(72,7, utf8_decode($row['cuenta']), 0, 0, 'C', 0);
	$pdf->Cell(10,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);

}
while ($row = $resultado64->fetch_assoc()) {
	$pdf->Cell(100,7, utf8_decode($row['clave']), 0, 0, 'C', 0);
	$pdf->Cell(22,7, utf8_decode('IMPUESTO SOBRE LA RENTA '), 0, 0, 'C', 0);
	$pdf->Cell(72,7, utf8_decode($row['cuenta']), 0, 0, 'C', 0);
	$pdf->Cell(10,7, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);

}
$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
while ($row = $resultado65->fetch_assoc()) {
	$pdf->Cell(150,10, utf8_decode('TOTAL DE DEDUCCIONES'), 0, 0, 'C', 0);
	$pdf->Cell(54,10, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
}

$pdf->Cell(43,5, utf8_decode(""), 0, 1, 'C', 0);
while ($row = $resultado66->fetch_assoc()) {
	$pdf->Cell(150,10, utf8_decode('TOTAL NETO A PAGAR'), 0, 0, 'C', 0);
	$pdf->Cell(54,10, utf8_decode("$".number_format($row['total1']-$row['total2'],2,".",",")), 0, 1, 'R', 0);
	$pdf->Cell(54,5, utf8_decode(""), 0, 1, 'R', 0);
}
while ($row = $resultado67->fetch_assoc()) {
	$pdf->Cell(150,10, utf8_decode('TOTAL SUELDOS EVENTUALES'), 0, 0, 'C', 0);
	$pdf->Cell(10,12, utf8_decode($row['cuenta']), 0, 1, 'C', 0);
}
while ($row = $resultado68->fetch_assoc()) {
	$pdf->Cell(150,10, utf8_decode('10.0% SERVICIOS DE SALUD'), 0, 0, 'C', 0);
	$pdf->Cell(54,10, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
}
while ($row = $resultado69->fetch_assoc()) {
	$pdf->Cell(150,10, utf8_decode('7.42% FONDO DEL SISTEMA SOLIDARIO D'), 0, 0, 'C', 0);
	$pdf->Cell(54,10, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
}

while ($row = $resultado70->fetch_assoc()) {
	$pdf->Cell(150,10, utf8_decode('1.85% SISTEMA DE CAPITALI. INDIVI.'), 0, 0, 'C', 0);
	$pdf->Cell(54,10, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
}

while ($row = $resultado71->fetch_assoc()) {
	$pdf->Cell(150,10, utf8_decode('0.875% GASTOS DE ADMINISTRACION'), 0, 0, 'C', 0);
	$pdf->Cell(54,10,utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
}

while ($row = $resultado72->fetch_assoc()) {
	$pdf->Cell(150,10, utf8_decode('1.0% PRIMA BASICA'), 0, 0, 'C', 0);
	$pdf->Cell(54,10, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
}

while ($row = $resultado73->fetch_assoc()) {
	$pdf->Cell(150,10, utf8_decode('1.989% PRIMA DE SINIESTRALIDAD'), 0, 0, 'C', 0);
	$pdf->Cell(54,10, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
}

while ($row = $resultado74->fetch_assoc()) {
	$pdf->Cell(150,10, utf8_decode('0.104% PRIMA RIESGO NO CONTROLADO'), 0, 0, 'C', 0);
	$pdf->Cell(54,10, utf8_decode("$".number_format($row['suma'],2,".",",")), 0, 1, 'R', 0);
}

while ($row = $resultado75->fetch_assoc()) {
	$pdf->Cell(150,10, utf8_decode('3.0% TOTAL IMPUESTO SOBRE REMUNERACIONES'), 0, 0, 'C', 0);
	$pdf->Cell(54,10, utf8_decode('PENDIENTE'), 0, 1, 'R', 0);
}




$pdf->Output();
?>
