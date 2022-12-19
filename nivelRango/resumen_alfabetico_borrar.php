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
$consulta = "SELECT DetNomina.CvePersonal,DetNomina.Clave,DetNomina.Importe,DetNomina.Del,DetNomina.Al,EmpGral.RFC,CONCAT(EmpGral.Nombre,' ',EmpGral.Paterno,' ',EmpGral.Materno) AS Nombre,EmpCont.CtaBanco,SUBSTR(catbanco.NomBanco,1,4)AS NomBanco,EmpGral.CURP,EmpCont.Dirgral,EmpCont.HrsMen,EmpGral.CveISSEMyM,EmpCont.UnidadRespon,EmpCont.CodCategoria,catcatego.Descripcion,catcatego.DescCorta
FROM DetNomina
INNER JOIN PerDedApo ON DetNomina.Clave = PerDedApo.Clave
INNER JOIN EmpGral ON DetNomina.CvePersonal=EmpGral.CvePersonal
INNER JOIN EmpCont ON DetNomina.CvePersonal = EmpCont.CvePersonal
INNER JOIN catbanco ON SUBSTR(EmpCont.CtaBanco, 1, 3) = catbanco.CveBanco
INNER JOIN catcatego ON EmpCont.CodCategoria = catcatego.CveCategoria
WHERE DetNomina.Clave IN (SELECT PerDedApo.Clave WHERE PerDedApo.TipoPDA=0) AND
EmpCont.CveContrato LIKE '%DEPOR%' AND DetNomina.CveNomina='$CveNomina' ORDER BY DetNomina.CvePersonal";
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
    //$pdf->Cell(2, 5, utf8_decode($row['cveeventuales']), 0, 0, 'C', 0);
    $pdf->Cell(55, 5, utf8_decode('SUELDOS EVENTUALES'), 0, 0, 'C', 0);
    $pdf->Cell(1, 5, utf8_decode('(' . $row['HrsMen'] . ')'), 0, 0, 'C', 0);
    $pdf->Cell(1, 5, utf8_decode(' ' . ' ' . ' ' . $row['DescCorta'] . ''), 0, 1, 'L', 0);


    
    $pdf->Cell(10, 5, utf8_decode(' ' . ' ' . ' ' . $row['Clave'] . ''), 0, 0, 'L', 0);
    $pdf->Cell(1, 5, utf8_decode(' ' . ' ' . ' ' . $row['Importe'] . ''), 0, 0, 'L', 0);

    //$pdf->Cell(65, 5, utf8_decode("$" . number_format($row['toteventuales'], 2, ".", ",")), 0, 0, 'R', 0);
    $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);

  

    //$pdf->Cell(122, 5, utf8_decode("$" . number_format($row['totpercepciones'], 2, ".", ",")), 0, 0, 'R', 0);
    //$pdf->Cell(138, 5, utf8_decode("$" . number_format($row['totdeducciones'], 2, ".", ",")), 0, 0, 'R', 0);
    $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);
    //$pdf->Cell(260, 5, utf8_decode("$" . number_format($row['sueldobruto'], 2, ".", ",")), 0, 0, 'R', 0);

    $pdf->Cell(1, 6, utf8_decode(''), 0, 1, 'L', 0);
    // $pdf->Cell(10, 5, utf8_decode(''), 0, 1, 'L', 0);

    //Sumas finales de deporte
    //$totalEmp = $row['totempleados'];
    //$totalPercepciones = $totalPercepciones + $row['totpercepciones'];
    //$totalDeducciones = $totalDeducciones + $row['totdeducciones'];
    //Sumas finales de nómina
    $a = $a + 1;
   // $defPercepciones = $defPercepciones + $row['totpercepciones'];
    //$defDeducciones = $defDeducciones + $row['totdeducciones'];
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

//Inidicando la salida del archivo como PDF en pantalla
$pdf->Output();
