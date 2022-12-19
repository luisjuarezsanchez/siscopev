<?php

require 'config.php';

$columns = ['CveTabulador', 'Anio', 'Nivel', 'Rango', 'SueldoBase', 'Gratificacion', 
'Compensacion', 'Despensa', 'Puesto','Vigente','PuestoAbrev'];
$table = "Tabulador";

$campo = isset($_POST['campo']) ? $conn->real_escape_string($_POST['campo']) : null;
$where = '';

if ($campo != null) {
    $where = "WHERE (";

    $cont = count($columns);
    for ($i = 0; $i < $cont; $i++) {
        $where .= $columns[$i] . " LIKE '%" . $campo . "%' OR ";
    }
    $where = substr_replace($where, "", -3);
    $where .= ")";
}

$sql = "SELECT " . implode(", ", $columns) . "
FROM  $table
$where ";
$resultado = $conn->query($sql);
$num_rows = $resultado->num_rows;

$html = '';

if ($num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $html .= '<tr>';
        $html .= '<td>' . $row['CveTabulador'] . '</td>';
        $html .= '<td>' . $row['Anio'] . '</td>';
        $html .= '<td>' . $row['Nivel'] . '</td>';
        $html .= '<td>' . $row['Rango'] . '</td>';
        $html .= '<td>' . $row['SueldoBase'] . '</td>';
        $html .= '<td>' . $row['Gratificacion'] . '</td>';
        $html .= '<td>' . $row['Compensacion'] . '</td>';
        $html .= '<td>' . $row['Despensa'] . '</td>';
        $html .= '<td>' . $row['Puesto'] . '</td>';
        $html .= '<td>' . $row['Vigente'] . '</td>';
        $html .= '<td>' . $row['PuestoAbrev'] . '</td>';
        $html .= '<td><img src="img/expedientes/editar.png" height="40" width="40" title="Editar"></td>';
        $html .= '<td><img src="img/expedientes/eliminar.png" height="40" width="40" title="Eliminar"></td>';
        $html .= '</td>';
    }
} else {
    $html .= '<tr>';
    $html .= '<td colspan="11">Sin resultados</td>';
    $html .= '</td>';
}

echo json_encode($html, JSON_UNESCAPED_UNICODE);
