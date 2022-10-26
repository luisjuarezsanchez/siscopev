<?php

require 'config.php';

$columns = ['CvePersonal', 'Clave', 'Nombre', 'Concepto'];
$table = "frontexcentosdedapo";

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
        $html .= '<td>' . $row['Nombre'] . '</td>';
        $html .= '<td>' . $row['Clave'] . '</td>';
        $html .= '<td>' . $row['Concepto'] . '</td>';

        $CvePersonal  = $row['CvePersonal'];
        $Clave  = $row['Clave'];

        $html .= '<td>
        <a href="editar_excentos.php?CvePersonal=' . $CvePersonal . ' & Clave=' . $Clave . ' "><img src="img/expedientes/editar.png" height="40" width="40" /></a>
        </td>';

        $html .= '<td>
        <a href="delete_excentos.php?CvePersonal=' . $CvePersonal . ' & Clave=' . $Clave . ' " onclick="return confirm(\'Estás seguro que deseas eliminar el registro?\');"><img src="img/expedientes/eliminar.png" height="40" width="40" /></a>
        </td>';
    }
} else {
    $html .= '<tr>';
    $html .= '<td colspan="11">Sin resultados</td>';
    $html .= '</td>';
}

echo json_encode($html, JSON_UNESCAPED_UNICODE);
