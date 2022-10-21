<?php

require 'config.php';

$columns = ['CvePersonal', 'RFC', 'Paterno', 'Materno', 'Nombre', 'CveISSEMyM', 'FechaIngreso', 'Nacionalidad', 'CURP'];
$table = "EmpGral";

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
        $html .= '<td>' . $row['CvePersonal'] . '</td>';
        $html .= '<td>' . $row['RFC'] . '</td>';
        $html .= '<td>' . $row['Paterno'] . '</td>';
        $html .= '<td>' . $row['Materno'] . '</td>';
        $html .= '<td>' . $row['Nombre'] . '</td>';
        $html .= '<td>' . $row['CveISSEMyM'] . '</td>';
        $html .= '<td>' . $row['FechaIngreso'] . '</td>';
        $html .= '<td>' . $row['Nacionalidad'] . '</td>';
        $html .= '<td>' . $row['CURP'] . '</td>';
        $html .= '<td><img src="img/expedientes/editar.png" height="40" width="40" title="Editar"></td>';
        //$html .= '<td><img src="img/expedientes/eliminar.png" height="40" width="40" title="Eliminar"></td>';
        $html .= '</td>';
    }
} else {
    $html .= '<tr>';
    $html .= '<td colspan="11">Sin resultados</td>';
    $html .= '</td>';
}

echo json_encode($html, JSON_UNESCAPED_UNICODE);
