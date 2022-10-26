<?php
require 'conexion.php';

$CveConAnterior = $_POST['CveConAnterior'];
$CveConNueva = $_POST['CveConNueva'];
$NuevoInicio = $_POST['NuevoInicio'];
$NuevoFin = $_POST['NuevoFin'];


echo $CveConAnterior;
echo '<br>';
echo $CveConNueva;
echo '<br>';
echo $NuevoInicio;
echo '<br>';
echo $NuevoFin;

$sql = "INSERT INTO tmpRenovaCon (CveConAnterior,CveConNueva,NuevoInicio,NuevoFin)
VALUES ('$CveConAnterior','$CveConNueva','$NuevoInicio','$NuevoFin')";
$query = mysqli_query($mysqli, $sql);

$sql2 = "INSERT INTO tmpempcont (CvePersonal,CtaBanco,CveContrato,TipoEmpleado,Inicio,Fin
,UltDia,Retenido,FechaFirma,PeriodosLab,PeriodosPagAgui,CveHorario,CveTabulador,NumPlaza,
CodCategoria,Funciones,Actividades,SueldoNeto,NumContrato,Folio,CveUniAdm,Codigo,UnidadRespon,CodSecre,
PrimaVac,UbicaFisica,HrsMen,Dirgral)
SELECT CvePersonal,CtaBanco,CveContrato,TipoEmpleado,Inicio,Fin
,UltDia,Retenido,FechaFirma,PeriodosLab,PeriodosPagAgui,CveHorario,CveTabulador,NumPlaza,
CodCategoria,Funciones,Actividades,SueldoNeto,NumContrato,Folio,CveUniAdm,Codigo,UnidadRespon,CodSecre,
PrimaVac,UbicaFisica,HrsMen,Dirgral FROM EmpCont WHERE CveContrato = '$CveConAnterior'";
$query = mysqli_query($mysqli, $sql2);

$sql3 = "UPDATE tmpempcont
SET CveContrato='$CveConNueva',Inicio='$NuevoInicio',Fin='$NuevoFin',UltDia='$NuevoFin'
WHERE CveContrato='$CveConAnterior'";
$query = mysqli_query($mysqli, $sql3);


if ($query) {
    Header("Location: alertas/contratorenovado.php");
};
