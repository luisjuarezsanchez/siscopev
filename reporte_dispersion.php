<?php
$CveNomina = $_POST['CveNomina'];
$num = 1;
$a = 0;

//Bibliotecas para reporte de Excel
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;

//Solicitando la conexion con la BD y Biblioteca
require 'conexion.php';
require 'vendor/autoload.php';


//Estilos de la hoja de Excel
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



//Efectuando la consulta para Dispersion de Deporte
$consulta = "SELECT SUBSTR(EmpCont.CtaBanco,1,3)AS CveBanco,catbanco.NomBanco,SUBSTR(EmpCont.CtaBanco,8,10) AS Cuenta,EmpCont.CtaBanco AS Clabe,CONCAT(EmpGral.Nombre,' ',EmpGral.Paterno,' ',EmpGral.Materno) AS Nombre
,SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=0) THEN Importe ELSE 0 END) AS TotPer,
SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=1) THEN Importe ELSE 0 END) AS TotDed,
SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=0) THEN Importe ELSE 0 END) - SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=1) THEN Importe ELSE 0 END) AS TotNeto,
DetNomina.CvePersonal,EmpGral.RFC,EmpGral.CURP,CONCAT('QUINCENA',' ',SUBSTR(DetNomina.CveNomina,5,2)) AS Quincena
FROM DetNomina
INNER JOIN EmpCont ON DetNomina.CvePersonal = EmpCont.CvePersonal
INNER JOIN EmpGral ON DetNomina.CvePersonal = EmpGral.CvePersonal
INNER JOIN PerDedApo ON DetNomina.Clave = PerDedApo.Clave
INNER JOIN catbanco ON SUBSTR(EmpCont.CtaBanco, 1, 3) = catbanco.CveBanco
WHERE DetNomina.CvePersonal AND DetNomina.CvePersonal IN (354,16,541,15,7,588,1,17,18) AND DetNomina.CveNomina='$CveNomina'
GROUP BY DetNomina.CvePersonal";
$resultado = $mysqli->query($consulta);

//Efectuando la consulta para Dispersion de COMEM
$consulta2 = "SELECT SUBSTR(EmpCont.CtaBanco,1,3)AS CveBanco,catbanco.NomBanco,SUBSTR(EmpCont.CtaBanco,8,10) AS Cuenta,EmpCont.CtaBanco AS Clabe,CONCAT(EmpGral.Nombre,' ',EmpGral.Paterno,' ',EmpGral.Materno) AS Nombre
,SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=0) THEN Importe ELSE 0 END) AS TotPer,
SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=1) THEN Importe ELSE 0 END) AS TotDed,
SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=0) THEN Importe ELSE 0 END) - SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=1) THEN Importe ELSE 0 END) AS TotNeto,
DetNomina.CvePersonal,EmpGral.RFC,EmpGral.CURP,CONCAT('QUINCENA',' ',SUBSTR(DetNomina.CveNomina,5,2)) AS Quincena
FROM DetNomina
INNER JOIN EmpCont ON DetNomina.CveEmpCont = EmpCont.CveEmpCont
INNER JOIN EmpGral ON DetNomina.CvePersonal = EmpGral.CvePersonal
INNER JOIN PerDedApo ON DetNomina.Clave = PerDedApo.Clave
INNER JOIN catbanco ON SUBSTR(EmpCont.CtaBanco, 1, 3) = catbanco.CveBanco
WHERE CveContrato LIKE '%COMEM%' AND DetNomina.CvePersonal 
AND DetNomina.CveNomina='$CveNomina' 
GROUP BY DetNomina.CvePersonal";
$resultado2 = $mysqli->query($consulta2);

//Efectuando la consulta para Dispersion de PATRI
$consulta3 = "SELECT SUBSTR(EmpCont.CtaBanco,1,3)AS CveBanco,catbanco.NomBanco,SUBSTR(EmpCont.CtaBanco,8,10) AS Cuenta,EmpCont.CtaBanco AS Clabe,CONCAT(EmpGral.Nombre,' ',EmpGral.Paterno,' ',EmpGral.Materno) AS Nombre
,SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=0) THEN Importe ELSE 0 END) AS TotPer,
SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=1) THEN Importe ELSE 0 END) AS TotDed,
SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=0) THEN Importe ELSE 0 END) - SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=1) THEN Importe ELSE 0 END) AS TotNeto,
DetNomina.CvePersonal,EmpGral.RFC,EmpGral.CURP,CONCAT('QUINCENA',' ',SUBSTR(DetNomina.CveNomina,5,2)) AS Quincena
FROM DetNomina
INNER JOIN EmpCont ON DetNomina.CveEmpCont = EmpCont.CveEmpCont
INNER JOIN EmpGral ON DetNomina.CvePersonal = EmpGral.CvePersonal
INNER JOIN PerDedApo ON DetNomina.Clave = PerDedApo.Clave
INNER JOIN catbanco ON SUBSTR(EmpCont.CtaBanco, 1, 3) = catbanco.CveBanco
WHERE CveContrato LIKE '%PATRI%' AND DetNomina.CvePersonal 
AND DetNomina.CveNomina='$CveNomina' 
GROUP BY DetNomina.CvePersonal";
$resultado3 = $mysqli->query($consulta3);

//Creando hoja de Excel
$excel = new Spreadsheet();
//Asignando la hoja activa
$hojaActiva = $excel->getActiveSheet();
//Titulo de la hoja
$hojaActiva->setTitle("Dispersion");

//Asignando color a las celdas en un rango
/*$excel->getActiveSheet()->getStyle('A1:L1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
->getStartColor()->setARGB('3678C7');*/

$excel->getActiveSheet()->getStyle('G:I')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_00);


//Alineacion
$excel->getActiveSheet()->getStyle('A:M')->setQuotePrefix(true);
$excel->getActiveSheet()->getStyle('A4:M4')->getAlignment()->setWrapText(true)->setVertical(true);

$excel->getActiveSheet()->getStyle('A:M')
    ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

//$excel->getActiveSheet()->getStyle('A1:L1')->ApplyFromArray($encabezados);
//$excel->getActiveSheet()->getStyle('A4:L4')->ApplyFromArray($bordes);

$hojaActiva->getColumnDimension('A')->setWidth(10); //Anchura de la celda
$hojaActiva->setCellValue('A1', 'SECRETARÍA DE CULTURA Y TURISMO'); //Titulo de la columna
$hojaActiva->setCellValue('A2', 'QUINCENA $Variable');
$hojaActiva->getColumnDimension('B')->setWidth(10);
$hojaActiva->setCellValue('B4', 'CLAVE DEL BANCO');
$hojaActiva->setCellValue('A6', 'DIRECCION GENERAL DE CULTURA FISICA Y DEPORTE');
$hojaActiva->getColumnDimension('C')->setWidth(20);
$hojaActiva->setCellValue('C4', 'NOMBRE DEL BANCO');
$hojaActiva->getColumnDimension('D')->setWidth(16);
$hojaActiva->setCellValue('D4', 'CUENTA');
$hojaActiva->getColumnDimension('E')->setWidth(20);
$hojaActiva->setCellValue('E4', 'CUENTA CLABE');
$hojaActiva->getColumnDimension('F')->setWidth(40);
$hojaActiva->setCellValue('F4', 'NOMBRE');
$hojaActiva->getColumnDimension('G')->setWidth(10);
$hojaActiva->setCellValue('G4', 'TOTAL PERCEPCIONES');
$hojaActiva->getColumnDimension('H')->setWidth(10);
$hojaActiva->setCellValue('H4', 'TOTAL DEDUCCIONES');
$hojaActiva->getColumnDimension('I')->setWidth(10);
$hojaActiva->setCellValue('I4', 'TOTAL NETO');
$hojaActiva->getColumnDimension('J')->setWidth(10);
$hojaActiva->setCellValue('J4', 'CLAVE EMPLEADO');
$hojaActiva->getColumnDimension('K')->setWidth(15);
$hojaActiva->setCellValue('K4', 'RFC');
$hojaActiva->getColumnDimension('L')->setWidth(18);
$hojaActiva->setCellValue('L4', 'CURP');
$hojaActiva->getColumnDimension('M')->setWidth(10);
$hojaActiva->setCellValue('M4', 'QUINCENA');



//Indicar que se comience desde la fila 2 de Excel y no reescribir los encanezados
$fila = 8;
$borde = 1;
$contadorservidor = 0;

//Ciclo para leer el contenido de la consulta
while ($rows = $resultado->fetch_assoc()) {
    $contadorservidor = $contadorservidor + 1;

    //Extrayendo campos de la BD y especificando la columna donde se mostrara el contenido
    $hojaActiva->setCellValue('A' . $fila, $contadorservidor);
    $hojaActiva->setCellValue('B' . $fila, $rows['CveBanco']);
    $hojaActiva->setCellValue('C' . $fila, $rows['NomBanco']);
    $hojaActiva->setCellValue('D' . $fila, $rows['Cuenta']);
    $hojaActiva->setCellValue('E' . $fila, $rows['Clabe']);
    $hojaActiva->setCellValue('F' . $fila, $rows['Nombre']);
    $hojaActiva->setCellValue('G' . $fila, $rows['TotPer']);
    $hojaActiva->setCellValue('H' . $fila, $rows['TotDed']);
    $hojaActiva->setCellValue('I' . $fila, $rows['TotNeto']);
    $hojaActiva->setCellValue('J' . $fila, $rows['CvePersonal']);
    $hojaActiva->setCellValue('K' . $fila, $rows['RFC']);
    $hojaActiva->setCellValue('L' . $fila, $rows['CURP']);
    $hojaActiva->setCellValue('M' . $fila, $rows['Quincena']);
    //Incrementando las filas en 1 para que se inserten apropiadamente
    $fila++;
    //Incrementando las filas en 1 para que el borde se pinte segun el numero de columnas
    $borde++;
    //Indicando las celdas en las que se generara el borde vertical
    //$excel->getActiveSheet()->getStyle("A$borde:M$borde")->ApplyFromArray($bordesH);
    //$excel->getActiveSheet()->getStyle("A$borde:L$borde")->ApplyFromArray($bordesV);
}

$fila++;
$fila++;
$fila++;
$contadorservidor = 0;
$hojaActiva->setCellValue('A' . $fila, 'DIRECCION GENERAL DEL CONSERVATORIO DE MUSICA DEL ESTADO DE MEXICO');
$fila++;
$fila++;

while ($rows = $resultado2->fetch_assoc()) {
    $contadorservidor = $contadorservidor + 1;
    //Extrayendo campos de la BD y especificando la columna donde se mostrara el contenido
    $hojaActiva->setCellValue('A' . $fila, $contadorservidor);
    $hojaActiva->setCellValue('B' . $fila, $rows['CveBanco']);
    $hojaActiva->setCellValue('C' . $fila, $rows['NomBanco']);
    $hojaActiva->setCellValue('D' . $fila, $rows['Cuenta']);
    $hojaActiva->setCellValue('E' . $fila, $rows['Clabe']);
    $hojaActiva->setCellValue('F' . $fila, $rows['Nombre']);
    $hojaActiva->setCellValue('G' . $fila, $rows['TotPer']);
    $hojaActiva->setCellValue('H' . $fila, $rows['TotDed']);
    $hojaActiva->setCellValue('I' . $fila, $rows['TotNeto']);
    $hojaActiva->setCellValue('J' . $fila, $rows['CvePersonal']);
    $hojaActiva->setCellValue('K' . $fila, $rows['RFC']);
    $hojaActiva->setCellValue('L' . $fila, $rows['CURP']);
    $hojaActiva->setCellValue('M' . $fila, $rows['Quincena']);
    //Incrementando las filas en 1 para que se inserten apropiadamente
    $fila++;
    //Incrementando las filas en 1 para que el borde se pinte segun el numero de columnas
    $borde++;
    //Indicando las celdas en las que se generara el borde vertical
    //$excel->getActiveSheet()->getStyle("A$borde:M$borde")->ApplyFromArray($bordesH);
    //$excel->getActiveSheet()->getStyle("A$borde:L$borde")->ApplyFromArray($bordesV);
}

$fila++;
$fila++;
$fila++;
$contadorservidor = 0;
$hojaActiva->setCellValue('A' . $fila, 'DIRECCION GENERAL DE PATRIMONIO Y SERVICIOS CULTURALES');
$fila++;
$fila++;
while ($rows = $resultado3->fetch_assoc()) {
    $contadorservidor = $contadorservidor + 1;
    //Extrayendo campos de la BD y especificando la columna donde se mostrara el contenido
    $hojaActiva->setCellValue('A' . $fila, $contadorservidor);
    $hojaActiva->setCellValue('B' . $fila, $rows['CveBanco']);
    $hojaActiva->setCellValue('C' . $fila, $rows['NomBanco']);
    $hojaActiva->setCellValue('D' . $fila, $rows['Cuenta']);
    $hojaActiva->setCellValue('E' . $fila, $rows['Clabe']);
    $hojaActiva->setCellValue('F' . $fila, $rows['Nombre']);
    $hojaActiva->setCellValue('G' . $fila, $rows['TotPer']);
    $hojaActiva->setCellValue('H' . $fila, $rows['TotDed']);
    $hojaActiva->setCellValue('I' . $fila, $rows['TotNeto']);
    $hojaActiva->setCellValue('J' . $fila, $rows['CvePersonal']);
    $hojaActiva->setCellValue('K' . $fila, $rows['RFC']);
    $hojaActiva->setCellValue('L' . $fila, $rows['CURP']);
    $hojaActiva->setCellValue('M' . $fila, $rows['Quincena']);
    //Incrementando las filas en 1 para que se inserten apropiadamente
    $fila++;
    //Incrementando las filas en 1 para que el borde se pinte segun el numero de columnas
    $borde++;
    //Indicando las celdas en las que se generara el borde vertical
    //$excel->getActiveSheet()->getStyle("A$borde:M$borde")->ApplyFromArray($bordesH);
    //$excel->getActiveSheet()->getStyle("A$borde:L$borde")->ApplyFromArray($bordesV);
}
//Creando el archivo de excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Dispersion' . $CveNomina . '.xlsx"');
header('Cache-Control: max-age=0');

//Indicando la salida del documento en xlsx 
$writer = IOFactory::createWriter($excel, 'Xlsx');
$writer->save('php://output');
exit;
