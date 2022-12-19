<?php
//http://localhost/nomina/pruebapdf.php
require('fpdf/force_justify.php');

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Helvetica', '', 10);
$pdf->SetFillColor(255, 255, 255);
// Print 2 Cells

//FJ Forzar el centrado



//Cell(Izquierda,Padding a los alrededeores)        //Margenes   Salto de linea   Alineacion


//IMAGEN
$pdf->Image('img/reportes/gob.jpg', 10, 2, 35);
$pdf->Ln();
$pdf->MultiCell(190, 3, "
COMPROBANTE DE PERCEPCIONES Y DEDUCCIONES            Recibo: 317860", 1, 'C', 1);






//Encabezados de CLAVE CONCEPTO IMPORTE
$pdf->SetFont('Helvetica', 'B', 6);
$y = $pdf->GetY();
$pdf->Cell(190, 3, "", 0, 'C', 0);



// Dos celdas para percepciones y deducciones
$y = $pdf->GetY();
$pdf->MultiCell(95, 3, "
Nombre: ALVAREZ FABELA MARTIN LEONARDO
CURP: AAFM751201HMCLBR09 
Puesto: CONT.TIEMPO INDETERMINADO
Dependencia: SECRETARIA DE CULTURA
Unidad Admva: DIR GRAL DE CULTURA FISICA Y DEPORTE
", 0, 'L', 1);
$pdf->SetXY(105, $y);
$pdf->MultiCell(95, 3, "
RFC.: AAFM751201KK8
Clave de ISSEMYM: 00620219 
Fecha de pago: 31/10/2022 
Periodo de pago: 16/10/2022 al 31/10/2022
Codigo de Unidad Administrativa: 22600005L 
Total neto: 1,520.69
", 0, 'L', 1);



// Dos celdas para Total percepciones y total deducciones  
$pdf->SetFont('Helvetica', 'B', 6);
$y = $pdf->GetY();
$pdf->MultiCell(95, 3, "
PERCEPCIONES
", 1, 'C', 1);
$pdf->SetXY(105, $y);
$pdf->MultiCell(95, 3, "
DEDUCCIONES
", 1, 'C', 1);


//Encabezados de CLAVE CONCEPTO IMPORTE
$pdf->SetFont('Helvetica', 'B', 6);
$y = $pdf->GetY();
$pdf->MultiCell(95, 2, "
CLAVE CONCEPTO IMPORTE
", 1, 'FJ', 1);
$pdf->SetXY(105, $y);
$pdf->MultiCell(95, 2, "
CLAVE CONCEPTO IMPORTE
", 1, 'FJ', 1);


//CONSULTA SQL DE PERCEPCIONDES Y DEDUCCIONES
$pdf->SetFont('Helvetica', '', 6);
$y = $pdf->GetY();
$pdf->MultiCell(95, 3, "
0202 SUELDOS EVENTUALES (7) 1,740.00
", 1, 'L', 1);
$pdf->SetXY(105, $y);
$pdf->MultiCell(95, 3, "
5540 SERVICIOS DE SALUD 4.625% 121.59
", 1, 'L', 1);


//TOTALES DE PERCEPCIONES Y DEDUCCIONES
$pdf->SetFont('Helvetica', '', 6);
$y = $pdf->GetY();
$pdf->MultiCell(95, 2, "
Total de percepciones: 1,839.46
", 1, 'C', 1);
$pdf->SetXY(105, $y);
$pdf->MultiCell(95, 2, "
Total de deducciones: 318.77
", 1, 'C', 1);


//Anotaciones finales 
$pdf->SetFont('Helvetica', '', 8);
$pdf->Ln();
$pdf->MultiCell(190, 4, '
SE REALIZO EL ABONO EN LA CUENTA Num: 3801816365        EL DIA 31/10/2022
CONSTITUYE EL RECIBO DE PAGO CORRESPONDIENTE. ', 1, 0, 'C', 1);


//CUADRO VACIO
$pdf->SetFont('Helvetica', '', 8);
$pdf->Ln();
$pdf->MultiCell(190, 4, '



', 1, 1, 'C', 1);


$pdf->Output();
