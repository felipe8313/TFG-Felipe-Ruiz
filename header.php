<header class="cabecera">
    <div class="row">
        <div class="col-md-1 col-lg-1 col-xs-1 col-sm-1">
            <img class="logoHeader" src="resources/imgs/logo.png"/>
        </div>
        <div class="col-md-7 col-lg-7 col-xs-7 col-sm-7">            
            <h1 class="titulo">LIBRARINO</h1>
            <h4 class="subtitulo">Tu biblioteca inteligente</h4>
        </div>
        <div class="col-md-4 col-lg-4 col-xs-4 col-sm-4">
            <nav>
                <?php
                // Muestro un botón u otro dependiendo si el usuario ha iniciado sesión
                if (isset($_SESSION['InicioSesion'])) {
                    echo '<a class="dropdown-toggle itemMenu" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"class="" href="#">Hola '.$_SESSION['Nombre'].'</a>';
                    echo '<ul class="dropdown-menu opcionesUsuario">
                        <li><a href="#" data-toggle="modal" data-target="#modalCambiarPass">Cambiar contraseña</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="controladores/loginController.php?accion=logout&modo=app">Cerrar sesión <span class="glyphicon glyphicon-off"></span></a></li>
                        </ul>';
                } else {
                    echo '<a class="itemMenu" data-toggle="modal" data-target="#modalInicioSesion" href="#">Iniciar Sesión</a>';  
                    
                }
                ?>
                <a class="itemMenu" href="ayuda.php">Ayuda</a>                
                <a class="itemMenu" href="index.php">Inicio</a>
            </nav>
            <?php
            ?>
            
        </div>
    </div>
</header>

<!-- modal para el inicio de sesión -->
<div id="modalInicioSesion" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Inicio de sesión</h4>
            </div>
            <form method="POST" action="controladores/loginController.php">
                <input type="hidden" name="accion" value="login">
                <input type="hidden" name="modo" value="app"/>
                <div class="modal-body">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input id="user" type="text" name="user" class="form-control">
                    </div>
                    <br><br>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input id="pass" type="password" name="pass" class="form-control">
                    </div>
                    <br>
                    <center>
                        <a href="#" onclick="abreModalNuevaPass()">¿Ha olvidado su contraseña?</a>
                    </center>
                </div>
                <div class="modal-footer">
                    <center>
                        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-ok"></span>&ensp;Iniciar sesión</button>
                    </center>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- modal para cambiar la contraseña -->
<div id="modalCambiarPass" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Cambiar contraseña</h4>
            </div>
            <form method="POST" action="controladores/usuarioController.php">
                <div class="modal-body">           
                    <input name="accion" type="hidden" value="cambiaContrasenia">
                    <label for="pass">Nueva contraseña</label>
                    <input name="pass" id="nuevaPass" onkeyup="compruebaPass()" class="form-control" type="text"><br>
                    <label for="repass">Repita contraseña</label>
                    <input name="repass" id="nuevaRepass" onkeyup="compruebaPass()" class="form-control" type="text">
                    <div id="mensajePass"><b style="color:red">Las contraseñas no coinciden</b></div>
                </div>
                <div class="modal-footer">
                    <center>
                        <input type="submit" id="boton" class="btn btn-default" value="Guardar">        
                    </center>
                </div>
            </form>
        </div>
    </div>
</div> 

<!-- modal para nueva contraseña -->
<div id="modalNuevaPass" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Generar nueva contraseña</h4>
            </div>
            <form method="POST" action="admin/controladores/usuariosController.php">
                <div class="modal-body">           
                    <input name="accion" type="hidden" value="nuevaContrasenia">
                    <input name="modo" type="hidden" value="app">
                    <label for="dniNewPass">DNI</label>
                    <input id="dniNewPass" name="dni" class="form-control" type="text"><br>
                </div>
                <div class="modal-footer">
                    <center>
                        <input type="submit" id="boton" class="btn btn-default" value="Generar">   
                    </center>
                </div>
            </form>
        </div>
    </div>
</div> 

<script type="text/javascript">    
    function compruebaPass(){
        
        var pass = $("#nuevaPass").val();
        var repass = $("#nuevaRepass").val();
        if (pass === repass && pass !== '' && repass !== ''){
            $("#mensajePass").html('<b style="color:green">Las contraseñas coinciden</b>');
            $('#boton').attr("disabled", false);
        }else{
            $("#mensajePass").html('<b style="color:red">Las contraseñas no coinciden</b>');
            $('#boton').attr("disabled", true);
        }    
    }  
    
    function abreModalNuevaPass(){
        $("#modalInicioSesion").modal("hide");
        setTimeout($("#modalNuevaPass").modal("show"), 1000);
    }
    
</script>


