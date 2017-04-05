<div class="col-md-3">
    <div class="panel panel-default">
        <div class="panel-heading">
            Mis reservas
        </div>
        <div class="panel-body">
            <?php
            if (isset($_SESSION['InicioSesion'])) {

                // compruebo si el usuario tiene reservado algún asiento y le muestro el id
                $datos = $bd->consulta("select Id, date_add(HoraReserva, INTERVAL 1 HOUR) as Hora from Asiento where Usuario_reserva = '" . $_SESSION['NIU'] . "'");
                if (is_array($datos)) {
                    $asientoReservado = $datos[0]['Id'];
                    $horaReserva = explode(' ', $datos[0]['Hora']);

                    echo '<center>Tiene reservado el siguiente asiento: <b class="parpadea azul">' . $asientoReservado . '</b><center>';
                    echo '<br>El asiento volverá a estar libre hoy a las ' . $horaReserva[1] . '</center>';
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
                $datos = $bd->consulta("select Id from Asiento where Usuario_ocupacion = '" . $_SESSION['NIU'] . "'");
                if (is_array($datos)) {
                    $asientoOcupado = $datos[0]['Id'];

                    echo '<center>Está ocupando el asiento: <b class="parpadea rojo">' . $asientoOcupado . '</b><center>';
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

<!-- modal para reservar asiento -->
<div id="modalReserva" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Reservar asiento</h4>
            </div>
            <form method="POST" action="controladores/reservaController.php">
                <input type="hidden" name="accion" value="reservar">
                <input type="hidden" id="asientoReservado" name="asientoReservado" value="reservar">
                <div class="modal-body">
                    <div align="center">                        
                        <?php
                        // Si el usuario ya ha reservado un sitio no puede reservar más
                        if (!isset($_SESSION['InicioSesion'])) {
                            echo '<h4>Para reservar un asiento debe iniciar sesión</h4>';
                        } else {
                            if (isset($asientoReservado) && $asientoReservado !=='') {
                                echo '<h4>holaa'.$asientoReservado.'Solo puede reservar un asiento</h4>';
                            } else {
                                echo '<div id="contenidoModalReserva"></div>';
                            }
                        }
                        ?>                    
                    </div>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
