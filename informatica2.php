<?php
session_start();
include 'clases/bd.class.php';

$asientoOcupado = '';
$asientoReservado = '';
$bd = new bd();

// Obtengo el nombre de la biblioteca
$datos = $bd->consulta('select Nombre from Biblioteca where Id = 1');
$nombreBiblio = $datos[0]['Nombre'];

// Obtengo el número de asientos libres de esta planta
$datos = $bd->consulta('select count(*) as num from Asiento join Mesa m on (m.id = Mesa_id) where Estado = 1 and Biblioteca_Id = 1 and Planta = 2');
$numAsientosLibres = $datos[0]['num'];
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
        <ul class="nav nav-pills nav-justified">
            <li role="presentation"><a href="informatica1.php"><h4>Planta 1</h4></a></li>
            <li role="presentation" class="active"><a href="informatica2.php"><h4>Planta 2</h4></a></li>
        </ul>
        <div class="contenido">
            <div align="center">
                <h3><?php echo utf8_encode($nombreBiblio) ?></h3>
            </div>
            <div class="row">
                <div class="col-md-3 margen">
                    <!-- Panel para mostrar el número de asientos libres-->
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div align="center">
                                <h2>Número de asientos libres</h2>
                                <h1 style="color: green"><?php echo $numAsientosLibres ?></h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 margen">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div style="margin-left: 25% !important">
                                <div id="mapa"></div> 
                            </div>
                        </div>
                    </div>
                </div>                
                <?php
                include 'modulos/panelReserva.php';
                ?>
            </div>
        </div>
        <?php include 'footer.php' ?>        
        <script type="text/javascript" src="resources/jquery.js"></script>
        <script type="text/javascript" src="resources/bootstrap/js/bootstrap.js"></script>
        <script type="text/javascript" src="resources/js/funcionesJs.js"></script>
        <script type="text/javascript">

            $(document).ready(function () {
                recargaMapa();
                setInterval(recargaMapa, 3000);
            });

            function recargaMapa() {
                $("#mapa").load('mapas/mapaInformaticaP2.php', {}, function () {


                    $(".asiento").click(
                            function () {

                                // Obtendo el id del asiento
                                var id = $(this).attr('id');

                                // Indico en la variable del formulario el asiento que se ha clicado
                                $("#asientoReservado").val(id);

                                // Obtengo el estado del asiento
                                var estado = $(this).data('estado');

                                // Segun el estado muestro una información u otra en el modal
                                if (estado === 1) { // asiento libre
                                    $("#contenidoModalReserva").html('<h4>¿Desea reservar este asiento?</h4><br><button type="submit" class="btn btn-primary">Reservar</button>');
                                } else { // asiento reservado/ocupado
                                    $("#contenidoModalReserva").html('<h4>Este asiento está reservado u ocupado</h4>');
                                }

                                // Muestro el modal
                                $("#modalReserva").modal("show");

                            });

                });
            }

        </script>
    </body>
</html>
