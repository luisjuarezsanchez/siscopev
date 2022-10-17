<?php
$conexion = mysqli_connect('localhost', 'root', '', 'Siscopevw2');
session_start();
$usuario = $_SESSION['username'];
if (!isset($usuario)) {
    header("location: index.php");
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <!--Icono de la pagina web-->
    <link rel="icon" href="img/iconos/escudo_armas.png">
    <link rel="stylesheet" type="text/css" href="css/header.css">
    <link rel="stylesheet" type="text/css" href="css/estilos_p_nomina.css">
    <link rel="stylesheet" type="text/css" href="css/fuente.css">
    <link rel="stylesheet" href="css/menulateral.css">
    <link rel="stylesheet" href="css/tablas.css">


    <!--Titulo de la página-->
    <title>Procesar nómina</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
    <script language="JavaScript" type="text/javascript" src="js/script.js"></script>
</head>



<body>
    <header class="header">
        <div class="logo">
            <a href="menu.php"><img class="logos" src="img/header/escudo_armas.png"></a>
        </div>
        <nav>
            <ul class="nav-links">
                <li id="producto1"><a href="#">Sistema de Nóminas</a></li>
                <li id="producto2"><a href="#">Secretaría de Cultura y Turismo</a></li>

            </ul>
        </nav>
        <a href="https://cultura.edomex.gob.mx/" target="_blank" class="btn"><button>Contacto</button></a>
    </header>
    <!--///////////////////Menú desplegable///////////////////////////-->

    <div id="sidemenu" class="menu-collapsed">
        <!--Header-->
        <div id="header">
            <div id="title"><span>Secretaría de Cultura y Turismo</span></div>
            <div id="menu-btn">
                <div class="btn-hamburger"></div>
                <div class="btn-hamburger"></div>
                <div class="btn-hamburger"></div>
            </div>
        </div>
        <!--Profile-->

        <div id="profile">
            <a href="menu.php">
                <div id="photo"><img src="img/header/logo_vertical.png" alt=""></div>
                <div id="name"><span>Sistema de Nóminas</span></div>
            </a>
        </div>
        <!--Items-->

        <div id="menu-items">
            <div class="item">
                <a href="#">
                    <div class="icon"> <img src="img/expedientes/usuaro.png" alt=""> </div>
                    <div class="title"><span><?php echo "Bienvenido(a): " . $usuario; ?></span></div>
                </a>
            </div>

            <div class="item">
                <a href="procesar_Nomina.php">
                    <div class="icon"> <img src="img/expedientes/volver.png" title="Volver al Menú"> </div>
                    <div class="title"><span>Volver</span></div>
                </a>
            </div>

            <div class="item">
                <a href="sesiones/salir.php">
                    <div class="icon"> <img src="img/expedientes/cerrarsesion.png" title="Cerrar sesión"> </div>
                    <div class="title"><span>Cerrar Sesión</span></div>
                </a>
            </div>

        </div>
    </div>
    <!--//////////////////////////////////////////////-->

    <!--Recibiendo variables por POST-->
    <?php
    // No mostrar los errores de PHP
    error_reporting(0);
    $Nomselect = $_POST['CveNomina'];
    $Cveselect = $_POST['CveEmpleado'];
    //$PerDedAposelect = $_POST['ClavePerDedApo'];
    //$monto = $_POST['monto'];

    ?>

    <br>
    <section id="blog">
        <h4 id="tituloTabla">Percepciones/Deucciones/Aportaciones manuales</h4>
        <br>
        <div>
            <form class="form-login-perdedapo" action="perdedapo_manuales.php" method="post">
                <p>Clave de nómina</p>
                <div style="text-align:center;">
                    <label><select id="lista" name="CveNomina">
                            <?php
                            include 'conexion.php';
                            $consulta = "SELECT CveNomina FROM Nominas WHERE Cerrada=0 ORDER BY CveNomina DESC";
                            $resultado = $mysqli->query($consulta);
                            ?>
                            <form action="perdedapo_manuales.php" method="post" class="form-login">
                                <?php foreach ($resultado as  $opciones) : ?>
                                    <option value="<?php echo $opciones['CveNomina'] ?>">
                                        <?php echo $opciones['CveNomina'] ?>
                                    </option>
                                <?php endforeach ?>
                        </select></label>
                    <?php
                    if ($Nomselect > 0) {
                        echo '<p>Nombre de empleado</p>';
                    }

                    ?>
                    <?php
                    if ($Nomselect > 0) {
                        echo '<label><select id="lista2" name="CveEmpleado">';
                    }
                    ?>
                    <?php
                    $consulta2 = "SELECT CONCAT(EmpGral.Paterno,' ',EmpGral.Materno,' ', EmpGral.Nombre)AS Nombre,DetNomina.CvePersonal FROM DetNomina INNER JOIN EmpGral ON DetNomina.CvePersonal = EmpGral.CvePersonal WHERE DetNomina.CveNomina = '$Nomselect' GROUP BY DetNomina.CvePersonal ORDER BY EmpGral.Paterno";
                    $resultado2 = $mysqli->query($consulta2);
                    ?>
                    <form action="perdedapo_manuales.php" method="post" class="form-login">
                        <?php foreach ($resultado2 as  $opciones2) :
                        ?>
                            <option value="<?php echo $opciones2['CvePersonal'] ?>">
                                <?php echo $opciones2['Nombre']; ?>
                            </option>
                        <?php endforeach ?>
                        </select></label>
                        <br>
                        <input class="buttons" type="submit" name="Enviar" value="Cargar nómina">
                        <br>
                        <?php
                        if ($Nomselect > 0) {
                            echo '<form class="form-login-perdedapo" action="depurar.php" method="post">';
                            echo '<input class="buttons" type="submit" name="Enviar" value="Cargar datos de empleado">';
                            echo '<br>';
                            echo '<br>';
                            echo '</form>';
                        }
                        ?>
                        <br>


                </div>
            </form>
            <br>
            <br>
            <div style="text-align:center;">
                <?php
                if ($Nomselect > 0 and $Cveselect > 0) {
                    echo '<h4 id="tituloTabla">Verifica que los datos cargados sean correctos de lo contrario<br>
                    vuelve a seleccionar los valores correspondientes</h4>';
                    $sql = "SELECT HrsMen,CONCAT(EmpGral.Paterno,' ',EmpGral.Materno,' ',EmpGral.Nombre) AS Nombre,DetNomina.Del,DetNomina.Al,DetNomina.CveNomina,DetNomina.CvePersonal 
                    FROM DetNomina INNER JOIN EmpGral ON DetNomina.CvePersonal=EmpGral.CvePersonal
                    WHERE DetNomina.CveNomina='$Nomselect' AND DetNomina.CvePersonal='$Cveselect' GROUP BY DetNomina.CvePersonal;";
                    $result = mysqli_query($conexion, $sql);
                    while ($mostrar = mysqli_fetch_array($result)) {
                        $Hrsselect = $mostrar['HrsMen'];
                        $Nombreselect = $mostrar['Nombre'];
                        $Delselect = $mostrar['Del'];
                        $Alselect = $mostrar['Al'];
                    }
                    echo "Clave de nomina: " . $Nomselect;
                    echo '<br>';
                    echo "Clave de empleado: " . $Cveselect;
                    echo '<br>';
                    echo "Nombre del empleado: " . $Nombreselect;
                    echo '<br>';
                    echo "Fecha inicio nómina: " . $Delselect;
                    echo '<br>';
                    echo "Fecha fin de nómina: " . $Alselect;
                    echo '<div style="text-align:center;">';
                    echo '<form action="insert_perded.php" method="post">';
                    echo '<label><select id="lista" name="ClavePerDedApo">';
                    $consulta3 = "SELECT Clave,Concepto FROM PerDedApo";
                    $resultado3 = $mysqli->query($consulta3);
                    foreach ($resultado3 as  $opciones3) :
                ?>
                        <option value="<?php echo $opciones3['Clave'] ?>">
                    <?php
                        echo $opciones3['Clave'].' '.$opciones3['Concepto'];
                        echo '</option>';
                    endforeach;
                    echo '</select></label>';
                    echo '<input type="decimal" placeholder="Importe" name="Importe">';
                    echo '<input type="hidden" name="CveNomina" value="' . $Nomselect . '">';
                    echo '<input type="hidden" name="Del" value="' . $Delselect . '">';
                    echo '<input type="hidden" name="Al" value="' . $Alselect . '">';
                    echo '<input type="hidden" name="CvePersonal" value="' . $Cveselect . '">';
                    echo '<input type="hidden" name="HrsMen" value="' . $Hrsselect . '">';

                    echo '<button class="buttons">Insertar PerDedApo manual</button>';
                    echo '</form>';
                }
                    ?>
                    <table>
                        <tr>
                            <thead>
                                <td>Clave</td>
                                <td>Importe</td>
                            </thead>
                        </tr>

                        <?php
                        if ($Nomselect > 0 and $Cveselect > 0) {
                            $sql = "SELECT * FROM DetNomina WHERE CveNomina='$Nomselect' AND CvePersonal='$Cveselect'";
                            $result = mysqli_query($conexion, $sql);
                        }


                        while ($mostrar = mysqli_fetch_array($result)) {
                        ?>
                            <tr>
                                <td><?php echo $mostrar['Clave'] ?></td>
                                <td><?php echo '$ ' . $mostrar['Importe'] ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </table>
                    <br>

            </div>
    </section>
    <br>
    <br>
    <!--/////////////////Animacion del menu desplegable/////////////////-->
    <script>
        const btn = document.querySelector('#menu-btn');
        const menu = document.querySelector('#sidemenu');

        btn.addEventListener('click', e => {
            menu.classList.toggle("menu-expanded");
            menu.classList.toggle("menu-collapsed");

            document.querySelector('body').classList.toggle('body-expanded')
        });
    </script>
    <!--//////////////////////////////////-->
</body>
<footer class="footer">

    <div class="img_footers">
        <img src="img/footer/escudo_armas.png" alt="">
    </div>
    <br>

</footer>

</html>