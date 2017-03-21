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
        ?>
        <div class="content">
            <div class="botonesCabecera">
                <button data-toggle="modal" data-target="#modalNuevoUsuario" class="btn btn-default" onclick="abrirModalNuevoUsuario()">
                    <span class="glyphicon glyphicon-plus"></span>&ensp; Nuevo usuario
                </button>
            </div>
            <table id="tablaUsuarios" class="table table-striped table-bordered table-hovered">
                <thead>
                    <tr>
                        <td>Usuario</td>
                        <td>Contraseña</td>
                        <td>Nombre</td>
                        <td>Apellidos</td>
                        <td>DNI</td>
                        <td>NIU</td>
                        <td>Rol</td>
                        <td>Tipo usuario</td>
                        <td>Titulación</td>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>

        <!-- Modal para crear un usuario -->
        <div id="modalNuevoUsuario" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Nuevo usuario</h4>
                    </div>
                    <form enctype="multipart/form-data" action="controladores/usuariosController.php" method="POST">
                        <input type="hidden" name="accion" value="crearUsuario">
                        <div class="modal-body row">
                            <p class="aviso">Los campos marcados con * son obligatorios</p>
                            <div class="col-md-6">
                                <label>NIU</label>
                                <input type="text" name="niu" class="form-control"><br>
                                <label>DNI*</label>
                                <input required="true" type="text" name="dni" class="form-control"><br>
                                <label>Nombre*</label>
                                <input required="true" type="text" name="nombre" class="form-control"><br>
                                <label>Apellidos*</label>
                                <input required="true" type="text" name="apellidos" class="form-control"><br>
                                <label>Email*</label>
                                <input required="true" type="email" name="email" class="form-control"><br>
                                
                            </div>
                            <div class="col-md-6">                               
                                
                                <!-- Solo el administrador podrá definir roles -->
                                <?php if ($_SESSION['Rol'] === 2) { ?>
                                    <label>Rol*</label>
                                    <select required="true" name="rol" class="form-control">
                                        <option value="">Seleccione...</option>
                                        <option value="1">Usuario normal</option>
                                        <option value="2">Bibliotecario</option>
                                        <option value="3">Administrador</option>
                                    </select><br>
                                <?php } ?>

                                <label>Bloqueado*</label>
                                <select required="true" name="bloqueado" class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Sí</option>
                                </select><br>                            
                                <label>Tipo de usuario*</label>
                                <select required="true" name="tipo" class="form-control">
                                    <option value="">Seleccione...</option>
                                    <option value="PDI">Pdi</option>
                                    <option value="PAS">Pas</option>
                                    <option value="ALUMNO">Alumno</option>
                                </select><br>
                                <label>Imagen*</label>
                                <input multiple="true" type="file" name="fichero" accept="image/*"><br>
                            </div>
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
                        $("#usuarios").addClass("selectedItem");
                        $('#tablaUsuarios').DataTable({
                            "language": {
                                "url": "//cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json"
                            }
                        });
                    })
    </script>
</html>
