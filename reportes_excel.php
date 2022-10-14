<?php
$CveNomina = $_POST['CveNomina'];
$FecPag = $_POST['FecPag'];
$num = 1;
$a = 0;

//Bibliotecas para reporte de Excel
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;

/********************Timbrado Maestro********************************** */
//Abriendo el archivo en modo de escritura
//$file = fopen("C:/Users/%USERNAME%/Desktop/MAESTRO.txt", "w");
$file = fopen("archivos/timbrados/TimMaestro.txt", "w");


//Solicitando la conexion con la BD
require 'conexion.php';
require 'vendor/autoload.php';

//Llamando al procedimiento que inserta los campos en tmptimmaes
$consulta = "CALL sp_GeneraTmpTimMaes('$CveNomina')";

//Insertando la fecha real de pago en tmptimmaes
$consulta2 = "UPDATE tmptimmaes SET FecPag='$FecPag'";

//Haciendo la consulta a tmptimmaes
$consulta3 = "SELECT * FROM tmptimmaes";

//Almaceando el resultado de las consultas en una variable (Por cada Query que se efectua)
//Llamada al procedimiento almacenado
$resultado = $mysqli->query($consulta);
//Insercion de fecha real de pago en la tabla tmptimmaes
$resultado2 = $mysqli->query($consulta2);
//Seleccionar todos los datos de tmptimmaes
$resultado3 = $mysqli->query($consulta3);

//Ciclo para lectura y escritura del archivo
while ($row = $resultado3->fetch_assoc()) {
  //Numero consecutivo
  //Ancho alto,borde,salto de linea justificacion relleno
  fwrite($file, $row['CveEmp'] . '|');
  fwrite($file, $row['Rfc'] . '|');
  fwrite($file, $row['NomEmp'] . '|');
  fwrite($file, $row['CveUniAds'] . '|');
  fwrite($file, $row['CodCat'] . '|');
  fwrite($file, $row['TipNom'] . '|');
  fwrite($file, $row['CveIsse'] . '|');
  fwrite($file, $row['Curp'] . '|');
  fwrite($file, $row['NumCon'] . '|');
  fwrite($file, $row['TotPer'] . '|');
  fwrite($file, $row['TotDed'] . '|');
  fwrite($file, $row['TotNet'] . '|');
  fwrite($file, ' ' . $row['TotDes'] . '|');
  fwrite($file, $row['Qna'] . '|');
  fwrite($file, $row['FecPag'] . '|'); //No la esta mandando
  fwrite($file, $row['Fecini'] . '|');
  fwrite($file, $row['FecFin'] . '|');
  fwrite($file, $row['NumChe'] . '|');
  fwrite($file, $row['CveOrg'] . '|');
  fwrite($file, $row['OriRec'] . '|');
  fwrite($file, $row['CveBan'] . '|');
  fwrite($file, $row['Cuenta'] . '|');
  fwrite($file, $row['FecIniCon'] . '|');

  //Obteniendo fechas para calcular la antiguedad
  $fecha1 = $row['FecIniCon'];
  $fecha2 = $row['FecFin'];
  //Almacenando fechas en variables 
  $fechainicial = new DateTime($fecha1);
  $fechafin = new DateTime($fecha2);
  //Obteniendo la diferencia y almacenando en variables los datos
  $diferencia = $fechainicial->diff($fechafin);
  $anio = $diferencia->format('%y');
  $mes = $diferencia->format('%m');
  $dia = $diferencia->format('%d');

  //Definiendo un array para imprimir los datos
  $array =  array();
  $array[0] = 'P';

  //Eliminando de la impresion los datos que sean 0
  if ($anio > 0) {
    $array[1] = $anio . "Y";
  } else {
    $array[1] = '';
  }

  if ($mes > 0) {
    $array[2] = $mes . "M";;
  } else {
    $array[2] = '';
  }

  if ($dia > 0) {
    $array[3] = ($dia + 1) . "D";
  } else {
    $array[3] = '';
  }
  //Imprimiendo las posiciones de los arreglos
  fwrite($file, $array[0]);
  fwrite($file, $array[1]);
  fwrite($file, $array[2]);
  fwrite($file, $array[3] . '|');

  //Se continua con la impresion de la BD
  fwrite($file, $row['Riesgo'] . '|');
  fwrite($file, $row['SalDiaInt'] . '|');
  fwrite($file, $row['TipCont'] . '|');
  //Eliminando el .00 de los resultados que sean igual a 0
  if ($row['Subent'] > 0) {
    fwrite($file, $row['Subent'] . '|');
  } else {
    fwrite($file, '0' . '|');
  }

  if ($row['SubCau'] > 0) {
    fwrite($file, $row['SubCau'] . '|');
  } else {
    fwrite($file, '0' . '|');
  }

  if ($row['AjusSub'] > 0) {
    fwrite($file, $row['AjusSub']);
  } else {
    fwrite($file, '0');
  }

  fwrite($file, '' . PHP_EOL);
}

//Cerrando el archivo
fclose($file);


/********************Timbrado Detalle********************************** */
//Llamando al procedimiento que inserta los campos en tmptidet
$consulta4 = "CALL sp_GeneraTmpTimDet('$CveNomina')";
//Haciendo la consulta a tmptimmaes
$consulta5 = "SELECT * FROM tmptimdet";
//Update a NumCon
$consulta6 = "SELECT CveEmp as clave,COUNT(*) AS cuenta FROM tmptimdet GROUP BY CveEmp";

//Ejecutando llamada al prcedimiento almacenado
$resultado4 = $mysqli->query($consulta4);
//Ejecutando consulta a tmptimmaes
$resultado5 = $mysqli->query($consulta5);
//jecutando update a NumCon
$resultado6 = $mysqli->query($consulta6);

while ($row = $resultado6->fetch_assoc()) {

  for ($i = 0; $i <= $row['cuenta']; $i++) {
    //Llamando al procedimiento que inserta los campos en tmptidet
    $clave = $row['clave'];
    $consulta7 = "UPDATE tmptimdet SET NumCon=$num WHERE CveEmp=$clave";

    //jecutando update a NumCon
    $resultado7 = $mysqli->query($consulta7);
  }
  $num = $num + 1;
}

//Ejecutando consulta a tmptimmaes
$resultado5 = $mysqli->query($consulta5);

//Abriendo el archivo detalle
$file2 = fopen("archivos/timbrados/TimDetalle.txt", "w");

while ($row = $resultado5->fetch_assoc()) {
  fwrite($file2, $row['NumCon'] . '|');
  fwrite($file2, $row['CveEmp'] . '|');
  fwrite($file2, ltrim($row['CvePerDed'], "0") . '|');
  fwrite($file2, $row['Monto'] . '|');
  fwrite($file2, $row['Descri'] . '|');
  fwrite($file2, $row['Cvesat'] . '|');
  fwrite($file2, $row['Qna'] . '|');
  fwrite($file2, $row['CveOrg']);
  fwrite($file2, '' . PHP_EOL);
}
fclose($file2);





$encabezados = [
  'borders' => array(
    'vertical' => array(
      'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
      'color' => array('argb' => 'FFFFFF'),
    ),
  ),

  'font' => [
    'color' => [
      'rgb' => 'FFFFFF'
    ],
    'bold' => true,
    'size' => 11
  ],
  'fill' => [
    'fillType' => Fill::FILL_SOLID,
    'startColor' => [
      'rgb' => '538ED5'
    ]
  ]

];


$bordesH = [
  'borders' => array(
    'vertical' => array(
      'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
      'color' => array('argb' => '000000'),
    ),
  ),
];

$bordesV = [
  'borders' => array(
    'outline' => array(
      'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DASHED,
      'color' => array('argb' => '000000'),
    ),
  ),
];

//Efectuando la consulta SWL
$consulta8 = "CALL sp_GeneraTmpSerPub('$CveNomina')";
$resultado8 = $mysqli->query($consulta8);

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
  ->getStartColor()->setARGB('3678C7');

$excel->getActiveSheet()->getStyle('B:L')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_00);


//Alineacion
//$excel->getActiveSheet() ->getStyle('A:L')->setQuotePrefix(true);
$excel->getActiveSheet()->getStyle('A1:L1')->getAlignment()->setWrapText(true)->setVertical(true);

$excel->getActiveSheet()->getStyle('A2:L2')
  ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

$excel->getActiveSheet()->getStyle('A1:L1')->ApplyFromArray($encabezados);
//$excel->getActiveSheet()->getStyle('A4:L4')->ApplyFromArray($bordes);

$hojaActiva->getColumnDimension('A')->setWidth(10); //Anchura de la celda
$hojaActiva->setCellValue('A1', 'PERIODO'); //Titulo de la columna
$hojaActiva->getColumnDimension('B')->setWidth(19);
$hojaActiva->setCellValue('B1', 'CLAVE UNIDAD ADMINISTRATIVA');
$hojaActiva->getColumnDimension('C')->setWidth(15);
$hojaActiva->setCellValue('C1', 'CLAVE SERVIDOR PUBLICO');
$hojaActiva->getColumnDimension('D')->setWidth(50);
$hojaActiva->setCellValue('D1', 'NOMBRE');
$hojaActiva->getColumnDimension('E')->setWidth(20);
$hojaActiva->setCellValue('E1', 'RFC');
$hojaActiva->getColumnDimension('F')->setWidth(13);
$hojaActiva->setCellValue('F1', '0202 SUELDOS EVENTUALES');
$hojaActiva->getColumnDimension('G')->setWidth(11);
$hojaActiva->setCellValue('G1', '0325 SUBSIDIO AL EMPLEO');
$hojaActiva->getColumnDimension('H')->setWidth(12);
$hojaActiva->setCellValue('H1', '5408 IMPUESTO SOBRE LA RENTA');
$hojaActiva->getColumnDimension('I')->setWidth(12);
$hojaActiva->setCellValue('I1', '5540 SERVICIOS DE SALUD');
$hojaActiva->getColumnDimension('J')->setWidth(11);
$hojaActiva->setCellValue('J1', '5541 SIS. SOLIDARIO DE REPARTO');
$hojaActiva->getColumnDimension('K')->setWidth(13);
$hojaActiva->setCellValue('K1', '5542 SIS. CAPITAL. INDIVIDUAL');
$hojaActiva->getColumnDimension('L')->setWidth(10);
$hojaActiva->setCellValue('L1', 'TOTAL NETO');

//Indicar que se comience desde la fila 2 de Excel y no reescribir los encanezados
$fila = 2;
$borde = 1;

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
  //Incrementando las filas en 1 para que se inserten apropiadamente
  $fila++;
  //Incrementando las filas en 1 para que el borde se pinte segun el numero de columnas
  $borde++;
  //Indicando las celdas en las que se generara el borde vertical
  $excel->getActiveSheet()->getStyle("A$borde:M$borde")->ApplyFromArray($bordesH);
  $excel->getActiveSheet()->getStyle("A$borde:L$borde")->ApplyFromArray($bordesV);
}

//Creando el archivo de excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="SerPub' . $CveNomina . '.xlsx"');
header('Cache-Control: max-age=0');

//Indicando la salida del documento en xlsx 
$writer = IOFactory::createWriter($excel, 'Xlsx');
$writer->save('php://output');
exit;


