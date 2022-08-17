<?php 
$CveNomina = $_POST['CveNomina'];
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

//Almaceando el resultado de la consulta en una variable
$resultado = $mysqli->query($consulta);
$resultado2 = $mysqli->query($consulta2);




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
