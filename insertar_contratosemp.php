<?php
session_start();
$usuario = $_SESSION['username'];
if (!isset($usuario)) {
    header("location: index.php");
}
require 'conexion.php';
//$Incrementable = $_GET['Incrementable'];

//$sql = "SELECT * FROM EmpCont WHERE Incrementable='$Incrementable'";
//$query = mysqli_query($mysqli, $sql);
//$row = mysqli_fetch_array($query);


//echo $Incrementable;

?>
<?php
$conexion = mysqli_connect('localhost', 'root', '', 'Siscopevw2');
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
        <h1 id="tituloTabla">Insertar nuevo contrato de empleado</h1>
        <h5 id="tituloUsuario"><?php echo "Estas modificando como usuario: " . $usuario; ?></h5>
        <br>

        <form class="form-login5" id="insertarempleado" action="#" method="POST">
            <p>Clave de personal</p>
            <p style="text-align:center;"><label><select id="lista" name="CvePersonal">
                        <?php
                        include 'conexion.php';
                        $consulta = "SELECT CvePersonal,CONCAT(Paterno, ' ',Materno,' ',Nombre) AS Nombre FROM EmpGral";
                        $resultado = $mysqli->query($consulta);
                        ?>
                        <form action="" method="post" class="form-login">
                            <?php foreach ($resultado as  $opciones) : ?>
                                <option value="<?php echo $opciones['CvePersonal'] ?>">
                                    <?php echo $opciones['Nombre'] ?>
                                </option>
                            <?php endforeach ?>
                    </select></label></p>

            <p>Cuenta de Banco</p>
            <input id="CtaBanco" class="controls" type="text" name="CtaBanco" placeholder="Ingresa la cuenta de banco" required minlength="18" maxlength="18" pattern="^[0-9]+$" title="Solo se aceptan valores númericos">

            <p>Clave de Contrato</p>
            <input class="controls" type="text" name="CveContrato" placeholder="Ingresa la clave de contrato" required pattern="^[A-Za-z0-9- ]+$" title="Solo se aceptan letras en este campo">

            <!--Tipo empleado de tipo HIDDEN siempre en 0-->
            <input class="controls" type="hidden" name="TipoEmpleado" value="0">

            <p>Inicio de contrato</p>
            <input class="controls" type="date" name="Inicio" required>

            <p>Fin de contrato</p>
            <input class="controls" type="date" name="Fin" required>

            <p>Último día</p>
            <input class="controls" type="date" name="UltDia" required>

            <!--Retenido de tipo HIDDEN siempre en 0-->
            <input class="controls" type="hidden" name="Retenido" value="0">

            <p>Fecha de firma</p>
            <input class="controls" type="date" name="FechaFirma" required>

            <!--PeriodosLab de tipo HIDDEN siempre en 0-->
            <input class="controls" type="hidden" name="PeriodosLab" value="0">

            <!--PeriodosLab de tipo HIDDEN siempre en 0-->
            <input class="controls" type="hidden" name="PeriodosPagAgui" value="0">

            <!--CveHorario de tipo HIDDEN siempre en 0-->
            <input class="controls" type="hidden" name="CveHorario" value="0">

            <!--Numero de plaza de tipo HIDDEN siempre en 10-->
            <input class="controls" type="hidden" name="NumPlaza" value="10">

            <p>Codigo de categoria</p>
            <p style="text-align:center;"><label><select id="lista" name="CodCategoria">
                        <?php
                        include 'conexion.php';
                        $consulta = "SELECT ";
                        $resultado = $mysqli->query($consulta);
                        ?>
                        <form action="" method="post" class="form-login">
                            <?php foreach ($resultado as  $opciones) : ?>
                                <option value="<?php echo $opciones['CodCategoria'] ?>">
                                    <?php echo $opciones['CodCategoria'] ?>
                                </option>
                            <?php endforeach ?>
                    </select></label></p>

            <!--Funciones de tipo HIDDEN siempre en A-->
            <input class="controls" type="hidden" name="Funciones" value="A">

            <!--Actividades de tipo HIDDEN siempre en A-->
            <input class="controls" type="hidden" name="Actividades" value="A">

            <!--SueldoNeto de tipo HIDDEN siempre en 0-->
            <input class="controls" type="hidden" name="SueldoNeto" value="0">

            <!--NumContrato de tipo HIDDEN siempre en 127-->
            <input class="controls" type="hidden" name="NumContrato" value="127">

            <!--Folio de tipo HIDDEN siempre en 62-->
            <input class="controls" type="hidden" name="Folio" value="62">

            <!--CveUniAdm de tipo HIDDEN siempre en 62-->
            <input class="controls" type="hidden" name="CveUniAdm" value="1">

            <!--Codigo de tipo HIDDEN siempre en 62-->
            <input class="controls" type="hidden" name="Codigo" value="1">

            <p>Unidad Responsable</p>
            <input class="controls" type="text" name="UnidadRespon" placeholder="Ingresa la Unidad Responsable" required pattern="^[A-Za-z0-9- ]+$" title="Solo se aceptan letras y números en este campo">

            <p>Codigo de secretaría</p>
            <input class="controls" type="text" name="CodSecre" placeholder="Ingresa el código de secretaría" required pattern="^[A-Za-z0-9- ]+$" title="Solo se aceptan letras y números en este campo">

            <p>Prima Vacacional</p>
            <p style="text-align: center;"><select name="PrimaVac" id="PrimaVac">
                    <option value="0">0</option>
                    <option value="1">1</option>
                </select></p>

            <p>Ubicación física</p>
            <input class="controls" type="text" name="UbicaFisica" placeholder="Ingresa la clave de ubicacion física" required pattern="^[A-Za-z0-9- ]+$" title="Solo se aceptan letras y números en este campo">

            <p>Horas mensuales</p>
            <input class="controls" type="number" name="HrsMen" placeholder="Ingresa las horas mensuales" required minlength="1" maxlength="5" pattern="^[0-9]+$" title="Solo se aceptan valores númericos">

            <p>Costo Hora</p>
            <input class="controls" type="number" name="CostoHra" placeholder="Ingresa el valor por hora" required minlength="1" maxlength="5" pattern="^[0-9]+$" title="Solo se aceptan valores númericos">

            <p>Dirección general</p>
            <p style="text-align: center;"><select name="DirGral" id="DirGral">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                </select></p>

            <br>
            <p style="text-align: center;"><input class="buttons" type="submit" name="enviar" value="Dar de alta"></p>

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