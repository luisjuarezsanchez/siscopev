<?php 
$CveNomina = $_POST['CveNomina'];
echo $CveNomina;

//Abriendo el archivo en modo de escritura
$file = fopen("C:/Users/luisj/Desktop/archivo3.txt", "w");

//Solicitando la conexion con la BD
require 'conexion.php';
//Efectuando la consulta de las vista
$consulta = "SELECT CveDetNomina,CveNomina,CvePersonal,Clave,Importe FROM Detnomina where CveNomina='$CveNomina'";
//Almaceando el resultado de la consulta en una variable
$resultado = $mysqli->query($consulta);



while ($row = $resultado->fetch_assoc()) {
	//Ancho alto,borde,salto de linea justificacion relleno
	fwrite($file,$row['CveDetNomina'].'|');
    fwrite($file,$row['CveNomina'].'|');
    fwrite($file,$row['CvePersonal'].'|');
    fwrite($file,$row['Clave'].'|');
    fwrite($file,$row['Importe']. PHP_EOL);
}


//Cerrando el archivo
fclose($file);

?>