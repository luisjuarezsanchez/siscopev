<?php 
$CveNomina = $_POST['CveNomina'];
$a=0;
echo $CveNomina;

//Abriendo el archivo en modo de escritura
$file = fopen("C:/Users/luisj/Desktop/archivo3.txt", "w");

//Solicitando la conexion con la BD
require 'conexion.php';
//Efectuando consulta de datos personales
$consulta = "SELECT CvePersonal,RFC,Paterno,Materno,Nombre,CveISSEMyM,CURP from EmpGral WHERE 
CvePersonal IN (SELECT CvePersonal from DetNomina WHERE CveNomina='$CveNomina') ORDER BY Paterno";
//Almaceando el resultado de la consulta en una variable
$resultado = $mysqli->query($consulta);

//Efectuando consulta de Totales
$consulta2="SELECT SUM(Importe)as tpercepciones FROM DetNomina WHERE 
Clave IN (SELECT clave FROM PerDedApo WHERE tipoPDA=0) and CveNomina='$CveNomina' GROUP BY CvePersonal";
//Almaceando el resultado de la consulta en una variable
$resultado2 = $mysqli->query($consulta2);


//Ciclo para lectura y escritura del archivo
while ($row = $resultado->fetch_assoc()) {
    //Numero consecutivo
    $a=$a+1;
	//Ancho alto,borde,salto de linea justificacion relleno
	fwrite($file,$row['CvePersonal'].'|');
    fwrite($file,$row['RFC'].'|');
    fwrite($file,$row['Paterno'].' ');
    fwrite($file,$row['Materno'].' ');
    fwrite($file,$row['Nombre'].'|');
    fwrite($file,'22600001L'.'|');
    fwrite($file,'A0363072'.'|');
    fwrite($file,'0'.'|');
    fwrite($file,$row['CveISSEMyM'].'|');
    fwrite($file,$row['CURP'].'|');
    fwrite($file,$a.'|');
    while ($row = $resultado2->fetch_assoc()) {
        //Ancho alto,borde,salto de linea justificacion relleno
        fwrite($file,$row['tpercepciones'].'|');
    }
    fwrite($file,''. PHP_EOL);
}

//. PHP_EOL

//Cerrando el archivo
fclose($file);

?>
