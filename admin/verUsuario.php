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
        <link rel="stylesheet" type="text/css" href="../resources/bootstrap/css/bootstrap.css"/>
        <link rel="stylesheet" type="text/css" href="../resources/style.css"/>
        <link rel="shortcut icon" href="../resources/imgs/logo.png">
    </head>
    <body>
        <?php
        $dni = $_GET['dni'];

        // Obtengo los datos del usuario
        $bd = new bd();

        $consulta = "select * from Usuario where DNI = '" . $dni . "'";
        $alumno = $bd->consulta($consulta);
        $niu = $alumno[0]['NIU'];
        $nombre = $alumno[0]['Nombre'];
        $apellidos = $alumno[0]['Apellidos'];
        $email = $alumno[0]['Email'];
        $bloqueado = $alumno[0]['Bloqueado'];
        $tipoUsuario = $alumno[0]['TipoUsuario'];
        $rol = $alumno[0]['Rol'];
        $imagen = $alumno[0]['Imagen'];
        ?>
        <div class="content">
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
            <div class="row">
                <button onclick="nuevaContrasenia('<?php echo $dni ?>')" class="btn btn-default"><span class="glyphicon glyphicon-lock"></span>&ensp; Generar nueva contraseña</button>
                <img class="imagenUsuario" width="15%" src="resources/imagenesUsuarios/<?php echo $imagen ?>">
            </div>
            <form enctype="multipart/form-data" action="controladores/usuariosController.php" method="POST">
                <input type="hidden" name="accion" value="modificarUsuario">
                <input type="hidden" name="dniActual" value="<?php echo $dni ?>">
                <div class="row">
                    <p class="aviso">Los campos marcados con * son obligatorios</p>
                    <div class="col-md-6">
                        <label>NIU</label>
                        <input value="<?php echo $niu ?>" type="text" name="niu" class="form-control"><br>
                        <label>DNI*</label>
                        <input required="true" value="<?php echo $dni ?>" type="text" name="dni" class="form-control"><br>
                        <label>Nombre*</label>
                        <input required="true" type="text" value="<?php echo $nombre ?>" name="nombre" class="form-control"><br>
                        <label>Apellidos*</label>
                        <input required="true" type="text" value="<?php echo $apellidos ?>" name="apellidos" class="form-control"><br>
                        <label>Email*</label>
                        <input required="true" type="email" value="<?php echo $email ?>" name="email" class="form-control"><br>

                    </div>
                    <div class="col-md-6">                     

                        <!-- Solo el administrador podrá definir roles -->
                            <?php if ($_SESSION['Rol'] === 3) { ?>
                            <label>Rol*</label>
                            <select required="true" id="rol" name="rol" class="form-control">
                                <option value="">Seleccione...</option>
                                <option value="1">Usuario normal</option>
                                <option value="2">Bibliotecario</option>
                                <option value="3">Administrador</option>
                            </select><br>
                            <?php } ?>

                        <label>Bloqueado*</label>
                        <select required="true" id="bloqueado" name="bloqueado" class="form-control">
                            <option value="0">No</option>
                            <option value="1">Sí</option>
                        </select><br>                            
                        <label>Tipo de usuario*</label>
                        <select required="true" id="tipo" name="tipo" class="form-control">
                            <option value="">Seleccione...</option>
                            <option value="PDI">Pdi</option>
                            <option value="PAS">Pas</option>
                            <option value="ALUMNO">Alumno</option>
                        </select><br>
                        <label>Imagen</label>
                        <input type="file" name="fichero" accept="image/*"><br>
                    </div>                    
                </div>
                <div class="row">
                    <center>
                        <button class="btn btn-default" ><span class="glyphicon glyphicon-ok"></span>&ensp; Guardar</button>
                    </center>
                </div>
            </form>
        </div>
    </body>
    <script type="text/javascript" src="../resources/jquery.js"></script>
    <script type="text/javascript" src="../resources/bootstrap/js/bootstrap.js"></script>
    <script>

                    $(document).ready(function () {

                        // Cargo los select
                        $("#tipo").val("<?php echo $tipoUsuario ?>");
                        $("#bloqueado").val("<?php echo $bloqueado ?>");
                        $("#rol").val("<?php echo $rol ?>");
                    });

                    function nuevaContrasenia(dni) {
                        $.ajax({
                            type: 'POST',
                            data: {accion: 'nuevaContrasenia', dni: dni},
                            url: 'controladores/usuariosController.php',
                            success: function () {
                                alert("Se ha mandado la nueva contraseña al email del usuario");
                            }

                        });
                    }

    </script>
</html>
