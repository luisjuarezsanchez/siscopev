<?php

/*function calculaTiempo($fechaInicio,$fechaFin){
    $datetime1 = date_create($fechaInicio);
    $datetime2 = date_create($fechaFin);
    $interval = date_diff($datetime1,$datetime2);

    return $interval;
    }

    echo "<pre>";
    print_r(calculaTiempo('2018-01-08','2022-06-15'));
    echo "</pre>";*/




    $datos=calculaTiempo('2018-01-08','2022-06-15');


    echo "Años: " . $datos[0];
    echo "<br>";
    echo "Meses: " . $datos[1];
    echo "<br>";
    echo "Dias: " . $datos[2]+1;
    echo "<br>";
    echo "<br>";


    echo "P".$datos[0]."Y".$datos[1]."M".($datos[2]+1)."D";

    function calculaTiempo($fechaInicio,$fechaFin){
        $datetime1 = date_create($fechaInicio);
        $datetime2 = date_create($fechaFin);
        $interval = date_diff($datetime1,$datetime2);

        $tiempo = array();

        foreach($interval as $valor){
            $tiempo[]=$valor;
            
       }
       return $tiempo;
    }
?>