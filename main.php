<?php
include 'clases/bd.class.php';
ini_set('error_reporting', E_ALL ^ E_NOTICE);
ini_set('display_errors', 'on');
session_start();

if (!isset($_SESSION['passCorrect'])){
    header('Location: paginaPass.php');
}
?>
<html>
    <head>
        <title>Bienvenido a Librarino</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="resources/style.css"/>
        <link rel="stylesheet" type="text/css" href="resources/bootstrap/css/bootstrap.css"/>
        <link rel="shortcut icon" href="resources/imgs/logo.png">
    </head>
    <body>        
        <?php include 'header.php'?>
        <div class="contenido">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div align="center">
                        <div class="row">
                            <?php
                            $bd = new bd();
                            $datos = $bd->consulta("select * from Biblioteca");
                            $cont = 0;

                            foreach ($datos as $biblioteca) {

                                if ($cont % 2 === 0) {
                                    echo '</div><div class="row">';
                                }
                                echo '<div class="col-md-6">';
                                echo '<div id="imagen">';
                                echo '<a href="'.$biblioteca['vista'].'"><img class="imagenMain" src="' .$biblioteca['DirectorioImagen'] . '"></a>';
                                echo '<div><h3 class="textoImagen">|| ' .  utf8_encode($biblioteca['Nombre']) . '</h3></div>';
                                echo '</div>';
                                echo '</div>';
                                $cont++;
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
        <?php include 'footer.php'?>        
        <script type="text/javascript" src="resources/jquery.js"></script>
        <script type="text/javascript" src="resources/bootstrap/js/bootstrap.js"></script>
        <script type="text/javascript">

        </script>
    </body>
</html>
