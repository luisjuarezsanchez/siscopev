<?php 
$CveNomina = $_POST['CveNomina'];
$FecPag = $_POST['FecPag'];
$a=0;
echo $CveNomina;
echo "<br>";
echo $FecPag;



//Abriendo el archivo en modo de escritura
$file = fopen("C:/Users/luisj/Desktop/archivo3.txt", "w");

//Solicitando la conexion con la BD
require 'conexion.php';

//Llamando al procedimiento que inserta los campos en tmptimmaes
$consulta = "CALL sp_GeneraTmpTimMaes('$CveNomina')";

//Insertando la fecha real de pago en tmptimmaes
$consulta2="UPDATE tmptimmaes SET FecPag='$FecPag'";

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
	fwrite($file,$row['CveEmp'].'|');
    fwrite($file,$row['Rfc'].'|');
    fwrite($file,$row['NomEmp'].'|');
    fwrite($file,$row['CveUniAds'].'|');
    fwrite($file,$row['CodCat'].'|');
    fwrite($file,$row['TipNom'].'|');
    fwrite($file,$row['CveIsse'].'|');
    fwrite($file,$row['Curp'].'|');
    fwrite($file,$row['NumCon'].'|');
    fwrite($file,$row['TotPer'].'|');
    fwrite($file,$row['TotDed'].'|');
    fwrite($file,$row['TotNet'].'|');
    fwrite($file,$row['TotDes'].'|');
    fwrite($file,$row['Qna'].'|');
    fwrite($file,$row['FecPag'].'|');//No la esta mandando
    fwrite($file,$row['Fecini'].'|');
    fwrite($file,$row['FecFin'].'|');
    fwrite($file,$row['NumChe'].'|');
    fwrite($file,$row['CveOrg'].'|');
    fwrite($file,$row['OriRec'].'|');
    fwrite($file,$row['CveBan'].'|');
    fwrite($file,$row['Cuenta'].'|');
    fwrite($file,$row['FecIniCon'].'|');


    //Antiguedad calculada con datos de la BD
    $fecha1=$row['FecIniCon'];//Restarle 1
    $fecha2=$row['FecFin'];
    $fechainicial=new DateTime($fecha1);
    $fechafin=new DateTime($fecha2);
    $diferencia=$fechainicial-> diff($fechafin);
    fwrite($file,$diferencia-> format('P%YY%MM%DD').'|');

    //Se continua con la impresion de la BD
    fwrite($file,$row['Riesgo'].'|');
    fwrite($file,$row['SalDiaInt'].'|');//Falta
    fwrite($file,$row['TipCont'].'|');
    fwrite($file,$row['Subent'].'|');
    fwrite($file,$row['SubCau'].'|');
    fwrite($file,$row['AjusSub'].'|');
    fwrite($file,''. PHP_EOL);

}

//. PHP_EOL

//Cerrando el archivo
fclose($file);

?>
