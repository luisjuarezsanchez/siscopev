<?php
require 'conexion.php';

$CveEmpCont =$_POST['CveEmpCont'];
$CvePersonal = $_POST['CvePersonal'];
$CtaBanco = $_POST['CtaBanco'];
$CveContrato = $_POST['CveContrato'];
$TipoEmpleado = $_POST['TipoEmpleado'];
$Inicio = $_POST['Inicio'];
$Fin = $_POST['Fin'];
$UltDia = $_POST['UltDia'];
$CodCategoria = $_POST['CodCategoria'];
$PrimaVac = $_POST['PrimaVac'];
$HrsMen = $_POST['HrsMen'];
$CostoHra = $_POST['CostoHra'];


$sql = "UPDATE EmpCont SET CvePersonal='$CvePersonal', CtaBanco='$CtaBanco',CveContrato='$CveContrato',
TipoEmpleado = '$TipoEmpleado',Inicio='$Inicio',Fin='$Fin',UltDia='$UltDia',CodCategoria='$CodCategoria',
PrimaVac='$PrimaVac',HrsMen = '$HrsMen' WHERE CveEmpCont='$CveEmpCont'";
$query = mysqli_query($mysqli, $sql);

if($query){
    Header("Location: crud_empcont.php");   
};
