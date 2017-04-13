<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
session_start();
include 'clases/bd.class.php';

?>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="resources/admin_style.css"/>
        <link rel="stylesheet" type="text/css" href="../resources/bootstrap/css/bootstrap.css"/>
        <link rel="shortcut icon" href="../resources/imgs/logo.png">
        <title>Panel administración</title>
    </head>
    <body class="bodyLogin">
        <div class="container">
            <div class="login">
                <div align="center">
                    <img width="30%" src="../resources/imgs/logo.png"/>
                </div>
                <br/>
                <?php 
                    // Muestro los mensajes de error
                    if (isset($_SESSION['error'])){
                        echo '<p class="aviso">'.$_SESSION['error'].'</p>';
                        unset($_SESSION['error']);
                    }
                ?>
                <form method="POST" action="../controladores/loginController.php">
                    <input type="hidden" name="modo" value="admin"/>
                    <input type="hidden" name="accion" value="login">
                    <input type="text" name="user" placeholder="Usuario" required="required" class="input-txt" />
                    <input type="password" name="pass" placeholder="Contraseña" required="required" class="input-txt" />
                    <div class="login-footer">
                        <div align="center"><button type="submit" class="botonLogin">Entrar  </button></div>
                    </div>
                </form>
            </div>
        </div>
    </body>
    <script type="text/javascript" src="../resources/jquery.js"></script>
    <script type="text/javascript" src="../resources/bootstrap/js/bootstrap.js"></script>
</html>
