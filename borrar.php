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




    $fecha1='2021-10-15';//Restarle 1
    $fecha2='2022-06-15 ';

    $fechainicial=new DateTime($fecha1);
    $fechafin=new DateTime($fecha2);

    $diferencia=$fechainicial-> diff($fechafin);

    echo $diferencia-> format('P %Y Y %M M %D D');
    echo "<br>";
    echo $diferencia-> format('P %Y');
    echo sprintf($formato);





    /*
    $datos=calculaTiempo('2018-01-08','2022-06-15');

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
    }*/
?>