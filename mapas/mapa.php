<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include '../clases/bd.class.php';
$bd = new bd();

if (!isset($biblio)){
    $biblio = $_POST['biblio'];
    $planta = $_POST['planta'];
}
if (!isset($modo)){
    $modo = 'lista';
}

$resultado = '<table class="table table-bordered">';
for ($i = 0; $i <= 30; $i++) {
    $resultado .= '<tr>';
    for ($j = 0; $j <= 30; $j++) {
        $resultado .= '<td data-y="'.$i.'" data-x="'.$j.'" class="suelta" id="x' . $j . 'y' . $i . '">&ensp;</td>';
    }
    $resultado .= '</tr>';
}
$resultado .= '</table>';
$resultadoScript = '<script>';

$mesas = $bd->consulta("select * from Mesa where Biblioteca_Id = ".$biblio." and Planta = ".$planta);

foreach ($mesas as $mesa) {

    $numAsientos = $mesa['numAsientos'];
    $asientosPorFila = $numAsientos / 2;
    $idMesa = $mesa['id'];
    $x = $mesa['x'];
    $y = $mesa['y'];
    $gradosRotacion = $mesa['gradosRotacion'];

    if ($modo !== 'modificar'){
        // Obtengo los asientos de la mesa
        $asientos = $bd->consulta("select * from Asiento where Mesa_id = '" . $idMesa . "'");
        $contAux = 0;
        $mesaHtml = '<table id=\"'.$idMesa.'\" style=\"width:'.(11 * $numAsientos).'px\" class=\"tablaAsientos\"><tr>';
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
                $mesaHtml .= '</tr><tr>';
            }

            $mesaHtml .= '<td><img data-estado=\"' . $estado . '\" class=\"hvr-grow asiento\" width=\"100%\" id=\"' . $idAsiento . '\" src=\"' . $icono . '\"></td>';
            $contAux++;
        }
        $mesaHtml .= '</tr></table>';
        
        $resultadoScript .= '$("#x' . $x . 'y' . $y . '").html("' . $mesaHtml . '");';
        $resultadoScript .= '$("#' . $idMesa. '").css(\'transform\', \'rotate('.$gradosRotacion.'deg)\');';
        
    }else{
        $resultadoScript .= '$("#x' . $x . 'y' . $y . '").html("<center><p data-rot=\"'.$gradosRotacion.'\" data-asientos=\"'.$numAsientos.'\" data-id=\"'.$idMesa.'\" class=\"numAsientos\">' . $numAsientos . '</p></center>");';
    }

    
}

$resultadoScript .= '</script>';


echo $resultado;
echo $resultadoScript;
