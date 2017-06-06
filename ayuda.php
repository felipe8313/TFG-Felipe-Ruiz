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
        <link rel="shortcut icon" href="resources/imgs/logo.png">
    </head>
    <body>        
        <?php include 'header.php' ?>
        <div class="contenido">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                <?php
                    if (isset($_SESSION['error'])){
                        echo '<div class="alert alert-danger" role="alert">'.$_SESSION['error'].'</div>';
                        unset($_SESSION['error']);
                    }
                    
                    if (isset($_SESSION['mensaje'])){
                        echo '<div class="alert alert-success" role="alert">'.$_SESSION['mensaje'].'</div>';
                        unset($_SESSION['mensaje']);
                    }
                ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <h3 class="tituloApartado">¿Qué es Librarino?</h3>
                    <p class="parrafoAyuda">
                        Librarino es un sistema que facilita al alumnado el acceso a las bibliotecas en períodos de máxima ocupación. A partir de un mapa que simula la ubicación de 
                        los asientos en cada biblioteca de la Universidad de Málaga y el estado de cada uno de ellos, 
                        el alumno podrá comprobar la disponibilidad de asientos sin necesidad de desplazarse a la biblioteca, además de poder reservar un asiento desde casa.
                    </p>
                    <p class="parrafoAyuda">                        
                        Cada asiento físico dispone un dispositivo en donde el alumnado tendrá que identificarse utilizando su carnet universitario. 
                    </p>
                    
                    <h3 class="tituloApartado">¿Cómo puedo reservar un asiento?</h3>
                    <p class="parrafoAyuda">
                        Para reservar un sitio lo primero que tendrás que hacer es iniciar sesión en esta plataforma con las credenciales que se te proporcionaron al darte de alta en el 
                        sistema (si aún no estas dado de alta debes acudir al mostrador de información de cualquier biblioteca de la UMA).                    
                    </p>
                    <div class="row">
                        <div class="col-md-6">
                            <img width="100%" src="resources/imgs/ayuda/inicioSesion1.png">
                        </div>
                        <div class="col-md-6">
                            <img width="100%" src="resources/imgs/ayuda/inicioSesion2.png">
                        </div>
                    </div>
                    <p class="parrafoAyuda">
                        A continuación solo tienes que entrar en la biblioteca que desees, seleccionar una planta y el asiento. Solo deberas pulsar el botón 'Reservar' para realizar la 
                        reserva. <b>Si en el plazo de una hora no has ocupado el asiento, volverá a estar libre.</b>
                    </p>
                    <h3 class="tituloApartado">Una vez en la biblioteca, ¿cómo ocupo el asiento?</h3>
                    <p class="parrafoAyuda">
                        Solo tendrás que pasar el código de barras de tu carnet universitario por el lector que tiene el dispositivo que hay frente a ti. Una vez hecho esto, <b>dispondrás de 
                            3 horas hasta que el asiento vuelva a quedar libre</b>, aunque podrás volver a ocuparlo si alguien no lo ha reservado antes.
                    </p>
                    <h3 class="tituloApartado">¿Y qué pasa cuando quiera irme?</h3>
                    <p class="parrafoAyuda">
                        Para liberar el asiento, pasa de nuevo el carnet universitario por el lector, así de simple.
                    </p>
                    <h3 class="tituloApartado">¿Qué significan los colores de los mapas?</h3>
                    <p class="parrafoAyuda">
                        El estado de cada asiento está representado por un color diferente. El color verde indica que el asiento está <b class="etiquetaLibre">libre</b>, azul si el 
                        asiento está <b class="etiquetaReservado">reservado</b> y el rojo indicará que el asiento está <b class="etiquetaOcupado">ocupado</b>.
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
