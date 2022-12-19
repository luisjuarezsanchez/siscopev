<?php
session_start();
$usuario = $_SESSION['username'];
if (!isset($usuario)) {
    header("location: index.php");
}
require 'conexion.php';
$CveEmpCont = $_GET['CveEmpCont'];

$sql = "SELECT * FROM EmpCont WHERE CveEmpCont='$CveEmpCont'";
$query = mysqli_query($mysqli, $sql);
$row = mysqli_fetch_array($query);


?>
<?php
?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <!--Icono de la pagina web-->
    <link rel="icon" href="img/iconos/escudo_armas.png">
    <!--Titulo de la página-->
    <title>Datos de empleados</title>

    <link rel="icon" href="img/menu/icono.png">
    <link rel="stylesheet" type="text/css" href="css/estilos_menu.css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous"></script>
    <!--Referencias de estilos CSS-->
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" type="text/css" href="css/fuente.css">
    <link rel="stylesheet" href="css/menulateral.css">
    <link rel="stylesheet" href="css/tablas.css">

</head>


<body>
    <!--Header de la pagina web-->
    <header class="header">
        <div class="logo">
            <a href="menu.php"><img class="logos" src="img/header/escudo_armas.png"></a>
        </div>
        <nav>
            <ul class="nav-links">
                <li id="producto1"><a href="#">Sistema de Nóminas</a></li>
                <li id="producto2"><a href="#">Secretaría de Cultura y Turismo</a></li>
                <li><a href="#"></a></li>
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
                <a href="crud_empleados.php">
                    <div class="icon"> <img src="img/expedientes/empleado.png" alt=""> </div>
                    <div class="title"><span>Actualización de empleados</span></div>
                </a>
            </div>


            <div class="item">
                <a href="crud_tlsr1.php">
                    <div class="icon"> <img src="img/expedientes/tlsr1.png" alt=""> </div>
                    <div class="title"><span>Actualización de tabla tlsr1</span></div>
                </a>
            </div>



            <div class="item">
                <a href="crud_tlsr2.php">
                    <div class="icon"> <img src="img/expedientes/tlsr2.png" alt=""> </div>
                    <div class="title"><span>Actualización tabla tlsr2</span></div>
                </a>
            </div>

            <div class="item">
                <a href="crud_contratos.php">
                    <div class="icon"> <img src="img/expedientes/contratos.png" alt=""> </div>
                    <div class="title"><span>Actualización de contratos</span></div>
                </a>
            </div>



            <div class="item">
                <a href="crud_excentos.php">
                    <div class="icon"> <img src="img/expedientes/excentos.png" alt=""> </div>
                    <div class="title"><span>Actualización de Excentos <br> de aportación</span></div>
                </a>
            </div>


            <div class="item">
                <a href="crud_tabulador.php">
                    <div class="icon"> <img src="img/expedientes/tabulador.png" alt=""> </div>
                    <div class="title"><span>Actualización de tabuladores</span></div>
                </a>
            </div>

            <div class="item">
                <a href="crud_empcont.php">
                    <div class="icon"> <img src="img/expedientes/empcont.png" alt=""> </div>
                    <div class="title"><span>Actualización de Contratos</span></div>
                </a>
            </div>

            <div class="item">
                <a href="crud_empcont.php">
                    <div class="icon"> <img src="img/expedientes/volver.png" alt=""> </div>
                    <div class="title"><span>Volver</span></div>
                </a>
            </div>

        </div>
    </div>

    <!--//////////////////////////////////////////////-->

    <div id="main-container">
        <br>
        <h1 id="tituloTabla">Editar campos de la tabla EmpCont</h1>
        <h5 id="tituloUsuario"><?php echo "Estas modificando como usuario: " . $usuario; ?></h5>


        <div id="campos_act"></div>
        <form id="editar4" action="update_empcont.php" method="POST">

            <input type="hidden" name="CveEmpCont" value="<?= $row['CveEmpCont'] ?>">
            <input type="hidden" name="CvePersonal" value="<?= $row['CvePersonal'] ?>">

            <label>Clave de Personal</label><br>
            <input disabled type="text" name="CvePersonal" value="<?= $row['CvePersonal'] ?>">
            <br><br>

            <label>Cuenta de banco</label><br>
            <input type="text" name="CtaBanco" value="<?= $row['CtaBanco'] ?>" required minlength="18" maxlength="18" pattern="^[0-9]+$" title="Solo se aceptan valores númericos">
            <br><br>

            <label>Clave de contrato</label><br>
            <input type="text" name="CveContrato" value="<?= $row['CveContrato'] ?>" pattern="^[A-Za-z0-9- ]+$" title="Digita un formato válido">
            <br><br>

            <label>Tipo de empleado</label><br>
            <input type="text" name="TipoEmpleado" value="<?= $row['TipoEmpleado'] ?>" required minlength="1" maxlength="1" pattern="^[0-1]+$" title="Solo se aceptan los valores 0 y 1">
            <br><br>

            <label>Inicio</label><br>
            <input type="date" name="Inicio" value="<?= $row['Inicio'] ?>" required>
            <br><br>

            <label>Fin</label><br>
            <input type="date" name="Fin" value="<?= $row['Fin'] ?>">
            <br><br>

            <label>Ultimo día</label><br>
            <input type="date" name="UltDia" value="<?= $row['UltDia'] ?>" required>
            <br><br>

            <label>Código de categoría</label><br>
            <input type="hidden" name="CodCategoria" value="<?= $row['CodCategoria'] ?>">
            <input disabled type="hidden" name="CodCategoria" value="<?= $row['CodCategoria'] ?>" required minlength="8" maxlength="8" pattern="^[A-Z0-9]+$" title="Digita un formato válido">
            <p style="text-align:center;"><label><select id="lista" name="CodCategoria">
                        <?php
                        include 'conexion.php';
                        $codigoUpdate = $row['CodCategoria'];
                        $consulta = "SELECT CveCategoria,Descripcion,CostoHra FROM catcatego ORDER BY CASE WHEN CveCategoria = '$codigoUpdate' THEN 1 ELSE 2 END,CveCategoria";
                        $resultado = $mysqli->query($consulta);
                        ?>
                        <form action="" method="post" class="form-login">
                            <?php foreach ($resultado as  $opciones) : ?>
                                <option value="<?php echo $opciones['CveCategoria'] ?>">
                                    <?php echo $opciones['CveCategoria'].' $'. $opciones['CostoHra'] ?>
                                </option>
                            <?php endforeach ?>
                    </select></label></p>
    

            <label>Prima vacacional</label><br>
            <input type="text" name="PrimaVac" value="<?= $row['PrimaVac'] ?>" required minlength="1" maxlength="1" pattern="^[0-1]+$" title="Solo se aceptan los valores 0 y 1">
            <br><br>

            <label>Horas mensuales</label><br>
            <input type="text" name="HrsMen" value="<?= $row['HrsMen'] ?>" required minlength="1" maxlength="3" pattern="^[0-9]+$" title="Solo se aceptan valores númericos">
            <br><br>

            <input type="submit" name="enviar" value="Actualizar">

        </form>

    </div>
    <br>

    </div>
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
<!--Footer de la página web-->
<footer class="footer">
    <div class="img_footers">
        <img src="img/footer/escudo_armas.png" alt="">
    </div>
</footer>

</html>