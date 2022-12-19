<?php
require 'conexion.php';

$CvePersonal = $_POST['CvePersonal'];
$Paterno = $_POST['Paterno'];
$Materno = $_POST['Materno'];
$Nombre = $_POST['Nombre'];
$CveISSEMyM = $_POST['CveISSEMyM'];
$FechaIngreso = $_POST['FechaIngreso'];
$Nacionalidad = $_POST['Nacionalidad'];
$CURP = $_POST['CURP'];



$sql = "UPDATE EmpGral SET CvePersonal='$CvePersonal',Paterno = '$Paterno'
,Materno = '$Materno',Nombre='$Nombre',CveISSEMyM='$CveISSEMyM',FechaIngreso='$FechaIngreso'
,Nacionalidad='$Nacionalidad',CURP = '$CURP' WHERE CvePersonal='$CvePersonal'";
$query = mysqli_query($mysqli, $sql);

if ($query) {
    Header("Location: alertas/empleadomodificado.php");
};
