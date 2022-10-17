<?php
require 'conexion.php';
$CveNomina = $_POST ['CveNomina'];
$CvePersonal = $_POST ['CvePersonal'];
$ClavePerDedApo = $_POST ['ClavePerDedApo'];
$Importe = $_POST ['Importe'];
$Del = $_POST ['Del'];
$Al = $_POST ['Al'];
$HrsMen = $_POST ['HrsMen'];



$sql ="INSERT INTO DetNomina (CveNomina,CvePersonal,Clave,Importe,HrsMen,Del,AL) VALUES
 ('$CveNomina','$CvePersonal','$ClavePerDedApo','$Importe','$HrsMen','$Del','$Al')";
$query = mysqli_query($mysqli, $sql);

if($query){
    Header("Location: alertas/insercionmanual.php");   
};
