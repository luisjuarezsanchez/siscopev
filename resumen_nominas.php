<?php
session_start();
$usuario = $_SESSION['username'];
if(!isset($usuario)){
	header("location: index.php");
}
$CveNomina = $_POST['CveNomina'];
$a = 0;
$b = 0;
$c = 0;
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
        $this->Cell(100, 5, utf8_decode(""), 0, 1, 'C', 0);

        $this->Cell(250, 15, utf8_decode('SECRETARIA DE CULTURA Y TURISMO'), 10, 10, 'C'); //derecha abajo Salto de línea
        //Saltos de linea
        $this->Ln(0);
        //$this->Cell(250, 14, utf8_decode('RESUMEN DE PERCEPCIONES Y DEDUCCIONES DE LA QUINCENA '.$GLOBALS["CveNomina"]), 10, 10, 'C');
        $this->Cell(250, 14, utf8_decode('RESUMEN DE PERCEPCIONES Y DEDUCCIONES DE LA QUINCENA '.substr($GLOBALS["CveNomina"],0,6)), 10, 10, 'C');
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
//CONSULTA PARA CONTRATOS DE DEPORTE//////////////////////////////////////////////////////////////////////////////////////////////////
$consulta = "SELECT 
#Calculo de sueldos eventuales
SUM(CASE WHEN DetNomina.Clave=0202 THEN Importe ELSE 0 END) AS eventuales,
#Cuenta de sueldos eventuales
(SELECT COUNT(*) FROM EmpCont INNER JOIN DetNomina ON EmpCont.CvePersonal = DetNomina.CvePersonal WHERE CveContrato LIKE '%DEPOR%' AND DetNomina.CveNomina='$CveNomina' AND DetNomina.Clave=0202) as sumaeventuales,
#Cuenta de subsidios
(SELECT COUNT(*) FROM EmpCont INNER JOIN DetNomina ON EmpCont.CvePersonal = DetNomina.CvePersonal WHERE CveContrato LIKE '%DEPOR%' AND DetNomina.CveNomina='$CveNomina' AND DetNomina.Clave=0325) as sumasubsidios,
#Calculo de subsidios al empleo
SUM(CASE WHEN DetNomina.Clave=0325 THEN Importe ELSE 0 END) AS subsidios,
#Calculo de Total de percepciones
SUM(CASE WHEN DetNomina.Clave IN (0325,0202) THEN Importe ELSE 0 END) AS totper,
#Cuenta de SERVICIOS DE SALUD
(SELECT COUNT(*) FROM EmpCont INNER JOIN DetNomina ON EmpCont.CvePersonal = DetNomina.CvePersonal WHERE CveContrato LIKE '%DEPOR%' AND DetNomina.CveNomina='$CveNomina' AND DetNomina.Clave=5540) as sumasalud,
#Calculo de Total de SERVICIOS DE SALUD
SUM(CASE WHEN DetNomina.Clave=5540 THEN Importe ELSE 0 END) AS sersalud,
#Cuenta de SISTEMA SOLIDARIO DE REPARTO
(SELECT COUNT(*) FROM EmpCont INNER JOIN DetNomina ON EmpCont.CvePersonal = DetNomina.CvePersonal WHERE CveContrato LIKE '%DEPOR%' AND DetNomina.CveNomina='$CveNomina' AND DetNomina.Clave=5541) as sumareparto,
#Calculo de Total de SISTEMA SOLIDARIO DE REPARTO
SUM(CASE WHEN DetNomina.Clave=5541 THEN Importe ELSE 0 END) AS sissolidario,
#Cuenta de APITALIZACION INDIVIDUAL 
(SELECT COUNT(*) FROM EmpCont INNER JOIN DetNomina ON EmpCont.CvePersonal = DetNomina.CvePersonal WHERE CveContrato LIKE '%DEPOR%' AND DetNomina.CveNomina='$CveNomina' AND DetNomina.Clave=5542) as sumacapita,
#Calculo de Total de CAPITALIZACION INDIVIDUAL
SUM(CASE WHEN DetNomina.Clave=5542 THEN Importe ELSE 0 END) AS siscapita,
#Cuenta de IMPUESTO SOBRE LA RENTA
(SELECT COUNT(*) FROM EmpCont INNER JOIN DetNomina ON EmpCont.CvePersonal = DetNomina.CvePersonal WHERE CveContrato LIKE '%DEPOR%' AND DetNomina.CveNomina='$CveNomina' AND DetNomina.Clave=5408) as sumaisr,
#Calculo de Total de IMPUESTO SOBRE LA RENTA
SUM(CASE WHEN DetNomina.Clave=5408 THEN Importe ELSE 0 END) AS isr,
#Calculo de Total de TOTAL DE DEDUCCIONES
SUM(CASE WHEN DetNomina.Clave IN (5540,5541,5542,5408) THEN Importe ELSE 0 END) AS totdeducciones,
#Calculo de Total de TOTAL NETO A PAGAR
SUM(CASE WHEN DetNomina.Clave IN (0202,0325) THEN Importe ELSE 0 END) - SUM(CASE WHEN DetNomina.Clave IN (5540,5541,5542,5408) THEN Importe ELSE 0 END)  AS totnetopagar,
#10.0% SERVICIOS DE SALUD  
SUM(CASE WHEN DetNomina.Clave = 5640 THEN Importe ELSE 0 END) AS sersalud2,
#7.42% FONDO DEL SISTEMA SOLIDARIO D 
SUM(CASE WHEN DetNomina.Clave = 5641 THEN Importe ELSE 0 END) AS fondosisre,
#1.85% SISTEMA DE CAPITALI. INDIVI.
SUM(CASE WHEN DetNomina.Clave = 5642 THEN Importe ELSE 0 END) AS siscapita2,
#0.875% GASTOS DE ADMINISTRACION
SUM(CASE WHEN DetNomina.Clave = 5643 THEN Importe ELSE 0 END) AS gasadmin,
#1.0% PRIMA BASICA
SUM(CASE WHEN DetNomina.Clave = 5644 THEN Importe ELSE 0 END) AS primbas,
#1.989% PRIMA DE SINIESTRALIDAD
SUM(CASE WHEN DetNomina.Clave = 5645 THEN Importe ELSE 0 END) AS primsinies,
#0.104% PRIMA RIESGO NO CONTROLADO
SUM(CASE WHEN DetNomina.Clave = 5647 THEN Importe ELSE 0 END) AS primriesgo,
#3.0% TOTAL IMPUESTO SOBRE REMUNERACIONES
SUM(CASE WHEN DetNomina.Clave = 5641 THEN Importe ELSE 0 END) AS impsoremunA
FROM EmpCont 
INNER JOIN
DetNomina ON EmpCont.CvePersonal = DetNomina.CvePersonal 
WHERE CveContrato LIKE '%DEPOR%' AND DetNomina.CveNomina='$CveNomina'";
//EFECTUANDO CONSULTA
$resultado = $mysqli->query($consulta);



//IMPRESION EN EL PDF DE CONTRATOS DE DEPORTE//////////////////////////////////////////////////////////////////////////////////////
// Creación del objeto de la clase heredada
$pdf = new PDF('L', 'mm', 'letter'); //Indicando formato horizontal del reporte
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Helvetica', '', 12);

$pdf->Cell(250, 10, utf8_decode('COORDINACION: 22600005L          DIREC. GRAL. DE CULTURA FISICA Y DEPORTE'), 10, 100, 'C', 0);

//Indicar salida del archivo pdf
$pdf->Cell(43, 10, utf8_decode(""), 0, 1, 'C', 0);
$pdf->Cell(150, 15, utf8_decode('PERCEPCIONES'), 0, 0, 'C', 0);
$pdf->Cell(43, 10, utf8_decode(""), 0, 1, 'C', 0);
$pdf->Cell(100, 16, utf8_decode('Clave'), 0, 0, 'C', 0);
$pdf->Cell(20, 16, utf8_decode('Concepto'), 0, 0, 'C', 0);
$pdf->Cell(150, 16, utf8_decode('Importe'), 0, 0, 'C', 0);
$pdf->Cell(43, 10, utf8_decode(''), 0, 1, 'C', 0);
$pdf->Cell(43, 10, utf8_decode(''), 0, 1, 'C', 0);
$pdf->SetFont('Helvetica', '', 12);


//$pdf->Cell(100,10, utf8_decode($row['Descripcion']), 0, 0, 'C', 0);

while ($row = $resultado->fetch_assoc()) {
    //SUEDOS EVENTUALES
    $pdf->Cell(100, 7, utf8_decode('0202'), 0, 0, 'C', 0);
    $pdf->Cell(22, 7, utf8_decode('SUELDOS EVENTUALES'), 0, 0, 'C', 0);
    $pdf->Cell(72, 7, utf8_decode($row['sumaeventuales']), 0, 0, 'C', 0);
    $pdf->Cell(10, 7, utf8_decode("$" . number_format($row['eventuales'], 2, ".", ",")), 0, 1, 'R', 0);

    //SUBSIDIO AL EMPLEO
    $pdf->Cell(100, 7, utf8_decode('0325'), 0, 0, 'C', 0);
    $pdf->Cell(22, 7, utf8_decode('SUBSIDIO AL EMPLEO'), 0, 0, 'C', 0);
    $pdf->Cell(72, 7, utf8_decode($row['sumasubsidios']), 0, 0, 'C', 0);
    $pdf->Cell(10, 7, utf8_decode("$" . number_format($row['subsidios'], 2, ".", ",")), 0, 1, 'R', 0);

    //TOTAL DE PERCEPCIONES
    $pdf->Cell(43, 5, utf8_decode(""), 0, 1, 'C', 0);
    $pdf->Cell(150, 10, utf8_decode('TOTAL DE PERCEPCIONES'), 0, 0, 'C', 0);
    $pdf->Cell(54, 10, utf8_decode("$" . number_format($row['totper'], 2, ".", ",")), 0, 1, 'R', 0);

    //ENCABEZADOS DEDUCCIONES
    $pdf->Cell(43, 5, utf8_decode(""), 0, 1, 'C', 0);
    $pdf->Cell(150, 10, utf8_decode('DEDUCCIONES'), 0, 0, 'C', 0);
    $pdf->Cell(43, 8, utf8_decode(""), 0, 1, 'C', 0);
    $pdf->Cell(100, 16, utf8_decode('Clave'), 0, 0, 'C', 0);
    $pdf->Cell(20, 16, utf8_decode('Concepto'), 0, 0, 'C', 0);
    $pdf->Cell(150, 16, utf8_decode('Importe'), 0, 0, 'C', 0);
    $pdf->Cell(43, 10, utf8_decode(''), 0, 1, 'C', 0);
    $pdf->Cell(43, 10, utf8_decode(''), 0, 1, 'C', 0);


    //SERVICIOS DE SALUD
    $pdf->Cell(100, 7, utf8_decode('5540'), 0, 0, 'C', 0);
    $pdf->Cell(22, 7, utf8_decode('SERVICIOS DE SALUD 4.625% '), 0, 0, 'C', 0);
    $pdf->Cell(72, 7, utf8_decode($row['sumasalud']), 0, 0, 'C', 0);
    $pdf->Cell(10, 7, utf8_decode("$" . number_format($row['sersalud'], 2, ".", ",")), 0, 1, 'R', 0);

    //SISTEMA SOLIDARIO DE REPARTO
    $pdf->Cell(100, 7, utf8_decode('5541'), 0, 0, 'C', 0);
    $pdf->Cell(22, 7, utf8_decode('SISTEMA SOLIDARIO DE REPARTO 6.1%'), 0, 0, 'C', 0);
    $pdf->Cell(72, 7, utf8_decode($row['sumareparto']), 0, 0, 'C', 0);
    $pdf->Cell(10, 7, utf8_decode("$" . number_format($row['sissolidario'], 2, ".", ",")), 0, 1, 'R', 0);

    //CAPITALIZACION INDIVIDUAL
    $pdf->Cell(100, 7, utf8_decode('5542'), 0, 0, 'C', 0);
    $pdf->Cell(22, 7, utf8_decode('CAPITALIZACION INDIVIDUAL 1.4%'), 0, 0, 'C', 0);
    $pdf->Cell(72, 7, utf8_decode($row['sumacapita']), 0, 0, 'C', 0);
    $pdf->Cell(10, 7, utf8_decode("$" . number_format($row['siscapita'], 2, ".", ",")), 0, 1, 'R', 0);

    //IMPUESTO SOBRE LA RENTA
    $pdf->Cell(100, 7, utf8_decode('5408'), 0, 0, 'C', 0);
    $pdf->Cell(22, 7, utf8_decode('IMPUESTO SOBRE LA RENTA '), 0, 0, 'C', 0);
    $pdf->Cell(72, 7, utf8_decode($row['sumaisr']), 0, 0, 'C', 0);
    $pdf->Cell(10, 7, utf8_decode("$" . number_format($row['isr'], 2, ".", ",")), 0, 1, 'R', 0);

    $pdf->Cell(43, 5, utf8_decode(""), 0, 1, 'C', 0);

    //TOTAL DEDUCCIONES
    $pdf->Cell(150, 10, utf8_decode('TOTAL DE DEDUCCIONES'), 0, 0, 'C', 0);
    $pdf->Cell(54, 10, utf8_decode("$" . number_format($row['totdeducciones'], 2, ".", ",")), 0, 1, 'R', 0);

    $pdf->Cell(43, 5, utf8_decode(""), 0, 1, 'C', 0);

    //TOTAL NETOS
    $pdf->Cell(150, 10, utf8_decode('TOTAL NETO A PAGAR'), 0, 0, 'C', 0);
    $pdf->Cell(54, 10, utf8_decode("$" . number_format($row['totnetopagar'], 2, ".", ",")), 0, 1, 'R', 0);
    $pdf->Cell(54, 5, utf8_decode(""), 0, 1, 'R', 0);

    //TOTAL SUELDOS EVENTUALES
    $pdf->Cell(150, 10, utf8_decode('TOTAL SUELDOS EVENTUALES'), 0, 0, 'C', 0);
    $pdf->Cell(10, 12, utf8_decode($row['sumaeventuales']), 0, 1, 'C', 0);

    //SERVICIOS DE SALUD
    $pdf->Cell(150, 10, utf8_decode('10.0% SERVICIOS DE SALUD'), 0, 0, 'C', 0);
    $pdf->Cell(54, 6, utf8_decode("$" . number_format($row['sersalud2'], 2, ".", ",")), 0, 1, 'R', 0);

    //FONDO DEL SISTEMA SOLIDARIO D
    $pdf->Cell(150, 10, utf8_decode('7.42% FONDO DEL SISTEMA SOLIDARIO D'), 0, 0, 'C', 0);
    $pdf->Cell(54, 7, utf8_decode("$" . number_format($row['fondosisre'], 2, ".", ",")), 0, 1, 'R', 0);

    //SISTEMA DE CAPITALI. INDIVI
    $pdf->Cell(150, 10, utf8_decode('1.85% SISTEMA DE CAPITALI. INDIVI.'), 0, 0, 'C', 0);
    $pdf->Cell(54, 7, utf8_decode("$" . number_format($row['siscapita2'], 2, ".", ",")), 0, 1, 'R', 0);

    //GASTOS DE ADMINISTRACION
    $pdf->Cell(150, 10, utf8_decode('0.875% GASTOS DE ADMINISTRACION'), 0, 0, 'C', 0);
    $pdf->Cell(54, 7, utf8_decode("$" . number_format($row['gasadmin'], 2, ".", ",")), 0, 1, 'R', 0);

    //PRIMA BASICA
    $pdf->Cell(150, 10, utf8_decode('1.0% PRIMA BASICA'), 0, 0, 'C', 0);
    $pdf->Cell(54, 7, utf8_decode("$" . number_format($row['primbas'], 2, ".", ",")), 0, 1, 'R', 0);

    //PRIMA DE SINIESTRALIDAD
    $pdf->Cell(150, 10, utf8_decode('1.989% PRIMA DE SINIESTRALIDAD'), 0, 0, 'C', 0);
    $pdf->Cell(54, 7, utf8_decode("$" . number_format($row['primsinies'], 2, ".", ",")), 0, 1, 'R', 0);

    //PRIMA RIESGO NO CONTROLADO
    $pdf->Cell(150, 10, utf8_decode('0.104% PRIMA RIESGO NO CONTROLADO'), 0, 0, 'C', 0);
    $pdf->Cell(54, 7, utf8_decode("$" . number_format($row['primriesgo'], 2, ".", ",")), 0, 1, 'R', 0);

    //TOTAL IMPUESTO SOBRE REMUNERACIONES
    $pdf->Cell(150, 10, utf8_decode('3.0% TOTAL IMPUESTO SOBRE REMUNERACIONES'), 0, 0, 'C', 0);
    $pdf->Cell(54, 7, utf8_decode('PENDIENTE'), 0, 1, 'R', 0);
}



//CONSULTA PARA CONTRATOS DE DEPORTE//////////////////////////////////////////////////////////////////////////////////////////////////
$consulta2 = "SELECT 
#Calculo de sueldos eventuales
SUM(CASE WHEN DetNomina.Clave=0202 THEN Importe ELSE 0 END) AS eventuales,
#Cuenta de sueldos eventuales
(SELECT COUNT(*) FROM EmpCont INNER JOIN DetNomina ON EmpCont.CvePersonal = DetNomina.CvePersonal WHERE CveContrato LIKE '%COMEM%' AND DetNomina.CveNomina='$CveNomina' AND DetNomina.Clave=0202) as sumaeventuales,
#Cuenta de subsidios
(SELECT COUNT(*) FROM EmpCont INNER JOIN DetNomina ON EmpCont.CvePersonal = DetNomina.CvePersonal WHERE CveContrato LIKE '%COMEM%' AND DetNomina.CveNomina='$CveNomina' AND DetNomina.Clave=0325) as sumasubsidios,
#Calculo de subsidios al empleo
SUM(CASE WHEN DetNomina.Clave=0325 THEN Importe ELSE 0 END) AS subsidios,
#Calculo de Total de percepciones
SUM(CASE WHEN DetNomina.Clave IN (0325,0202) THEN Importe ELSE 0 END) AS totper,
#Cuenta de SERVICIOS DE SALUD
(SELECT COUNT(*) FROM EmpCont INNER JOIN DetNomina ON EmpCont.CvePersonal = DetNomina.CvePersonal WHERE CveContrato LIKE '%COMEM%' AND DetNomina.CveNomina='$CveNomina' AND DetNomina.Clave=5540) as sumasalud,
#Calculo de Total de SERVICIOS DE SALUD
SUM(CASE WHEN DetNomina.Clave=5540 THEN Importe ELSE 0 END) AS sersalud,
#Cuenta de SISTEMA SOLIDARIO DE REPARTO
(SELECT COUNT(*) FROM EmpCont INNER JOIN DetNomina ON EmpCont.CvePersonal = DetNomina.CvePersonal WHERE CveContrato LIKE '%COMEM%' AND DetNomina.CveNomina='$CveNomina' AND DetNomina.Clave=5541) as sumareparto,
#Calculo de Total de SISTEMA SOLIDARIO DE REPARTO
SUM(CASE WHEN DetNomina.Clave=5541 THEN Importe ELSE 0 END) AS sissolidario,
#Cuenta de APITALIZACION INDIVIDUAL 
(SELECT COUNT(*) FROM EmpCont INNER JOIN DetNomina ON EmpCont.CvePersonal = DetNomina.CvePersonal WHERE CveContrato LIKE '%COMEM%' AND DetNomina.CveNomina='$CveNomina' AND DetNomina.Clave=5542) as sumacapita,
#Calculo de Total de CAPITALIZACION INDIVIDUAL
SUM(CASE WHEN DetNomina.Clave=5542 THEN Importe ELSE 0 END) AS siscapita,
#Cuenta de IMPUESTO SOBRE LA RENTA
(SELECT COUNT(*) FROM EmpCont INNER JOIN DetNomina ON EmpCont.CvePersonal = DetNomina.CvePersonal WHERE CveContrato LIKE '%COMEM%' AND DetNomina.CveNomina='$CveNomina' AND DetNomina.Clave=5408) as sumaisr,
#Calculo de Total de IMPUESTO SOBRE LA RENTA
SUM(CASE WHEN DetNomina.Clave=5408 THEN Importe ELSE 0 END) AS isr,
#Calculo de Total de TOTAL DE DEDUCCIONES
SUM(CASE WHEN DetNomina.Clave IN (5540,5541,5542,5408) THEN Importe ELSE 0 END) AS totdeducciones,
#Calculo de Total de TOTAL NETO A PAGAR
SUM(CASE WHEN DetNomina.Clave IN (0202,0325) THEN Importe ELSE 0 END) - SUM(CASE WHEN DetNomina.Clave IN (5540,5541,5542,5408) THEN Importe ELSE 0 END)  AS totnetopagar,
#10.0% SERVICIOS DE SALUD  
SUM(CASE WHEN DetNomina.Clave = 5640 THEN Importe ELSE 0 END) AS sersalud2,
#7.42% FONDO DEL SISTEMA SOLIDARIO D 
SUM(CASE WHEN DetNomina.Clave = 5641 THEN Importe ELSE 0 END) AS fondosisre,
#1.85% SISTEMA DE CAPITALI. INDIVI.
SUM(CASE WHEN DetNomina.Clave = 5642 THEN Importe ELSE 0 END) AS siscapita2,
#0.875% GASTOS DE ADMINISTRACION
SUM(CASE WHEN DetNomina.Clave = 5643 THEN Importe ELSE 0 END) AS gasadmin,
#1.0% PRIMA BASICA
SUM(CASE WHEN DetNomina.Clave = 5644 THEN Importe ELSE 0 END) AS primbas,
#1.989% PRIMA DE SINIESTRALIDAD
SUM(CASE WHEN DetNomina.Clave = 5645 THEN Importe ELSE 0 END) AS primsinies,
#0.104% PRIMA RIESGO NO CONTROLADO
SUM(CASE WHEN DetNomina.Clave = 5647 THEN Importe ELSE 0 END) AS primriesgo,
#3.0% TOTAL IMPUESTO SOBRE REMUNERACIONES
SUM(CASE WHEN DetNomina.Clave = 5641 THEN Importe ELSE 0 END) AS impsoremun
FROM EmpCont 
INNER JOIN
DetNomina ON EmpCont.CvePersonal = DetNomina.CvePersonal 
WHERE CveContrato LIKE '%COMEM%' AND DetNomina.CveNomina='$CveNomina'";
//EFECTUANDO CONSULTA
$resultado2 = $mysqli->query($consulta2);





//IMPRESION EN EL PDF DE CONTRATOS DE CONSERVATORIO DE MUSICA//////////////////////////////////////////////////////////////////////////////////////
//Agregando pagina para que inicie desde en encabezado
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Helvetica', '', 12);
$pdf->Cell(250, 10, utf8_decode('COORDINACION: 22600003L          DIREC GRAL CONSERVATORIO DE MUSICA DEL EDOMEX'), 10, 100, 'C', 0);

//Indicar salida del archivo pdf
$pdf->Cell(43, 10, utf8_decode(""), 0, 1, 'C', 0);
$pdf->Cell(150, 15, utf8_decode('PERCEPCIONES'), 0, 0, 'C', 0);
$pdf->Cell(43, 10, utf8_decode(""), 0, 1, 'C', 0);
$pdf->Cell(100, 16, utf8_decode('Clave'), 0, 0, 'C', 0);
$pdf->Cell(20, 16, utf8_decode('Concepto'), 0, 0, 'C', 0);
$pdf->Cell(150, 16, utf8_decode('Importe'), 0, 0, 'C', 0);
$pdf->Cell(43, 10, utf8_decode(''), 0, 1, 'C', 0);
$pdf->Cell(43, 10, utf8_decode(''), 0, 1, 'C', 0);
$pdf->SetFont('Helvetica', '', 12);


//$pdf->Cell(100,10, utf8_decode($row['Descripcion']), 0, 0, 'C', 0);

while ($row = $resultado2->fetch_assoc()) {
    //SUEDOS EVENTUALES
    $pdf->Cell(100, 7, utf8_decode('0202'), 0, 0, 'C', 0);
    $pdf->Cell(22, 7, utf8_decode('SUELDOS EVENTUALES'), 0, 0, 'C', 0);
    $pdf->Cell(72, 7, utf8_decode($row['sumaeventuales']), 0, 0, 'C', 0);
    $pdf->Cell(10, 7, utf8_decode("$" . number_format($row['eventuales'], 2, ".", ",")), 0, 1, 'R', 0);

    //SUBSIDIO AL EMPLEO
    $pdf->Cell(100, 7, utf8_decode('0325'), 0, 0, 'C', 0);
    $pdf->Cell(22, 7, utf8_decode('SUBSIDIO AL EMPLEO'), 0, 0, 'C', 0);
    $pdf->Cell(72, 7, utf8_decode($row['sumasubsidios']), 0, 0, 'C', 0);
    $pdf->Cell(10, 7, utf8_decode("$" . number_format($row['subsidios'], 2, ".", ",")), 0, 1, 'R', 0);

    //TOTAL DE PERCEPCIONES
    $pdf->Cell(43, 5, utf8_decode(""), 0, 1, 'C', 0);
    $pdf->Cell(150, 10, utf8_decode('TOTAL DE PERCEPCIONES'), 0, 0, 'C', 0);
    $pdf->Cell(54, 10, utf8_decode("$" . number_format($row['totper'], 2, ".", ",")), 0, 1, 'R', 0);

    //ENCABEZADOS DEDUCCIONES
    $pdf->Cell(43, 5, utf8_decode(""), 0, 1, 'C', 0);
    $pdf->Cell(150, 10, utf8_decode('DEDUCCIONES'), 0, 0, 'C', 0);
    $pdf->Cell(43, 8, utf8_decode(""), 0, 1, 'C', 0);
    $pdf->Cell(100, 16, utf8_decode('Clave'), 0, 0, 'C', 0);
    $pdf->Cell(20, 16, utf8_decode('Concepto'), 0, 0, 'C', 0);
    $pdf->Cell(150, 16, utf8_decode('Importe'), 0, 0, 'C', 0);
    $pdf->Cell(43, 10, utf8_decode(''), 0, 1, 'C', 0);
    $pdf->Cell(43, 10, utf8_decode(''), 0, 1, 'C', 0);


    //SERVICIOS DE SALUD
    $pdf->Cell(100, 7, utf8_decode('5540'), 0, 0, 'C', 0);
    $pdf->Cell(22, 7, utf8_decode('SERVICIOS DE SALUD 4.625% '), 0, 0, 'C', 0);
    $pdf->Cell(72, 7, utf8_decode($row['sumasalud']), 0, 0, 'C', 0);
    $pdf->Cell(10, 7, utf8_decode("$" . number_format($row['sersalud'], 2, ".", ",")), 0, 1, 'R', 0);

    //SISTEMA SOLIDARIO DE REPARTO
    $pdf->Cell(100, 7, utf8_decode('5541'), 0, 0, 'C', 0);
    $pdf->Cell(22, 7, utf8_decode('SISTEMA SOLIDARIO DE REPARTO 6.1%'), 0, 0, 'C', 0);
    $pdf->Cell(72, 7, utf8_decode($row['sumareparto']), 0, 0, 'C', 0);
    $pdf->Cell(10, 7, utf8_decode("$" . number_format($row['sissolidario'], 2, ".", ",")), 0, 1, 'R', 0);

    //CAPITALIZACION INDIVIDUAL
    $pdf->Cell(100, 7, utf8_decode('5542'), 0, 0, 'C', 0);
    $pdf->Cell(22, 7, utf8_decode('CAPITALIZACION INDIVIDUAL 1.4%'), 0, 0, 'C', 0);
    $pdf->Cell(72, 7, utf8_decode($row['sumacapita']), 0, 0, 'C', 0);
    $pdf->Cell(10, 7, utf8_decode("$" . number_format($row['siscapita'], 2, ".", ",")), 0, 1, 'R', 0);

    //IMPUESTO SOBRE LA RENTA
    $pdf->Cell(100, 7, utf8_decode('5408'), 0, 0, 'C', 0);
    $pdf->Cell(22, 7, utf8_decode('IMPUESTO SOBRE LA RENTA '), 0, 0, 'C', 0);
    $pdf->Cell(72, 7, utf8_decode($row['sumaisr']), 0, 0, 'C', 0);
    $pdf->Cell(10, 7, utf8_decode("$" . number_format($row['isr'], 2, ".", ",")), 0, 1, 'R', 0);

    $pdf->Cell(43, 5, utf8_decode(""), 0, 1, 'C', 0);

    //TOTAL DEDUCCIONES
    $pdf->Cell(150, 10, utf8_decode('TOTAL DE DEDUCCIONES'), 0, 0, 'C', 0);
    $pdf->Cell(54, 10, utf8_decode("$" . number_format($row['totdeducciones'], 2, ".", ",")), 0, 1, 'R', 0);

    $pdf->Cell(43, 5, utf8_decode(""), 0, 1, 'C', 0);

    //TOTAL NETOS
    $pdf->Cell(150, 10, utf8_decode('TOTAL NETO A PAGAR'), 0, 0, 'C', 0);
    $pdf->Cell(54, 10, utf8_decode("$" . number_format($row['totnetopagar'], 2, ".", ",")), 0, 1, 'R', 0);
    $pdf->Cell(54, 5, utf8_decode(""), 0, 1, 'R', 0);

    //TOTAL SUELDOS EVENTUALES
    $pdf->Cell(150, 10, utf8_decode('TOTAL SUELDOS EVENTUALES'), 0, 0, 'C', 0);
    $pdf->Cell(10, 12, utf8_decode($row['sumaeventuales']), 0, 1, 'C', 0);

    //SERVICIOS DE SALUD
    $pdf->Cell(150, 10, utf8_decode('10.0% SERVICIOS DE SALUD'), 0, 0, 'C', 0);
    $pdf->Cell(54, 6, utf8_decode("$" . number_format($row['sersalud2'], 2, ".", ",")), 0, 1, 'R', 0);

    //FONDO DEL SISTEMA SOLIDARIO D
    $pdf->Cell(150, 10, utf8_decode('7.42% FONDO DEL SISTEMA SOLIDARIO D'), 0, 0, 'C', 0);
    $pdf->Cell(54, 7, utf8_decode("$" . number_format($row['fondosisre'], 2, ".", ",")), 0, 1, 'R', 0);

    //SISTEMA DE CAPITALI. INDIVI
    $pdf->Cell(150, 10, utf8_decode('1.85% SISTEMA DE CAPITALI. INDIVI.'), 0, 0, 'C', 0);
    $pdf->Cell(54, 7, utf8_decode("$" . number_format($row['siscapita2'], 2, ".", ",")), 0, 1, 'R', 0);

    //GASTOS DE ADMINISTRACION
    $pdf->Cell(150, 10, utf8_decode('0.875% GASTOS DE ADMINISTRACION'), 0, 0, 'C', 0);
    $pdf->Cell(54, 7, utf8_decode("$" . number_format($row['gasadmin'], 2, ".", ",")), 0, 1, 'R', 0);

    //PRIMA BASICA
    $pdf->Cell(150, 10, utf8_decode('1.0% PRIMA BASICA'), 0, 0, 'C', 0);
    $pdf->Cell(54, 7, utf8_decode("$" . number_format($row['primbas'], 2, ".", ",")), 0, 1, 'R', 0);

    //PRIMA DE SINIESTRALIDAD
    $pdf->Cell(150, 10, utf8_decode('1.989% PRIMA DE SINIESTRALIDAD'), 0, 0, 'C', 0);
    $pdf->Cell(54, 7, utf8_decode("$" . number_format($row['primsinies'], 2, ".", ",")), 0, 1, 'R', 0);

    //PRIMA RIESGO NO CONTROLADO
    $pdf->Cell(150, 10, utf8_decode('0.104% PRIMA RIESGO NO CONTROLADO'), 0, 0, 'C', 0);
    $pdf->Cell(54, 7, utf8_decode("$" . number_format($row['primriesgo'], 2, ".", ",")), 0, 1, 'R', 0);

    //TOTAL IMPUESTO SOBRE REMUNERACIONES
    $pdf->Cell(150, 10, utf8_decode('3.0% TOTAL IMPUESTO SOBRE REMUNERACIONES'), 0, 0, 'C', 0);
    $pdf->Cell(54, 7, utf8_decode('PENDIENTE'), 0, 1, 'R', 0);
}





//CONSULTA PARA CONTRATOS DE PATRIMONIO//////////////////////////////////////////////////////////////////////////////////////////////////
$consulta3 = "SELECT 
#Calculo de sueldos eventuales
SUM(CASE WHEN DetNomina.Clave=0202 THEN Importe ELSE 0 END) AS eventuales,
#Cuenta de sueldos eventuales
(SELECT COUNT(*) FROM EmpCont INNER JOIN DetNomina ON EmpCont.CvePersonal = DetNomina.CvePersonal WHERE CveContrato LIKE '%PATRI%' AND DetNomina.CveNomina='$CveNomina' AND DetNomina.Clave=0202) as sumaeventuales,
#Cuenta de subsidios
(SELECT COUNT(*) FROM EmpCont INNER JOIN DetNomina ON EmpCont.CvePersonal = DetNomina.CvePersonal WHERE CveContrato LIKE '%PATRI%' AND DetNomina.CveNomina='$CveNomina' AND DetNomina.Clave=0325) as sumasubsidios,
#Calculo de subsidios al empleo
SUM(CASE WHEN DetNomina.Clave=0325 THEN Importe ELSE 0 END) AS subsidios,
#Calculo de Total de percepciones
SUM(CASE WHEN DetNomina.Clave IN (0325,0202) THEN Importe ELSE 0 END) AS totper,
#Cuenta de SERVICIOS DE SALUD
(SELECT COUNT(*) FROM EmpCont INNER JOIN DetNomina ON EmpCont.CvePersonal = DetNomina.CvePersonal WHERE CveContrato LIKE '%PATRI%' AND DetNomina.CveNomina='$CveNomina' AND DetNomina.Clave=5540) as sumasalud,
#Calculo de Total de SERVICIOS DE SALUD
SUM(CASE WHEN DetNomina.Clave=5540 THEN Importe ELSE 0 END) AS sersalud,
#Cuenta de SISTEMA SOLIDARIO DE REPARTO
(SELECT COUNT(*) FROM EmpCont INNER JOIN DetNomina ON EmpCont.CvePersonal = DetNomina.CvePersonal WHERE CveContrato LIKE '%PATRI%' AND DetNomina.CveNomina='$CveNomina' AND DetNomina.Clave=5541) as sumareparto,
#Calculo de Total de SISTEMA SOLIDARIO DE REPARTO
SUM(CASE WHEN DetNomina.Clave=5541 THEN Importe ELSE 0 END) AS sissolidario,
#Cuenta de APITALIZACION INDIVIDUAL 
(SELECT COUNT(*) FROM EmpCont INNER JOIN DetNomina ON EmpCont.CvePersonal = DetNomina.CvePersonal WHERE CveContrato LIKE '%PATRI%' AND DetNomina.CveNomina='$CveNomina' AND DetNomina.Clave=5542) as sumacapita,
#Calculo de Total de CAPITALIZACION INDIVIDUAL
SUM(CASE WHEN DetNomina.Clave=5542 THEN Importe ELSE 0 END) AS siscapita,
#Cuenta de IMPUESTO SOBRE LA RENTA
(SELECT COUNT(*) FROM EmpCont INNER JOIN DetNomina ON EmpCont.CvePersonal = DetNomina.CvePersonal WHERE CveContrato LIKE '%PATRI%' AND DetNomina.CveNomina='$CveNomina' AND DetNomina.Clave=5408) as sumaisr,
#Calculo de Total de IMPUESTO SOBRE LA RENTA
SUM(CASE WHEN DetNomina.Clave=5408 THEN Importe ELSE 0 END) AS isr,
#Calculo de Total de TOTAL DE DEDUCCIONES
SUM(CASE WHEN DetNomina.Clave IN (5540,5541,5542,5408) THEN Importe ELSE 0 END) AS totdeducciones,
#Calculo de Total de TOTAL NETO A PAGAR
SUM(CASE WHEN DetNomina.Clave IN (0202,0325) THEN Importe ELSE 0 END) - SUM(CASE WHEN DetNomina.Clave IN (5540,5541,5542,5408) THEN Importe ELSE 0 END)  AS totnetopagar,
#10.0% SERVICIOS DE SALUD  
SUM(CASE WHEN DetNomina.Clave = 5640 THEN Importe ELSE 0 END) AS sersalud2,
#7.42% FONDO DEL SISTEMA SOLIDARIO D 
SUM(CASE WHEN DetNomina.Clave = 5641 THEN Importe ELSE 0 END) AS fondosisre,
#1.85% SISTEMA DE CAPITALI. INDIVI.
SUM(CASE WHEN DetNomina.Clave = 5642 THEN Importe ELSE 0 END) AS siscapita2,
#0.875% GASTOS DE ADMINISTRACION
SUM(CASE WHEN DetNomina.Clave = 5643 THEN Importe ELSE 0 END) AS gasadmin,
#1.0% PRIMA BASICA
SUM(CASE WHEN DetNomina.Clave = 5644 THEN Importe ELSE 0 END) AS primbas,
#1.989% PRIMA DE SINIESTRALIDAD
SUM(CASE WHEN DetNomina.Clave = 5645 THEN Importe ELSE 0 END) AS primsinies,
#0.104% PRIMA RIESGO NO CONTROLADO
SUM(CASE WHEN DetNomina.Clave = 5647 THEN Importe ELSE 0 END) AS primriesgo,
#3.0% TOTAL IMPUESTO SOBRE REMUNERACIONES
SUM(CASE WHEN DetNomina.Clave = 5641 THEN Importe ELSE 0 END) AS impsoremun
FROM EmpCont 
INNER JOIN
DetNomina ON EmpCont.CvePersonal = DetNomina.CvePersonal 
WHERE CveContrato LIKE '%PATRI%' AND DetNomina.CveNomina='$CveNomina'";
//EFECTUANDO CONSULTA
$resultado3 = $mysqli->query($consulta3);

//IMPRESION EN EL PDF DE CONTRATOS DE PATRIMOMNIO//////////////////////////////////////////////////////////////////////////////////////
//Agregando pagina para que inicie desde en encabezado
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Helvetica', '', 12);
$pdf->Cell(250, 10, utf8_decode('COORDINACION: 22600002L          DIR GRAL DE PATRIMONIO Y SERVICIOS CULTURALES'), 10, 100, 'C', 0);

//Indicar salida del archivo pdf
$pdf->Cell(43, 10, utf8_decode(""), 0, 1, 'C', 0);
$pdf->Cell(150, 15, utf8_decode('PERCEPCIONES'), 0, 0, 'C', 0);
$pdf->Cell(43, 10, utf8_decode(""), 0, 1, 'C', 0);
$pdf->Cell(100, 16, utf8_decode('Clave'), 0, 0, 'C', 0);
$pdf->Cell(20, 16, utf8_decode('Concepto'), 0, 0, 'C', 0);
$pdf->Cell(150, 16, utf8_decode('Importe'), 0, 0, 'C', 0);
$pdf->Cell(43, 10, utf8_decode(''), 0, 1, 'C', 0);
$pdf->Cell(43, 10, utf8_decode(''), 0, 1, 'C', 0);
$pdf->SetFont('Helvetica', '', 12);


//$pdf->Cell(100,10, utf8_decode($row['Descripcion']), 0, 0, 'C', 0);

while ($row = $resultado3->fetch_assoc()) {
    //SUEDOS EVENTUALES
    $pdf->Cell(100, 7, utf8_decode('0202'), 0, 0, 'C', 0);
    $pdf->Cell(22, 7, utf8_decode('SUELDOS EVENTUALES'), 0, 0, 'C', 0);
    $pdf->Cell(72, 7, utf8_decode($row['sumaeventuales']), 0, 0, 'C', 0);
    $pdf->Cell(10, 7, utf8_decode("$" . number_format($row['eventuales'], 2, ".", ",")), 0, 1, 'R', 0);

    //SUBSIDIO AL EMPLEO
    $pdf->Cell(100, 7, utf8_decode('0325'), 0, 0, 'C', 0);
    $pdf->Cell(22, 7, utf8_decode('SUBSIDIO AL EMPLEO'), 0, 0, 'C', 0);
    $pdf->Cell(72, 7, utf8_decode($row['sumasubsidios']), 0, 0, 'C', 0);
    $pdf->Cell(10, 7, utf8_decode("$" . number_format($row['subsidios'], 2, ".", ",")), 0, 1, 'R', 0);

    //TOTAL DE PERCEPCIONES
    $pdf->Cell(43, 5, utf8_decode(""), 0, 1, 'C', 0);
    $pdf->Cell(150, 10, utf8_decode('TOTAL DE PERCEPCIONES'), 0, 0, 'C', 0);
    $pdf->Cell(54, 10, utf8_decode("$" . number_format($row['totper'], 2, ".", ",")), 0, 1, 'R', 0);

    //ENCABEZADOS DEDUCCIONES
    $pdf->Cell(43, 5, utf8_decode(""), 0, 1, 'C', 0);
    $pdf->Cell(150, 10, utf8_decode('DEDUCCIONES'), 0, 0, 'C', 0);
    $pdf->Cell(43, 8, utf8_decode(""), 0, 1, 'C', 0);
    $pdf->Cell(100, 16, utf8_decode('Clave'), 0, 0, 'C', 0);
    $pdf->Cell(20, 16, utf8_decode('Concepto'), 0, 0, 'C', 0);
    $pdf->Cell(150, 16, utf8_decode('Importe'), 0, 0, 'C', 0);
    $pdf->Cell(43, 10, utf8_decode(''), 0, 1, 'C', 0);
    $pdf->Cell(43, 10, utf8_decode(''), 0, 1, 'C', 0);


    //SERVICIOS DE SALUD
    $pdf->Cell(100, 7, utf8_decode('5540'), 0, 0, 'C', 0);
    $pdf->Cell(22, 7, utf8_decode('SERVICIOS DE SALUD 4.625% '), 0, 0, 'C', 0);
    $pdf->Cell(72, 7, utf8_decode($row['sumasalud']), 0, 0, 'C', 0);
    $pdf->Cell(10, 7, utf8_decode("$" . number_format($row['sersalud'], 2, ".", ",")), 0, 1, 'R', 0);

    //SISTEMA SOLIDARIO DE REPARTO
    $pdf->Cell(100, 7, utf8_decode('5541'), 0, 0, 'C', 0);
    $pdf->Cell(22, 7, utf8_decode('SISTEMA SOLIDARIO DE REPARTO 6.1%'), 0, 0, 'C', 0);
    $pdf->Cell(72, 7, utf8_decode($row['sumareparto']), 0, 0, 'C', 0);
    $pdf->Cell(10, 7, utf8_decode("$" . number_format($row['sissolidario'], 2, ".", ",")), 0, 1, 'R', 0);

    //CAPITALIZACION INDIVIDUAL
    $pdf->Cell(100, 7, utf8_decode('5542'), 0, 0, 'C', 0);
    $pdf->Cell(22, 7, utf8_decode('CAPITALIZACION INDIVIDUAL 1.4%'), 0, 0, 'C', 0);
    $pdf->Cell(72, 7, utf8_decode($row['sumacapita']), 0, 0, 'C', 0);
    $pdf->Cell(10, 7, utf8_decode("$" . number_format($row['siscapita'], 2, ".", ",")), 0, 1, 'R', 0);

    //IMPUESTO SOBRE LA RENTA
    $pdf->Cell(100, 7, utf8_decode('5408'), 0, 0, 'C', 0);
    $pdf->Cell(22, 7, utf8_decode('IMPUESTO SOBRE LA RENTA '), 0, 0, 'C', 0);
    $pdf->Cell(72, 7, utf8_decode($row['sumaisr']), 0, 0, 'C', 0);
    $pdf->Cell(10, 7, utf8_decode("$" . number_format($row['isr'], 2, ".", ",")), 0, 1, 'R', 0);

    $pdf->Cell(43, 5, utf8_decode(""), 0, 1, 'C', 0);

    //TOTAL DEDUCCIONES
    $pdf->Cell(150, 10, utf8_decode('TOTAL DE DEDUCCIONES'), 0, 0, 'C', 0);
    $pdf->Cell(54, 10, utf8_decode("$" . number_format($row['totdeducciones'], 2, ".", ",")), 0, 1, 'R', 0);

    $pdf->Cell(43, 5, utf8_decode(""), 0, 1, 'C', 0);

    //TOTAL NETOS
    $pdf->Cell(150, 10, utf8_decode('TOTAL NETO A PAGAR'), 0, 0, 'C', 0);
    $pdf->Cell(54, 10, utf8_decode("$" . number_format($row['totnetopagar'], 2, ".", ",")), 0, 1, 'R', 0);
    $pdf->Cell(54, 5, utf8_decode(""), 0, 1, 'R', 0);

    //TOTAL SUELDOS EVENTUALES
    $pdf->Cell(150, 10, utf8_decode('TOTAL SUELDOS EVENTUALES'), 0, 0, 'C', 0);
    $pdf->Cell(10, 12, utf8_decode($row['sumaeventuales']), 0, 1, 'C', 0);

    //SERVICIOS DE SALUD
    $pdf->Cell(150, 10, utf8_decode('10.0% SERVICIOS DE SALUD'), 0, 0, 'C', 0);
    $pdf->Cell(54, 6, utf8_decode("$" . number_format($row['sersalud2'], 2, ".", ",")), 0, 1, 'R', 0);

    //FONDO DEL SISTEMA SOLIDARIO D
    $pdf->Cell(150, 10, utf8_decode('7.42% FONDO DEL SISTEMA SOLIDARIO D'), 0, 0, 'C', 0);
    $pdf->Cell(54, 7, utf8_decode("$" . number_format($row['fondosisre'], 2, ".", ",")), 0, 1, 'R', 0);

    //SISTEMA DE CAPITALI. INDIVI
    $pdf->Cell(150, 10, utf8_decode('1.85% SISTEMA DE CAPITALI. INDIVI.'), 0, 0, 'C', 0);
    $pdf->Cell(54, 7, utf8_decode("$" . number_format($row['siscapita2'], 2, ".", ",")), 0, 1, 'R', 0);

    //GASTOS DE ADMINISTRACION
    $pdf->Cell(150, 10, utf8_decode('0.875% GASTOS DE ADMINISTRACION'), 0, 0, 'C', 0);
    $pdf->Cell(54, 7, utf8_decode("$" . number_format($row['gasadmin'], 2, ".", ",")), 0, 1, 'R', 0);

    //PRIMA BASICA
    $pdf->Cell(150, 10, utf8_decode('1.0% PRIMA BASICA'), 0, 0, 'C', 0);
    $pdf->Cell(54, 7, utf8_decode("$" . number_format($row['primbas'], 2, ".", ",")), 0, 1, 'R', 0);

    //PRIMA DE SINIESTRALIDAD
    $pdf->Cell(150, 10, utf8_decode('1.989% PRIMA DE SINIESTRALIDAD'), 0, 0, 'C', 0);
    $pdf->Cell(54, 7, utf8_decode("$" . number_format($row['primsinies'], 2, ".", ",")), 0, 1, 'R', 0);

    //PRIMA RIESGO NO CONTROLADO
    $pdf->Cell(150, 10, utf8_decode('0.104% PRIMA RIESGO NO CONTROLADO'), 0, 0, 'C', 0);
    $pdf->Cell(54, 7, utf8_decode("$" . number_format($row['primriesgo'], 2, ".", ",")), 0, 1, 'R', 0);

    //TOTAL IMPUESTO SOBRE REMUNERACIONES
    $pdf->Cell(150, 10, utf8_decode('3.0% TOTAL IMPUESTO SOBRE REMUNERACIONES'), 0, 0, 'C', 0);
    $pdf->Cell(54, 7, utf8_decode('PENDIENTE'), 0, 1, 'R', 0);
}










//CONSULTA PARA TOTALES FINALES//////////////////////////////////////////////////////////////////////////////////////////////////
$consulta4 = "SELECT 
#Calculo de sueldos eventuales
SUM(CASE WHEN DetNomina.Clave=0202 THEN Importe ELSE 0 END) AS eventuales,
#Cuenta de sueldos eventuales
(SELECT COUNT(*) FROM EmpCont INNER JOIN DetNomina ON EmpCont.CvePersonal = DetNomina.CvePersonal WHERE DetNomina.CveNomina='$CveNomina' AND DetNomina.Clave=0202) as sumaeventuales,
#Cuenta de subsidios
(SELECT COUNT(*) FROM EmpCont INNER JOIN DetNomina ON EmpCont.CvePersonal = DetNomina.CvePersonal WHERE DetNomina.CveNomina='$CveNomina' AND DetNomina.Clave=0325) as sumasubsidios,
#Calculo de subsidios al empleo
SUM(CASE WHEN DetNomina.Clave=0325 THEN Importe ELSE 0 END) AS subsidios,
#Calculo de Total de percepciones
SUM(CASE WHEN DetNomina.Clave IN (0325,0202) THEN Importe ELSE 0 END) AS totper,
#Cuenta de SERVICIOS DE SALUD
(SELECT COUNT(*) FROM EmpCont INNER JOIN DetNomina ON EmpCont.CvePersonal = DetNomina.CvePersonal WHERE DetNomina.CveNomina='$CveNomina' AND DetNomina.Clave=5540) as sumasalud,
#Calculo de Total de SERVICIOS DE SALUD
SUM(CASE WHEN DetNomina.Clave=5540 THEN Importe ELSE 0 END) AS sersalud,
#Cuenta de SISTEMA SOLIDARIO DE REPARTO
(SELECT COUNT(*) FROM EmpCont INNER JOIN DetNomina ON EmpCont.CvePersonal = DetNomina.CvePersonal WHERE DetNomina.CveNomina='$CveNomina' AND DetNomina.Clave=5541) as sumareparto,
#Calculo de Total de SISTEMA SOLIDARIO DE REPARTO
SUM(CASE WHEN DetNomina.Clave=5541 THEN Importe ELSE 0 END) AS sissolidario,
#Cuenta de APITALIZACION INDIVIDUAL 
(SELECT COUNT(*) FROM EmpCont INNER JOIN DetNomina ON EmpCont.CvePersonal = DetNomina.CvePersonal WHERE DetNomina.CveNomina='$CveNomina' AND DetNomina.Clave=5542) as sumacapita,
#Calculo de Total de CAPITALIZACION INDIVIDUAL
SUM(CASE WHEN DetNomina.Clave=5542 THEN Importe ELSE 0 END) AS siscapita,
#Cuenta de IMPUESTO SOBRE LA RENTA
(SELECT COUNT(*) FROM EmpCont INNER JOIN DetNomina ON EmpCont.CvePersonal = DetNomina.CvePersonal WHERE DetNomina.CveNomina='$CveNomina' AND DetNomina.Clave=5408) as sumaisr,
#Calculo de Total de IMPUESTO SOBRE LA RENTA
SUM(CASE WHEN DetNomina.Clave=5408 THEN Importe ELSE 0 END) AS isr,
#Calculo de Total de TOTAL DE DEDUCCIONES
SUM(CASE WHEN DetNomina.Clave IN (5540,5541,5542,5408) THEN Importe ELSE 0 END) AS totdeducciones,
#Calculo de Total de TOTAL NETO A PAGAR
SUM(CASE WHEN DetNomina.Clave IN (0202,0325) THEN Importe ELSE 0 END) - SUM(CASE WHEN DetNomina.Clave IN (5540,5541,5542,5408) THEN Importe ELSE 0 END)  AS totnetopagar,
#10.0% SERVICIOS DE SALUD  
SUM(CASE WHEN DetNomina.Clave = 5640 THEN Importe ELSE 0 END) AS sersalud2,
#7.42% FONDO DEL SISTEMA SOLIDARIO D 
SUM(CASE WHEN DetNomina.Clave = 5641 THEN Importe ELSE 0 END) AS fondosisre,
#1.85% SISTEMA DE CAPITALI. INDIVI.
SUM(CASE WHEN DetNomina.Clave = 5642 THEN Importe ELSE 0 END) AS siscapita2,
#0.875% GASTOS DE ADMINISTRACION
SUM(CASE WHEN DetNomina.Clave = 5643 THEN Importe ELSE 0 END) AS gasadmin,
#1.0% PRIMA BASICA
SUM(CASE WHEN DetNomina.Clave = 5644 THEN Importe ELSE 0 END) AS primbas,
#1.989% PRIMA DE SINIESTRALIDAD
SUM(CASE WHEN DetNomina.Clave = 5645 THEN Importe ELSE 0 END) AS primsinies,
#0.104% PRIMA RIESGO NO CONTROLADO
SUM(CASE WHEN DetNomina.Clave = 5647 THEN Importe ELSE 0 END) AS primriesgo,
#3.0% TOTAL IMPUESTO SOBRE REMUNERACIONES
SUM(CASE WHEN DetNomina.Clave = 5641 THEN Importe ELSE 0 END) AS impsoremun
FROM EmpCont 
INNER JOIN
DetNomina ON EmpCont.CvePersonal = DetNomina.CvePersonal 
WHERE DetNomina.CveNomina='$CveNomina'";
//EFECTUANDO CONSULTA
$resultado4 = $mysqli->query($consulta4);

//IMPRESION EN EL PDF DE TOTALES//////////////////////////////////////////////////////////////////////////////////////
//Agregando pagina para que inicie desde en encabezado
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Helvetica', '', 12);
$pdf->Cell(250, 10, utf8_decode('COORDINACION: 22600000L          SECRETARIA DE CULTURA Y TURISMO'), 10, 100, 'C', 0);

//Indicar salida del archivo pdf
$pdf->Cell(43, 10, utf8_decode(""), 0, 1, 'C', 0);
$pdf->Cell(150, 15, utf8_decode('PERCEPCIONES'), 0, 0, 'C', 0);
$pdf->Cell(43, 10, utf8_decode(""), 0, 1, 'C', 0);
$pdf->Cell(100, 16, utf8_decode('Clave'), 0, 0, 'C', 0);
$pdf->Cell(20, 16, utf8_decode('Concepto'), 0, 0, 'C', 0);
$pdf->Cell(150, 16, utf8_decode('Importe'), 0, 0, 'C', 0);
$pdf->Cell(43, 10, utf8_decode(''), 0, 1, 'C', 0);
$pdf->Cell(43, 10, utf8_decode(''), 0, 1, 'C', 0);
$pdf->SetFont('Helvetica', '', 12);


//$pdf->Cell(100,10, utf8_decode($row['Descripcion']), 0, 0, 'C', 0);

while ($row = $resultado4->fetch_assoc()) {
    //SUEDOS EVENTUALES
    $pdf->Cell(100, 7, utf8_decode('0202'), 0, 0, 'C', 0);
    $pdf->Cell(22, 7, utf8_decode('SUELDOS EVENTUALES'), 0, 0, 'C', 0);
    $pdf->Cell(72, 7, utf8_decode($row['sumaeventuales']), 0, 0, 'C', 0);
    $pdf->Cell(10, 7, utf8_decode("$" . number_format($row['eventuales'], 2, ".", ",")), 0, 1, 'R', 0);

    //SUBSIDIO AL EMPLEO
    $pdf->Cell(100, 7, utf8_decode('0325'), 0, 0, 'C', 0);
    $pdf->Cell(22, 7, utf8_decode('SUBSIDIO AL EMPLEO'), 0, 0, 'C', 0);
    $pdf->Cell(72, 7, utf8_decode($row['sumasubsidios']), 0, 0, 'C', 0);
    $pdf->Cell(10, 7, utf8_decode("$" . number_format($row['subsidios'], 2, ".", ",")), 0, 1, 'R', 0);

    //TOTAL DE PERCEPCIONES
    $pdf->Cell(43, 5, utf8_decode(""), 0, 1, 'C', 0);
    $pdf->Cell(150, 10, utf8_decode('TOTAL DE PERCEPCIONES'), 0, 0, 'C', 0);
    $pdf->Cell(54, 10, utf8_decode("$" . number_format($row['totper'], 2, ".", ",")), 0, 1, 'R', 0);

    //ENCABEZADOS DEDUCCIONES
    $pdf->Cell(43, 5, utf8_decode(""), 0, 1, 'C', 0);
    $pdf->Cell(150, 10, utf8_decode('DEDUCCIONES'), 0, 0, 'C', 0);
    $pdf->Cell(43, 8, utf8_decode(""), 0, 1, 'C', 0);
    $pdf->Cell(100, 16, utf8_decode('Clave'), 0, 0, 'C', 0);
    $pdf->Cell(20, 16, utf8_decode('Concepto'), 0, 0, 'C', 0);
    $pdf->Cell(150, 16, utf8_decode('Importe'), 0, 0, 'C', 0);
    $pdf->Cell(43, 10, utf8_decode(''), 0, 1, 'C', 0);
    $pdf->Cell(43, 10, utf8_decode(''), 0, 1, 'C', 0);


    //SERVICIOS DE SALUD
    $pdf->Cell(100, 7, utf8_decode('5540'), 0, 0, 'C', 0);
    $pdf->Cell(22, 7, utf8_decode('SERVICIOS DE SALUD 4.625% '), 0, 0, 'C', 0);
    $pdf->Cell(72, 7, utf8_decode($row['sumasalud']), 0, 0, 'C', 0);
    $pdf->Cell(10, 7, utf8_decode("$" . number_format($row['sersalud'], 2, ".", ",")), 0, 1, 'R', 0);

    //SISTEMA SOLIDARIO DE REPARTO
    $pdf->Cell(100, 7, utf8_decode('5541'), 0, 0, 'C', 0);
    $pdf->Cell(22, 7, utf8_decode('SISTEMA SOLIDARIO DE REPARTO 6.1%'), 0, 0, 'C', 0);
    $pdf->Cell(72, 7, utf8_decode($row['sumareparto']), 0, 0, 'C', 0);
    $pdf->Cell(10, 7, utf8_decode("$" . number_format($row['sissolidario'], 2, ".", ",")), 0, 1, 'R', 0);

    //CAPITALIZACION INDIVIDUAL
    $pdf->Cell(100, 7, utf8_decode('5542'), 0, 0, 'C', 0);
    $pdf->Cell(22, 7, utf8_decode('CAPITALIZACION INDIVIDUAL 1.4%'), 0, 0, 'C', 0);
    $pdf->Cell(72, 7, utf8_decode($row['sumacapita']), 0, 0, 'C', 0);
    $pdf->Cell(10, 7, utf8_decode("$" . number_format($row['siscapita'], 2, ".", ",")), 0, 1, 'R', 0);

    //IMPUESTO SOBRE LA RENTA
    $pdf->Cell(100, 7, utf8_decode('5408'), 0, 0, 'C', 0);
    $pdf->Cell(22, 7, utf8_decode('IMPUESTO SOBRE LA RENTA '), 0, 0, 'C', 0);
    $pdf->Cell(72, 7, utf8_decode($row['sumaisr']), 0, 0, 'C', 0);
    $pdf->Cell(10, 7, utf8_decode("$" . number_format($row['isr'], 2, ".", ",")), 0, 1, 'R', 0);

    $pdf->Cell(43, 5, utf8_decode(""), 0, 1, 'C', 0);

    //TOTAL DEDUCCIONES
    $pdf->Cell(150, 10, utf8_decode('TOTAL DE DEDUCCIONES'), 0, 0, 'C', 0);
    $pdf->Cell(54, 10, utf8_decode("$" . number_format($row['totdeducciones'], 2, ".", ",")), 0, 1, 'R', 0);

    $pdf->Cell(43, 5, utf8_decode(""), 0, 1, 'C', 0);

    //TOTAL NETOS
    $pdf->Cell(150, 10, utf8_decode('TOTAL NETO A PAGAR'), 0, 0, 'C', 0);
    $pdf->Cell(54, 10, utf8_decode("$" . number_format($row['totnetopagar'], 2, ".", ",")), 0, 1, 'R', 0);
    $pdf->Cell(54, 5, utf8_decode(""), 0, 1, 'R', 0);

    //TOTAL SUELDOS EVENTUALES
    $pdf->Cell(150, 10, utf8_decode('TOTAL SUELDOS EVENTUALES'), 0, 0, 'C', 0);
    $pdf->Cell(10, 12, utf8_decode($row['sumaeventuales']), 0, 1, 'C', 0);

    //SERVICIOS DE SALUD
    $pdf->Cell(150, 10, utf8_decode('10.0% SERVICIOS DE SALUD'), 0, 0, 'C', 0);
    $pdf->Cell(54, 6, utf8_decode("$" . number_format($row['sersalud2'], 2, ".", ",")), 0, 1, 'R', 0);

    //FONDO DEL SISTEMA SOLIDARIO D
    $pdf->Cell(150, 10, utf8_decode('7.42% FONDO DEL SISTEMA SOLIDARIO D'), 0, 0, 'C', 0);
    $pdf->Cell(54, 7, utf8_decode("$" . number_format($row['fondosisre'], 2, ".", ",")), 0, 1, 'R', 0);

    //SISTEMA DE CAPITALI. INDIVI
    $pdf->Cell(150, 10, utf8_decode('1.85% SISTEMA DE CAPITALI. INDIVI.'), 0, 0, 'C', 0);
    $pdf->Cell(54, 7, utf8_decode("$" . number_format($row['siscapita2'], 2, ".", ",")), 0, 1, 'R', 0);

    //GASTOS DE ADMINISTRACION
    $pdf->Cell(150, 10, utf8_decode('0.875% GASTOS DE ADMINISTRACION'), 0, 0, 'C', 0);
    $pdf->Cell(54, 7, utf8_decode("$" . number_format($row['gasadmin'], 2, ".", ",")), 0, 1, 'R', 0);

    //PRIMA BASICA
    $pdf->Cell(150, 10, utf8_decode('1.0% PRIMA BASICA'), 0, 0, 'C', 0);
    $pdf->Cell(54, 7, utf8_decode("$" . number_format($row['primbas'], 2, ".", ",")), 0, 1, 'R', 0);

    //PRIMA DE SINIESTRALIDAD
    $pdf->Cell(150, 10, utf8_decode('1.989% PRIMA DE SINIESTRALIDAD'), 0, 0, 'C', 0);
    $pdf->Cell(54, 7, utf8_decode("$" . number_format($row['primsinies'], 2, ".", ",")), 0, 1, 'R', 0);

    //PRIMA RIESGO NO CONTROLADO
    $pdf->Cell(150, 10, utf8_decode('0.104% PRIMA RIESGO NO CONTROLADO'), 0, 0, 'C', 0);
    $pdf->Cell(54, 7, utf8_decode("$" . number_format($row['primriesgo'], 2, ".", ",")), 0, 1, 'R', 0);

    //TOTAL IMPUESTO SOBRE REMUNERACIONES
    $pdf->Cell(150, 10, utf8_decode('3.0% TOTAL IMPUESTO SOBRE REMUNERACIONES'), 0, 0, 'C', 0);
    $pdf->Cell(54, 7, utf8_decode('PENDIENTE'), 0, 1, 'R', 0);
}




$pdf->Output();