<?php
require 'conexion.php';

$CvePersonal = $_POST['CvePersonal'];
$RFC = $_POST['RFC'];
$Paterno = $_POST['Paterno'];
$Materno = $_POST['Materno'];
$Nombre = $_POST['Nombre'];
$CveISSEMyM = $_POST['CveISSEMyM'];
$FechaIngreso = $_POST['FechaIngreso'];
$Nacionalidad = $_POST['Nacionalidad'];
$CURP = $_POST['CURP'];

/*
echo $CvePersonal;
echo '<br>';
echo $RFC;
echo '<br>';
echo $Paterno;
echo '<br>';
echo $Materno;
echo '<br>';
echo $Nombre;
echo '<br>';
echo $CveISSEMyM;
echo '<br>';
echo $FechaIngreso;
echo '<br>';
echo $Nacionalidad;
echo '<br>';
echo $CURP;*/

$sql = "INSERT INTO EmpGral (CvePersonal,RFC,Paterno,Materno,Nombre,CveISSEMyM,FechaIngreso
,Nacionalidad,CURP) VALUES 
('$CvePersonal','$RFC','$Paterno','$Materno','$Nombre','$CveISSEMyM','$FechaIngreso','$Nacionalidad','$CURP')";
$query = mysqli_query($mysqli, $sql);

if ($query) {
    Header("Location:alertas/empleadoagregado.php");
};
