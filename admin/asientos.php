<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Usuarios</title>
        <link rel="stylesheet" type="text/css" href="resources/menu_style.css"/>
        <link rel="stylesheet" type="text/css" href="resources/admin_style.css"/>
        <link rel="stylesheet" type="text/css" href="resources/datatables/dataTables.min.css"/> 
        <link rel="stylesheet" type="text/css" href="../resources/bootstrap/css/bootstrap.css"/>
        <link rel="stylesheet" type="text/css" href="../resources/style.css"/>
        <link rel="shortcut icon" href="../resources/imgs/logo.png">
    </head>
    <body>
        <?php
        session_start();
        include_once 'header.php';
        include_once 'menuLateral.php';
        include_once '../clases/bd.class.php';
        $bd = new bd();
        error_reporting(0);


        // Cargamos las opciones del select de biblioteca
        $bibliotecas = $bd->consulta("select Id, Plantas, Nombre from biblioteca");
        $opcionesBibliotecas = '';

        foreach ($bibliotecas as $biblio) {
            $opcionesBibliotecas .= '<option value="' . $biblio['Id'] . '/' . $biblio['Plantas'] . '">' . utf8_encode($biblio['Nombre']) . '</option>';
        }
        ?>
        <div class="content">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="col-md-6">
                        <label>Biblioteca</label>
                        <select id="biblioteca" onchange="cargaSelectPlantas(this.value)" class="form-control">
                            <option>Seleccione una biblioteca</option>
                            <?php echo $opcionesBibliotecas ?>
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
    </body>
    <script type="text/javascript" src="../resources/jquery.js"></script>
    <script type="text/javascript" src="../resources/bootstrap/js/bootstrap.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
                            $(document).ready(function () {
                                $("#asientos").addClass("selectedItem");
                            });

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

                                // Obtengo la biblioteca actual
                                var infoBiblio = $("#biblioteca").val();
                                var res = infoBiblio.split("/");
                                var biblio = res[0];

                                $("#mapa").load('controladores/asientosController.php', {accion: 'cargarMapa', biblio: biblio, planta: planta},
                                function () {
                                    $(".numAsientos").css("cursor", "move");
                                    $(".numAsientos").draggable();

                                    $(".suelta").droppable({
                                        drop: function (event, ui) {
                                            /*if (!ui.draggable.data("soltado")){ 
                                             ui.draggable.data("soltado", true); 
                                             var elem = $(this); 
                                             elem.data("numsoltar", elem.data("numsoltar") + 1) 
                                             elem.html("Llevo " + elem.data("numsoltar") + " elementos soltados"); 
                                             }*/
        
                                            var celda = $(this);
                                            var celdaX = celda.data("x");
                                            var celdaY = celda.data("y");

                                            var mesaX = ui.draggable.data("x");
                                            var mesaY = ui.draggable.data("y");
                                            //alert("Mesa --> x: " + mesaX + "; y: " + mesaY + " - Celda --> x: " + celdaX + "; y: " + celdaY);
                                            
                                            // Actualizo la nueva localización mediante ajax
                                            $.ajax({
                                                type: 'POST',
                                                url: 'controladores/asientosController.php',
                                                data: {accion: 'actualizaPosicion', mesaX: mesaX, mesaY: mesaY, celdaX: celdaX, celdaY:celdaY},
                                                success: function(){
                                                    var plantaActual = $("#planta").val();
                                                    cargaPlanta(plantaActual);                                                    
                                                }
                                            });
                                            
                                        }
                                    });
                                });
                            }




    </script>
</html>
