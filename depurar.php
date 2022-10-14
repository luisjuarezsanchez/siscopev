<?php
$CveNomina = $_POST['CveNomina'];
$FecPag = $_POST['FecPag'];
$num = 1;
$a = 0;
//echo $CveNomina;
//echo '<br>';
//echo $FecPag;

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


header("Content-disposition: attachment; filename=archivos/timbrados/TimMaestro.txt");
//header("Content-type: MIME");
readfile("archivos/timbrados/TimMaestro.txt");
?>


<?php
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

header("Content-disposition: attachment; filename=archivos/timbrados/TimDetalle.txt");
//header("Content-type: MIME");
readfile("archivos/timbrados/TimDetalle.txt");



