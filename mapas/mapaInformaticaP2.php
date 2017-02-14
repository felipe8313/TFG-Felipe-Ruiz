<?php

include '../clases/bd.class.php';
$bd = new bd();

$mapa = '';

// Zona 1                    
$mapa .= '<div class="row"><div class="col-md-5"><table class="tablaMesas"><tr>';
$bd = new bd();
$mesas = $bd->consulta("select * from Mesa where Biblioteca_Id = 1 and zona = 1 and Planta = 2");

foreach ($mesas as $mesa) {

    $mapa .= '<td>';
    $idMesa = $mesa['id'];

    // Obtengo los asientos de la mesa
    $asientos = $bd->consulta("select * from Asiento where Mesa_id = '" . $idMesa . "'");
    $mapa .= '<table style="width:45px" class="tablaAsientos"><tr>';
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

        $mapa .= '<td><img data-estado="' . $estado . '" class="hvr-grow asiento" width="100%" id="' . $idAsiento . '" src="' . $icono . '"></td>';
    }
    $mapa .= '</tr></table></td>';
}
$mapa .= '</tr></table><br><br><br>';

// Zona 2                    
$mapa .= '<table class="tablaMesas"><tr>';
$bd = new bd();
$mesas = $bd->consulta("select * from Mesa where Biblioteca_Id = 1 and zona = 2 and Planta = 2");
$contMesas = 0;
$contFilas = 0;

foreach ($mesas as $mesa) {

    if ($contMesas % 3 == 0 && $contFilas % 2 == 0 && $contMesas !== 0) {
        $mapa .= '</tr><tr class="espacioFila">';
        $contFilas++;
    } else if ($contMesas % 3 == 0 && $contMesas !== 0) {
        $mapa .= '</tr><tr>';
        $contFilas++;
    }
    $mapa .= '<td>';
    $idMesa = $mesa['id'];

    // Obtengo los asientos de la mesa
    $asientos = $bd->consulta("select * from Asiento where Mesa_id = '" . $idMesa . "'");
    $mapa .= '<table style="width:45px" id="' . $idMesa . '" class="tablaAsientos"><tr>';

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

        $mapa .= '<td><img data-estado="' . $estado . '" class="hvr-grow asiento" width="100%" id="' . $idAsiento . '" src="' . $icono . '"></td>';
    }

    $contMesas++;

    $mapa .= '</tr></table></td>';
}
$mapa .= '</tr></table>';

// Zona 3                    
$mapa .= '<div class="zona3"><table class="tablaMesas"><tr>';
$bd = new bd();
$mesas = $bd->consulta("select * from Mesa where Biblioteca_Id = 1 and zona = 3 and Planta = 2");
$contMesas = 0;

foreach ($mesas as $mesa) {

    if ($contMesas % 3 == 0 && $contMesas !== 0) {
        $mapa .= '</tr><tr>';
    }
    $mapa .= '<td>';
    $numAsientos = $mesa['numAsientos'];
    $asientosPorFila = $numAsientos / 2;
    $idMesa = $mesa['id'];

    // Obtengo los asientos de la mesa
    $asientos = $bd->consulta("select * from Asiento where Mesa_id = '" . $idMesa . "'");
    $mapa .= '<table style="width:45px" id="' . $idMesa . '" class="tablaAsientos"><tr>';

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

    $contMesas++;
    $mapa .= '</tr></table></td>';
}
$mapa .= '</tr></table></div></div>';

// Zona 4                    
$mapa .= '<div class="col-md-5"><table class="tablaMesas"><tr>';
$bd = new bd();
$mesas = $bd->consulta("select * from Mesa where Biblioteca_Id = 1 and zona = 4 and Planta = 2");

foreach ($mesas as $mesa) {
    $mapa .= '<td>';
    $numAsientos = $mesa['numAsientos'];
    $asientosPorFila = $numAsientos / 2;
    $idMesa = $mesa['id'];

    $mapa .= '</tr><tr>';

    // Obtengo los asientos de la mesa
    $asientos = $bd->consulta("select * from Asiento where Mesa_id = '" . $idMesa . "'");
    $mapa .= '<table style="width:27px" id="' . $idMesa . '" class="tablaAsientos"><tr>';

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
    $mapa .= '</tr></table></td>';
}
$mapa .= '</tr></table></div></div>';

echo $mapa;
?>