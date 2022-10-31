<?php
require 'conexion.php';

echo $CveEmpCont = $_POST['CveEmpCont'];
//echo '<br>';
echo $CvePersonal = $_POST['CvePersonal'];
//echo '<br>';
echo $CtaBanco = $_POST['CtaBanco'];
//echo '<br>';
echo $CveContrato = $_POST['CveContrato'];
//echo '<br>';
echo $TipoEmpleado = $_POST['TipoEmpleado'];
//echo '<br>';
echo $Inicio = $_POST['Inicio'];
//echo '<br>';
echo $Fin = $_POST['Fin'];
//echo '<br>';
echo $UltDia = $_POST['UltDia'];
//echo '<br>';
echo $CodCategoria = $_POST['CodCategoria'];
//echo '<br>';
echo $PrimaVac = $_POST['PrimaVac'];
//echo '<br>';
echo $HrsMen = $_POST['HrsMen'];
//echo '<br>';


$sql = "UPDATE EmpCont SET CvePersonal='$CvePersonal', CtaBanco='$CtaBanco',CveContrato='$CveContrato',
TipoEmpleado = '$TipoEmpleado',Inicio='$Inicio',Fin='$Fin',UltDia='$UltDia',CodCategoria='$CodCategoria',
PrimaVac=$PrimaVac,HrsMen = '$HrsMen' WHERE CveEmpCont='$CveEmpCont'";
$query = mysqli_query($mysqli, $sql);

if ($query) {
    Header("Location: alertas/empcontactualizado.php");
};
