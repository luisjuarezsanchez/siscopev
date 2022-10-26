<?php
//Verificar la sesión iniciada
session_start();
$usuario = $_SESSION['username'];
if (!isset($usuario)) {
    header("location: index.php");
}
//Recibiendo variable por metodo POST del formulario
$CveNomina = $_POST['CveNomina'];
//Variables de control por contrato
$totalEmp = 0;
$totalPercepciones = 0;
$totalDeducciones = 0;
//Variables de control totales de nomina
$a = 0;
$defPercepciones = 0;
$defDeducciones = 0;
//Contadores de empleados
$contadorDeporte = 0;
$contadorComem = 0;
$contadorPatri = 0;


//Solicitando los archivos de FPDF
require('fpdf/fpdf.php');
class PDF extends FPDF
{
    // Cabecera de página
    function Header()
    {
        // Logos
        $this->Image('img/reportes/gob.jpg', 10, 2, 35);
        $this->Image('img/reportes/logo_vertical.png', 240, 2, 25);
        // Arial bold 15
        $this->SetFont('Arial', 'B', 13);
        // Movernos a la derecha
        $this->Cell(80);
        // Título
        $this->Cell(100, 5, utf8_decode(""), 0, 1, 'C', 0);

        $this->Cell(250, 3, utf8_decode('SECRETARÍA DE CULTURA Y TURISMO'), 10, 10, 'C'); //derecha abajo Salto de línea
        //Saltos de linea
        $this->Ln(0);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(250, 14, utf8_decode('RESUMEN DE PERCEPCIONES Y DEDUCCIONES DE LA QUINCENA ' . substr($GLOBALS["CveNomina"], 0, 6)), 10, 10, 'C');
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
/**********************CONSULTA PARA CONTRATOS DE DEPORTE**********************/
$consulta = "SELECT
DetNomina.CvePersonal,EmpGral.RFC,CONCAT(EmpGral.Nombre,' ',EmpGral.Paterno,' ',EmpGral.Materno) AS Nombre,EmpCont.CtaBanco,SUBSTR(catbanco.NomBanco,1,4) AS NomBanco,EmpGral.CURP,EmpCont.Dirgral,EmpCont.HrsMen,EmpGral.CveISSEMyM,EmpCont.UnidadRespon,
EmpCont.CodCategoria,catcatego.Descripcion,catcatego.DescCorta,DetNomina.Del,DetNomina.Al,
#Total de sueldos eventuales
'0202' AS cveeventuales,
SUM(CASE WHEN DetNomina.Clave=0202 THEN Importe ELSE 0 END) AS toteventuales,
#Total de subsidios
'0325' AS cvesubsidios,
SUM(CASE WHEN DetNomina.Clave=0325 THEN Importe ELSE 0 END) AS totsubsidios,
#Total de percepciones
SUM(CASE WHEN DetNomina.Clave IN (0325,0202) THEN Importe ELSE 0 END) AS totpercepciones,
#Total ISR
'5408' AS cveisr,
SUM(CASE WHEN DetNomina.Clave=5408 THEN Importe ELSE 0 END) AS totisr,
#Total Servicios de salud
'5540' AS cvesalud,
SUM(CASE WHEN DetNomina.Clave=5540 THEN Importe ELSE 0 END) AS totsalud,
#Total Sistema solidario de reparto
'5541' AS cvesisrep,
SUM(CASE WHEN DetNomina.Clave=5541 THEN Importe ELSE 0 END) AS totsisrep,
#Total Capitalizacion individual
'5542' AS cvecapita,
SUM(CASE WHEN DetNomina.Clave=5542 THEN Importe ELSE 0 END) AS totcapita,
#Total deducciones
SUM(CASE WHEN DetNomina.Clave IN (5408,5540,5541,5542) THEN Importe ELSE 0 END) AS totdeducciones,
#Sueldo Bruto
SUM(CASE WHEN DetNomina.Clave IN (0325,0202) THEN Importe ELSE 0 END)- SUM(CASE WHEN DetNomina.Clave IN (5408,5540,5541,5542) THEN Importe ELSE 0 END) AS sueldobruto,
#Total de empleados
(SELECT COUNT(*) FROM EmpCont WHERE EmpCont.Dirgral=0) AS totempleados
FROM EmpCont INNER JOIN 
DetNomina ON EmpCont.CvePersonal = DetNomina.CvePersonal INNER JOIN
EmpGral ON EmpCont.CvePersonal = EmpGral.CvePersonal INNER JOIN
catbanco ON SUBSTR(EmpCont.CtaBanco, 1, 3) = catbanco.CveBanco INNER JOIN
catcatego ON EmpCont.CodCategoria = catcatego.CveCategoria
WHERE EmpCont.CveContrato LIKE '%DEPOR%' AND DetNomina.CveNomina='$CveNomina' GROUP BY DetNomina.CvePersonal";
//EFECTUANDO CONSULTA
$resultado = $mysqli->query($consulta);



/**********************IMPRESION EN EL PDF DE CONTRATOS DE DEPORTE*********************/
// Creación del objeto de la clase heredada
$pdf = new PDF('L', 'mm', 'letter'); //Indicando formato horizontal del reporte
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Helvetica', '', 11);

//Indicar salida del archivo pdf y estableciendo tamaño de letra
$pdf->SetFont('Helvetica', '', 8);
while ($row = $resultado->fetch_assoc()) {
    $pdf->Cell(2, 5, utf8_decode($row['CvePersonal']), 0, 0, 'C', 0);
    $pdf->Cell(65, 5, utf8_decode($row['RFC']), 0, 0, 'C', 0);
    $pdf->Cell(80, 5, utf8_decode($row['Nombre']), 0, 0, 'C', 0);
    $pdf->Cell(55, 5, utf8_decode($row['CtaBanco']), 0, 0, 'C', 0);
    $pdf->Cell(1, 5, utf8_decode('(' . $row['NomBanco'] . ')'), 0, 0, 'C', 0);
    $pdf->Cell(74, 5, utf8_decode($row['CURP']), 0, 0, 'C', 0);
    $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    $pdf->Cell(1, 5, utf8_decode($row['Dirgral']), 0, 0, 'C', 0);
    $pdf->Cell(30, 5, utf8_decode($row['CveISSEMyM']), 0, 0, 'C', 0);
    $pdf->Cell(10, 5, utf8_decode($row['UnidadRespon']), 0, 0, 'C', 0);
    $pdf->Cell(90, 5, utf8_decode('DIR GRAL DE CULTURA FISICA Y DEPORTE'), 0, 0, 'C', 0);
    $pdf->Cell(20, 5, utf8_decode($row['CodCategoria']), 0, 0, 'C', 0);
    $pdf->Cell(65, 5, utf8_decode($row['Descripcion']), 0, 0, 'C', 0);
    $pdf->Cell(15, 5, utf8_decode($row['Del']), 0, 0, 'C', 0);
    $pdf->Cell(40, 5, utf8_decode($row['Al']), 0, 0, 'C', 0);
    $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    $pdf->Cell(2, 5, utf8_decode($row['cveeventuales']), 0, 0, 'C', 0);
    $pdf->Cell(55, 5, utf8_decode('SUELDOS EVENTUALES'), 0, 0, 'C', 0);
    $pdf->Cell(1, 5, utf8_decode('(' . $row['HrsMen'] . ')'), 0, 0, 'C', 0);
    $pdf->Cell(1, 5, utf8_decode(' ' . ' ' . ' ' . $row['DescCorta'] . ''), 0, 0, 'L', 0);
    $pdf->Cell(65, 5, utf8_decode("$" . number_format($row['toteventuales'], 2, ".", ",")), 0, 0, 'R', 0);
    $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);

    if ($row['totsubsidios'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvesubsidios']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('SUBSIDIO AL EMPLEO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totsubsidios'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totisr'] > 0) {
        $pdf->Cell(150, 5, utf8_decode($row['cveisr']), 0, 0, 'R', 0);
        $pdf->Cell(55, 5, utf8_decode('IMPUESTO SOBRE LA RENTA'), 0, 0, 'R', 0);
        $pdf->Cell(55, 5, utf8_decode("$" . number_format($row['totisr'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totsalud'] > 0) {
        $pdf->Cell(150, 5, utf8_decode($row['cvesalud']), 0, 0, 'R', 0);
        $pdf->Cell(55, 5, utf8_decode('SERVICIOS DE SALUD 4.625%'), 0, 0, 'R', 0);
        $pdf->Cell(55, 5, utf8_decode("$" . number_format($row['totsalud'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totsisrep'] > 0) {
        $pdf->Cell(150, 5, utf8_decode($row['cvesisrep']), 0, 0, 'R', 0);
        $pdf->Cell(72, 5, utf8_decode('SISTEMA SOLIDARIO DE REPARTO 6.1%'), 0, 0, 'R', 0);
        $pdf->Cell(38, 5, utf8_decode("$" . number_format($row['totsisrep'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totcapita'] > 0) {
        $pdf->Cell(150, 5, utf8_decode($row['cvecapita']), 0, 0, 'R', 0);
        $pdf->Cell(62, 5, utf8_decode('CAPITALIZACION INDIVIDUAL 1.4%'), 0, 0, 'R', 0);
        $pdf->Cell(48, 5, utf8_decode("$" . number_format($row['totcapita'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    $pdf->Cell(122, 5, utf8_decode("$" . number_format($row['totpercepciones'], 2, ".", ",")), 0, 0, 'R', 0);
    $pdf->Cell(138, 5, utf8_decode("$" . number_format($row['totdeducciones'], 2, ".", ",")), 0, 0, 'R', 0);
    $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    $pdf->Cell(260, 5, utf8_decode("$" . number_format($row['sueldobruto'], 2, ".", ",")), 0, 0, 'R', 0);

    $pdf->Cell(1, 6, utf8_decode(''), 0, 1, 'L', 0);
    // $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);

    //Sumas finales de deporte
    $totalEmp = $row['totempleados'];
    $totalPercepciones = $totalPercepciones + $row['totpercepciones'];
    $totalDeducciones = $totalDeducciones + $row['totdeducciones'];
    //Sumas finales de nómina
    $a = $a + 1;
    $defPercepciones = $defPercepciones + $row['totpercepciones'];
    $defDeducciones = $defDeducciones + $row['totdeducciones'];
    $contadorDeporte = $contadorDeporte + 1;
}

//Imprimiendo totales en pantalla
$pdf->Cell(45, 5, utf8_decode("TOTALES DE EMPLEADOS: " . $contadorDeporte), 0, 0, 'R', 0);
$pdf->Cell(90, 5, utf8_decode("$" . number_format($totalPercepciones, 2, ".", ",")), 0, 0, 'R', 0);
$pdf->Cell(120, 5, utf8_decode("$" . number_format($totalDeducciones, 2, ".", ",")), 0, 0, 'R', 0);
$pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
$pdf->Cell(255, 5, utf8_decode("$" . number_format($totalPercepciones - $totalDeducciones, 2, ".", ",")), 0, 0, 'R', 0);
//Limpiando variables acumulativas
$totalEmp = 0;
$totalPercepciones = 0;
$totalDeducciones = 0;
$pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);














/**********************CONSULTA PARA CONTRATOS DE COMEM**********************/
$consulta2 = "SELECT
DetNomina.CvePersonal,EmpGral.RFC,CONCAT(EmpGral.Nombre,' ',EmpGral.Paterno,' ',EmpGral.Materno) AS Nombre,EmpCont.CtaBanco,SUBSTR(catbanco.NomBanco,1,4)AS NomBanco,EmpGral.CURP,EmpCont.Dirgral,EmpGral.CveISSEMyM,EmpCont.UnidadRespon,
EmpCont.CodCategoria,catcatego.Descripcion,catcatego.DescCorta,DetNomina.Del,DetNomina.Al,EmpCont.HrsMen,
COUNT(DetNomina.Clave=0202) AS indicador,
#Total de sueldos eventuales
'0202' AS cveeventuales,
SUM(CASE WHEN DetNomina.Clave=0202 THEN Importe ELSE 0 END) AS toteventuales,
#Total de subsidios
'0325' AS cvesubsidios,
SUM(CASE WHEN DetNomina.Clave=0325 THEN Importe ELSE 0 END) AS totsubsidios,
#Total de percepciones
SUM(CASE WHEN DetNomina.Clave IN (0325,0202) THEN Importe ELSE 0 END) AS totpercepciones,
#Total ISR
'5408' AS cveisr,
SUM(CASE WHEN DetNomina.Clave=5408 THEN Importe ELSE 0 END) AS totisr,
#Total Servicios de salud
'5540' AS cvesalud,
SUM(CASE WHEN DetNomina.Clave=5540 THEN Importe ELSE 0 END) AS totsalud,
#Total Sistema solidario de reparto
'5541' AS cvesisrep,
SUM(CASE WHEN DetNomina.Clave=5541 THEN Importe ELSE 0 END) AS totsisrep,
#Total Capitalizacion individual
'5542' AS cvecapita,
SUM(CASE WHEN DetNomina.Clave=5542 THEN Importe ELSE 0 END) AS totcapita,
#Total deducciones
SUM(CASE WHEN DetNomina.Clave IN (5408,5540,5541,5542) THEN Importe ELSE 0 END) AS totdeducciones,
#Sueldo Bruto
SUM(CASE WHEN DetNomina.Clave IN (0325,0202) THEN Importe ELSE 0 END)- SUM(CASE WHEN DetNomina.Clave IN (5408,5540,5541,5542) THEN Importe ELSE 0 END) AS sueldobruto,
#Total de empleados
(SELECT COUNT(*) FROM EmpCont WHERE EmpCont.Dirgral=1) AS totempleados
FROM EmpCont INNER JOIN 
DetNomina ON EmpCont.CvePersonal = DetNomina.CvePersonal INNER JOIN
EmpGral ON EmpCont.CvePersonal = EmpGral.CvePersonal INNER JOIN
catbanco ON SUBSTR(EmpCont.CtaBanco, 1, 3) = catbanco.CveBanco INNER JOIN
catcatego ON EmpCont.CodCategoria = catcatego.CveCategoria
WHERE EmpCont.CveContrato LIKE '%COMEM%' AND DetNomina.CveNomina='$CveNomina' GROUP BY DetNomina.CvePersonal";
//EFECTUANDO CONSULTA
$resultado2 = $mysqli->query($consulta2);

//Agregando pagina para que inicie desde en encabezado
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Helvetica', '', 11);

//Indicar salida del archivo pdf
$pdf->SetFont('Helvetica', '', 8);
while ($row = $resultado2->fetch_assoc()) {
    $pdf->Cell(2, 5, utf8_decode($row['CvePersonal']), 0, 0, 'C', 0);
    $pdf->Cell(65, 5, utf8_decode($row['RFC']), 0, 0, 'C', 0);
    $pdf->Cell(80, 5, utf8_decode($row['Nombre']), 0, 0, 'C', 0);
    $pdf->Cell(55, 5, utf8_decode($row['CtaBanco']), 0, 0, 'C', 0);
    $pdf->Cell(1, 5, utf8_decode('(' . $row['NomBanco'] . ')'), 0, 0, 'C', 0);
    $pdf->Cell(74, 5, utf8_decode($row['CURP']), 0, 0, 'C', 0);
    $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    $pdf->Cell(1, 5, utf8_decode($row['Dirgral']), 0, 0, 'C', 0);
    $pdf->Cell(30, 5, utf8_decode($row['CveISSEMyM']), 0, 0, 'C', 0);
    $pdf->Cell(10, 5, utf8_decode($row['UnidadRespon']), 0, 0, 'C', 0);
    $pdf->Cell(90, 5, utf8_decode('DIR GRAL DE CULTURA FISICA Y DEPORTE'), 0, 0, 'C', 0);
    $pdf->Cell(20, 5, utf8_decode($row['CodCategoria']), 0, 0, 'C', 0);
    $pdf->Cell(65, 5, utf8_decode($row['Descripcion']), 0, 0, 'C', 0);
    $pdf->Cell(15, 5, utf8_decode($row['Del']), 0, 0, 'C', 0);
    $pdf->Cell(40, 5, utf8_decode($row['Al']), 0, 0, 'C', 0);
    $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    $pdf->Cell(2, 5, utf8_decode($row['cveeventuales']), 0, 0, 'C', 0);
    $pdf->Cell(55, 5, utf8_decode('SUELDOS EVENTUALES'), 0, 0, 'C', 0);
    $pdf->Cell(1, 5, utf8_decode('(' . $row['HrsMen'] . ')'), 0, 0, 'C', 0);
    $pdf->Cell(1, 5, utf8_decode(' ' . ' ' . ' ' . $row['DescCorta'] . ''), 0, 0, 'L', 0);
    $pdf->Cell(65, 5, utf8_decode("$" . number_format($row['toteventuales'], 2, ".", ",")), 0, 0, 'R', 0);
    $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);

    if ($row['totsubsidios'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvesubsidios']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('SUBSIDIO AL EMPLEO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totsubsidios'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totisr'] > 0) {
        $pdf->Cell(150, 5, utf8_decode($row['cveisr']), 0, 0, 'R', 0);
        $pdf->Cell(55, 5, utf8_decode('IMPUESTO SOBRE LA RENTA'), 0, 0, 'R', 0);
        $pdf->Cell(55, 5, utf8_decode("$" . number_format($row['totisr'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totsalud'] > 0) {
        $pdf->Cell(150, 5, utf8_decode($row['cvesalud']), 0, 0, 'R', 0);
        $pdf->Cell(55, 5, utf8_decode('SERVICIOS DE SALUD 4.625%'), 0, 0, 'R', 0);
        $pdf->Cell(55, 5, utf8_decode("$" . number_format($row['totsalud'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totsisrep'] > 0) {
        $pdf->Cell(150, 5, utf8_decode($row['cvesisrep']), 0, 0, 'R', 0);
        $pdf->Cell(72, 5, utf8_decode('SISTEMA SOLIDARIO DE REPARTO 6.1%'), 0, 0, 'R', 0);
        $pdf->Cell(38, 5, utf8_decode("$" . number_format($row['totsisrep'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totcapita'] > 0) {
        $pdf->Cell(150, 5, utf8_decode($row['cvecapita']), 0, 0, 'R', 0);
        $pdf->Cell(62, 5, utf8_decode('CAPITALIZACION INDIVIDUAL 1.4%'), 0, 0, 'R', 0);
        $pdf->Cell(48, 5, utf8_decode("$" . number_format($row['totcapita'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    $pdf->Cell(122, 5, utf8_decode("$" . number_format($row['totpercepciones'], 2, ".", ",")), 0, 0, 'R', 0);
    $pdf->Cell(138, 5, utf8_decode("$" . number_format($row['totdeducciones'], 2, ".", ",")), 0, 0, 'R', 0);
    $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    $pdf->Cell(260, 5, utf8_decode("$" . number_format($row['sueldobruto'], 2, ".", ",")), 0, 0, 'R', 0);

    $pdf->Cell(1, 6, utf8_decode(''), 0, 1, 'L', 0);
    // $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);


    //Declaraciones finales
    $totalEmp = $row['totempleados'];
    $totalPercepciones = $totalPercepciones + $row['totpercepciones'];
    $totalDeducciones = $totalDeducciones + $row['totdeducciones'];
    //Sumas finales de nómina
    $a = $a + 1;
    $defPercepciones = $defPercepciones + $row['totpercepciones'];
    $defDeducciones = $defDeducciones + $row['totdeducciones'];
    $contadorComem = $contadorComem + 1;
}

//Imprimiendo totales en pantalla
$pdf->Cell(45, 5, utf8_decode("TOTALES DE EMPLEADOS: " . $contadorComem), 0, 0, 'R', 0);
$pdf->Cell(90, 5, utf8_decode("$" . number_format($totalPercepciones, 2, ".", ",")), 0, 0, 'R', 0);
$pdf->Cell(120, 5, utf8_decode("$" . number_format($totalDeducciones, 2, ".", ",")), 0, 0, 'R', 0);
$pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
$pdf->Cell(255, 5, utf8_decode("$" . number_format($totalPercepciones - $totalDeducciones, 2, ".", ",")), 0, 0, 'R', 0);
//Limpiando variables acumulativas
$totalEmp = 0;
$totalPercepciones = 0;
$totalDeducciones = 0;
$pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);















/**********************CONSULTA PARA CONTRATOS DE PATRIMONIO**********************/
$consulta3 = "SELECT
DetNomina.CvePersonal,EmpGral.RFC,CONCAT(EmpGral.Nombre,' ',EmpGral.Paterno,' ',EmpGral.Materno) AS Nombre,EmpCont.CtaBanco,SUBSTR(catbanco.NomBanco,1,4)AS NomBanco,EmpCont.CtaBanco,EmpGral.CURP,EmpCont.Dirgral,EmpCont.HrsMen,EmpGral.CveISSEMyM,EmpCont.UnidadRespon,
EmpCont.CodCategoria,catcatego.Descripcion,catcatego.DescCorta,DetNomina.Del,DetNomina.Al,
#Total de sueldos eventuales
'0202' AS cveeventuales,
SUM(CASE WHEN DetNomina.Clave=0202 THEN Importe ELSE 0 END) AS toteventuales,
#Total de subsidios
'0325' AS cvesubsidios,
SUM(CASE WHEN DetNomina.Clave=0325 THEN Importe ELSE 0 END) AS totsubsidios,
#Total de percepciones
SUM(CASE WHEN DetNomina.Clave IN (0325,0202) THEN Importe ELSE 0 END) AS totpercepciones,
#Total ISR
'5408' AS cveisr,
SUM(CASE WHEN DetNomina.Clave=5408 THEN Importe ELSE 0 END) AS totisr,
#Total Servicios de salud
'5540' AS cvesalud,
SUM(CASE WHEN DetNomina.Clave=5540 THEN Importe ELSE 0 END) AS totsalud,
#Total Sistema solidario de reparto
'5541' AS cvesisrep,
SUM(CASE WHEN DetNomina.Clave=5541 THEN Importe ELSE 0 END) AS totsisrep,
#Total Capitalizacion individual
'5542' AS cvecapita,
SUM(CASE WHEN DetNomina.Clave=5542 THEN Importe ELSE 0 END) AS totcapita,
#Total deducciones
SUM(CASE WHEN DetNomina.Clave IN (5408,5540,5541,5542) THEN Importe ELSE 0 END) AS totdeducciones,
#Sueldo Bruto
SUM(CASE WHEN DetNomina.Clave IN (0325,0202) THEN Importe ELSE 0 END)- SUM(CASE WHEN DetNomina.Clave IN (5408,5540,5541,5542) THEN Importe ELSE 0 END) AS sueldobruto,
#Total de empleados
(SELECT COUNT(*) FROM EmpCont WHERE EmpCont.Dirgral=2) AS totempleados
FROM EmpCont INNER JOIN 
DetNomina ON EmpCont.CvePersonal = DetNomina.CvePersonal INNER JOIN
EmpGral ON EmpCont.CvePersonal = EmpGral.CvePersonal INNER JOIN
catbanco ON SUBSTR(EmpCont.CtaBanco, 1, 3) = catbanco.CveBanco INNER JOIN
catcatego ON EmpCont.CodCategoria = catcatego.CveCategoria
WHERE EmpCont.CveContrato LIKE '%PATRI%' AND DetNomina.CveNomina='$CveNomina' GROUP BY DetNomina.CvePersonal";
//EFECTUANDO CONSULTA
$resultado3 = $mysqli->query($consulta3);

//Agregando pagina para que inicie desde en encabezado
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Helvetica', '', 11);

//Indicar salida del archivo pdf
$pdf->SetFont('Helvetica', '', 8);
while ($row = $resultado3->fetch_assoc()) {
    $pdf->Cell(2, 5, utf8_decode($row['CvePersonal']), 0, 0, 'C', 0);
    $pdf->Cell(65, 5, utf8_decode($row['RFC']), 0, 0, 'C', 0);
    $pdf->Cell(80, 5, utf8_decode($row['Nombre']), 0, 0, 'C', 0);
    $pdf->Cell(55, 5, utf8_decode($row['CtaBanco']), 0, 0, 'C', 0);
    $pdf->Cell(1, 5, utf8_decode('(' . $row['NomBanco'] . ')'), 0, 0, 'C', 0);
    $pdf->Cell(74, 5, utf8_decode($row['CURP']), 0, 0, 'C', 0);
    $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    $pdf->Cell(1, 5, utf8_decode($row['Dirgral']), 0, 0, 'C', 0);
    $pdf->Cell(30, 5, utf8_decode($row['CveISSEMyM']), 0, 0, 'C', 0);
    $pdf->Cell(10, 5, utf8_decode($row['UnidadRespon']), 0, 0, 'C', 0);
    $pdf->Cell(90, 5, utf8_decode('DIR GRAL PAT Y SERV CULT -TOLUCAE'), 0, 0, 'C', 0);
    $pdf->Cell(20, 5, utf8_decode($row['CodCategoria']), 0, 0, 'C', 0);
    $pdf->Cell(65, 5, utf8_decode($row['Descripcion']), 0, 0, 'C', 0);
    $pdf->Cell(15, 5, utf8_decode($row['Del']), 0, 0, 'C', 0);
    $pdf->Cell(40, 5, utf8_decode($row['Al']), 0, 0, 'C', 0);
    $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    $pdf->Cell(2, 5, utf8_decode($row['cveeventuales']), 0, 0, 'C', 0);
    $pdf->Cell(55, 5, utf8_decode('SUELDOS EVENTUALES'), 0, 0, 'C', 0);
    $pdf->Cell(1, 5, utf8_decode('(' . $row['HrsMen'] . ')'), 0, 0, 'C', 0);
    $pdf->Cell(1, 5, utf8_decode(' ' . ' ' . ' ' . $row['DescCorta'] . ''), 0, 0, 'L', 0);
    $pdf->Cell(65, 5, utf8_decode("$" . number_format($row['toteventuales'], 2, ".", ",")), 0, 0, 'R', 0);
    $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);

    if ($row['totsubsidios'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvesubsidios']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('SUBSIDIO AL EMPLEO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totsubsidios'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totisr'] > 0) {
        $pdf->Cell(150, 5, utf8_decode($row['cveisr']), 0, 0, 'R', 0);
        $pdf->Cell(55, 5, utf8_decode('IMPUESTO SOBRE LA RENTA'), 0, 0, 'R', 0);
        $pdf->Cell(55, 5, utf8_decode("$" . number_format($row['totisr'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totsalud'] > 0) {
        $pdf->Cell(150, 5, utf8_decode($row['cvesalud']), 0, 0, 'R', 0);
        $pdf->Cell(55, 5, utf8_decode('SERVICIOS DE SALUD 4.625%'), 0, 0, 'R', 0);
        $pdf->Cell(55, 5, utf8_decode("$" . number_format($row['totsalud'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totsisrep'] > 0) {
        $pdf->Cell(150, 5, utf8_decode($row['cvesisrep']), 0, 0, 'R', 0);
        $pdf->Cell(72, 5, utf8_decode('SISTEMA SOLIDARIO DE REPARTO 6.1%'), 0, 0, 'R', 0);
        $pdf->Cell(38, 5, utf8_decode("$" . number_format($row['totsisrep'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totcapita'] > 0) {
        $pdf->Cell(150, 5, utf8_decode($row['cvecapita']), 0, 0, 'R', 0);
        $pdf->Cell(62, 5, utf8_decode('CAPITALIZACION INDIVIDUAL 1.4%'), 0, 0, 'R', 0);
        $pdf->Cell(48, 5, utf8_decode("$" . number_format($row['totcapita'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    $pdf->Cell(122, 5, utf8_decode("$" . number_format($row['totpercepciones'], 2, ".", ",")), 0, 0, 'R', 0);
    $pdf->Cell(138, 5, utf8_decode("$" . number_format($row['totdeducciones'], 2, ".", ",")), 0, 0, 'R', 0);
    $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    $pdf->Cell(260, 5, utf8_decode("$" . number_format($row['sueldobruto'], 2, ".", ",")), 0, 0, 'R', 0);


    $pdf->Cell(1, 6, utf8_decode(''), 0, 1, 'L', 0);
    // $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);

    //Declaraciones finales
    $totalEmp = $row['totempleados'];
    $totalPercepciones = $totalPercepciones + $row['totpercepciones'];
    $totalDeducciones = $totalDeducciones + $row['totdeducciones'];
    //Sumas finales de nómina
    $a = $a + 1;
    $defPercepciones = $defPercepciones + $row['totpercepciones'];
    $defDeducciones = $defDeducciones + $row['totdeducciones'];
    $contadorPatri = $contadorPatri + 1;
}

//Imprimiendo totales en pantalla
$pdf->Cell(45, 5, utf8_decode("TOTALES DE EMPLEADOS: " . $contadorPatri), 0, 0, 'R', 0);
$pdf->Cell(90, 5, utf8_decode("$" . number_format($totalPercepciones, 2, ".", ",")), 0, 0, 'R', 0);
$pdf->Cell(120, 5, utf8_decode("$" . number_format($totalDeducciones, 2, ".", ",")), 0, 0, 'R', 0);
$pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
$pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
$pdf->Cell(255, 5, utf8_decode("$" . number_format($totalPercepciones - $totalDeducciones, 2, ".", ",")), 0, 0, 'R', 0);
$pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
$pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
$pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);

//Totales de toda la nomina
$pdf->Cell(250, 5, utf8_decode("-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"), 0, 0, 'C', 0);
$pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
$pdf->Cell(45, 5, utf8_decode("TOTALES DE EMPLEADOS: " . $contadorDeporte + $contadorPatri + $contadorComem), 0, 0, 'R', 0);
$pdf->Cell(90, 5, utf8_decode("$" . number_format($defPercepciones, 2, ".", ",")), 0, 0, 'R', 0);
$pdf->Cell(120, 5, utf8_decode("$" . number_format($defDeducciones, 2, ".", ",")), 0, 0, 'R', 0);
$pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
$pdf->Cell(255, 5, utf8_decode("$" . number_format($defPercepciones - $defDeducciones, 2, ".", ",")), 0, 0, 'R', 0);

//Inidicando la salida del archivo como PDF en pantalla
$pdf->Output();
