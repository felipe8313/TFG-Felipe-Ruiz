<header class="cabecera">
    <div class="row">
        <div class="col-md-8">
            <h1 class="titulo">LIBRARINO</h1>
            <h4 class="subtitulo">Tu biblioteca inteligente</h4>
        </div>
        <div class="col-md-4">
            <nav>
                <?php
                // Muestro un botón u otro dependiendo si el usuario ha iniciado sesión
                if (isset($_SESSION['InicioSesion'])) {
                    echo '<a class="dropdown-toggle itemMenu" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"class="" href="#">Hola '.$_SESSION['Nombre'].'</a>';
                    echo '<ul class="dropdown-menu opcionesUsuario">
                        <li><a href="#" data-toggle="modal" data-target="#modalCambiarPass">Cambiar contraseña</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="controladores/loginController.php">Cerrar sesión <span class="glyphicon glyphicon-off"></span></a></li>
                        </ul>';
                } else {
                    echo '<a class="itemMenu" data-toggle="modal" data-target="#modalInicioSesion" href="#">Iniciar Sesión</a>';  
                    
                }
                ?>
                <a class="itemMenu" href="enconstruccion.php">Ayuda</a>                
                <a class="itemMenu" href="main.php">Inicio</a>
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
                <div class="modal-body">
                    <label for="user">Usuario</label>
                    <input id="user" type="text" name="user" class="form-control"><br>
                    <label for="pass">Contraseña</label>
                    <input id="pass" type="password" name="pass" class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Iniciar sesión</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


