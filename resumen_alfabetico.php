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
#Total Prima vacacional
'0305' AS cveprimavac,
SUM(CASE WHEN DetNomina.Clave=0305 THEN Importe ELSE 0 END) AS totprimavac,
#Total Aguinaldo
'0308' AS cveaguinaldo,
SUM(CASE WHEN DetNomina.Clave=0308 THEN Importe ELSE 0 END) AS totaguinaldo,
#Total de subsidios
'0325' AS cvesubsidios,
SUM(CASE WHEN DetNomina.Clave=0325 THEN Importe ELSE 0 END) AS totsubsidios,
#Total de retroactivos
'1202' AS cveretroactivos,
SUM(CASE WHEN DetNomina.Clave=1202 THEN Importe ELSE 0 END) AS totretroactivos,
#Total de primavacacional
'1305' AS cveprimavac2,
SUM(CASE WHEN DetNomina.Clave=1305 THEN Importe ELSE 0 END) AS totprimavac2,
#Total de retroactivos
'1308' AS cveretaguieve,
SUM(CASE WHEN DetNomina.Clave=1308 THEN Importe ELSE 0 END) AS totretaguieve,
#Total de retroactivos
'1325' AS cveretsubemp,
SUM(CASE WHEN DetNomina.Clave=1325 THEN Importe ELSE 0 END) AS totretsubemp,
#Total de retroactivos
'2408' AS cvedevisr,
SUM(CASE WHEN DetNomina.Clave=2408 THEN Importe ELSE 0 END) AS totdevisr,
#Total de Devolucion de tiempo no laborado
'2450' AS cvedevtinolab,
SUM(CASE WHEN DetNomina.Clave=2450 THEN Importe ELSE 0 END) AS totdevtinolab,
#Total de Devolucion de auscentismo
'2451' AS cvedevaus,
SUM(CASE WHEN DetNomina.Clave=2451 THEN Importe ELSE 0 END) AS totcvedevaus,
#Total de Devolucion de auscentismo
'2540' AS cvedevsersa,
SUM(CASE WHEN DetNomina.Clave=2540 THEN Importe ELSE 0 END) AS totdevsersa,
#Total de Devolucion del sistema solidario de reparto
'2541' AS cvesissolrep,
SUM(CASE WHEN DetNomina.Clave=2541 THEN Importe ELSE 0 END) AS totsissolrep,
#Total de Devolucion del sistema capitalizacion individual
'2542' AS cvesiscapindividual,
SUM(CASE WHEN DetNomina.Clave=2542 THEN Importe ELSE 0 END) AS totsiscapindividual,
#Total de D/D/I ISR AJUSTE MENSUAL
'2651' AS cveddiajusmen,
SUM(CASE WHEN DetNomina.Clave=2651 THEN Importe ELSE 0 END) AS totddiajusmen,
#Total de D/D/I SUB PAGADO INDEB A SP
'2652' AS cveddisubpaginasp,
SUM(CASE WHEN DetNomina.Clave=2652 THEN Importe ELSE 0 END) AS totddisubpaginasp,
#Total de D/D/I AJUSTE SUBSIDIO CAUSADO
'2653' AS cveddiajussubcausado,
SUM(CASE WHEN DetNomina.Clave=2653 THEN Importe ELSE 0 END) AS totddiajussubcausado,
#Total de ESTANCIA INFANTIL
'5431' AS cveestanciainf,
SUM(CASE WHEN DetNomina.Clave=5431 THEN Importe ELSE 0 END) AS totestanciainf,
#Total de C.VACACIONAL VALLE DE BRAVO
'5438' AS cvevacvallebra,
SUM(CASE WHEN DetNomina.Clave=5438 THEN Importe ELSE 0 END) AS totvacvallebra,
#Total de DESCUENTO POR TIEMPO
'5450' AS cvedescportiempo,
SUM(CASE WHEN DetNomina.Clave=5450 THEN Importe ELSE 0 END) AS totdescportiempo,
#Total de SANCION POR IMPUNTUALIDAD
'5451' AS cvesancionimp,
SUM(CASE WHEN DetNomina.Clave=5451 THEN Importe ELSE 0 END) AS totsancionimp,
#Total de CUOTA VOLUNTARIA S.C.I.
'5545' AS cvecuotavol,
SUM(CASE WHEN DetNomina.Clave=5545 THEN Importe ELSE 0 END) AS totcuotavol,
#Total de APORT. ESTANCIAS ($1,025.00)
'5631' AS cveaporestan,
SUM(CASE WHEN DetNomina.Clave=5631 THEN Importe ELSE 0 END) AS totaporestan,
#Total de ISR AJUSTE MENSUAL
'5651' AS cveisrajusmensual,
SUM(CASE WHEN DetNomina.Clave=5651 THEN Importe ELSE 0 END) AS totajusmensual,
#Total de SUB PAGADO INDEB AL SP
'5652' AS cvesubpagalsp,
SUM(CASE WHEN DetNomina.Clave=5652 THEN Importe ELSE 0 END) AS totsubpagalsp,
#Total de AJUSTE AL SUBSIDIO CAUSADO
'5653' AS cveajusalsubpag,
SUM(CASE WHEN DetNomina.Clave=5653 THEN Importe ELSE 0 END) AS totajusalsubpag,
#Total de DED.PEND. IMPUESTO SOBRE LA RENTA
'6408' AS cvependisr,
SUM(CASE WHEN DetNomina.Clave=6408 THEN Importe ELSE 0 END) AS totpendisr,
#Total de DEDUCC. PEND. ESTANCIAS
'6431' AS cvededucpendest,
SUM(CASE WHEN DetNomina.Clave=6431 THEN Importe ELSE 0 END) AS totdeducpendest,
#Total de DEDUC.PEND.CEN.VACA.VALLE DE BRAVO
'6438' AS cvededpencenvdbra,
SUM(CASE WHEN DetNomina.Clave=6438 THEN Importe ELSE 0 END) AS totdedpencenvdbra,
#Total de DEDUC.PEND.APLICACION AUSENTISMO
'6451' AS cvededpenaplaus,
SUM(CASE WHEN DetNomina.Clave=6451 THEN Importe ELSE 0 END) AS totdedpenaplaus,
#Total de 6.1% DEDUC.PEND.SIST.SOLIDARIO REP.
'6541' AS cvededucpensissolrep,
SUM(CASE WHEN DetNomina.Clave=6541 THEN Importe ELSE 0 END) AS totdeducpensissolrep,
#Total de 1.4% DEDUC.PEND.CAPITALI.INDIVIDUAL
'6542' AS cvededucpcapind,
SUM(CASE WHEN DetNomina.Clave=6542 THEN Importe ELSE 0 END) AS totdeducpcapind,
#Total de D/P/A ISR AJUSTE MENSUAL
'6651' AS cvedpaisrajusmen,
SUM(CASE WHEN DetNomina.Clave=6651 THEN Importe ELSE 0 END) AS totdpaisrajusmen,
#Total de D/P/A SUB PAGADO INDEB A SP
'6652' AS cvedpasubpagado,
SUM(CASE WHEN DetNomina.Clave=6652 THEN Importe ELSE 0 END) AS totdpasubpagado,
#Total de D/P/A AJUSTE SUBSIDIO CAUSADO
'6653' AS cvedpaasubsidiocausado,
SUM(CASE WHEN DetNomina.Clave=6653 THEN Importe ELSE 0 END) AS totdpaasubsidiocausado,
#Total de DED.PERC.IND. SUELDOS EVENTUALES
'8202' AS cvededpercind,
SUM(CASE WHEN DetNomina.Clave=8202 THEN Importe ELSE 0 END) AS totdedpercind,
#Total de DEDUC. PRIMA VAC. INDEBIDO
'8305' AS cvededucpvacind,
SUM(CASE WHEN DetNomina.Clave=8305 THEN Importe ELSE 0 END) AS totdeducpvacind,
#Total de DED.PERC.INDB. SUBSIDIO AL EMPLEO
'8325' AS cvededperindsubemp,
SUM(CASE WHEN DetNomina.Clave=8325 THEN Importe ELSE 0 END) AS totdedperindsubemp,
#Total de DEDUC.PERCEP.INDEBIDA DEVOL.AUSEN.
'8451' AS cvededucinddevaus,
SUM(CASE WHEN DetNomina.Clave=8451 THEN Importe ELSE 0 END) AS totdeducinddevaus,
#Total de DEVOL. SERVICIO DE SALUD 4.625%
'8540' AS cve8540,
SUM(CASE WHEN DetNomina.Clave=8540 THEN Importe ELSE 0 END) AS tot8540,
#Total de DEVOL. SIST. SOLID. REPART 6.1%
'8541' AS cve8541,
SUM(CASE WHEN DetNomina.Clave=8541 THEN Importe ELSE 0 END) AS tot8541,
#Total de DEVOL. CAPITAL. INDIVIDUAL 1.4%
'8542' AS cve8542,
SUM(CASE WHEN DetNomina.Clave=8542 THEN Importe ELSE 0 END) AS tot8542,
#Total de D/P/I AJUSTE MENSUAL
'8651' AS cve8651,
SUM(CASE WHEN DetNomina.Clave=8651 THEN Importe ELSE 0 END) AS tot8651,
#Total de D/P/I SUB PAGADO INDEB A SP
'8652' AS cve8652,
SUM(CASE WHEN DetNomina.Clave=8652 THEN Importe ELSE 0 END) AS tot8652,
#Total de D/P/I SUB PAGADO INDEB A SP
'8653' AS cve8653,
SUM(CASE WHEN DetNomina.Clave=8653 THEN Importe ELSE 0 END) AS tot8653,
#Total de percepciones
SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=0) THEN Importe ELSE 0 END) AS totpercepciones,
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
SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=1) THEN Importe ELSE 0 END) AS totdeducciones,
#Sueldo Bruto
SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=0) THEN Importe ELSE 0 END)- SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=1) THEN Importe ELSE 0 END) AS sueldobruto,
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

    if ($row['totprimavac'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cveprimavac']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('PRIMA VACACIONAL'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totprimavac'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totaguinaldo'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cveaguinaldo']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('AGUINALDO EVENTUALES'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totaguinaldo'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totretroactivos'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cveretroactivos']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('RET. SUELDOS EVENTUALES'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totretroactivos'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totprimavac2'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cveprimavac2']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('PRIMA VACACIONAL'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totprimavac2'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totretaguieve'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cveretaguieve']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('RETR AGUINALDO EVENTUALES'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totretaguieve'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totretsubemp'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cveretsubemp']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('RETRO. SUBSIDIO AL EMPLEO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totretsubemp'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdevisr'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvedevisr']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEVOL. IMPUESTO SOBRE LA RENTA'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdevisr'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdevtinolab'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvedevtinolab']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEVOL. TIEMPO NO LABORADO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdevtinolab'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totcvedevaus'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvecvedevaus']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEVOLUCION AUSENTISMO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totcvedevaus'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdevsersa'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvedevsersa']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEVOL. SERVICIOS DE SALUD 4.625%'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdevsersa'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totsissolrep'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvesissolrep']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEVOL. SIS. SOLIDA. DE REPARTO 6.1%'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totsissolrep'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totsiscapindividual'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvesiscapindividual']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEVOL. SISTEMA DE CAPITAL. INDIV.'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totsiscapindividual'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totddiajusmen'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cveddiajusmen']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('D/D/I ISR AJUSTE MENSUAL'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totddiajusmen'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totddisubpaginasp'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cveddisubpaginasp']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('D/D/I SUB PAGADO INDEB A SP'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totddisubpaginasp'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totddiajussubcausado'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cveddiajussubcausado']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('D/D/I AJUSTE SUBSIDIO CAUSADO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totddiajussubcausado'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totestanciainf'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cveestanciainf']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('ESTANCIA INFANTIL'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totestanciainf'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totvacvallebra'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvevacvallebra']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('C.VACACIONAL VALLE DE BRAVO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totvacvallebra'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdescportiempo'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvedescportiempo']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DESCUENTO POR TIEMPO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdescportiempo'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totsancionimp'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvesancionimp']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('SANCION POR IMPUNTUALIDAD'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totsancionimp'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totcuotavol'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvecuotavol']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('CUOTA VOLUNTARIA S.C.I.'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totcuotavol'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totaporestan'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cveaporestan']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('APORT. ESTANCIAS ($1,025.00)'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totaporestan'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totajusmensual'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cveajusmensual']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('ISR AJUSTE MENSUAL'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totajusmensual'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totsubpagalsp'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvesubpagalsp']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('SUB PAGADO INDEB AL SP'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totsubpagalsp'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totajusalsubpag'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cveajusalsubpag']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('AJUSTE AL SUBSIDIO CAUSADO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totajusalsubpag'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totpendisr'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvependisr']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DED.PEND. IMPUESTO SOBRE LA RENTA'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totpendisr'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdeducpendest'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvededucpendest']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEDUCC. PEND. ESTANCIAS'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdeducpendest'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdedpencenvdbra'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvededpencenvdbra']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEDUC.PEND.CEN.VACA.VALLE DE BRAVO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdedpencenvdbra'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdedpenaplaus'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvededpenaplaus']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEDUC.PEND.APLICACION AUSENTISMO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdedpenaplaus'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdeducpensissolrep'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvededucpensissolrep']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('6.1% DEDUC.PEND.SIST.SOLIDARIO REP.'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdeducpensissolrep'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdeducpcapind'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvededucpcapind']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('1.4% DEDUC.PEND.CAPITALI.INDIVIDUAL'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdeducpcapind'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdpaisrajusmen'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvedpaisrajusmen']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('D/P/A ISR AJUSTE MENSUAL'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdpaisrajusmen'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdpasubpagado'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvedpasubpagado']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('D/P/A SUB PAGADO INDEB A SP'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdpasubpagado'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdpaasubsidiocausado'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvedpaasubsidiocausado']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('D/P/A AJUSTE SUBSIDIO CAUSADO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdpaasubsidiocausado'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdedpercind'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvededpercind']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DED.PERC.IND. SUELDOS EVENTUALES'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdedpercind'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdeducpvacind'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvededucpvacind']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEDUC. PRIMA VAC. INDEBIDO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdeducpvacind'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdedperindsubemp'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvededperindsubemp']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DED.PERC.INDB. SUBSIDIO AL EMPLEO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdedperindsubemp'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdeducinddevaus'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvededucinddevaus']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEDUC.PERCEP.INDEBIDA DEVOL.AUSEN.'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdeducinddevaus'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['tot8540'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cve8540']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEVOL. SERVICIO DE SALUD 4.625%'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['tot8540'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['tot8541'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cve8541']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEVOL. SIST. SOLID. REPART 6.1%'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['tot8541'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['tot8542'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cve8542']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEVOL. CAPITAL. INDIVIDUAL 1.4%'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['tot8542'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['tot8651'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cve8651']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('D/P/I AJUSTE MENSUAL'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['tot8651'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['tot8652'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cve8652']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('D/P/I SUB PAGADO INDEB A SP'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['tot8652'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['tot8653'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cve8653']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('D/P/I AJUSTE SUBSIDIO CAUSADO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['tot8653'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    /*DEDUCCIONES**********************************************/
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
DetNomina.CvePersonal,EmpGral.RFC,CONCAT(EmpGral.Nombre,' ',EmpGral.Paterno,' ',EmpGral.Materno) AS Nombre,EmpCont.CtaBanco,SUBSTR(catbanco.NomBanco,1,4) AS NomBanco,EmpGral.CURP,EmpCont.Dirgral,EmpCont.HrsMen,EmpGral.CveISSEMyM,EmpCont.UnidadRespon,
EmpCont.CodCategoria,catcatego.Descripcion,catcatego.DescCorta,DetNomina.Del,DetNomina.Al,
#Total de sueldos eventuales
'0202' AS cveeventuales,
SUM(CASE WHEN DetNomina.Clave=0202 THEN Importe ELSE 0 END) AS toteventuales,
#Total Prima vacacional
'0305' AS cveprimavac,
SUM(CASE WHEN DetNomina.Clave=0305 THEN Importe ELSE 0 END) AS totprimavac,
#Total Aguinaldo
'0308' AS cveaguinaldo,
SUM(CASE WHEN DetNomina.Clave=0308 THEN Importe ELSE 0 END) AS totaguinaldo,
#Total de subsidios
'0325' AS cvesubsidios,
SUM(CASE WHEN DetNomina.Clave=0325 THEN Importe ELSE 0 END) AS totsubsidios,
#Total de retroactivos
'1202' AS cveretroactivos,
SUM(CASE WHEN DetNomina.Clave=1202 THEN Importe ELSE 0 END) AS totretroactivos,
#Total de primavacacional
'1305' AS cveprimavac2,
SUM(CASE WHEN DetNomina.Clave=1305 THEN Importe ELSE 0 END) AS totprimavac2,
#Total de retroactivos
'1308' AS cveretaguieve,
SUM(CASE WHEN DetNomina.Clave=1308 THEN Importe ELSE 0 END) AS totretaguieve,
#Total de retroactivos
'1325' AS cveretsubemp,
SUM(CASE WHEN DetNomina.Clave=1325 THEN Importe ELSE 0 END) AS totretsubemp,
#Total de retroactivos
'2408' AS cvedevisr,
SUM(CASE WHEN DetNomina.Clave=2408 THEN Importe ELSE 0 END) AS totdevisr,
#Total de Devolucion de tiempo no laborado
'2450' AS cvedevtinolab,
SUM(CASE WHEN DetNomina.Clave=2450 THEN Importe ELSE 0 END) AS totdevtinolab,
#Total de Devolucion de auscentismo
'2451' AS cvedevaus,
SUM(CASE WHEN DetNomina.Clave=2451 THEN Importe ELSE 0 END) AS totcvedevaus,
#Total de Devolucion de auscentismo
'2540' AS cvedevsersa,
SUM(CASE WHEN DetNomina.Clave=2540 THEN Importe ELSE 0 END) AS totdevsersa,
#Total de Devolucion del sistema solidario de reparto
'2541' AS cvesissolrep,
SUM(CASE WHEN DetNomina.Clave=2541 THEN Importe ELSE 0 END) AS totsissolrep,
#Total de Devolucion del sistema capitalizacion individual
'2542' AS cvesiscapindividual,
SUM(CASE WHEN DetNomina.Clave=2542 THEN Importe ELSE 0 END) AS totsiscapindividual,
#Total de D/D/I ISR AJUSTE MENSUAL
'2651' AS cveddiajusmen,
SUM(CASE WHEN DetNomina.Clave=2651 THEN Importe ELSE 0 END) AS totddiajusmen,
#Total de D/D/I SUB PAGADO INDEB A SP
'2652' AS cveddisubpaginasp,
SUM(CASE WHEN DetNomina.Clave=2652 THEN Importe ELSE 0 END) AS totddisubpaginasp,
#Total de D/D/I AJUSTE SUBSIDIO CAUSADO
'2653' AS cveddiajussubcausado,
SUM(CASE WHEN DetNomina.Clave=2653 THEN Importe ELSE 0 END) AS totddiajussubcausado,
#Total de ESTANCIA INFANTIL
'5431' AS cveestanciainf,
SUM(CASE WHEN DetNomina.Clave=5431 THEN Importe ELSE 0 END) AS totestanciainf,
#Total de C.VACACIONAL VALLE DE BRAVO
'5438' AS cvevacvallebra,
SUM(CASE WHEN DetNomina.Clave=5438 THEN Importe ELSE 0 END) AS totvacvallebra,
#Total de DESCUENTO POR TIEMPO
'5450' AS cvedescportiempo,
SUM(CASE WHEN DetNomina.Clave=5450 THEN Importe ELSE 0 END) AS totdescportiempo,
#Total de SANCION POR IMPUNTUALIDAD
'5451' AS cvesancionimp,
SUM(CASE WHEN DetNomina.Clave=5451 THEN Importe ELSE 0 END) AS totsancionimp,
#Total de CUOTA VOLUNTARIA S.C.I.
'5545' AS cvecuotavol,
SUM(CASE WHEN DetNomina.Clave=5545 THEN Importe ELSE 0 END) AS totcuotavol,
#Total de APORT. ESTANCIAS ($1,025.00)
'5631' AS cveaporestan,
SUM(CASE WHEN DetNomina.Clave=5631 THEN Importe ELSE 0 END) AS totaporestan,
#Total de ISR AJUSTE MENSUAL
'5651' AS cveisrajusmensual,
SUM(CASE WHEN DetNomina.Clave=5651 THEN Importe ELSE 0 END) AS totajusmensual,
#Total de SUB PAGADO INDEB AL SP
'5652' AS cvesubpagalsp,
SUM(CASE WHEN DetNomina.Clave=5652 THEN Importe ELSE 0 END) AS totsubpagalsp,
#Total de AJUSTE AL SUBSIDIO CAUSADO
'5653' AS cveajusalsubpag,
SUM(CASE WHEN DetNomina.Clave=5653 THEN Importe ELSE 0 END) AS totajusalsubpag,
#Total de DED.PEND. IMPUESTO SOBRE LA RENTA
'6408' AS cvependisr,
SUM(CASE WHEN DetNomina.Clave=6408 THEN Importe ELSE 0 END) AS totpendisr,
#Total de DEDUCC. PEND. ESTANCIAS
'6431' AS cvededucpendest,
SUM(CASE WHEN DetNomina.Clave=6431 THEN Importe ELSE 0 END) AS totdeducpendest,
#Total de DEDUC.PEND.CEN.VACA.VALLE DE BRAVO
'6438' AS cvededpencenvdbra,
SUM(CASE WHEN DetNomina.Clave=6438 THEN Importe ELSE 0 END) AS totdedpencenvdbra,
#Total de DEDUC.PEND.APLICACION AUSENTISMO
'6451' AS cvededpenaplaus,
SUM(CASE WHEN DetNomina.Clave=6451 THEN Importe ELSE 0 END) AS totdedpenaplaus,
#Total de 6.1% DEDUC.PEND.SIST.SOLIDARIO REP.
'6541' AS cvededucpensissolrep,
SUM(CASE WHEN DetNomina.Clave=6541 THEN Importe ELSE 0 END) AS totdeducpensissolrep,
#Total de 1.4% DEDUC.PEND.CAPITALI.INDIVIDUAL
'6542' AS cvededucpcapind,
SUM(CASE WHEN DetNomina.Clave=6542 THEN Importe ELSE 0 END) AS totdeducpcapind,
#Total de D/P/A ISR AJUSTE MENSUAL
'6651' AS cvedpaisrajusmen,
SUM(CASE WHEN DetNomina.Clave=6651 THEN Importe ELSE 0 END) AS totdpaisrajusmen,
#Total de D/P/A SUB PAGADO INDEB A SP
'6652' AS cvedpasubpagado,
SUM(CASE WHEN DetNomina.Clave=6652 THEN Importe ELSE 0 END) AS totdpasubpagado,
#Total de D/P/A AJUSTE SUBSIDIO CAUSADO
'6653' AS cvedpaasubsidiocausado,
SUM(CASE WHEN DetNomina.Clave=6653 THEN Importe ELSE 0 END) AS totdpaasubsidiocausado,
#Total de DED.PERC.IND. SUELDOS EVENTUALES
'8202' AS cvededpercind,
SUM(CASE WHEN DetNomina.Clave=8202 THEN Importe ELSE 0 END) AS totdedpercind,
#Total de DEDUC. PRIMA VAC. INDEBIDO
'8305' AS cvededucpvacind,
SUM(CASE WHEN DetNomina.Clave=8305 THEN Importe ELSE 0 END) AS totdeducpvacind,
#Total de DED.PERC.INDB. SUBSIDIO AL EMPLEO
'8325' AS cvededperindsubemp,
SUM(CASE WHEN DetNomina.Clave=8325 THEN Importe ELSE 0 END) AS totdedperindsubemp,
#Total de DEDUC.PERCEP.INDEBIDA DEVOL.AUSEN.
'8451' AS cvededucinddevaus,
SUM(CASE WHEN DetNomina.Clave=8451 THEN Importe ELSE 0 END) AS totdeducinddevaus,
#Total de DEVOL. SERVICIO DE SALUD 4.625%
'8540' AS cve8540,
SUM(CASE WHEN DetNomina.Clave=8540 THEN Importe ELSE 0 END) AS tot8540,
#Total de DEVOL. SIST. SOLID. REPART 6.1%
'8541' AS cve8541,
SUM(CASE WHEN DetNomina.Clave=8541 THEN Importe ELSE 0 END) AS tot8541,
#Total de DEVOL. CAPITAL. INDIVIDUAL 1.4%
'8542' AS cve8542,
SUM(CASE WHEN DetNomina.Clave=8542 THEN Importe ELSE 0 END) AS tot8542,
#Total de D/P/I AJUSTE MENSUAL
'8651' AS cve8651,
SUM(CASE WHEN DetNomina.Clave=8651 THEN Importe ELSE 0 END) AS tot8651,
#Total de D/P/I SUB PAGADO INDEB A SP
'8652' AS cve8652,
SUM(CASE WHEN DetNomina.Clave=8652 THEN Importe ELSE 0 END) AS tot8652,
#Total de D/P/I SUB PAGADO INDEB A SP
'8653' AS cve8653,
SUM(CASE WHEN DetNomina.Clave=8653 THEN Importe ELSE 0 END) AS tot8653,
#Total de percepciones
SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=0) THEN Importe ELSE 0 END) AS totpercepciones,
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
SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=1) THEN Importe ELSE 0 END) AS totdeducciones,
#Sueldo Bruto
SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=0) THEN Importe ELSE 0 END)- SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=1) THEN Importe ELSE 0 END) AS sueldobruto,
#Total de empleados
(SELECT COUNT(*) FROM EmpCont WHERE EmpCont.Dirgral=0) AS totempleados
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

    if ($row['totprimavac'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cveprimavac']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('PRIMA VACACIONAL'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totprimavac'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totaguinaldo'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cveaguinaldo']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('AGUINALDO EVENTUALES'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totaguinaldo'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totretroactivos'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cveretroactivos']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('RET. SUELDOS EVENTUALES'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totretroactivos'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totprimavac2'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cveprimavac2']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('PRIMA VACACIONAL'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totprimavac2'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totretaguieve'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cveretaguieve']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('RETR AGUINALDO EVENTUALES'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totretaguieve'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totretsubemp'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cveretsubemp']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('RETRO. SUBSIDIO AL EMPLEO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totretsubemp'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdevisr'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvedevisr']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEVOL. IMPUESTO SOBRE LA RENTA'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdevisr'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdevtinolab'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvedevtinolab']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEVOL. TIEMPO NO LABORADO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdevtinolab'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totcvedevaus'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvecvedevaus']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEVOLUCION AUSENTISMO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totcvedevaus'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdevsersa'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvedevsersa']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEVOL. SERVICIOS DE SALUD 4.625%'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdevsersa'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totsissolrep'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvesissolrep']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEVOL. SIS. SOLIDA. DE REPARTO 6.1%'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totsissolrep'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totsiscapindividual'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvesiscapindividual']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEVOL. SISTEMA DE CAPITAL. INDIV.'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totsiscapindividual'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totddiajusmen'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cveddiajusmen']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('D/D/I ISR AJUSTE MENSUAL'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totddiajusmen'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totddisubpaginasp'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cveddisubpaginasp']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('D/D/I SUB PAGADO INDEB A SP'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totddisubpaginasp'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totddiajussubcausado'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cveddiajussubcausado']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('D/D/I AJUSTE SUBSIDIO CAUSADO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totddiajussubcausado'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totestanciainf'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cveestanciainf']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('ESTANCIA INFANTIL'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totestanciainf'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totvacvallebra'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvevacvallebra']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('C.VACACIONAL VALLE DE BRAVO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totvacvallebra'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdescportiempo'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvedescportiempo']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DESCUENTO POR TIEMPO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdescportiempo'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totsancionimp'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvesancionimp']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('SANCION POR IMPUNTUALIDAD'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totsancionimp'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totcuotavol'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvecuotavol']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('CUOTA VOLUNTARIA S.C.I.'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totcuotavol'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totaporestan'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cveaporestan']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('APORT. ESTANCIAS ($1,025.00)'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totaporestan'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totajusmensual'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cveajusmensual']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('ISR AJUSTE MENSUAL'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totajusmensual'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totsubpagalsp'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvesubpagalsp']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('SUB PAGADO INDEB AL SP'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totsubpagalsp'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totajusalsubpag'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cveajusalsubpag']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('AJUSTE AL SUBSIDIO CAUSADO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totajusalsubpag'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totpendisr'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvependisr']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DED.PEND. IMPUESTO SOBRE LA RENTA'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totpendisr'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdeducpendest'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvededucpendest']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEDUCC. PEND. ESTANCIAS'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdeducpendest'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdedpencenvdbra'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvededpencenvdbra']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEDUC.PEND.CEN.VACA.VALLE DE BRAVO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdedpencenvdbra'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdedpenaplaus'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvededpenaplaus']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEDUC.PEND.APLICACION AUSENTISMO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdedpenaplaus'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdeducpensissolrep'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvededucpensissolrep']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('6.1% DEDUC.PEND.SIST.SOLIDARIO REP.'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdeducpensissolrep'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdeducpcapind'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvededucpcapind']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('1.4% DEDUC.PEND.CAPITALI.INDIVIDUAL'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdeducpcapind'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdpaisrajusmen'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvedpaisrajusmen']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('D/P/A ISR AJUSTE MENSUAL'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdpaisrajusmen'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdpasubpagado'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvedpasubpagado']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('D/P/A SUB PAGADO INDEB A SP'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdpasubpagado'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdpaasubsidiocausado'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvedpaasubsidiocausado']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('D/P/A AJUSTE SUBSIDIO CAUSADO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdpaasubsidiocausado'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdedpercind'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvededpercind']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DED.PERC.IND. SUELDOS EVENTUALES'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdedpercind'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdeducpvacind'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvededucpvacind']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEDUC. PRIMA VAC. INDEBIDO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdeducpvacind'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdedperindsubemp'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvededperindsubemp']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DED.PERC.INDB. SUBSIDIO AL EMPLEO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdedperindsubemp'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdeducinddevaus'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvededucinddevaus']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEDUC.PERCEP.INDEBIDA DEVOL.AUSEN.'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdeducinddevaus'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['tot8540'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cve8540']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEVOL. SERVICIO DE SALUD 4.625%'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['tot8540'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['tot8541'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cve8541']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEVOL. SIST. SOLID. REPART 6.1%'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['tot8541'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['tot8542'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cve8542']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEVOL. CAPITAL. INDIVIDUAL 1.4%'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['tot8542'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['tot8651'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cve8651']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('D/P/I AJUSTE MENSUAL'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['tot8651'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['tot8652'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cve8652']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('D/P/I SUB PAGADO INDEB A SP'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['tot8652'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['tot8653'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cve8653']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('D/P/I AJUSTE SUBSIDIO CAUSADO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['tot8653'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    /*DEDUCCIONES**********************************************/
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
DetNomina.CvePersonal,EmpGral.RFC,CONCAT(EmpGral.Nombre,' ',EmpGral.Paterno,' ',EmpGral.Materno) AS Nombre,EmpCont.CtaBanco,SUBSTR(catbanco.NomBanco,1,4) AS NomBanco,EmpGral.CURP,EmpCont.Dirgral,EmpCont.HrsMen,EmpGral.CveISSEMyM,EmpCont.UnidadRespon,
EmpCont.CodCategoria,catcatego.Descripcion,catcatego.DescCorta,DetNomina.Del,DetNomina.Al,
#Total de sueldos eventuales
'0202' AS cveeventuales,
SUM(CASE WHEN DetNomina.Clave=0202 THEN Importe ELSE 0 END) AS toteventuales,
#Total Prima vacacional
'0305' AS cveprimavac,
SUM(CASE WHEN DetNomina.Clave=0305 THEN Importe ELSE 0 END) AS totprimavac,
#Total Aguinaldo
'0308' AS cveaguinaldo,
SUM(CASE WHEN DetNomina.Clave=0308 THEN Importe ELSE 0 END) AS totaguinaldo,
#Total de subsidios
'0325' AS cvesubsidios,
SUM(CASE WHEN DetNomina.Clave=0325 THEN Importe ELSE 0 END) AS totsubsidios,
#Total de retroactivos
'1202' AS cveretroactivos,
SUM(CASE WHEN DetNomina.Clave=1202 THEN Importe ELSE 0 END) AS totretroactivos,
#Total de primavacacional
'1305' AS cveprimavac2,
SUM(CASE WHEN DetNomina.Clave=1305 THEN Importe ELSE 0 END) AS totprimavac2,
#Total de retroactivos
'1308' AS cveretaguieve,
SUM(CASE WHEN DetNomina.Clave=1308 THEN Importe ELSE 0 END) AS totretaguieve,
#Total de retroactivos
'1325' AS cveretsubemp,
SUM(CASE WHEN DetNomina.Clave=1325 THEN Importe ELSE 0 END) AS totretsubemp,
#Total de retroactivos
'2408' AS cvedevisr,
SUM(CASE WHEN DetNomina.Clave=2408 THEN Importe ELSE 0 END) AS totdevisr,
#Total de Devolucion de tiempo no laborado
'2450' AS cvedevtinolab,
SUM(CASE WHEN DetNomina.Clave=2450 THEN Importe ELSE 0 END) AS totdevtinolab,
#Total de Devolucion de auscentismo
'2451' AS cvedevaus,
SUM(CASE WHEN DetNomina.Clave=2451 THEN Importe ELSE 0 END) AS totcvedevaus,
#Total de Devolucion de auscentismo
'2540' AS cvedevsersa,
SUM(CASE WHEN DetNomina.Clave=2540 THEN Importe ELSE 0 END) AS totdevsersa,
#Total de Devolucion del sistema solidario de reparto
'2541' AS cvesissolrep,
SUM(CASE WHEN DetNomina.Clave=2541 THEN Importe ELSE 0 END) AS totsissolrep,
#Total de Devolucion del sistema capitalizacion individual
'2542' AS cvesiscapindividual,
SUM(CASE WHEN DetNomina.Clave=2542 THEN Importe ELSE 0 END) AS totsiscapindividual,
#Total de D/D/I ISR AJUSTE MENSUAL
'2651' AS cveddiajusmen,
SUM(CASE WHEN DetNomina.Clave=2651 THEN Importe ELSE 0 END) AS totddiajusmen,
#Total de D/D/I SUB PAGADO INDEB A SP
'2652' AS cveddisubpaginasp,
SUM(CASE WHEN DetNomina.Clave=2652 THEN Importe ELSE 0 END) AS totddisubpaginasp,
#Total de D/D/I AJUSTE SUBSIDIO CAUSADO
'2653' AS cveddiajussubcausado,
SUM(CASE WHEN DetNomina.Clave=2653 THEN Importe ELSE 0 END) AS totddiajussubcausado,
#Total de ESTANCIA INFANTIL
'5431' AS cveestanciainf,
SUM(CASE WHEN DetNomina.Clave=5431 THEN Importe ELSE 0 END) AS totestanciainf,
#Total de C.VACACIONAL VALLE DE BRAVO
'5438' AS cvevacvallebra,
SUM(CASE WHEN DetNomina.Clave=5438 THEN Importe ELSE 0 END) AS totvacvallebra,
#Total de DESCUENTO POR TIEMPO
'5450' AS cvedescportiempo,
SUM(CASE WHEN DetNomina.Clave=5450 THEN Importe ELSE 0 END) AS totdescportiempo,
#Total de SANCION POR IMPUNTUALIDAD
'5451' AS cvesancionimp,
SUM(CASE WHEN DetNomina.Clave=5451 THEN Importe ELSE 0 END) AS totsancionimp,
#Total de CUOTA VOLUNTARIA S.C.I.
'5545' AS cvecuotavol,
SUM(CASE WHEN DetNomina.Clave=5545 THEN Importe ELSE 0 END) AS totcuotavol,
#Total de APORT. ESTANCIAS ($1,025.00)
'5631' AS cveaporestan,
SUM(CASE WHEN DetNomina.Clave=5631 THEN Importe ELSE 0 END) AS totaporestan,
#Total de ISR AJUSTE MENSUAL
'5651' AS cveisrajusmensual,
SUM(CASE WHEN DetNomina.Clave=5651 THEN Importe ELSE 0 END) AS totajusmensual,
#Total de SUB PAGADO INDEB AL SP
'5652' AS cvesubpagalsp,
SUM(CASE WHEN DetNomina.Clave=5652 THEN Importe ELSE 0 END) AS totsubpagalsp,
#Total de AJUSTE AL SUBSIDIO CAUSADO
'5653' AS cveajusalsubpag,
SUM(CASE WHEN DetNomina.Clave=5653 THEN Importe ELSE 0 END) AS totajusalsubpag,
#Total de DED.PEND. IMPUESTO SOBRE LA RENTA
'6408' AS cvependisr,
SUM(CASE WHEN DetNomina.Clave=6408 THEN Importe ELSE 0 END) AS totpendisr,
#Total de DEDUCC. PEND. ESTANCIAS
'6431' AS cvededucpendest,
SUM(CASE WHEN DetNomina.Clave=6431 THEN Importe ELSE 0 END) AS totdeducpendest,
#Total de DEDUC.PEND.CEN.VACA.VALLE DE BRAVO
'6438' AS cvededpencenvdbra,
SUM(CASE WHEN DetNomina.Clave=6438 THEN Importe ELSE 0 END) AS totdedpencenvdbra,
#Total de DEDUC.PEND.APLICACION AUSENTISMO
'6451' AS cvededpenaplaus,
SUM(CASE WHEN DetNomina.Clave=6451 THEN Importe ELSE 0 END) AS totdedpenaplaus,
#Total de 6.1% DEDUC.PEND.SIST.SOLIDARIO REP.
'6541' AS cvededucpensissolrep,
SUM(CASE WHEN DetNomina.Clave=6541 THEN Importe ELSE 0 END) AS totdeducpensissolrep,
#Total de 1.4% DEDUC.PEND.CAPITALI.INDIVIDUAL
'6542' AS cvededucpcapind,
SUM(CASE WHEN DetNomina.Clave=6542 THEN Importe ELSE 0 END) AS totdeducpcapind,
#Total de D/P/A ISR AJUSTE MENSUAL
'6651' AS cvedpaisrajusmen,
SUM(CASE WHEN DetNomina.Clave=6651 THEN Importe ELSE 0 END) AS totdpaisrajusmen,
#Total de D/P/A SUB PAGADO INDEB A SP
'6652' AS cvedpasubpagado,
SUM(CASE WHEN DetNomina.Clave=6652 THEN Importe ELSE 0 END) AS totdpasubpagado,
#Total de D/P/A AJUSTE SUBSIDIO CAUSADO
'6653' AS cvedpaasubsidiocausado,
SUM(CASE WHEN DetNomina.Clave=6653 THEN Importe ELSE 0 END) AS totdpaasubsidiocausado,
#Total de DED.PERC.IND. SUELDOS EVENTUALES
'8202' AS cvededpercind,
SUM(CASE WHEN DetNomina.Clave=8202 THEN Importe ELSE 0 END) AS totdedpercind,
#Total de DEDUC. PRIMA VAC. INDEBIDO
'8305' AS cvededucpvacind,
SUM(CASE WHEN DetNomina.Clave=8305 THEN Importe ELSE 0 END) AS totdeducpvacind,
#Total de DED.PERC.INDB. SUBSIDIO AL EMPLEO
'8325' AS cvededperindsubemp,
SUM(CASE WHEN DetNomina.Clave=8325 THEN Importe ELSE 0 END) AS totdedperindsubemp,
#Total de DEDUC.PERCEP.INDEBIDA DEVOL.AUSEN.
'8451' AS cvededucinddevaus,
SUM(CASE WHEN DetNomina.Clave=8451 THEN Importe ELSE 0 END) AS totdeducinddevaus,
#Total de DEVOL. SERVICIO DE SALUD 4.625%
'8540' AS cve8540,
SUM(CASE WHEN DetNomina.Clave=8540 THEN Importe ELSE 0 END) AS tot8540,
#Total de DEVOL. SIST. SOLID. REPART 6.1%
'8541' AS cve8541,
SUM(CASE WHEN DetNomina.Clave=8541 THEN Importe ELSE 0 END) AS tot8541,
#Total de DEVOL. CAPITAL. INDIVIDUAL 1.4%
'8542' AS cve8542,
SUM(CASE WHEN DetNomina.Clave=8542 THEN Importe ELSE 0 END) AS tot8542,
#Total de D/P/I AJUSTE MENSUAL
'8651' AS cve8651,
SUM(CASE WHEN DetNomina.Clave=8651 THEN Importe ELSE 0 END) AS tot8651,
#Total de D/P/I SUB PAGADO INDEB A SP
'8652' AS cve8652,
SUM(CASE WHEN DetNomina.Clave=8652 THEN Importe ELSE 0 END) AS tot8652,
#Total de D/P/I SUB PAGADO INDEB A SP
'8653' AS cve8653,
SUM(CASE WHEN DetNomina.Clave=8653 THEN Importe ELSE 0 END) AS tot8653,
#Total de percepciones
SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=0) THEN Importe ELSE 0 END) AS totpercepciones,
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
SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=1) THEN Importe ELSE 0 END) AS totdeducciones,
#Sueldo Bruto
SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=0) THEN Importe ELSE 0 END)- SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=1) THEN Importe ELSE 0 END) AS sueldobruto,
#Total de empleados
(SELECT COUNT(*) FROM EmpCont WHERE EmpCont.Dirgral=0) AS totempleados
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

    if ($row['totprimavac'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cveprimavac']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('PRIMA VACACIONAL'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totprimavac'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totaguinaldo'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cveaguinaldo']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('AGUINALDO EVENTUALES'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totaguinaldo'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totretroactivos'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cveretroactivos']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('RET. SUELDOS EVENTUALES'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totretroactivos'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totprimavac2'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cveprimavac2']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('PRIMA VACACIONAL'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totprimavac2'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totretaguieve'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cveretaguieve']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('RETR AGUINALDO EVENTUALES'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totretaguieve'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totretsubemp'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cveretsubemp']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('RETRO. SUBSIDIO AL EMPLEO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totretsubemp'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdevisr'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvedevisr']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEVOL. IMPUESTO SOBRE LA RENTA'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdevisr'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdevtinolab'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvedevtinolab']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEVOL. TIEMPO NO LABORADO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdevtinolab'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totcvedevaus'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvecvedevaus']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEVOLUCION AUSENTISMO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totcvedevaus'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdevsersa'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvedevsersa']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEVOL. SERVICIOS DE SALUD 4.625%'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdevsersa'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totsissolrep'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvesissolrep']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEVOL. SIS. SOLIDA. DE REPARTO 6.1%'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totsissolrep'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totsiscapindividual'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvesiscapindividual']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEVOL. SISTEMA DE CAPITAL. INDIV.'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totsiscapindividual'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totddiajusmen'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cveddiajusmen']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('D/D/I ISR AJUSTE MENSUAL'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totddiajusmen'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totddisubpaginasp'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cveddisubpaginasp']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('D/D/I SUB PAGADO INDEB A SP'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totddisubpaginasp'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totddiajussubcausado'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cveddiajussubcausado']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('D/D/I AJUSTE SUBSIDIO CAUSADO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totddiajussubcausado'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totestanciainf'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cveestanciainf']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('ESTANCIA INFANTIL'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totestanciainf'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totvacvallebra'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvevacvallebra']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('C.VACACIONAL VALLE DE BRAVO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totvacvallebra'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdescportiempo'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvedescportiempo']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DESCUENTO POR TIEMPO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdescportiempo'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totsancionimp'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvesancionimp']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('SANCION POR IMPUNTUALIDAD'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totsancionimp'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totcuotavol'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvecuotavol']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('CUOTA VOLUNTARIA S.C.I.'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totcuotavol'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totaporestan'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cveaporestan']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('APORT. ESTANCIAS ($1,025.00)'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totaporestan'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totajusmensual'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cveajusmensual']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('ISR AJUSTE MENSUAL'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totajusmensual'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totsubpagalsp'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvesubpagalsp']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('SUB PAGADO INDEB AL SP'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totsubpagalsp'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totajusalsubpag'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cveajusalsubpag']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('AJUSTE AL SUBSIDIO CAUSADO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totajusalsubpag'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totpendisr'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvependisr']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DED.PEND. IMPUESTO SOBRE LA RENTA'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totpendisr'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdeducpendest'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvededucpendest']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEDUCC. PEND. ESTANCIAS'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdeducpendest'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdedpencenvdbra'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvededpencenvdbra']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEDUC.PEND.CEN.VACA.VALLE DE BRAVO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdedpencenvdbra'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdedpenaplaus'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvededpenaplaus']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEDUC.PEND.APLICACION AUSENTISMO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdedpenaplaus'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdeducpensissolrep'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvededucpensissolrep']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('6.1% DEDUC.PEND.SIST.SOLIDARIO REP.'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdeducpensissolrep'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdeducpcapind'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvededucpcapind']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('1.4% DEDUC.PEND.CAPITALI.INDIVIDUAL'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdeducpcapind'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdpaisrajusmen'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvedpaisrajusmen']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('D/P/A ISR AJUSTE MENSUAL'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdpaisrajusmen'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdpasubpagado'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvedpasubpagado']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('D/P/A SUB PAGADO INDEB A SP'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdpasubpagado'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdpaasubsidiocausado'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvedpaasubsidiocausado']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('D/P/A AJUSTE SUBSIDIO CAUSADO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdpaasubsidiocausado'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdedpercind'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvededpercind']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DED.PERC.IND. SUELDOS EVENTUALES'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdedpercind'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdeducpvacind'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvededucpvacind']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEDUC. PRIMA VAC. INDEBIDO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdeducpvacind'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdedperindsubemp'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvededperindsubemp']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DED.PERC.INDB. SUBSIDIO AL EMPLEO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdedperindsubemp'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['totdeducinddevaus'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cvededucinddevaus']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEDUC.PERCEP.INDEBIDA DEVOL.AUSEN.'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['totdeducinddevaus'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['tot8540'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cve8540']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEVOL. SERVICIO DE SALUD 4.625%'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['tot8540'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['tot8541'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cve8541']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEVOL. SIST. SOLID. REPART 6.1%'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['tot8541'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['tot8542'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cve8542']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('DEVOL. CAPITAL. INDIVIDUAL 1.4%'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['tot8542'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['tot8651'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cve8651']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('D/P/I AJUSTE MENSUAL'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['tot8651'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['tot8652'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cve8652']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('D/P/I SUB PAGADO INDEB A SP'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['tot8652'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    if ($row['tot8653'] > 0) {
        $pdf->Cell(2, 5, utf8_decode($row['cve8653']), 0, 0, 'C', 0);
        $pdf->Cell(55, 5, utf8_decode('D/P/I AJUSTE SUBSIDIO CAUSADO'), 0, 0, 'C', 0);
        $pdf->Cell(66, 5, utf8_decode("$" . number_format($row['tot8653'], 2, ".", ",")), 0, 0, 'R', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    }

    /*DEDUCCIONES**********************************************/
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
