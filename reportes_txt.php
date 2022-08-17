<?php 
$CveNomina = $_POST['CveNomina'];
$FecPag = $_POST['FecPag'];
$a=0;
echo $CveNomina;

//Abriendo el archivo en modo de escritura
$file = fopen("C:/Users/luisj/Desktop/archivo3.txt", "w");

//Solicitando la conexion con la BD
require 'conexion.php';

//Llamando al procedimiento que inserta los campos en tmptimmaes
$consulta = "CALL sp_GeneraTmpTimMaes('$CveNomina')";

//Haciendo la consulta a tmptimmaes
$consulta2 = "SELECT * FROM tmptimmaes";

//Insertando la fecha real de pago en tmptimmaes
$consulta3="UPDATE tmptimmaes SET FecPag='$FecPag'";


//Almaceando el resultado de las consultas en una variable (Por cada Query que se efectua)
//Llamada al procedimiento almacenado
$resultado = $mysqli->query($consulta);
//Seleccionar todos los datos de tmptimmaes
$resultado2 = $mysqli->query($consulta2);
//Insercion de fecha real de pago en la tabla tmptimmaes
$resultado3 = $mysqli->query($consulta3);






//Ciclo para lectura y escritura del archivo
while ($row = $resultado2->fetch_assoc()) {
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
    fwrite($file,$row['Qna'].'');
    fwrite($file,$row['FecPag'].'|');
    fwrite($file,$row['Fecini'].'|');
    fwrite($file,$row['FecFin'].'|');
    fwrite($file,$row['NumChe'].'|');
    fwrite($file,$row['CveOrg'].'|');
    fwrite($file,$row['OriRec'].'|');
    fwrite($file,$row['CveBan'].'|');
    fwrite($file,$row['Cuenta'].'|');
    fwrite($file,$row['FecIniCon'].'|');//Falta
    fwrite($file,$row['Antigu'].'|');//Falta
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
