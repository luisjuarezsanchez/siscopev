<?php
session_start();
$usuario = $_SESSION['username'];
if (!isset($usuario)) {
    header("location: index.php");
}
$CveNomina = $_POST['CveNomina'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="img/iconos/escudo_armas.png">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/alfabetico.css">
    <title>Alfabético de nómina</title>
</head>


<body>

    <?php
    require 'conexion.php';
    $consulta = "SELECT 
    DetNomina.CvePersonal,EmpGral.RFC,CONCAT(EmpGral.Nombre,' ',EmpGral.Paterno,' ',EmpGral.Materno) AS Nombre,EmpCont.CtaBanco,SUBSTR(catbanco.NomBanco,1,4) AS NomBanco,EmpGral.CURP,EmpCont.Dirgral,EmpCont.HrsMen,EmpGral.CveISSEMyM,EmpCont.UnidadRespon,
    EmpCont.CodCategoria,
    catpuestos.descripcion AS Descripcion,catpuestos.descorta,
    DetNomina.Del,DetNomina.Al
    FROM EmpCont 
    INNER JOIN DetNomina ON EmpCont.CvePersonal = DetNomina.CvePersonal 
    INNER JOIN EmpGral ON EmpCont.CvePersonal = EmpGral.CvePersonal 
    INNER JOIN catbanco ON SUBSTR(EmpCont.CtaBanco, 1, 3) = catbanco.CveBanco 
    INNER JOIN catpuestos ON EmpCont.CodCategoria = catpuestos.codpuesto
    WHERE empcont.TipoEmpleado=1
    GROUP BY DetNomina.CvePersonal
    ORDER BY empcont.Dirgral";
    $resultado = $mysqli->query($consulta);
    ?>

    <table style="text-align: center; margin: 0 auto;">
        <tr>
            <th colspan="2">
                <!-- <p style="text-align: left;"><img src="img/reportes/gob.jpg" width="150" height="100"></p>-->
            </th>
            <th colspan="2"></th>
            <th colspan="2"></th>
            <th colspan="2">
                <!--  <p style="text-align: right;"><img src="img/reportes/logo_vertical.png" width="125" height="100"></p>-->
            </th>
        </tr>

        <tr>
            <th colspan="8">
                <h1>SECRETARÍA DE CULTURA Y TURISMO</h1>
            </th>

        </tr>

        <tr>
            <th colspan="8">
                <h1>RESUMEN DE PERCEPCIONES Y DEDUCCIONES DE LA QUINCENA</h1>
            </th>
        </tr>
        <?php
        while ($row = $resultado->fetch_assoc()) {
            $CvePersonalcaptura = $row['CvePersonal'];
            $totPercepciones = 0;
            $totDeducciones = 0;
            echo '
            <tr>
            <th colspan="1">
                <p style="text-align:center;"></p>' . $row['CvePersonal'] . '
            </th>

            <th colspan="1">
                <p style="text-align:center;"></p>' . $row['RFC'] . '
            </th>

            <th colspan="2">
                <p style="text-align:center;"></p>' . $row['Nombre'] . '
            </th>

            <th colspan="1">
                <p style="text-align:center;"></p>' . $row['CtaBanco'] . '
            </th>

            <th colspan="1">
                <p style="text-align:center;"></p>' . '('.$row['NomBanco'].')' . '
            </th>

            <th colspan="2">
                <p style="text-align:center;"></p>' . $row['CURP'] . '
            </th>
        </tr>

            <tr>
            <th colspan="1">
                <p style="text-align:center;"></p>' . $row['Dirgral'] . '
            </th>

            <th colspan="1">
                <p style="text-align:center;"></p>' . $row['CveISSEMyM'] . '
            </th>

            <th colspan="1">
                <p style="text-align:center;"></p>' . $row['UnidadRespon'] . '
            </th>

            <th colspan="1">';
            if ($row['Dirgral'] == 3 or $row['Dirgral'] == 4) {
                echo '<p style="text-align:center;">DIR GRAL PAT Y SERV CULT</p>';
            }

            if ($row['Dirgral'] == 5) {
                echo '<p style="text-align:center;">DIREC GRAL DEL COMEM</p>';
            }

            if ($row['Dirgral'] == 6) {
                echo '<p style="text-align:center;">DIR GRAL DE CULTURA FISICA Y DEPORTE</p>';
            }
            echo' 
            </th>
            <th colspan="1">
                <p style="text-align:center;"></p>' . $row['CodCategoria'] . '
            </th>
            <th colspan="1">
                <p style="text-align:center;"></p>' . $row['Descripcion'] . '
            </th>

            <th colspan="1">
                <p style="text-align:center;"></p>' . $row['Del'] . '
            </th>

            <th colspan="1">
                <p style="text-align:center;"></p>' . $row['Al'] . '
            </th>
        </tr>
            ';
            $consulta2 = "SELECT DetNomina.Clave,PerDedApo.Concepto,DetNomina.Importe,EmpCont.HrsMen,catpuestos.descorta
            FROM DetNomina
            INNER JOIN PerDedApo ON DetNomina.Clave = PerDedApo.Clave
            INNER JOIN EmpCont ON DetNomina.CvePersonal = EmpCont.CvePersonal
            INNER JOIN catpuestos ON EmpCont.CodCategoria = catpuestos.codpuesto
            WHERE DetNomina.CvePersonal=$CvePersonalcaptura 
            AND PerDedApo.TipoPDA=0";
            $resultado2 = $mysqli->query($consulta2);

            $consulta3 = "SELECT DetNomina.Clave,PerDedApo.Concepto,DetNomina.Importe,EmpCont.HrsMen,catpuestos.descorta
            FROM DetNomina
            INNER JOIN PerDedApo ON DetNomina.Clave = PerDedApo.Clave
            INNER JOIN EmpCont ON DetNomina.CvePersonal = EmpCont.CvePersonal
            INNER JOIN catpuestos ON EmpCont.CodCategoria = catpuestos.codpuesto
            WHERE DetNomina.CvePersonal=$CvePersonalcaptura 
            AND PerDedApo.TipoPDA=1";
            $resultado3 = $mysqli->query($consulta3);


            echo '
            <tr>
            <th id="contenido" colspan="4">
            <div id="contenido">
            ';
            while ($row = $resultado2->fetch_assoc()) {
                echo '<p  id="importe">' .  $row['Clave'] . ' ' . $row['Concepto']  . '</p>' . '<p style="text-align: right">' . ' $' . number_format($row['Importe'], 2, ".", ",") . '</p>';
                // echo '<br>';
                $totPercepciones = $totPercepciones + $row['Importe'];
            }
            echo '
            </div>
                </th>
        
        
            <th class="consulta" id="contenido" colspan="4">
            <div id="contenido">
            ';

            while ($row = $resultado3->fetch_assoc()) {
                echo '<p style="text-align: left">' . $row['Clave'] . ' ' . $row['Concepto'] . ' $ ' . number_format($row['Importe'], 2, ".", ",") . '</p>';
                echo '<br>';
                $totDeducciones = $totDeducciones + $row['Importe'];
            }
            echo ' 
            </div>
            </th>
        </tr>
        

        <tr>
            <th class="consulta" colspan="4">' . '$' . number_format($totPercepciones, 2, ".", ",") . '</th>
            <th class="consulta" colspan="4">' . '$' . number_format($totDeducciones, 2, ".", ",") . '</th>
        </tr>

        <tr>
            <th class="consulta" colspan="4">' . '' . '</th>
            <th class="consulta" colspan="4">' . '$' . number_format(($totPercepciones - $totDeducciones), 2, ".", ",") . '</th>
        </tr>

        
        <tr>
            <th class="consulta" colspan="4"><br></th>
        </tr>

 

        ';
        }

        ?>



        <tr>
            <th colspan="4"></th>
            <th colspan="4"> <br> </th>
        </tr>

        <tr>
            <th colspan="4">TOTAL DE EMPLEADOS: 41</th>
        </tr>




    </table>

</body>

</html>