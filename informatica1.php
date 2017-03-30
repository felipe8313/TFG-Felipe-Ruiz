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
$datos = $bd->consulta('select count(*) as num from Asiento join Mesa m on (m.id = Mesa_id) where Estado = 1 and Biblioteca_Id = 1 and Planta = 1');
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
            <li role="presentation" class="active"><a href="informatica1.php"><h4>Planta 1</h4></a></li>
            <li role="presentation"><a href="informatica2.php"><h4>Planta 2</h4></a></li>
        </ul>
        <div class="contenido">
            <div align="center">
                <h3><?php echo utf8_encode($nombreBiblio) ?></h3>
            </div>
            <div class="row">
                <div class="col-md-12">


                    <?php
                    include 'modulos/panelReserva.php';
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <center>
                                <div id="mapa"></div>
                            </center>
                        </div>
                    </div>
                </div>                
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
                $("#mapa").load('mapas/mapa.php', {biblio: 1, planta: 1}, function () {

                    // Elimino el borde de las tablas
                    $(".table").addClass("mapa");
                    $(".table").removeClass("table-bordered");
                    $(".table").removeClass("table");

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

                    // Añado el parpadeo al asiento ocupado o reservado
                    $("#<?php echo $asientoOcupado ?>").addClass("parpadea");
                    $("#<?php echo $asientoReservado ?>").addClass("parpadea");

                });
            }

        </script>
    </body>
</html>
