<?php
session_start();
$usuario = $_SESSION['username'];
if (!isset($usuario)) {
    header("location: index.php");
}
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
                    <div class="icon"> <img src="img/expedientes/empleado.png" alt="" title="Actualización de empleados"> </div>
                    <div class="title"><span>Actualización de empleados</span></div>
                </a>
            </div>


            <div class="item">
                <a href="crud_tlsr1.php">
                    <div class="icon"> <img src="img/expedientes/tlsr1.png" alt="" title="Actualización de tabla tlsr1"> </div>
                    <div class="title"><span>Actualización de tabla tlsr1</span></div>
                </a>
            </div>



            <div class="item">
                <a href="crud_tlsr2.php">
                    <div class="icon"> <img src="img/expedientes/tlsr2.png" alt="" title="Actualización tabla tlsr2"> </div>
                    <div class="title"><span>Actualización tabla tlsr2</span></div>
                </a>
            </div>

            <div class="item">
                <a href="crud_contratos.php">
                    <div class="icon"> <img src="img/expedientes/contratos.png" alt="" title="Actualización de contratos"> </div>
                    <div class="title"><span>Actualización de contratos</span></div>
                </a>
            </div>



            <div class="item">
                <a href="crud_excentos.php">
                    <div class="icon"> <img src="img/expedientes/excentos.png" alt="" title="Actualización de Excentos"> </div>
                    <div class="title"><span>Actualización de Excentos <br> de aportación</span></div>
                </a>
            </div>


            <div class="item">
                <a href="crud_empcont.php">
                    <div class="icon"> <img src="img/expedientes/empcont.png" alt="" title="Actualización de Contratos"> </div>
                    <div class="title"><span>Actualización de Contratos</span></div>
                </a>
            </div>

            <div class="item">
                <a href="renovar_contratos.php">
                    <div class="icon"> <img src="img/expedientes/renovar.png" alt="" title="Renovación de contratos"> </div>
                    <div class="title"><span>Renovación de contratos</span></div>
                </a>
            </div>

            <div class="item">
                <a href="menu.php">
                    <div class="icon"> <img src="img/expedientes/volver.png" alt="" title="Volver"> </div>
                    <div class="title"><span>Volver</span></div>
                </a>
            </div>

        </div>
    </div>

    <!--//////////////////////////////////////////////-->




    <div id="main-container">
        <br>
        <h1 id="tituloTabla">Tabla de Empleados</h1>
        <h5 id="tituloUsuario"><?php echo "Estas modificando como usuario: " . $usuario; ?></h5>
        <form action="">
            <a href="insertar_empleado.php">
                <p href="menu.php" style="text-align:right; margin-right: 20px;"><img src="img/expedientes/agregarusuario.png" height="70" width="70" alt=""></p>
            </a>
            <label for="campo">Buscar:</label>
            <input type="text" name="campo" id="campo" placeholder="Digita tu búsqueda">
        </form>

        <table>
            <thead>
                <tr>
                    <th>Clave Personal</th>
                    <th>RFC</th>
                    <th>Paterno</th>
                    <th>Materno</th>
                    <th>Nombre</th>
                    <th>Clave de Issemym</th>
                    <th>Fecha de ingreso</th>
                    <th>Nacionalidad</th>
                    <th>CURP</th>
                    <th>Editar</th>

                </tr>
            </thead>
            <tbody id="content">

            </tbody>
        </table>

        <script>
            getData() 
            //Programando los eventos de AJAX (Actualizacion en tiempo real)
            document.getElementById('campo').addEventListener("keyup", getData);

            function getData() {
                let input = document.getElementById("campo").value
                let content = document.getElementById("content")
                let url = "load_empleados.php"
                let formaData = new FormData()
                formaData.append('campo', input)
                fetch(url, {
                        method: "POST",
                        body: formaData
                    }).then(response => response.json())
                    .then(data => {
                        content.innerHTML = data
                    }).catch(err => console.log(err))
            }
        </script>
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