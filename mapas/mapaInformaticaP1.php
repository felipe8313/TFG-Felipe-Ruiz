<?php

include '../clases/bd.class.php';
$bd = new bd();

$mapa = '';

// Zona 1                    
$mapa .= '<div class="zona1"><div class="col-md-3"><table class="tablaMesas">';
$mesas = $bd->consulta("select * from Mesa where Biblioteca_Id = 1 and zona = 1 and Planta = 1");


foreach ($mesas as $mesa) {
    $mapa .= '<tr><td>';

    $numAsientos = $mesa['numAsientos'];
    $asientosPorFila = $numAsientos / 2;
    $idMesa = $mesa['id'];

    // Obtengo los asientos de la mesa
    $asientos = $bd->consulta("select * from Asiento where Mesa_id = '" . $idMesa . "'");
    $contAux = 0;
    $mapa .= '<table style="width:77px" class="tablaAsientos"><tr>';
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
            $mapa .= '</tr><tr>';
        }

        $mapa .= '<td><img data-estado="' . $estado . '" class="hvr-grow asiento" width="100%" id="' . $idAsiento . '" src="' . $icono . '"></td>';
        $contAux++;
    }
    $mapa .= '</tr></table>';
    $mapa .= '</td></tr>';
}
$mapa .= '</table></div></div>';

// Zona 2              
$mapa .= '<div class="zona2"><div class="col-md-3"><table class="tablaMesas">';
$mesas = $bd->consulta("select * from Mesa where Biblioteca_Id = 1 and zona = 2 and Planta = 1 order by id asc");


foreach ($mesas as $mesa) {
    $mapa .= '<tr><td>';

    $numAsientos = $mesa['numAsientos'];
    $asientosPorFila = $numAsientos / 2;
    $idMesa = $mesa['id'];

    // Obtengo los asientos de la mesa
    $asientos = $bd->consulta("select * from Asiento where Mesa_id = '" . $idMesa . "'");
    $contAux = 0;

    // Según el número de asientos hago mas o menos grande la mesa
    if ((int) $numAsientos === 6) {
        $mapa .= '<table style="width:60px" class="tablaAsientos"><tr>';
    } else {
        $mapa .= '<table style="width:43px" class="tablaAsientos"><tr>';
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
            $mapa .= '</tr><tr>';
        }
        $mapa .= '<td><img data-estado="' . $estado . '" class="hvr-grow asiento" width="100%" id="' . $idAsiento . '" src="' . $icono . '"></td>';
        $contAux++;
    }
    $mapa .= '</tr></table>';
}
$mapa .= '</table></div></div>';


// Zona 3                  
$mapa .= '<div class="zona3"><div class="col-md-3"><table class="tablaMesas">';
$mesas = $bd->consulta("select * from Mesa where Biblioteca_Id = 1 and zona = 3 and Planta = 1");

foreach ($mesas as $mesa) {
    $mapa .= '<tr><td>';

    $numAsientos = $mesa['numAsientos'];
    $asientosPorFila = $numAsientos / 2;
    $idMesa = $mesa['id'];

    // Obtengo los asientos de la mesa
    $asientos = $bd->consulta("select * from Asiento where Mesa_id = '" . $idMesa . "'");
    $contAux = 0;
    $mapa .= '<table style="width:43px" class="tablaAsientos"><tr>';
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
            $mapa .= '</tr><tr>';
        }

        $mapa .= '<td><img data-estado="' . $estado . '" class="hvr-grow asiento" width="100%" id="' . $idAsiento . '" src="' . $icono . '"></td>';
        $contAux++;
    }
    $mapa .= '</tr></table>';
    $mapa .= '</td></tr>';
}
$mapa .= '</table></div></div>';

// Zona 4
$mapa .= '<div class="zona4"><div class="col-md-3"><table class="tablaMesas">';
$mesas = $bd->consulta("select * from Mesa where Biblioteca_Id = 1 and zona = 4 and Planta = 1");


foreach ($mesas as $mesa) {
    $mapa .= '<tr><td>';

    $numAsientos = $mesa['numAsientos'];
    $asientosPorFila = $numAsientos / 2;
    $idMesa = $mesa['id'];

    // Obtengo los asientos de la mesa
    $asientos = $bd->consulta("select * from Asiento where Mesa_id = '" . $idMesa . "'");
    $contAux = 0;
    $mapa .= '<table style="width:43px" class="tablaAsientos"><tr>';
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
            $mapa .= '</tr><tr>';
        }
        $mapa .= '<td><img data-estado="' . $estado . '" class="hvr-grow asiento" width="100%" id="' . $idAsiento . '" src="' . $icono . '"></td>';
        $contAux++;
    }
    $mapa .= '</tr></table>';
    $mapa .= '</td></tr>';
}
$mapa .= '</table></div></div>';

echo $mapa;
?>