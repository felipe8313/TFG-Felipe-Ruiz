<?php
session_start();
include 'clases/bd.class.php';
?>
<html>
    <head>
        <title>Bienvenido a Librarino</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="resources/style.css"/>
        <link rel="stylesheet" type="text/css" href="resources/bootstrap/css/bootstrap.css"/>
    </head>
    <body>        
        <?php include 'header.php' ?>
        <div class="contenido">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <h3 class="tituloApartado">¿Qué es Librarino?</h3>
                    <p class="parrafoAyuda">
                        Librarino es un sistema que facilita al alumnado el acceso a las bibliotecas en períodos de máxima ocupación. En esta web puede verse un mapa con la disposición de cada asiento y el estado del mismo, 
                        pudiendo reservar alguno desde casa.    
                    </p>
                    <p class="parrafoAyuda">                        
                        Cada asiento físico dispone un dispositivo en donde el alumnado tendrá que identificarse utilizando su carné universatario.
                    </p>
                    
                </div>    
            </div>
        </div>
        <?php include 'footer.php' ?>        
        <script type="text/javascript" src="resources/jquery.js"></script>
        <script type="text/javascript" src="resources/bootstrap/js/bootstrap.js"></script>
        <script type="text/javascript" src="resources/js/funcionesJs.js"></script>
    </body>
</html>
