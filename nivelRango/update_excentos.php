<?php
require 'conexion.php';

$CvePersonal = $_POST['CvePersonal'];
$ClaveA = $_POST['ClaveA'];
$Clave = $_POST['Clave'];
/*
echo $CvePersonal;
echo '<br>';
echo $Clave;
echo '<br>';
echo $ClaveA;*/


$sql = "UPDATE ExcentosDedApo SET Clave='$Clave' WHERE
CvePersonal='$CvePersonal' AND Clave='$ClaveA'";
$query = mysqli_query($mysqli, $sql);

if ($query) {
    Header("Location: alertas/excepcionactualizada.php");
};
