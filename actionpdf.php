<?php
require('fpdf/WriteHTML.php');
//require('fpdf/fpdf.php');

$pdf=new PDF_HTML();

$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(true, 15);

$pdf->AddPage();
//$pdf->Image('logo.png',18,13,33);
$pdf->SetFont('Arial','B',12); 
$htmlTable='
<table>
<tr>
    <th style="border: black 0.5px solid;" colspan="3">COMPROBANTE DE PRECEPCIONES Y DEDUCCIONES RECIBO: 317859</th>

</tr>

<tr>
    <th style="border: black 0.5px solid;" colspan="2">
        Nombre: <br>
        CURP: <br>
        Puesto: <br>
        Dependencia: <br>
        Unidad Admva: <br>
    </th>
    <th colspan="2">
        RFC: <br>
        Clave de ISSEMYM: <br>
        Fecha de pago: <br>
        Periodo de pago: <br>
        Codigo de Unidad Administrativa: <br>
        Total neto: <br>
    </th>
</tr>

<tr>
    <th  colspan="2" colspan="2">Percepciones</th>
    <th  colspan="2" colspan="2">Deducciones</th>
</tr>

<tr>
    <th colspan="2">Clave Concepto Importe</th>
    <th colspan="2">Clave Concepto Importe</th>
</tr>
</table>

';
$pdf->WriteHTML("<br><br><br>$htmlTable");
$pdf->SetFont('Arial','B',6);
$pdf->Output();
