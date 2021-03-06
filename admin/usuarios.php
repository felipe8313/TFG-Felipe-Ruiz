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
if ($_SESSION['Rol'] === 1){
    error('No está autorizado a ver la página anterior', false);
    header('Location: ../');
}
?>
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
        // Cargo en la tabla los usuarios que coinciden con el filtro
        if (isset($_GET['nombre'])) {
            $nombre = strtoupper($_GET['nombre']);
        } else {
            $nombre = '';
        }

        if (isset($_GET['niuDni'])) {
            $niuDni = strtoupper($_GET['niuDni']);
        } else {
            $niuDni = '';
        }
        
        // El bibliotecario solo podrá gestionar alumnos
        if ($_SESSION['Rol'] === 2){
            $condicionBibliotecario = ' and Rol = 1';
        }else{
            $condicionBibliotecario = '';
        }
        
        if ($nombre !== '' || $niuDni !== '') {
            $bd = new bd();
            $consulta = "select * from Usuarios where (ucase(nombre) like '%" . $nombre . "%' or ucase(apellidos) like '%" . $nombre . "%')  and (ucase(DNI) like '%" . $niuDni . "%' or ucase(NIU) like '%" . $niuDni . "%')".$condicionBibliotecario;
            $datos = $bd->consulta($consulta);
            $cuerpoTabla = '';

            foreach ($datos as $usuario) {
                
                $roles = array('' => '', 1 => 'Usuario', 2 => 'Bibliotecario', 3 => 'Administrador');
                $estados = array('' => '', 0 => 'NO', 1 => 'SI');
                
                $cuerpoTabla .= '<tr>';
                $cuerpoTabla .= '<td>' . $usuario['NIU'] . '</td>';
                $cuerpoTabla .= '<td>' . $usuario['DNI'] . '</td>';
                $cuerpoTabla .= '<td>' . $usuario['Nombre'] . '</td>';
                $cuerpoTabla .= '<td>' . $usuario['Apellidos'] . '</td>';
                $cuerpoTabla .= '<td>' . $roles[$usuario['Rol']] . '</td>';
                $cuerpoTabla .= '<td>' . $estados[$usuario['Bloqueado']] . '</td>';
                $cuerpoTabla .= '<td>' . $usuario['TipoUsuario'] . '</td>';
                $cuerpoTabla .= '<td>' . $usuario['Titulación'] . '</td>';
                $cuerpoTabla .= '<td>' . $usuario['Email'] . '</td>';
                $cuerpoTabla .= '<td><button type="button" class="btn btn-default" onclick="cargarUsuario(\'' . $usuario['DNI'] . '\')"><span class="glyphicon glyphicon-pencil"></span></button></td>';
                $cuerpoTabla .= '</tr>';
            }
        }
        ?>
        <div class="content">
            <div class="botonesCabecera">
                <button data-toggle="modal" data-target="#modalNuevoUsuario" class="btn btn-default" onclick="abrirModalNuevoUsuario()">
                    <span class="glyphicon glyphicon-plus"></span>&ensp; Nuevo usuario
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
                <div class="row">
                    <div class="panel panel-default">
                        <div class="panel-heading">Filtro</div>
                        <div class="panel-body">
                            <form method="GET" action="usuarios.php">
                                <div class="col-md-5">
                                    <label>Nombre y/o apellidos</label>
                                    <input value="<?php echo $nombre ?>" type="text" class="form-control" name="nombre">  
                                </div>
                                <div class="col-md-5">
                                    <label>NIU o DNI</label>
                                    <input value="<?php echo $niuDni ?>" type="text" class="form-control" name="niuDni">  
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-default"><span class="glyphicon glyphicon-search"></span>&ensp; Buscar</button><br><br>
                                    <button type="button" onclick="limpiar()" class="btn btn-default"><span class="glyphicon glyphicon-trash"></span>&ensp; Limpiar</button>
                                </div>                                
                            </form>                        
                        </div>
                    </div>
                </div>
                <table id="tablaUsuarios" class="table table-striped table-bordered table-hovered">
                    <thead>
                        <tr>
                            <td>NIU</td>
                            <td>DNI</td>                            
                            <td>Nombre</td>
                            <td>Apellidos</td>
                            <td>Rol</td>
                            <td>Bloqueado</td>
                            <td>Tipo usuario</td>
                            <td>Titulación</td>
                            <td>Email</td>
                            <th>Modi.</th>
                        </tr>
                    </thead>
                    <tbody>
<?php echo $cuerpoTabla ?>
                    </tbody>
                </table>
            </div>
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
                                <?php if ($_SESSION['Rol'] === 3) { ?>
                                <label>Rol*</label>
                                <select onchange="actualizaSelects(this.value)" required="true" name="rol" class="form-control">
                                    <option value="">Seleccione...</option>
                                    <option value="1">Alumno</option>
                                    <option value="2">Bibliotecario</option>
                                    <option value="3">Administrador</option>
                                </select><br>
                                <label>Biblioteca</label>
                                <select disabled="true" id="biblioteca" onchange="cargaSelectPlantas(this.value)" name="biblioteca" class="form-control">
                                    <option value="0">Seleccione una biblioteca</option>
                                    <?php echo getBibliotecas($bd) ?>
                                </select>
                                <br>
                                <?php } ?>

                                <label>Bloqueado*</label>
                                <select required="true" name="bloqueado" class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Sí</option>
                                </select><br>                            
                                <label>Tipo de usuario*</label>
                                <?php if ($_SESSION['Rol'] === 2) { ?>
                                <select id="tipo" name="tipo" class="form-control">
                                <?php }else{ ?>
                                <select disabled="true" id="tipo" name="tipo" class="form-control">
                                <?php } ?>
                                    <option value="0">Seleccione...</option>
                                    <option value="PDI">Pdi</option>
                                    <option value="PAS">Pas</option>
                                    <option value="ALUMNO">Alumno</option>
                                </select><br>                                
                                <label>Imagen</label>
                                <input type="file" name="fichero" accept="image/*"><br>
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
                                        });

                                        function limpiar() {
                                            window.location.href = "usuarios.php";
                                        }

                                        function cargarUsuario(dni) {
                                            window.location.href = "verUsuario.php?dni=" + dni;
                                        }
                                        
                                        function actualizaSelects(tipoUsuario){                                            
                                            if (tipoUsuario == 2){
                                                $("#biblioteca").attr("disabled", false);
                                                $("#tipo").attr("disabled", true);
                                            }else if (tipoUsuario == 1){
                                                $("#biblioteca").attr("disabled", true);
                                                $("#tipo").attr("disabled", false);                                                
                                            }else if (tipoUsuario == 3){
                                                $("#biblioteca").attr("disabled", true);
                                                $("#tipo").attr("disabled", true);                                                
                                            }
                                        }
    </script>
</html>
