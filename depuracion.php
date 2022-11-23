<?php
// Verificando sesion iniciada
session_start();
$usuario = $_SESSION['username'];
if (!isset($usuario)) {
    header("location: index.php");
}
// Recibiendo la clave de la nómina
$CveNomina = $_POST['CveNomina'];

// Solicitando los archivos de la biblioteca
require_once __DIR__ . '/vendor/autoload.php';
// Solicitando los estilos CSS del reporte
$css = file_get_contents('css/comprobantes.css');

// Creando una instancia de la clase 
$mpdf = new \Mpdf\Mpdf();

//Insertado estilos CSS
$mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);

// Efectuando conexion a la Base de Datos
require 'conexion.php';

// Haciendo la consulta SQL
$consulta = "SELECT 
DetNomina.CvePersonal,EmpGral.RFC,CONCAT(EmpGral.Nombre,' ',EmpGral.Paterno,' ',EmpGral.Materno) AS Nombre,EmpCont.CtaBanco,SUBSTR(catbanco.NomBanco,1,4) AS NomBanco,EmpGral.CURP,EmpCont.Dirgral,EmpCont.HrsMen,EmpGral.CveISSEMyM,EmpCont.UnidadRespon,
EmpCont.CodCategoria,catcatego.Descripcion,catcatego.DescCorta,DetNomina.Del,DetNomina.Al,
SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=0) THEN Importe ELSE 0 END)- SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=1) THEN Importe ELSE 0 END) AS sueldobruto,
SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=0) THEN Importe ELSE 0 END) AS totpercepciones,
    SUM(CASE WHEN DetNomina.Clave IN (SELECT PerDedApo.Clave FROM PerDedApo WHERE PerDedApo.TipoPDA=1) THEN Importe ELSE 0 END) AS totdeducciones
,Contratos.Descripcion,ComPerDed.Folio
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

// Escribiendo el codigo HTML

while ($row = $resultado->fetch_assoc()) {
    $mpdf->WriteHTML('
    <body>
        <table border="1" align="center">
            <tr>
                <td colspan="6" width="1000" height="200"><img src="img/iconos/escudo_comprobantes.png" alt=""></td>
            </tr>
    
            <tr>
                <td colspan="6" class="cabecera" width="1000" height="50">COMPROBANTE DE PERCEPCIONES Y DEDUCCIONES &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Recibo: 317860</td>
            </tr>
    
            <tr>
                <td colspan="3" class="informacion" valign="top" height="100" width="500">
                    <strong>Nombre: &nbsp;</strong>ALVAREZ FABELA MARTIN LEONARDO <br>
                    <strong>CURP: &nbsp;</strong>AAFM751201HMCLBR09 <br>
                    <strong>Puesto: &nbsp;</strong>CONT.TIEMPO INDETERMINADO <br>
                    <strong>Dependencia: &nbsp;</strong>SECRETARIA DE CULTURA <br>
                    <strong>Unidad Admva: &nbsp;</strong>DIR GRAL DE CULTURA FISICA Y DEPORTE<br>
                </td>
    
                <td colspan="3" class="informacion" valign="top" height="100" width="500">
                    <strong>RFC: &nbsp;</strong>AAFM751201KK8 <br>
                    <strong>Clave de ISSEMYM: &nbsp;</strong>00620219 <br>
                    <strong>Fecha de pago: &nbsp;</strong>31/10/2022 <br>
                    <strong>Periodo de pago: &nbsp;</strong>16/10/2022 al 31/10/2022 <br>
                    <strong>Código de Unidad Administrativa: &nbsp;</strong>22600005L <br>
                    <strong>Total neto: &nbsp;</strong>1,520.69 <br>
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
                <td colspan="3" cellspacing="0" class="consultaperded" valign="top">
                    0202 SUELDOS EVENTUALES (7)1,740.00 <br>
                    0325 SUBSIDIO AL EMPLEO 99.46 <br> <br>
                    <img class="escudoagua" src="img/iconos/escudo_armas.png" width="500" height="450">
                </td>
                <td colspan="3" cellspacing="0" class="consultaperded" valign="top">
                    5540 SERVICIOS DE SALUD 4.625% 121.59 <br>
                    5541 SISTEMA SOLIDARIO DE REPARTO 6.1% 160.37 <br>
                    5542 CAPITALIZACION INDIVIDUAL 1.4% 36.81 <br>
                    <img class="escudoagua" src="img/iconos/escudo_armas.png" width="500" height="450">
                </td>
            </tr>
    
            <tr>
                <td colspan="3" class="totales">Total de percepciones: 1,839.46</td>
                <td colspan="3" class="totales">Total de deducciones: 318.77
                </td>
            </tr>
    
            <tr>
                <td colspan="6">
                    <br>
                    SE REALIZÓ EL ABONO EN LA CUENTA Num.: 3801816365 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; EL DÍA: 31/10/2022 <br><br><br>
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
    ', \Mpdf\HTMLParserMode::HTML_BODY);
    $mpdf->SetFooter('{PAGENO}');
}



// Indicando la salida del PDF al navegador
$mpdf->Output();
