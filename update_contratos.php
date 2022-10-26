<?php
require 'conexion.php';

$CveContrato = $_POST['CveContrato'];
$CveContratoNueva = $_POST['CveContratoNueva'];
$Inicio = $_POST['Inicio']; 
$Fin = $_POST['Fin'];
$Cerrado = $_POST['Cerrado'];
$Anio = $_POST['Anio'];
/*
echo $CveContratoNueva;
echo '<br>';
echo $CveContrato;
echo '<br>';
echo $Inicio;
echo '<br>';
echo $Fin;
echo '<br>';
echo $Anio;*/

$sql = "UPDATE Contratos SET CveContrato = '$CveContratoNueva',Inicio='$Inicio',Fin='$Fin',Cerrado='$Cerrado',Anio='$Anio'
WHERE CveContrato = '$CveContrato';";
$query = mysqli_query($mysqli, $sql);

if ($query) {
    Header("Location: alertas/contratoactualizado.php");
};
