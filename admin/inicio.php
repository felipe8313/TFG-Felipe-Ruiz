<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
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
        <?php 
        include_once 'header.php';
        include_once 'menuLateral.php';
        ?>
        <div class="content">
            <center>
                <h2>Bienvenid@ al panel de administración</h2>
                <h4>Desde aquí puede gestionar todo el sistema Librarino</h4>
                <?php echo $_SERVER['DOCUMENT_ROOT'].'/librarinoApp/admin/resources/imagenesUsuarios'?>
            </center>
        </div>
    </body>
    <script type="text/javascript" src="../resources/jquery.js"></script>
    <script type="text/javascript" src="../resources/bootstrap/js/bootstrap.js"></script>
</html>
