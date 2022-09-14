<?php
$host = 'localhost';
$basededatos = 'erbase';
$usuario = 'root';
$contrase = '';

$conexion = new mysqli($host,$usuario,$contrase,$basededatos);
if ($conexion -> connect_error)
{
    die("Fallo la conexion");
}

$tabla="";
$query="SELECT * FROM EmpGral";

//Evento al teclear el campo de busqueda
if(isset($_POST['empleados'])){
    $q=$conexion->real_escape_string($_POST['empleados']);
    $query="SELECT * FROM empgral WHERE Nombre LIKE '%".$q."%'";
}

$buscarEmpleados=$conexion->query($query);
if ($buscarEmpleados->num_rows>0){
    $tabla.=
    '<table class="table">
    <tr>
        <td>a</td>
        <td>a</td>
        <td>a</td>
        <td>a</td>
        <td>a</td>
        <td>a</td>
        <td>a</td>
        <td>a</td>
        <td>a</td>
    </tr>';

    while ($filaEmpleados = $buscarEmpleados->fetch_assoc()){
        $tabla.=
        '<tr>
            <td>'.$filaEmpleados['CvePersonal'].'</td>
            <td>'.$filaEmpleados['RFC'].'</td>
            <td>'.$filaEmpleados['Paterno'].'</td>
            <td>'.$filaEmpleados['Materno'].'</td>
            <td>'.$filaEmpleados['Nombre'].'</td>
            <td>'.$filaEmpleados['CveIsseMyM'].'</td>
            <td>'.$filaEmpleados['FechaIngreso'].'</td>
            <td>'.$filaEmpleados['Nacionalidad'].'</td>
            <td>'.$filaEmpleados['CURP'].'</td>
        </tr>';
    }

    $tabla.='</table>';
}else{
    $tabla="No se encontraron coincidencias con sus criterios de busqueda";
}
    

echo $tabla;
?>


