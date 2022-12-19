<?php
require 'conexion.php';
$CveContrato = $_POST['CveContrato'];
$Descripcion = $_POST['Descripcion'];
$Inicio = $_POST['Inicio'];
$Fin = $_POST['Fin'];
$Anio = $_POST['Anio'];
/*
echo $CveContrato;
echo '<br>';
echo $Descripcion;
echo '<br>';
echo $Inicio;
echo '<br>';
echo $Fin;
echo '<br>';
echo $Fin;
echo '<br>';
echo $Anio;*/

$sql = "INSERT INTO Contratos (CveContrato,Descripcion,Inicio,Fin,TipoContrato,Cerrado,
Prisma,Anio, Region,Funcion, SubFuncion, Programa, SubPrograma, Proyecto, FuenteFinan,Unidad,
CveCentroCosto,Partida,DescUnidad,NumOficio,FecOficio,PartidaAgui,PorRecHum,PorFinanzas) VALUES
('$CveContrato','$Descripcion','$Inicio','$Fin','0','0','10094','$Anio','MEX','A','A','A','A','A','12',
'A','A','A','A','650','2022-09-16','A','A','A')";
$query = mysqli_query($mysqli, $sql);

if ($query) {
    Header("Location: alertas/contratoinsertado.php");
};
