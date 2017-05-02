<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<?php
session_start();
include_once 'header.php';
include_once 'menuLateral.php';
include_once '../clases/bd.class.php';
include_once '../controladores/funcionesComunes.php';
$bd = new bd();
error_reporting(0);

if (!isset($_SESSION['InicioSesion']) && !$_SESSION['InicioSesion']){
    header('Location: index.php');
}


?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Asientos</title>
        <link rel="stylesheet" type="text/css" href="resources/menu_style.css"/>
        <link rel="stylesheet" type="text/css" href="resources/admin_style.css"/>
        <link rel="stylesheet" type="text/css" href="resources/datatables/dataTables.min.css"/> 
        <link rel="stylesheet" type="text/css" href="../resources/bootstrap/css/bootstrap.css"/>
        <link rel="stylesheet" type="text/css" href="../resources/style.css"/>
        <link rel="shortcut icon" href="resources/imgs/logo.png">
    </head>
    <body>
        <div class="content">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="col-md-6">
                        <label>Biblioteca</label>
                        <select id="biblioteca" onchange="cargaSelectPlantas(this.value)" class="form-control">
                            <option>Seleccione una biblioteca</option>
                            <?php echo getBibliotecas($bd, true) ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Planta</label>
                        <select id="planta" onchange="cargaPlanta(this.value)" class="form-control">
                            <option>Seleccione una planta</option>
                        </select>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div id="mapa">
                    <?php
                    echo '<table class="table table-bordered">';
                    for ($i = 0; $i <= 30; $i++) {
                        echo '<tr>';
                        for ($j = 0; $j <= 30; $j++) {
                            echo '<td id="x' . $i . 'y' . $i . '">&ensp;</td>';
                        }
                        echo '</tr>';
                    }
                    echo '</table>';
                    ?>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="modalModiMesa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Modificar mesa</h4>
                    </div>
                    <input type="hidden" id="mesaId" value="">
                    <div class="modal-body">
                        <label>Número de asientos</label>
                        <input class="form-control" type="number" min="1" id="modiNumAsientos" value="">
                        <br>
                        <label>Rotación (0 - 180 grados)</label>
                        <input class="form-control" type="number" max="180" min="0" id="modiGradosRot" value="">
                        <br>
                        <label>Activa</label>
                        <select required="true" class="form-control" id="modiAct" name="modiAct" >
                            <option value="1">Activada</option>
                            <option value="0">Desactivada</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <center>
                            <button onclick="modificarMesa()" class="btn btn-default"><span class="glyphicon glyphicon-ok"></span>&ensp;Guardar</button>
                            <button onclick="eliminarMesa()" class="btn btn-default"><span class="glyphicon glyphicon-remove"></span>&ensp;Eliminar</button>
                        </center>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modalCreaMesa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Crear mesa</h4>
                    </div>
                    <input type="hidden" id="creaX" value="">
                    <input type="hidden" id="creaY" value="">
                    <div class="modal-body">
                        <label>Número de asientos</label>
                        <input class="form-control" type="number" min="1" id="creaNumAsientos" value="">
                        <br>
                        <label>Rotación (0 - 180 grados)</label>
                        <input class="form-control" type="number" max="180" min="0" id="creaGradosRot" value="0">
                        <br>
                        <select required="true" class="form-control" id="creaAct" name="creaAct" >
                            <option selected="selected" value="1">Activada</option>
                            <option value="0">Desactivada</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button onclick="crearMesa()" class="btn btn-default"><span class="glyphicon glyphicon-ok"></span>&ensp;Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script type="text/javascript" src="../resources/jquery.js"></script>
    <script type="text/javascript" src="../resources/bootstrap/js/bootstrap.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
                            $(document).ready(function () {
                                $("#asientos").addClass("selectedItem");
                            });

                            function getBiblioActual() {

                                // Obtengo la biblioteca actual
                                var infoBiblio = $("#biblioteca").val();
                                var res = infoBiblio.split("/");
                                var biblio = res[0];

                                return biblio;
                            }

                            function cargaSelectPlantas(info) {
                                var i;
                                var res = info.split("/");
                                var plantas = res[1];

                                // Reiniciamos el select
                                $("#planta").html("<option>Seleccione una planta</option>");

                                for (i = 1; i <= plantas; i++) {
                                    $('#planta').append($('<option>', {
                                        value: i,
                                        text: 'Planta ' + i
                                    }));
                                }
                            }

                            function cargaPlanta(planta) {

                                var biblio = getBiblioActual();

                                $("#mapa").load('controladores/asientosController.php', {accion: 'cargarMapa', biblio: biblio, planta: planta},
                                function () {
                                    $(".numAsientos").css("cursor", "move");
                                    $(".numAsientos").draggable();
                                    $(".numAsientos").click(function () {

                                        var mesa = $(this);
                                        var asientos = mesa.data("asientos");
                                        var gradosRot = mesa.data("rot");
                                        var id = mesa.data("id");
                                        var activa = mesa.data("activa");

                                        // Cargo los datos de la mesa
                                        $("#mesaId").val(id);
                                        $("#modiPlanta").val(planta);
                                        $("#modiBiblio").val(biblio);
                                        $("#modiNumAsientos").val(asientos);
                                        $("#modiGradosRot").val(gradosRot);
                                        $("#modiAct").val(activa);
                                        $("#modalModiMesa").modal('show');
                                    });

                                    $(".suelta").dblclick(function () {

                                        var celda = $(this);
                                        $("#creaX").val(celda.data("x"));
                                        $("#creaY").val(celda.data("y"));
                                        $("#modalCreaMesa").modal('show');

                                    });

                                    $(".suelta").droppable({
                                        drop: function (event, ui) {

                                            var celda = $(this);
                                            var celdaX = celda.data("x");
                                            var celdaY = celda.data("y");
                                            var mesaId = ui.draggable.data("id");

                                            // Actualizo la nueva localización mediante ajax
                                            $.ajax({
                                                type: 'POST',
                                                url: 'controladores/asientosController.php',
                                                data: {accion: 'actualizaPosicion', mesaId: mesaId, celdaX: celdaX, celdaY: celdaY},
                                                success: function () {
                                                    cargaPlanta(planta);
                                                }
                                            });

                                        }
                                    });
                                });
                            }

                            function modificarMesa() {

                                var id = $("#mesaId").val();
                                var asientos = $("#modiNumAsientos").val();
                                var gradosRot = $("#modiGradosRot").val();
                                var planta = $("#planta").val();
                                var activa = $("#modiAct").val();

                                if (asientos === '' || gradosRot === '' || activa === '') {
                                    alert('Debe completar todos los campos');
                                } else {
                                    $.ajax({
                                        type: "POST",
                                        data: {accion: 'modiMesa', asientos: asientos, id: id, gradosRot: gradosRot, activa: activa},
                                        url: 'controladores/asientosController.php',
                                        success: function () {
                                            $("#modalModiMesa").modal('hide');
                                            cargaPlanta(planta);
                                        }
                                    });
                                }

                            }

                            function eliminarMesa() {

                                var id = $("#mesaId").val();
                                var asientos = $("#modiNumAsientos").val();
                                var planta = $("#planta").val();

                                $.ajax({
                                    type: "POST",
                                    data: {accion: 'eliminarMesa', id: id, asientos: asientos},
                                    url: 'controladores/asientosController.php',
                                    success: function () {
                                        $("#modalModiMesa").modal('hide');
                                        cargaPlanta(planta);
                                    }
                                });

                            }

                            function crearMesa() {

                                var asientos = $("#creaNumAsientos").val();
                                var gradosRot = $("#creaGradosRot").val();
                                var planta = $("#planta").val();
                                var biblio = getBiblioActual();
                                var activa = $("#creaAct").val();
                                var x = $("#creaX").val();
                                var y = $("#creaY").val();

                                if (asientos === '' || gradosRot === '' || activa === '') {
                                    alert('Debe completar todos los campos');
                                } else {
                                    $.ajax({
                                        type: "POST",
                                        data: {accion: 'crearMesa', asientos: asientos, gradosRot: gradosRot, planta: planta, biblio: biblio, x: x, y: y, activa: activa},
                                        url: 'controladores/asientosController.php',
                                        success: function () {
                                            $("#modalCreaMesa").modal('hide');
                                            cargaPlanta(planta);
                                        }
                                    });
                                }
                            }






    </script>
</html>
