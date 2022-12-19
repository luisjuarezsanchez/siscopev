<?php
require('fpdf/html_table.php');

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Helvetica', '', 12);

$html = '
<table border="1">
<tr>
    <td width="200"></td><td width="200"><img src="img/iconos/escudo_comprobantes.png" width="180" height="80"></td>
</tr>

<tr>
<td></td>
</tr>
<tr>
<td></td>
</tr>
<tr>
<td></td>
</tr>
<tr>
<td></td>
</tr>
<tr>
<td></td>
</tr>

<tr>
<td  width="750">                  COMPROBANTE DE PERCEPCIONES Y DEDUCCIONES    RECIBO: 317860</td>
</tr>

<tr>
<td  width="750">
Nombre: ALVAREZ FABELA MARTIN LEONARDO <br>
CURP: AAFM751201HMCLBR09

</td>
</tr>
</table>


';
//


$pdf->WriteHTML($html);
$pdf->Output();
