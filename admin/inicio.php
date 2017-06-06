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
        <title>Panel administración</title>
        <link rel="stylesheet" type="text/css" href="resources/menu_style.css"/>
        <link rel="stylesheet" type="text/css" href="resources/admin_style.css"/>
        <link rel="stylesheet" type="text/css" href="../resources/bootstrap/css/bootstrap.css"/>
        <link rel="stylesheet" type="text/css" href="../resources/style.css"/>
        <link rel="shortcut icon" href="../resources/imgs/logo.png">
    </head>
    <body>
        <div class="content">
            <center>
                <h2>Bienvenid@ al panel de administración</h2>
                <h4>Desde aquí puede gestionar todo el sistema Librarino</h4>
            </center>
        </div>
    </body>
    <script type="text/javascript" src="../resources/jquery.js"></script>
    <script type="text/javascript" src="../resources/bootstrap/js/bootstrap.js"></script>
</html>
