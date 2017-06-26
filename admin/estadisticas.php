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
error_reporting(0);

if (!isset($_SESSION['InicioSesion']) && !$_SESSION['InicioSesion']) {
    header('Location: index.php');
}

// El usuario normal no tiene permisos para acceder aquí
if ($_SESSION['Rol'] === 1) {
    error('No está autorizado a ver la página anterior', false);
    header('Location: ../');
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Estadísticas</title>
        <link rel="stylesheet" type="text/css" href="resources/menu_style.css"/>
        <link rel="stylesheet" type="text/css" href="resources/admin_style.css"/>
        <link rel="stylesheet" type="text/css" href="resources/select2/css/select2.min.css"/>
        <link rel="stylesheet" type="text/css" href="../resources/style.css"/>
        <link rel="stylesheet" type="text/css" href="../resources/bootstrap/css/bootstrap.css"/>
        <link rel="stylesheet" type="text/css" href="../resources/style.css"/>
        <link rel="shortcut icon" href="../resources/imgs/logo.png">
        <link rel="stylesheet" href="resources/datepicker/jquery-ui.css">
    </head>
    <body>
        <?php
        $bd = new bd();
        ?>
        <div class="content">
            <div class="col-md-12">                
                <div class="row">
                    <h3 class="tituloApartado">Mensual por biblioteca</h3>
                    <div class="col-md-4">                        
                        <label>Año</label>
                        <select id="anio" class="form-control">
                            <option value="">Seleccione un año...</option>
                            <?php
                            $anioActual = getAnioActual();

                            for ($i = $anioActual; $i > $anioActual - 10; $i--) {
                                echo '<option value="' . $i . '">' . $i . '</option>';
                            }
                            ?>
                        </select>                        
                        <br>
                        <br>
                        <label>Bibliotecas</label>
                        <select class="form-control" id="bibliotecas" multiple>
                            <?php echo getBibliotecas($bd) ?>
                        </select>                        
                        <br>
                        <br>
                        <center>
                            <button class="btn btn-default" onclick="cargaGraficoMensual()"><span class="glyphicon glyphicon-search"></span>&ensp; Cargar gráfico</button>
                        </center>
                    </div>
                    <div class="col-md-8">
                        <center id="contenedorGraficoMensual">
                        </center>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <h3 class="tituloApartado">Total en rango de fechas</h3>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" class="form-control datepicker" placeholder="Desde..." id="desde">
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control datepicker" placeholder="Hasta..." id="hasta">
                            </div>
                        </div>
                        <br><br>
                        <center>
                            <button class="btn btn-default" onclick="cargaGraficoRango()"><span class="glyphicon glyphicon-search"></span>&ensp; Cargar gráfico</button>
                        </center>
                    </div>
                    <div class="col-md-8 col-xs-8 col-lg-8">
                        <center id="contenedorGraficoRango">
                        </center>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <h3 class="tituloApartado">Por tipo de usuario, biblioteca y rango de fechas</h3>
                    <div class="col-md-4">
                        <label>Biblioteca</label>
                        <select class="form-control" id="biblioteca">
                            <option value="">Seleccione una opción...</option>
                            <?php echo getBibliotecas($bd) ?>
                        </select>
                        <br><br>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" class="form-control datepicker" placeholder="Desde..." id="desdeTipo">
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control datepicker" placeholder="Hasta..." id="hastaTipo">
                            </div>
                        </div>
                        <br><br>
                        <center>
                            <button class="btn btn-default" onclick="cargaGraficoTipoUsuario()"><span class="glyphicon glyphicon-search"></span>&ensp; Cargar gráfico</button>
                        </center>
                    </div>
                    <div class="col-md-8">
                        <center id="contenedorGraficoTipo">
                        </center>
                    </div>
                </div>
            </div>
        </div>                         
    </body>
    <script type="text/javascript" src="../resources/jquery.js"></script>
    <script type="text/javascript"src="resources/datepicker/jquery-ui.js"></script>
    <script type="text/javascript" src="../resources/bootstrap/js/bootstrap.js"></script>
    <script type="text/javascript" src="resources/chart/Chart.bundle.min.js"></script>
    <script type="text/javascript" src="resources/select2/js/select2.full.min.js"></script>

    <script>
                                $(document).ready(function () {

                                    $("#bibliotecas").select2({
                                        tags: true
                                    });

                                    $(".datepicker").datepicker({
                                        dateFormat: "dd-mm-yy",
                                        dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
                                        monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre",
                                            "Noviembre", "Diciembre"],
                                        firstDay: 1
                                    });

                                    $("#estadisticas").addClass("selectedItem");

                                });

                                function cargaGraficoMensual() {

                                    var anio = $("#anio").val();
                                    var bibliotecas = $("#bibliotecas").val();

                                    if (anio === '' || bibliotecas === null) {
                                        alert("Debe completar todos los parámetros del filtro");
                                    } else {

                                        // Reseteo el gráfico
                                        $("#graficaMensualTodasBiblios").remove();
                                        $("#contenedorGraficoMensual").append('<canvas style=" margin: 0" id="graficaMensualTodasBiblios"></canvas>');

                                        $.ajax({
                                            type: 'POST',
                                            data: {accion: 'graficaMensualTodasBiblios', anio: anio, bibliotecas: bibliotecas},
                                            url: 'controladores/estadisticasController.php',
                                            success: function (response) {
                                                console.log(response);
                                                var ctx = $("#graficaMensualTodasBiblios");
                                                var myChart = new Chart(ctx, {
                                                    type: 'bar',
                                                    data: response,
                                                    options: {
                                                        scales: {
                                                            yAxes: [{
                                                                    ticks: {
                                                                        beginAtZero: true
                                                                    },
                                                                    scaleLabel: {
                                                                        display: true,
                                                                        labelString: 'Número de ocupaciones'
                                                                    }
                                                                }],
                                                            xAxes: [{
                                                                    ticks: {
                                                                        beginAtZero: true
                                                                    },
                                                                    scaleLabel: {
                                                                        display: true,
                                                                        labelString: 'Mes'
                                                                    }
                                                                }]
                                                        }                                                      
                                                    }
                                                });
                                            }
                                        });
                                    }
                                }

                                function cargaGraficoRango() {
                                    var desde = $("#desde").val();
                                    var hasta = $("#hasta").val();

                                    if (desde === '' || hasta === '') {
                                        alert("Debe completar todos los parámetros del filtro");
                                    } else {

                                        // Reseteo el gráfico
                                        $("#graficaRango").remove();
                                        $("#contenedorGraficoRango").append('<canvas style=" margin: 0" width="' + (screen.width * 0.2) + '" height="' + (screen.height * 0.2) + '" id="graficaRango"></canvas>');

                                        $.ajax({
                                            type: 'POST',
                                            data: {accion: 'graficaRango', desde: desde, hasta: hasta},
                                            url: 'controladores/estadisticasController.php',
                                            success: function (response) {
                                                console.log(response);
                                                var ctx = $("#graficaRango");
                                                var myChart = new Chart(ctx, {
                                                    type: 'pie',
                                                    data: response,
                                                    options: {
                                                        legend: {
                                                            position: 'bottom',
                                                            fullWidth: false,
                                                            labels: {
                                                                boxWidth: 10,
                                                                fontsize: 5
                                                            }
                                                        }
                                                    }
                                                });
                                            }
                                        });
                                    }
                                }



                                function cargaGraficoTipoUsuario() {

                                    var desde = $("#desdeTipo").val();
                                    var hasta = $("#hastaTipo").val();
                                    var biblioteca = $("#biblioteca").val();

                                    if (desde === '' || hasta === '' || biblioteca === '') {
                                        alert("Debe completar todos los parámetros del filtro");
                                    } else {

                                        // Reseteo el gráfico
                                        $("#graficaTipo").remove();
                                        $("#contenedorGraficoTipo").append('<canvas style=" margin: 0" width="' + (screen.width * 0.2) + '" height="' + (screen.height * 0.2) + '" id="graficaTipo"></canvas>');

                                        $.ajax({
                                            type: 'POST',
                                            data: {accion: 'graficaTipo', desde: desde, hasta: hasta, biblioteca: biblioteca},
                                            url: 'controladores/estadisticasController.php',
                                            success: function (response) {
                                                console.log(response);
                                                var ctx = $("#graficaTipo");
                                                var myChart = new Chart(ctx, {
                                                    type: 'pie',
                                                    data: response,
                                                    options: {
                                                        legend: {
                                                            position: 'bottom',
                                                            fullWidth: false,
                                                            labels: {
                                                                boxWidth: 10,
                                                                fontsize: 5
                                                            }
                                                        }
                                                    }
                                                });
                                            }
                                        });
                                    }

                                }

    </script>
</html>
