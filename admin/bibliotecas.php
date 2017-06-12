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

if (!isset($_SESSION['InicioSesion']) && !$_SESSION['InicioSesion']) {
    header('Location: index.php');
}

// El usuario normal no tiene permisos para acceder aquí
if ($_SESSION['Rol'] === 1){
    error('No está autorizado a ver la página anterior', false);
    header('Location: ../');
}
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Bibliotecas</title>
        <link rel="stylesheet" type="text/css" href="resources/menu_style.css"/>
        <link rel="stylesheet" type="text/css" href="resources/admin_style.css"/>
        <link rel="stylesheet" type="text/css" href="resources/datatables/dataTables.min.css"/> 
        <link rel="stylesheet" type="text/css" href="../resources/bootstrap/css/bootstrap.css"/>
        <link rel="stylesheet" type="text/css" href="../resources/style.css"/>
        <link rel="shortcut icon" href="../resources/imgs/logo.png">
    </head>
    <body>
        <?php
        $consulta = "select * from Bibliotecas";
        $datos = $bd->consulta($consulta);
        $cuerpoTabla = '';

        foreach ($datos as $biblio) {
            $cuerpoTabla .= '<tr>';
            $cuerpoTabla .= '<td>' . $biblio['Id'] . '</td>';
            $cuerpoTabla .= '<td>' . utf8_encode($biblio['Nombre']) . '</td>';
            $cuerpoTabla .= '<td>' . utf8_encode($biblio['Direccion']) . '</td>';
            $cuerpoTabla .= '<td>' . $biblio['Plantas'] . '</td>';
            $cuerpoTabla .= '<td><button type="button" class="btn btn-default" onclick="cargarBiblio(\'' . $biblio['Id'] . '\')"><span class="glyphicon glyphicon-pencil"></span></button>&ensp;';
            $cuerpoTabla .= '<button type="button" class="btn btn-default" onclick="eliminaBiblio(\'' . $biblio['Id'] . '\')"><span class="glyphicon glyphicon-remove"></span></button></td>';
            $cuerpoTabla .= '</tr>';
        }
        ?>
        <div class="content">
            <div class="botonesCabecera">
                <button data-toggle="modal" data-target="#modalNuevaBiblio" class="btn btn-default">
                    <span class="glyphicon glyphicon-plus"></span>&ensp; Nueva biblioteca
                </button>
            </div>
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
            <div class="col-md-12">
                <table style=" width: 100%" id="tablaBiblios" class="table table-striped table-bordered table-hovered">
                    <thead>
                        <tr>
                            <td>Id</td>
                            <td>Nombre</td>                            
                            <td>Dirección</td>
                            <td>Plantas</td>
                            <td>Operaciones</td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php echo $cuerpoTabla ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal para crear una biblioteca -->
        <div id="modalNuevaBiblio" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Nueva biblioteca</h4>
                    </div>
                    <form enctype="multipart/form-data" action="controladores/bibliotecasController.php" method="POST">
                        <input type="hidden" name="accion" value="crearBiblio">
                        <div class="modal-body row">
                            <p class="aviso">Los campos marcados con * son obligatorios</p>
                            <label>Nombre*</label>
                            <input required="true" type="text" name="nombre" class="form-control"><br>
                            <label>Dirección*</label>
                            <input required="true" type="text" name="direccion" class="form-control"><br>
                            <label>Plantas*</label>
                            <input required="true" type="text" name="plantas" class="form-control"><br>
                            <label>Imagen*</label>
                            <input type="file" name="fichero" required="true" accept="image/*"><br>
                        </div>
                        <div class="modal-footer">
                            <center>
                                <button class="btn btn-default" ><span class="glyphicon glyphicon-ok"></span>&ensp; Guardar</button>
                            </center>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <!-- Modal para modificar una biblioteca -->
        <div id="modalModiBiblio" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Modificar biblioteca</h4>
                    </div>
                    <form enctype="multipart/form-data" action="controladores/bibliotecasController.php" method="POST">
                        <input type="hidden" name="accion" value="modiBiblio">
                        <input type="hidden" name="id" id="id" value="">
                        <div class="modal-body row">
                            <p class="aviso">Los campos marcados con * son obligatorios</p>
                            <label>Nombre*</label>
                            <input required="true" type="text" id="nombreModi" name="nombre" class="form-control"><br>
                            <label>Dirección*</label>
                            <input required="true" type="text" name="direccion" id="direccionModi" class="form-control"><br>
                            <label>Plantas*</label>
                            <input required="true" type="text" id="plantasModi" name="plantas" class="form-control"><br>
                            <label>Nueva imagen</label>
                            <input type="file" name="ficheroModi" id="ficheroModi" accept="image/*"><br>
                        </div>
                        <div class="modal-footer">
                            <center>
                                <button class="btn btn-default" ><span class="glyphicon glyphicon-ok"></span>&ensp; Guardar</button>
                            </center>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

    </body>
    <script type="text/javascript" src="../resources/jquery.js"></script>
    <script type="text/javascript" src="../resources/bootstrap/js/bootstrap.js"></script>
    <script type="text/javascript" src="resources/datatables/dataTables.min.js"></script>
    <script type="text/javascript" src="resources/datatables/dataTables.bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#menuBiblioteca").addClass("selectedItem");
            $('#tablaBiblios').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json"
                },
                "lengthMenu": [[-1, 10, 25, 50], ["Todos", 10, 25, 50]],
                scrollY: $(window).height() - 350,
                scrollX: "100%"
            });
        });

        function cargarBiblio(id) {
            $.ajax({
                type: 'POST',
                url: 'controladores/bibliotecasController.php',
                data: {accion: 'cargarBiblio', id: id},
                success: function (response) {

                    $("#id").val(id);
                    $("#nombreModi").val(response.nombre);
                    $("#direccionModi").val(response.direccion);
                    $("#plantasModi").val(response.plantas);

                    $("#modalModiBiblio").modal("show");
                }
            });


        }

        function eliminaBiblio(id) {

            if (confirm('¿Está seguro de eliminar está biblioteca?')) {
                window.location.href = "controladores/bibliotecasController.php?accion=eliminarBiblio&id=" + id;
            }
        }

    </script>
</html>
