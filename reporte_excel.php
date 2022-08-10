<?php
require 'vendor/autoload.php';
require 'conexion.php';

//Solicitando el objeto a la Biblioteca
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


//Efectuando la consulta SWL
$sql = "SELECT CvePersonal, RFC, Paterno, Materno, Nombre FROM EmpGral";
$resultado = $mysqli->query($sql);

//Creando hoja de Excel
$excel = new Spreadsheet();
//Asignando la hoja activa
$hojaActiva = $excel->getActiveSheet();
//Titulo de la hoja
$hojaActiva->setTitle("Empleados");

//Generando encabezados y asignando tamaños de celda

$excel->getActiveSheet()->getStyle('A1:E1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
->getStartColor()->setARGB('00FF7F');
$hojaActiva->getColumnDimension('A')->setWidth(20);
$hojaActiva->setCellValue('A1', 'CvePersonal');
$hojaActiva->getColumnDimension('B')->setWidth(20);
$hojaActiva->setCellValue('B1', 'RFC');
$hojaActiva->getColumnDimension('C')->setWidth(20);
$hojaActiva->setCellValue('C1', 'Paterno');
$hojaActiva->getColumnDimension('D')->setWidth(20);
$hojaActiva->setCellValue('D1', 'Materno');
$hojaActiva->getColumnDimension('E')->setWidth(25);
$hojaActiva->setCellValue('E1', 'Nombre');

//Indicar que se comience desde la fila 2 de Excel y no reescribir los encanezados
$fila = 2;

//Ciclo para leer el contenido de la consulta
while ($rows = $resultado->fetch_assoc()) {
    $hojaActiva->setCellValue('A' . $fila, $rows['CvePersonal']);
    $hojaActiva->setCellValue('B' . $fila, $rows['RFC']);
    $hojaActiva->setCellValue('C' . $fila, $rows['Paterno']);
    $hojaActiva->setCellValue('D' . $fila, $rows['Materno']);
    $hojaActiva->setCellValue('E' . $fila, $rows['Nombre']);
    $fila++;
}

//Creando el archivo de excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Reporte_Empleados.xlsx"');
header('Cache-Control: max-age=0');

//Indicando la salida del documento en xlsx 
$writer = IOFactory::createWriter($excel, 'Xlsx');
$writer->save('php://output');
exit;
