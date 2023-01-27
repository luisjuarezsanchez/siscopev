<?php
// Verificando sesion iniciada
session_start();
$usuario = $_SESSION['username'];
if (!isset($usuario)) {
    header("location: index.php");
}
$CveNomina = $_POST['CveNomina'];

// Solicitando los archivos de la biblioteca
require_once __DIR__ . '/vendor/autoload.php';

// Efectuando conexion a la Base de Datos
require 'conexion.php';
// Haciendo la consulta SQL
$consulta = "SELECT 
DetNomina.CvePersonal,EmpGral.RFC,CONCAT(EmpGral.Nombre,' ',EmpGral.Paterno,' ',EmpGral.Materno) AS Nombre,EmpCont.CtaBanco,SUBSTR(catbanco.NomBanco,1,4) AS NomBanco,EmpGral.CURP,EmpCont.Dirgral,EmpCont.HrsMen,EmpGral.CveISSEMyM,EmpCont.UnidadRespon,
EmpCont.CodCategoria,catpuestos.descripcion,catpuestos.descorta,DetNomina.Del,DetNomina.Al,
SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=0) THEN Importe ELSE 0 END)- SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=1) THEN Importe ELSE 0 END) AS sueldobruto,
SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=0) THEN Importe ELSE 0 END) AS totpercepciones,
    SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=1) THEN Importe ELSE 0 END) AS totdeducciones
FROM EmpCont 
INNER JOIN  DetNomina ON EmpCont.CvePersonal = DetNomina.CvePersonal 
INNER JOIN EmpGral ON EmpCont.CvePersonal = EmpGral.CvePersonal 
INNER JOIN catbanco ON SUBSTR(EmpCont.CtaBanco, 1, 3) = catbanco.CveBanco 
INNER JOIN catpuestos ON EmpCont.CodCategoria = catpuestos.codpuesto 
GROUP BY DetNomina.CvePersonal ORDER BY EmpCont.Dirgral,DetNomina.CvePersonal";
$resultado = $mysqli->query($consulta);


// Solicitando los estilos CSS del reporte
$css = file_get_contents('css/comprobantes.css'); 

// Creando una instancia de la clase 
$mpdf = new \Mpdf\Mpdf();

//Insertado estilos CSS
$mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);

while ($row = $resultado->fetch_assoc()) {
    $CveCapturada = $row['CvePersonal'];
    $totpercepciones = $row['totpercepciones'];
    $totdeducciones = $row['totdeducciones'];
    $CtaBanco = $row['CtaBanco'];
    $Al = $row['Al'];
    $Del = $row['Del'];

    $originalDate = "$Al";
    $Alddmmaaaa = date("d/m/Y", strtotime($originalDate));

    $originalDate2 = "$Del";
    $Delddmmaaaa = date("d/m/Y", strtotime($originalDate2));


    $plantilla = '  
<body>
<table border="1" align="center">
    <tr>
        <td colspan="6" width="1000" height="200"><img src="img/iconos/escudo_comprobantes.jpg" alt=""></td>
    </tr>
    <tr>
        <td colspan="6" class="cabecera" width="1000" height="50">COMPROBANTE DE PERCEPCIONES Y DEDUCCIONES &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Recibo: ' . '  </td>
    </tr>
    <tr>
        <td colspan="3" class="informacion" valign="top" height="100" width="500">
            <strong>Nombre: &nbsp;</strong>' . $row['Nombre'] . ' <br>
            <strong>CURP: &nbsp;</strong>' . $row['CURP'] . ' <br>
            <strong>Puesto: &nbsp;</strong>' . ' <br>
            <strong>Dependencia: &nbsp;</strong>SECRETARIA DE CULTURA <br>
            <strong>Unidad Admva: &nbsp;</strong>'  . '<br>
        </td>
        <td colspan="3" class="informacion" valign="top" height="100" width="500">
            <strong>RFC: &nbsp;</strong>' . $row['RFC'] . ' <br>
            <strong>Clave de ISSEMYM: &nbsp;</strong>' . $row['CveISSEMyM'] . ' <br>f
            <strong>Fecha de pago: &nbsp;</strong>' . $Alddmmaaaa . ' <br>
            <strong>Periodo de pago: &nbsp;</strong>' . $Delddmmaaaa . ' al ' . $Alddmmaaaa . ' <br>
            <strong>Código de Unidad Administrativa: &nbsp;</strong>' . $row['UnidadRespon'] . ' <br>
            <strong>Total neto: &nbsp;</strong>' . $row['sueldobruto'] . '<br>
        </td>
    </tr>
    <tr>
        <td colspan="3" cellspacing="0" class="cabeceras_perded"><strong>Percepciones</strong></td>
        <td colspan="3" cellspacing="0" class="cabeceras_perded"><strong>Deducciones</strong></td>
    </tr>
    <tr>
        <td colspan="1" cellspacing="0" class="claconimp">Clave</td>
        <td colspan="1" cellspacing="0" class="claconimp">Concepto</td>
        <td colspan="1" cellspacing="0" class="claconimp">Importe</td>
        <td colspan="1" cellspacing="0" class="claconimp">Clave</td>
        <td colspan="1" cellspacing="0" class="claconimp">Concepto</td>
        <td colspan="1" cellspacing="0" class="claconimp">Importe</td>
    </tr>
    <tr>
    <td colspan="3" cellspacing="0" class="consultaperded" valign="top">';

    //Consulta para enlistar las PERCEPCIONES de cada servidor
    $consulta2 = "SELECT DetNomina.Clave, DetNomina.Importe, PerDedApo.Concepto, COUNT(DetNomina.Clave) AS Cuenta
    FROM DetNomina 
    INNER JOIN PerDedApo ON DetNomina.Clave = PerDedApo.Clave
    WHERE CvePersonal ='$CveCapturada' AND PerDedApo.TipoPDA=0
    GROUP BY DetNomina.Clave
    ORDER BY DetNomina.Clave ASC";
    $resultado2 = $mysqli->query($consulta2);
    $saltosper = 0;

    while ($row = $resultado2->fetch_assoc()) {

        $plantilla .= '
        ' . $row['Clave'] . ' ' . $row['Concepto'] . ' ' . number_format($row['Importe'], 2, ".", ",")  . ' <br>
        ';
        $saltosper = $saltosper + $row['Cuenta'];
    }
    $plantilla .= ' 
    ';
    for ($i = $saltosper; $i < 6; $i++) {
        $plantilla .= '<br>';
    }
    $plantilla .= ' 
    <img class="escudoagua" src="img/iconos/escudo_agua.jpg" width="500" height="450">
    </td>
    <td colspan="3" cellspacing="0" class="consultaperded" valign="top">';
    //Consulta para enlistar las DEDUCCIONES de cada servidor
    $consulta3 = "SELECT DetNomina.Clave, DetNomina.Importe, PerDedApo.Concepto, COUNT(DetNomina.Clave) AS Cuenta
    FROM DetNomina 
    INNER JOIN PerDedApo ON DetNomina.Clave = PerDedApo.Clave
    WHERE CvePersonal ='$CveCapturada' AND PerDedApo.TipoPDA=1
    GROUP BY DetNomina.Clave
    ORDER BY DetNomina.Clave ASC";
    $resultado3 = $mysqli->query($consulta3);
    $saltosded = 0;
    while ($row = $resultado3->fetch_assoc()) {
        $plantilla .= ' 
        ' . $row['Clave'] . ' ' . $row['Concepto'] . ' ' . number_format($row['Importe'], 2, ".", ",")  . ' <br>
        ';
        $saltosded = $saltosded + $row['Cuenta'];
    }
    $plantilla .= ' 
            ';
    for ($i = $saltosded; $i < 6; $i++) {
        $plantilla .= '<br>';
    }
    $plantilla .= ' 
    <img class="escudoagua" src="img/iconos/escudo_agua.jpg" width="500" height="450">
        </td>
    </tr>
    <tr>
        <td colspan="3" class="totales">Total de percepcionesss: ' . number_format($totpercepciones, 2, ".", ",")  . '</td>
        <td colspan="3" class="totales">Total de deducciones: ' . number_format($totdeducciones, 2, ".", ",")  . '
        </td>
    </tr>
    <tr>
        <td colspan="6">
            <br>
            SE REALIZÓ EL ABONO EN LA CUENTA Num.: ' . $CtaBanco . ' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; EL DÍA: ' . $Alddmmaaaa . ' <br><br><br>
            CONSTITUYE EL RECIBO DE PAGO CORRESPONDIENTE. <br><br><br><br>
        </td>
    </tr>
    <tr>
        <td colspan="6">
            <br><br><br><br><br><br><br><br><br><br><br><br><br><br>
        </td>
    </tr>
</table>
</body>
';

    $mpdf->WriteHTML($plantilla, \Mpdf\HTMLParserMode::HTML_BODY);
    /*Descomentar esta linea en caso de que los reportes no se 
    generen 1 por cada página*/
    //$mpdf->AddPage(); )
}

// Indicando la salida del PDF al navegador
$mpdf->Output();
