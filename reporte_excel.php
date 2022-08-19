<?php
require 'conexion.php';
require 'vendor/autoload.php';
//Solicitando el objeto a la Biblioteca
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\PHPExcel_Style_NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$TableHead = [
  'font'=>[
    'color'=>[
      'rgb'=>'FFFFFF'
    ],
    'bold=' => true,
    'size'=>11
  ],
  'fill'=>[
    'fillType' => Fill::FILL_SOLID,
      'startColor'=>[
        'rgb' => '538ED5'
            ]  
         ]
      ];

//Efectuando la consulta SWL
$consulta7 = "SELECT * FROM tmpSerPub";
$resultado7 = $mysqli->query($consulta7);

//Creando hoja de Excel
$excel = new Spreadsheet();
//Asignando la hoja activa
$hojaActiva = $excel->getActiveSheet();
//Titulo de la hoja
$hojaActiva->setTitle("Empleados");

//Asignando color a las celdas en un rango
$excel->getActiveSheet()->getStyle('A1:L1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
->getStartColor()->setARGB('4472C4');

$excel->getActiveSheet()->getStyle('B:L')->getNumberFormat()->
setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_00);


//Alineacion
//$excel->getActiveSheet() ->getStyle('A:L')->setQuotePrefix(true);
$excel->getActiveSheet()->getStyle('A1:L1')->getAlignment()->setWrapText(true);

$excel->getActiveSheet()->getStyle('A2:L2')
    ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

  $excel->getActiveSheet()->getStyle('A1:L1')->ApplyFromArray($TableHead);









//$excel->getActiveSheet()->getStyle('A2:L2')
  //  ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);








$hojaActiva->getColumnDimension('A')->setWidth(10);//Anchura de la celda
$hojaActiva->setCellValue('A1', 'PERIODO');//Titulo de la columna
$hojaActiva->getColumnDimension('B')->setWidth(19);
$hojaActiva->setCellValue('B1', 'CLAVE UNIDAD ADMINISTRATIVA');
$hojaActiva->getColumnDimension('C')->setWidth(15);
$hojaActiva->setCellValue('C1', 'CLAVE SERVIDOR PUBLICO');
$hojaActiva->getColumnDimension('D')->setWidth(50);
$hojaActiva->setCellValue('D1', 'NOMBRE');
$hojaActiva->getColumnDimension('E')->setWidth(20);
$hojaActiva->setCellValue('E1', 'RFC');
$hojaActiva->getColumnDimension('F')->setWidth(10);
$hojaActiva->setCellValue('F1', '0202 SUELDOS EVENTUALES');
$hojaActiva->getColumnDimension('G')->setWidth(10);
$hojaActiva->setCellValue('G1', '0325 SUBSIDIO AL EMPLEO');
$hojaActiva->getColumnDimension('H')->setWidth(10);
$hojaActiva->setCellValue('H1', '5408 IMPUESTO SOBRE LA RENTA');
$hojaActiva->getColumnDimension('I')->setWidth(10);
$hojaActiva->setCellValue('I1', '5540 SERVICIOS DE SALUD');
$hojaActiva->getColumnDimension('J')->setWidth(10);
$hojaActiva->setCellValue('J1', '5541 SIS. SOLIDARIO DE REPARTO');
$hojaActiva->getColumnDimension('K')->setWidth(10);
$hojaActiva->setCellValue('K1', '5542 SIS. CAPITAL. INDIVIDUAL');
$hojaActiva->getColumnDimension('L')->setWidth(10);
$hojaActiva->setCellValue('L1', 'TOTAL NETO');

//Indicar que se comience desde la fila 2 de Excel y no reescribir los encanezados
$fila = 2;

//Ciclo para leer el contenido de la consulta
while ($rows = $resultado7->fetch_assoc()) {
    //Extrayendo campos de la BD y especificando la columna donde se mostrara el contenido
    $hojaActiva->setCellValue('A' . $fila, $rows['Periodo']);
    $hojaActiva->setCellValue('B' . $fila, $rows['ClaveUniAds']);
    $hojaActiva->setCellValue('C' . $fila, $rows['CvePersonal']);
    $hojaActiva->setCellValue('D' . $fila, $rows['Nombre']);
    $hojaActiva->setCellValue('E' . $fila, $rows['Rfc']);
    $hojaActiva->setCellValue('F' . $fila, $rows['SuelEven']);
    $hojaActiva->setCellValue('G' . $fila, $rows['SubsEmp']);
    $hojaActiva->setCellValue('H' . $fila, $rows['Isr']);
    $hojaActiva->setCellValue('I' . $fila, $rows['ServSalud']);
    $hojaActiva->setCellValue('J' . $fila, $rows['SisSolRep']);
    $hojaActiva->setCellValue('K' . $fila, $rows['SisCapInd']);
    $hojaActiva->setCellValue('L' . $fila, $rows['TotNet']);
    $fila++;//Incrementando las filas en 1 para que se inserten apropiadamente
}

//Creando el archivo de excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Reporte_Empleados.xlsx"');
header('Cache-Control: max-age=0');

//Indicando la salida del documento en xlsx 
$writer = IOFactory::createWriter($excel, 'Xlsx');
$writer->save('php://output');
exit;