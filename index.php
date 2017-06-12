<?php
include 'clases/bd.class.php';
session_start();

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
                <div class="col-md-10 col-md-offset-1">
                    <div align="center">
                        <div class="row">
                            <?php
                            $bd = new bd();
                            $datos = $bd->consulta("select b.*, count(a.id) as numLibres from Bibliotecas b join Mesas m on (b.Id = m.Biblioteca_Id) 
                                                    join Asientos a on (a.Mesa_Id = m.id)
                                                    where estado = 1
                                                    group by b.id");
                            $cont = 0;

                            foreach ($datos as $biblioteca) {
                                
                                $numLibres = (int)$biblioteca['numLibres'];
                                
                                // Si no hay asientos libres, muestro el texto en rojo sino en verde
                                if ($numLibres > 0){
                                    $claseNumLibres = 'hayLibres';
                                }else{
                                    $claseNumLibres = 'noHayLbres';
                                }
                                
                                if ($numLibres === 1){
                                    $txtNumLibres = ' asiento libre';
                                }else{
                                    $txtNumLibres = ' asientos libres';
                                }                           
                                
                                if ($cont % 2 === 0) {
                                    echo '</div><div class="row">';
                                }
                                echo '<div class="col-md-6">';
                                echo '<div class="panel panel-default">';
                                echo '<div class="panel-body" style="padding: 0 !important">';
                                echo '<a href="biblioteca.php?id='.$biblioteca['Id'].'&planta=1"><img class="imagenMain" src="' .$biblioteca['DirectorioImagen'] . '"></a>';
                                echo '<div class="desImagen"><h4>|| ' .  utf8_encode($biblioteca['Nombre']) . '</h4></div>';                                
                                echo '</div>';
                                echo '<br><h4 class="'.$claseNumLibres.'">'.$numLibres.$txtNumLibres.'</h4><br>';                              
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
        
    </body>
</html>
