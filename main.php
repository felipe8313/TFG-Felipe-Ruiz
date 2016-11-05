<?php
include 'clases/bd.class.php';
ini_set('error_reporting', E_ALL ^ E_NOTICE);
ini_set('display_errors', 'on');
?>

<html>
    <head>
        <title>Bienvenido a Librarino</title>
        <link rel="stylesheet" type="text/css" href="resources/style.css"/>
        <link rel="stylesheet" type="text/css" href="resources/bootstrap/css/bootstrap.css"/>



    </head>
    <body>

        <div class="row">
            <div class="col-md-10 col-md-offset-1" style=" padding-top: 20px">

                <?php
                $bd = new bd();
                $datos = $bd->consulta("select * from Biblioteca");


                foreach ($datos as $biblioteca) {
                    echo '<a href="#"><img id="imagen' . $biblioteca['Id'] . '" class="imagenMain" src="' . $biblioteca['DirectorioImagen'] . '"></a>';
                    echo '<h1 id="texto' . $biblioteca['Id'] . '" class="textoImagen">|| ' . $biblioteca['Nombre'] . '</h1>';
                }
                ?>
            </div>
        </div>
        <footer>
            <div align="center">
                <h4 style="padding-top: 25px"><?php echo utf8_decode('Librarino&copy; es el Trabajo de Fin de Grado de Felipe Ruiz Pinto en la E.T.S de Ingeniería Informática de la Universidad de Málaga')?></h>
            </div>
        </footer>
        <script type="text/javascript" src="resources/jquery.js"></script>
        <script type="text/javascript" src="resources/bootstrap/js/bootstrap.js"></script>
        <script type="text/javascript">


<?php
foreach ($datos as $biblioteca) {
    echo '$("#imagen' . $biblioteca['Id'] . '").hover(function (){ 
                                $("#texto' . $biblioteca['Id'] . '").toggle(500);             
                            });';
}
?>

        </script>
    </body>
</html>
