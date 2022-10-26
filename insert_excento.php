<?php
require 'conexion.php';

$CvePersonal =$_POST['CvePersonal'];
$Clave =$_POST['Clave'];

/*echo $CvePersonal;
echo '<br>';
echo $Clave;*/

$sql = "INSERT INTO ExcentosDedApo (CvePersonal,Clave) VALUES 
('$CvePersonal','$Clave')";
$query = mysqli_query($mysqli, $sql);

if($query){
    Header("Location: alertas/excentoagregado.php");   
};
