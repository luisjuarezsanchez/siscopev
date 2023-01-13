<?php
$CveNomina = $_POST['CveNomina'];
$num = 1;
$a = 0;
$totalConservacion = 0;
$totalServicios = 0;
$totalMusica = 0;
$totalRecreativas = 0;

//Bibliotecas para reporte de Excel
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;

//Solicitando la conexion con la BD y Biblioteca
require 'conexion.php';
require '../vendor/autoload.php';


//Estilos de la hoja de Excel
$encabezados = [
    'font' => [
        'bold' => true
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



//Efectuando la consulta para Dispersion de conservacion, restauracion
$consulta = "SELECT 
SUBSTR(EmpCont.CtaBanco,1,3)AS CveBanco,catbanco.NomBanco,SUBSTR(EmpCont.CtaBanco,8,10) AS Cuenta,EmpCont.CtaBanco AS Clabe,CONCAT(EmpGral.Nombre,' ',EmpGral.Paterno,' ',EmpGral.Materno) AS Nombre
,SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=0) THEN Importe ELSE 0 END) AS TotPer,
SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=1) THEN Importe ELSE 0 END) AS TotDed,
SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=0) THEN Importe ELSE 0 END) - SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=1) THEN Importe ELSE 0 END) AS TotNeto,
DetNomina.CvePersonal,EmpGral.RFC,EmpGral.CURP,CONCAT('QUINCENA',' ',SUBSTR(DetNomina.CveNomina,5,2)) AS Quincena
FROM DetNomina
INNER JOIN EmpCont ON DetNomina.CvePersonal = EmpCont.CvePersonal
INNER JOIN EmpGral ON DetNomina.CvePersonal = EmpGral.CvePersonal
INNER JOIN PerDedApo ON DetNomina.Clave = PerDedApo.Clave
INNER JOIN catbanco ON SUBSTR(EmpCont.CtaBanco, 1, 3) = catbanco.CveBanco
WHERE empcont.Dirgral=3 AND empcont.TipoEmpleado=1
GROUP BY DetNomina.CvePersonal";
$resultado = $mysqli->query($consulta);

//Efectuando la consulta para Dispersion servicios culturales
$consulta2 = "SELECT 
SUBSTR(EmpCont.CtaBanco,1,3)AS CveBanco,catbanco.NomBanco,SUBSTR(EmpCont.CtaBanco,8,10) AS Cuenta,EmpCont.CtaBanco AS Clabe,CONCAT(EmpGral.Nombre,' ',EmpGral.Paterno,' ',EmpGral.Materno) AS Nombre
,SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=0) THEN Importe ELSE 0 END) AS TotPer,
SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=1) THEN Importe ELSE 0 END) AS TotDed,
SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=0) THEN Importe ELSE 0 END) - SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=1) THEN Importe ELSE 0 END) AS TotNeto,
DetNomina.CvePersonal,EmpGral.RFC,EmpGral.CURP,CONCAT('QUINCENA',' ',SUBSTR(DetNomina.CveNomina,5,2)) AS Quincena
FROM DetNomina
INNER JOIN EmpCont ON DetNomina.CvePersonal = EmpCont.CvePersonal
INNER JOIN EmpGral ON DetNomina.CvePersonal = EmpGral.CvePersonal
INNER JOIN PerDedApo ON DetNomina.Clave = PerDedApo.Clave
INNER JOIN catbanco ON SUBSTR(EmpCont.CtaBanco, 1, 3) = catbanco.CveBanco
WHERE empcont.Dirgral=4 AND empcont.TipoEmpleado=1
GROUP BY DetNomina.CvePersonal";
$resultado2 = $mysqli->query($consulta2);

//Efectuando la consulta para Dispersion de conservatorio de musica
$consulta3 = "SELECT 
SUBSTR(EmpCont.CtaBanco,1,3)AS CveBanco,catbanco.NomBanco,SUBSTR(EmpCont.CtaBanco,8,10) AS Cuenta,EmpCont.CtaBanco AS Clabe,CONCAT(EmpGral.Nombre,' ',EmpGral.Paterno,' ',EmpGral.Materno) AS Nombre
,SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=0) THEN Importe ELSE 0 END) AS TotPer,
SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=1) THEN Importe ELSE 0 END) AS TotDed,
SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=0) THEN Importe ELSE 0 END) - SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=1) THEN Importe ELSE 0 END) AS TotNeto,
DetNomina.CvePersonal,EmpGral.RFC,EmpGral.CURP,CONCAT('QUINCENA',' ',SUBSTR(DetNomina.CveNomina,5,2)) AS Quincena
FROM DetNomina
INNER JOIN EmpCont ON DetNomina.CvePersonal = EmpCont.CvePersonal
INNER JOIN EmpGral ON DetNomina.CvePersonal = EmpGral.CvePersonal
INNER JOIN PerDedApo ON DetNomina.Clave = PerDedApo.Clave
INNER JOIN catbanco ON SUBSTR(EmpCont.CtaBanco, 1, 3) = catbanco.CveBanco
WHERE empcont.Dirgral=5 AND empcont.TipoEmpleado=1
GROUP BY DetNomina.CvePersonal";
$resultado3 = $mysqli->query($consulta3);

//Efectuando la consulta para Dispersion fomento de las actividades deportivas recreativas
$consulta4 = "SELECT 
SUBSTR(EmpCont.CtaBanco,1,3)AS CveBanco,catbanco.NomBanco,SUBSTR(EmpCont.CtaBanco,8,10) AS Cuenta,EmpCont.CtaBanco AS Clabe,CONCAT(EmpGral.Nombre,' ',EmpGral.Paterno,' ',EmpGral.Materno) AS Nombre
,SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=0) THEN Importe ELSE 0 END) AS TotPer,
SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=1) THEN Importe ELSE 0 END) AS TotDed,
SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=0) THEN Importe ELSE 0 END) - SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=1) THEN Importe ELSE 0 END) AS TotNeto,
DetNomina.CvePersonal,EmpGral.RFC,EmpGral.CURP,CONCAT('QUINCENA',' ',SUBSTR(DetNomina.CveNomina,5,2)) AS Quincena
FROM DetNomina
INNER JOIN EmpCont ON DetNomina.CvePersonal = EmpCont.CvePersonal
INNER JOIN EmpGral ON DetNomina.CvePersonal = EmpGral.CvePersonal
INNER JOIN PerDedApo ON DetNomina.Clave = PerDedApo.Clave
INNER JOIN catbanco ON SUBSTR(EmpCont.CtaBanco, 1, 3) = catbanco.CveBanco
WHERE empcont.Dirgral=6 AND empcont.TipoEmpleado=1
GROUP BY DetNomina.CvePersonal";
$resultado4 = $mysqli->query($consulta4);

//Creando hoja de Excel
$excel = new Spreadsheet();
//Asignando la hoja activa
$hojaActiva = $excel->getActiveSheet();
//Titulo de la hoja
$hojaActiva->setTitle("Dispersion");

//Formato númerico para los totales
$excel->getActiveSheet()->getStyle('G:I')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
//Formato númerico para las claves de banco y no aparezcan como notacion cientifica
$excel->getActiveSheet()->getStyle('D:E')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER);
//Alineando la impresion de los datos a la izquierda
$excel->getActiveSheet()->getStyle('A:M')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
//Formatos de encabezados
$excel->getActiveSheet()->getStyle('A4:M4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
$excel->getActiveSheet()->getStyle('A4:M4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$excel->getActiveSheet()->getStyle('A:M')->setQuotePrefix(true);
$excel->getActiveSheet()->getStyle('A4:M4')->getAlignment()->setWrapText(true)->setVertical(true);
$excel->getActiveSheet()->getStyle('A:M')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
$excel->getActiveSheet()->getStyle('A4:M4')->ApplyFromArray($encabezados);

//Imprimiendo los encabezados de las celdas
$hojaActiva->getColumnDimension('A')->setWidth(10);
$hojaActiva->setCellValue('A1', 'SECRETARÍA DE CULTURA Y TURISMO');
$hojaActiva->setCellValue('A2', 'QUINCENA ' . substr($CveNomina, 0, 4) . '-' . substr($CveNomina, 4, 2));
$hojaActiva->getColumnDimension('B')->setWidth(10);
$hojaActiva->setCellValue('B4', 'CLAVE DEL BANCO');
$hojaActiva->setCellValue('A6', 'CONSERVACION,RESTAURACION Y DIFUSION DEL PATRIMONIO CULTURAL');
$hojaActiva->getColumnDimension('C')->setWidth(20);
$hojaActiva->setCellValue('C4', 'NOMBRE DEL BANCO');
$hojaActiva->getColumnDimension('D')->setWidth(16);
$hojaActiva->setCellValue('D4', 'CUENTA');
$hojaActiva->getColumnDimension('E')->setWidth(20);
$hojaActiva->setCellValue('E4', 'CUENTA CLABE');
$hojaActiva->getColumnDimension('F')->setWidth(40);
$hojaActiva->setCellValue('F4', 'NOMBRE');
$hojaActiva->getColumnDimension('G')->setWidth(15);
$hojaActiva->setCellValue('G4', 'TOTAL PERCEPCIONES');
$hojaActiva->getColumnDimension('H')->setWidth(18);
$hojaActiva->setCellValue('H4', 'TOTAL DEDUCCIONES');
$hojaActiva->getColumnDimension('I')->setWidth(13);
$hojaActiva->setCellValue('I4', 'TOTAL NETO');
$hojaActiva->getColumnDimension('J')->setWidth(15);
$hojaActiva->setCellValue('J4', 'CLAVE EMPLEADO');
$hojaActiva->getColumnDimension('K')->setWidth(18);
$hojaActiva->setCellValue('K4', 'RFC');
$hojaActiva->getColumnDimension('L')->setWidth(25);
$hojaActiva->setCellValue('L4', 'CURP');
$hojaActiva->getColumnDimension('M')->setWidth(18);
$hojaActiva->setCellValue('M4', 'QUINCENA');



//Indicar que se comience desde la fila 8 de Excel y no reescribir los encabezados
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
    //Calculando el total conservacion
    $totalConservacion = $totalConservacion + $rows['TotNeto'];
    //Incrementando las filas en 1 para que se inserten apropiadamente
    $fila++;
    //Incrementando las filas en 1 para que el borde se pinte segun el numero de columnas
    $borde++;
}

$fila++;
//Imprimiendo total de Deporte
$hojaActiva->setCellValue('I' . $fila, $totalConservacion);
$fila++;
$fila++;
$contadorservidor = 0;
$hojaActiva->setCellValue('A' . $fila, 'SERVICIOS CULTURALES');
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
    $totalServicios = $totalServicios + $rows['TotNeto'];
    //Incrementando las filas en 1 para que se inserten apropiadamente
    $fila++;
    //Incrementando las filas en 1 para que el borde se pinte segun el numero de columnas
    $borde++;
}

$fila++;
$hojaActiva->setCellValue('I' . $fila, $totalServicios);
$fila++;
$fila++;
$contadorservidor = 0;
$hojaActiva->setCellValue('A' . $fila, 'CONSERVATORIO DE MUSICA');
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
    $totalMusica = $totalMusica + $rows['TotNeto'];
    //Incrementando las filas en 1 para que se inserten apropiadamente
    $fila++;
    //Incrementando las filas en 1 para que el borde se pinte segun el numero de columnas
    $borde++;
}


$fila++;
$hojaActiva->setCellValue('I' . $fila, $totalMusica);
$fila++;
$fila++;
$contadorservidor = 0;
$hojaActiva->setCellValue('A' . $fila, 'FOMENTO DE LAS ACTIVIDADADES DEPORTIVAS RECREATIVAS');
$fila++;
$fila++;
while ($rows = $resultado4->fetch_assoc()) {
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
    $totalRecreativas = $totalRecreativas + $rows['TotNeto'];
    //Incrementando las filas en 1 para que se inserten apropiadamente
    $fila++;
    //Incrementando las filas en 1 para que el borde se pinte segun el numero de columnas
    $borde++;
}
$fila++;
$hojaActiva->setCellValue('I' . $fila, $totalRecreativas);
$fila++;
$fila++;
$fila++;
$hojaActiva->setCellValue('I' . $fila, ($totalConservacion + $totalServicios + $totalMusica + $totalRecreativas));
//Creando el archivo de excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Dispersion' . $CveNomina . '.xlsx"');
header('Cache-Control: max-age=0');

//Indicando la salida del documento en xlsx 
$writer = IOFactory::createWriter($excel, 'Xlsx');
$writer->save('php://output');
exit;
