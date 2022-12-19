<?php
session_start();
$usuario = $_SESSION['username'];
if (!isset($usuario)) {
    header("location: index.php");
}
$CveNomina = $_POST['CveNomina'];
$pagina = 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="img/iconos/escudo_armas.png">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/comprobantes.css">
    <title>Comprobantes de PerDed</title>
</head>


<body>

    <!--DEPORTE-->
    <?php
    require 'conexion.php';
    $consulta = "SELECT 
    DetNomina.CvePersonal,EmpGral.RFC,CONCAT(EmpGral.Nombre,' ',EmpGral.Paterno,' ',EmpGral.Materno) AS Nombre,EmpCont.CtaBanco,SUBSTR(catbanco.NomBanco,1,4) AS NomBanco,EmpGral.CURP,EmpCont.Dirgral,EmpCont.HrsMen,EmpGral.CveISSEMyM,EmpCont.UnidadRespon,
    EmpCont.CodCategoria,catcatego.Descripcion,catcatego.DescCorta,DetNomina.Del,DetNomina.Al,
    SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=0) THEN Importe ELSE 0 END)- SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=1) THEN Importe ELSE 0 END) AS sueldobruto,
    SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=0) THEN Importe ELSE 0 END) AS totpercepciones,Contratos.Descripcion,ComPerDed.Folio
    FROM EmpCont INNER JOIN 
    DetNomina ON EmpCont.CvePersonal = DetNomina.CvePersonal INNER JOIN
    EmpGral ON EmpCont.CvePersonal = EmpGral.CvePersonal INNER JOIN
    catbanco ON SUBSTR(EmpCont.CtaBanco, 1, 3) = catbanco.CveBanco INNER JOIN
    catcatego ON EmpCont.CodCategoria = catcatego.CveCategoria INNER JOIN
    Contratos ON EmpCont.CveContrato = Contratos.CveContrato INNER JOIN
    ComPerDed ON EmpCont.CvePersonal = ComPerDed.CvePersonal
    WHERE (EmpCont.CveContrato LIKE '%DEPOR%' OR EmpCont.CveContrato LIKE '%COMEM%' OR EmpCont.CveContrato LIKE '%PATRI%') 
    AND DetNomina.CveNomina='$CveNomina' AND ComPerDed.CveNomina='$CveNomina' GROUP BY DetNomina.CvePersonal ORDER BY EmpCont.Dirgral,DetNomina.CvePersonal";
    $resultado = $mysqli->query($consulta);





    ?>

    <?php
    while ($row = $resultado->fetch_assoc()) {
        $Clavecaptura = $row['CvePersonal'];
        $BancoCaptura = $row['CtaBanco'];
        $Al = $row['Al'];
        echo '
        <table style="text-align: center; margin: 0 auto;">
        <tr id="espacio">
            <th id="logo" colspan="6">
              <p style="text-align: left;"><img src="img/iconos/escudo_comprobantes.png" width="300" height="100"></p>
                </th>
        </tr>

        <tr id="espacio">
            <th style = "text-align: center;" id="titulo" colspan="6">COMPROBANTE DE PRECEPCIONES Y DEDUCCIONES 
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            RECIBO:' . $row['Folio'] . ' </th>
        </tr>



        <tr>
            <th style="text-align: left;" class="sinlinea" colspan="3">
                <p>Nombre:  ' . $row['Nombre'] . '</p>
                <p>CURP:  ' . $row['CURP'] . ' </p>
                <p>Puesto: CONT.TIEMPO INDETERMINADO</p> 
                <p>Dependencia: SECRETARÍA DE CULTURA </p>
                <p>Unidad Admva: ' . $row['Descripcion'] . '</p> 
            </th>
            <th style="text-align: left;" class="sinlinea" colspan="3">
            <p>RFC:' . $row['CURP'] . ' </p>
            <p>Clave de ISSEMYM:' . $row['CveISSEMyM'] . ' </p>
            <p>Fecha de pago:' . $row['Del'] . ' </p>
            <p>Periodo de pago: ' . $row['Al'] . '</p>
            <p>Codigo de Unidad Administrativa: ' . $row['UnidadRespon'] . '</p>
            <p> Total neto: $' . number_format($row['sueldobruto'], 2, ".", ",") . '</p>
            
            </th>
        </tr>

        <tr>
            <th class="perded" colspan="3">
                Percepciones
            </th>



            <th class="perded" colspan="3">
                Deducciones
            </th>
        </tr>

        <tr>
            <th id="izquierdo" class="sinlinea">
                Clave
            </th>
            <th class="sinlinea">
                Concepto
            </th>
            <th id="derecho" class="sinlinea">
                Importe
            </th>


            <th id="izquierdo" class="sinlinea">
                Clave
            </th>
            <th class="sinlinea">
                Concepto
            </th>
            <th id="derecho" class="sinlinea">
                Importe
            </th>
        </tr>';
        $consulta2 = " SELECT DetNomina.Clave,DetNomina.Importe,PerDedApo.Concepto FROM DetNomina
        INNER JOIN PerDedApo ON DetNomina.Clave=PerDedApo.Clave
        WHERE DetNomina.CveNomina='$CveNomina' AND DetNomina.CvePersonal='$Clavecaptura' AND PerDedApo.TipoPDA=0";
        $resultado2 = $mysqli->query($consulta2);

        $consulta3 = " SELECT DetNomina.Clave,DetNomina.Importe,PerdedApo.Concepto
        FROM DetNomina
        INNER JOIN PerDedApo ON DetNomina.Clave=PerDedApo.Clave
        WHERE DetNomina.CveNomina='$CveNomina' AND DetNomina.CvePersonal='$Clavecaptura' AND PerDedApo.TipoPDA=1";
        $resultado3 = $mysqli->query($consulta3);

        //Total de percepciones
        $consulta4 = "SELECT
        SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=0) THEN Importe ELSE 0 END) AS totpercepciones
        FROM DetNomina
        INNER JOIN PerDedApo ON DetNomina.Clave=PerDedApo.Clave
        WHERE DetNomina.CveNomina='$CveNomina' AND DetNomina.CvePersonal=$Clavecaptura AND PerDedApo.TipoPDA=0
        ";
        $resultado4 = $mysqli->query($consulta4);

        //Total de deducciones
        $consulta5 = "SELECT
        SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=1) THEN Importe ELSE 0 END) AS totdeducciones
        FROM DetNomina
        INNER JOIN PerDedApo ON DetNomina.Clave=PerDedApo.Clave
        WHERE DetNomina.CveNomina='$CveNomina' AND DetNomina.CvePersonal=$Clavecaptura AND PerDedApo.TipoPDA=1
        ";
        $resultado5 = $mysqli->query($consulta5);
        echo '
        <div class="texto">
    <tr>
    
    <th id="contenido" colspan="3">';
        while ($row = $resultado2->fetch_assoc()) {
            echo  $row['Clave'] . ' ' . $row['Concepto'] . ' $ ' . number_format($row['Importe'], 2, ".", ",");
            echo '<br>';
        }
        echo '
        <p style="text-align:center;"><img id="agua" src="img/iconos/escudoarmas_agua.png" height="250" width="250"></p>
        
        </th>
        


    <th class="consulta" id="contenido" colspan="3">';

        while ($row = $resultado3->fetch_assoc()) {
            echo  $row['Clave'] . ' ' . $row['Concepto'] . ' $ ' . number_format($row['Importe'], 2, ".", ",");
            echo '<br>';
        }

        echo ' 
        <p style="text-align:center;"><img id="agua"   src="img/iconos/escudoarmas_agua.png" height="250" width="250"></p>
    </th>
</tr>
</div>


<tr>';
        while ($row = $resultado4->fetch_assoc()) {
            $percepciones = $row['totpercepciones'];
        }

        while ($row = $resultado5->fetch_assoc()) {
            $deducciones = $row['totdeducciones'];
        }
        echo ' 
    <th colspan="3">Total de percepciones $' . number_format($percepciones, 2, ".", ",") . '</th>
    <th colspan="3">Total de deducciones $' . number_format($deducciones, 2, ".", ",") . '</th>

</tr>

<tr>';
        $originalDate = "$Al";
        $newDate = date("d/m/Y", strtotime($originalDate));
        echo ' 
    <th style="text-align: left;" colspan="6">SE REALIZÓ EL ABONO EN LA CUENTA Num: ' . substr($BancoCaptura, 7, 10) . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;EL DÍA: ' . $newDate . '' . '<br>
        CONSTITUYE EL RECIBO DE PAGO CORRESPONDIENTE 
    </th>
</tr>

<tr>
    <th id="blanco" colspan="6"> <br></th>
</tr>

<br>

</table>


<br><br><br><br><br><br><br><br><br><br><br>
<hr>';

        $pagina = $pagina + 1;
        echo ' 

<p style="text-align: center;">Página ' . $pagina . '</p>

        ';
    }
    ?>
</body>

</html>