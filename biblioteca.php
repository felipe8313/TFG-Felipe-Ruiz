<?php
session_start();
include 'clases/bd.class.php';

$biblioteca = (int) $_GET['id'];
$planta = (int) $_GET['planta'];


$asientoOcupado = '';
$asientoReservado = '';

$bd = new bd();

// Obtengo el nombre y el número de plantas de la biblioteca
$datos = $bd->consulta('select Nombre, Plantas from Biblioteca where Id = ' . $biblioteca);
$nombreBiblio = $datos[0]['Nombre'];
$plantas = $datos[0]['Plantas'];

// Obtengo el número de asientos libres de esta planta
$datos = $bd->consulta('select count(*) as num from Asiento join Mesa m on (m.id = Mesa_id) where Estado = 1 and Biblioteca_Id = ' . $biblioteca . ' and Planta = ' . $planta);
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
            <?php
            if ($plantas > 1) {
                for ($i = 1; $i <= $plantas; $i++) {
                    if ($planta === $i) {
                        $activa = 'class="active"';
                    } else {
                        $activa = '';
                    }
                    echo '<li ' . $activa . ' role="presentation"><a href="biblioteca.php?id=' . $biblioteca . '&planta=' . $i . '"><h4>Planta ' . $i . '</h4></a></li>';
                }
            }
            ?>
        </ul>
        <div class="contenido">
            <div align="center">
                <h3><?php echo utf8_encode($nombreBiblio) ?></h3>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <?php
                    if (isset($_SESSION['error'])) {
                        echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error'] . '</div>';
                        unset($_SESSION['error']);
                    }

                    if (isset($_SESSION['mensaje'])) {
                        echo '<div class="alert alert-success" role="alert">' . $_SESSION['mensaje'] . '</div>';
                        unset($_SESSION['mensaje']);
                    }
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-3">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Mis reservas
                            </div>
                            <div class="panel-body">
                                <?php
                                if (isset($_SESSION['InicioSesion'])) {

                                    // compruebo si el usuario tiene reservado algún asiento y le muestro el id
                                    $datos = $bd->consulta("select a.Id, date_add(HoraReserva, INTERVAL 1 HOUR) as Hora, Nombre, Planta from Asiento a join Mesa m on (m.id = a.Mesa_Id) join Biblioteca b on (b.id = m.Biblioteca_id) where Usuario_reserva = '" . $_SESSION['NIU'] . "'");
                                    if (is_array($datos)) {
                                        $asientoReservado = $datos[0]['Id'];
                                        $horaReserva = explode(' ', $datos[0]['Hora']);
                                        $bibliotecaRes = $datos[0]['Nombre'];
                                        $plantaRes = $datos[0]['Planta'];

                                        echo '<b>Biblioteca: </b>' . utf8_encode($bibliotecaRes) . '<br>';
                                        echo '<b>Planta: </b>' . utf8_encode($plantaRes) . '<br>';
                                        echo '<b>Asiento: </b><b class="parpadea azul">' . $asientoReservado . '</b>';
                                        echo '<br>El asiento volverá a estar libre hoy a las ' . $horaReserva[1] . '';
                                        echo '<form method="POST" action="controladores/reservaController.php"><input type="hidden" name="accion" value="cancelarReserva"><input type="hidden" name="asientoReservado" value="' . $asientoReservado . '">'
                                        . '<div align="center"><br><input type="submit" class="btn btn-primary" value="Cancelar reserva"></div></form>';
                                    } else {
                                        echo '<center>Todavía no ha reservado ningún asiento</center>';
                                    }
                                } else {
                                    echo '<center>Para reservar un asiento debe iniciar sesión</center>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Panel para mostrar el número de asientos libres-->
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div align="center">
                                    <h3>Número de asientos libres</h3>
                                    <h2 style="color: green"><?php echo $numAsientosLibres ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>  


                    <div class="col-md-3">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Mi asiento
                            </div>
                            <div class="panel-body">
                                <?php
                                if (isset($_SESSION['InicioSesion'])) {

                                    // compruebo si el usuario tiene reservado algún asiento y le muestro el id
                                    $datos = $bd->consulta("select a.Id, Nombre, Planta from Asiento a join Mesa m on (m.id = a.Mesa_Id) join Biblioteca b on (b.id = m.Biblioteca_id) where Usuario_ocupacion ='" . $_SESSION['NIU'] . "'");
                                    if (is_array($datos)) {
                                        $asientoOcupado = $datos[0]['Id'];
                                        $bibliotecaOcu = $datos[0]['Nombre'];
                                        $plantaOcu = $datos[0]['Planta'];

                                        echo '<b>Biblioteca: </b>' . utf8_encode($bibliotecaOcu) . '<br>';
                                        echo '<b>Planta: </b>' . utf8_encode($plantaOcu) . '<br>';
                                        echo '<b>Asiento: </b><b class="parpadea rojo">' . $asientoOcupado . '</b>';
                                    } else {
                                        echo '<center>Todavía no ha ocupado ningún asiento</center>';
                                    }
                                } else {
                                    echo '<center>Para reservar un asiento debe iniciar sesión</center>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
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

        <!-- modal para reservar asiento -->
        <div id="modalReserva" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Asiento</h4>
                    </div>
                    <div class="modal-body">
                        <div align="center">                        
                            <?php
                            // Si el usuario ya ha reservado un sitio no puede reservar más
                            if (!isset($_SESSION['InicioSesion'])) {
                                echo '<h4>Para reservar un asiento debe iniciar sesión</h4>';
                            } else {
                                if ((isset($asientoReservado) && $asientoReservado !== '') || (isset($asientoOcupado) && $asientoOcupado !== '')) {
                                    echo '<h4>Solo puede reservar u ocupar un asiento al mismo tiempo</h4>';
                                } else {
                                    echo '<form method="POST" action="controladores/reservaController.php">';
                                    echo '<input type="hidden" name="accion" value="reservar">';
                                    echo '<input type="hidden" id="asientoReservado" name="asientoReservado" value="">';
                                    echo '<div id="contenidoModalReserva"></div>';
                                    echo '</form>';
                                }

                                // Para notificar una incidencia
                                echo '<hr>';
                                echo '<form method="POST" action="controladores/reservaController.php">';
                                echo '<input type="hidden" name="accion" value="incidencia">';
                                echo '<input type="hidden" id="asientoIncidencia" name="asientoIncidencia" value="">';
                                echo '<h4>Notificar una incidencia</h4><br>';
                                echo '<textarea id="txtIncidencia" name="txtIncidencia" class="form-control" col="4" rows="6"></textarea><br>';
                                echo '<button class="btn btn-default">Notificar</button>';
                                echo '</form>';
                            }
                            ?>                    
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
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
                $("#mapa").load('mapa.php', {biblio: <?php echo $biblioteca ?>, planta: <?php echo $planta ?>}, function () {

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
                                $("#asientoIncidencia").val(id);

                                // Obtengo el estado del asiento
                                var estado = $(this).data('estado');

                                // Segun el estado muestro una información u otra en el modal
                                if (estado === 1) { // asiento libre
                                    $("#contenidoModalReserva").html('<h4>¿Desea reservar este asiento?</h4><br><button type="submit" class="btn btn-default">Reservar</button>');
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
