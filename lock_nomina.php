<?php
//Solicitado la conexion
require 'conexion.php';
//Verificando sesion
session_start();
$usuario = $_SESSION['username'];
if (!isset($usuario)) {
    header("location: index.php");
}
//Recibiendo CveNomina del formulario por metodo POST
/*
DELETE FROM ComPerDed;
ALTER TABLE ComPerDed AUTO_INCREMENT= 318067;
*/
$CveNomina = $_POST['CveNomina'];

$sql = "UPDATE Nominas SET Cerrada = 1 WHERE CveNomina = '$CveNomina'";
$query = mysqli_query($mysqli, $sql);

$sql2 = "SELECT MAX(Folio) AS ultimo FROM ComPerDed";
$query3 = mysqli_query($mysqli, $sql2);

while ($row = $query3->fetch_assoc()) {
    $contador = $row['ultimo'] + 1;
}


$sql3 = "SELECT 
DetNomina.CvePersonal,DetNomina.CveNomina
FROM EmpCont INNER JOIN  
DetNomina ON EmpCont.CvePersonal = DetNomina.CvePersonal INNER JOIN
EmpGral ON EmpCont.CvePersonal = EmpGral.CvePersonal INNER JOIN
catbanco ON SUBSTR(EmpCont.CtaBanco, 1, 3) = catbanco.CveBanco INNER JOIN
catcatego ON EmpCont.CodCategoria = catcatego.CveCategoria INNER JOIN
Contratos ON EmpCont.CveContrato = Contratos.CveContrato
WHERE (EmpCont.CveContrato LIKE '%DEPOR%' OR EmpCont.CveContrato LIKE '%COMEM%' OR EmpCont.CveContrato LIKE '%PATRI%') 
AND DetNomina.CveNomina='$CveNomina' GROUP BY DetNomina.CvePersonal ORDER BY EmpCont.Dirgral,DetNomina.CvePersonal";
$query3 = mysqli_query($mysqli, $sql3);

while ($row = $query3->fetch_assoc()) {
    $PersonalCaptura = $row['CvePersonal'];
    $NominaCaptura = $row['CveNomina'];

    //Insertado valores en ComPerDed
    $cons = "INSERT INTO ComPerDed (Folio,CveNomina,CvePersonal)
    VALUES ('$contador','$CveNomina','$PersonalCaptura')";
    $rescons = $mysqli->query($cons);
    $contador = $contador + 1;
}


// No mostrar los errores de PHP
error_reporting(0);

echo '<script>
alert("Nómina cerrada correctamente, recuerda que ahora ya no se podran efectuar modificaciones")
</script>
<meta http-equiv="refresh" content="0.1;url=cerrar_nomina.php" />';
