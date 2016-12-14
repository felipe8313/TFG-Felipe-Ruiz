
<html>
    <head>
        <title>Bienvenido a Librarino</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="resources/style.css"/>
        <link rel="stylesheet" type="text/css" href="resources/bootstrap/css/bootstrap.css"/>
    </head>
    <body>        
        <?php include 'header.php' ?>
        <div class="contenido">
            <ul class="nav nav-pills nav-justified">
                <li role="presentation" class="active"><a href="informatica1.php"><h4>Planta 1</h4></a></li>
                <li role="presentation"><a href="informatica2.php"><h4>Planta 2</h4></a></li>
            </ul>
            <div class="row">
                <div class="col-md-10 col-md-offset-4">



                    <?php
                    // Zona 1                    
                    echo '<div class="zona1"><div class="col-md-1"><table class="tablaMesas">';
                    $bd = new bd();
                    $mesas = $bd->consulta("select * from Mesa where Biblioteca_Id = 1 and zona = 1 and Planta = 1");


                    foreach ($mesas as $mesa) {
                        echo '<tr><td>';

                        $numAsientos = $mesa['numAsientos'];
                        $asientosPorFila = $numAsientos / 2;
                        $idMesa = $mesa['id'];

                        // Obtengo los asientos de la mesa
                        $asientos = $bd->consulta("select * from Asiento where Mesa_id = '" . $idMesa . "'");
                        $contAux = 0;
                        echo '<table style="width:77px" class="tablaAsientos"><tr>';
                        foreach ($asientos as $asiento) {

                            $idAsiento = $asiento['Id'];
                            $estado = (int) $asiento['Estado'];

                            if ($estado === 1) { // Asiento libre
                                $icono = 'resources/imgs/libre.png';
                            } else if ($estado === 0) { // Asiento ocupado
                                $icono = 'resources/imgs/ocupado.png';
                            } else if ($estado === 2) { // Asiento reservado
                                $icono = 'resources/imgs/reservado.png';
                            }

                            if ($contAux % $asientosPorFila === 0 && $contAux !== 0) {
                                echo '</tr><tr>';
                            }

                            echo '<td><img width="100%" id="' . $idAsiento . '" src="' . $icono . '"></td>';
                            $contAux++;
                        }
                        echo '</tr></table>';
                        echo '</td></tr>';
                    }
                    echo '</table></div></div>';

                    // Zona 4                    
                    echo '<div class="zona4"><div class="col-md-1"><table class="tablaMesas">';
                    $mesas = $bd->consulta("select * from Mesa where Biblioteca_Id = 1 and zona = 4 and Planta = 1");


                    foreach ($mesas as $mesa) {
                        echo '<tr><td>';

                        $numAsientos = $mesa['numAsientos'];
                        $asientosPorFila = $numAsientos / 2;
                        $idMesa = $mesa['id'];

                        // Obtengo los asientos de la mesa
                        $asientos = $bd->consulta("select * from Asiento where Mesa_id = '" . $idMesa . "'");
                        $contAux = 0;

                        // Según el número de asientos hago mas o menos grande la mesa
                        if ((int) $numAsientos === 6) {
                            echo '<table style="width:60px" class="tablaAsientos"><tr>';
                        } else {
                            echo '<table style="width:43px" class="tablaAsientos"><tr>';
                        }

                        foreach ($asientos as $asiento) {

                            $idAsiento = $asiento['Id'];
                            $estado = (int) $asiento['Estado'];

                            if ($estado === 1) { // Asiento libre
                                $icono = 'resources/imgs/libre.png';
                            } else if ($estado === 0) { // Asiento ocupado
                                $icono = 'resources/imgs/ocupado.png';
                            } else if ($estado === 2) { // Asiento reservado
                                $icono = 'resources/imgs/reservado.png';
                            }

                            if ($contAux % $asientosPorFila === 0 && $contAux !== 0) {
                                echo '</tr><tr>';
                            }
                            echo '<td><img width="100%" id="' . $idAsiento . '" src="' . $icono . '"></td>';
                            $contAux++;
                        }
                        echo '</tr></table>';
                    }
                    echo '</table></div></div>';

                    // Zona 2                        
                    echo '<div class="zona2"><div class="col-md-1"><table class="tablaMesas">';
                    $mesas = $bd->consulta("select * from Mesa where Biblioteca_Id = 1 and zona = 2 and Planta = 1");

                    foreach ($mesas as $mesa) {
                        echo '<tr><td>';

                        $numAsientos = $mesa['numAsientos'];
                        $asientosPorFila = $numAsientos / 2;
                        $idMesa = $mesa['id'];

                        // Obtengo los asientos de la mesa
                        $asientos = $bd->consulta("select * from Asiento where Mesa_id = '" . $idMesa . "'");
                        $contAux = 0;
                        echo '<table style="width:43px" class="tablaAsientos"><tr>';
                        foreach ($asientos as $asiento) {

                            $idAsiento = $asiento['Id'];
                            $estado = (int) $asiento['Estado'];

                            if ($estado === 1) { // Asiento libre
                                $icono = 'resources/imgs/libre.png';
                            } else if ($estado === 0) { // Asiento ocupado
                                $icono = 'resources/imgs/ocupado.png';
                            } else if ($estado === 2) { // Asiento reservado
                                $icono = 'resources/imgs/reservado.png';
                            }

                            if ($contAux % $asientosPorFila === 0 && $contAux !== 0) {
                                echo '</tr><tr>';
                            }

                            echo '<td><img width="100%" id="' . $idAsiento . '" src="' . $icono . '"></td>';
                            $contAux++;
                        }
                        echo '</tr></table>';
                        echo '</td></tr>';
                    }
                    echo '</table></div></div>';

                    // Zona 3
                    echo '<div class="zona3"><div class="col-md-1"><table class="tablaMesas">';
                    $mesas = $bd->consulta("select * from Mesa where Biblioteca_Id = 1 and zona = 3 and Planta = 1");


                    foreach ($mesas as $mesa) {
                        echo '<tr><td>';

                        $numAsientos = $mesa['numAsientos'];
                        $asientosPorFila = $numAsientos / 2;
                        $idMesa = $mesa['id'];

                        // Obtengo los asientos de la mesa
                        $asientos = $bd->consulta("select * from Asiento where Mesa_id = '" . $idMesa . "'");
                        $contAux = 0;
                        echo '<table style="width:43px" class="tablaAsientos"><tr>';
                        foreach ($asientos as $asiento) {

                            $idAsiento = $asiento['Id'];
                            $estado = (int) $asiento['Estado'];

                            if ($estado === 1) { // Asiento libre
                                $icono = 'resources/imgs/libre.png';
                            } else if ($estado === 0) { // Asiento ocupado
                                $icono = 'resources/imgs/ocupado.png';
                            } else if ($estado === 2) { // Asiento reservado
                                $icono = 'resources/imgs/reservado.png';
                            }

                            if ($contAux % $asientosPorFila === 0 && $contAux !== 0) {
                                echo '</tr><tr>';
                            }
                            echo '<td><img width="100%" id="' . $idAsiento . '" src="' . $icono . '"></td>';
                            $contAux++;
                        }
                        echo '</tr></table>';
                        echo '</td></tr>';
                    }
                    echo '</table></div></div>';
                    ?>
                </div>
            </div>
        </div>

                    <?php include 'footer.php' ?>        
        <script type="text/javascript" src="resources/jquery.js"></script>
        <script type="text/javascript" src="resources/bootstrap/js/bootstrap.js"></script>
        <script type="text/javascript">

        </script>
    </body>
</html>
