<?php

require_once('../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');
$matrizFile = [];
$fila = 0;

foreach ($data as $row => $line) {
    foreach (str_split($line) as $col => $element){
        $matrizFile[$row][$col] = $element;
    }
}

$numbers = locateNumbers($matrizFile);

echo 'Solution: '.array_sum($numbers);

function locateNumbers($matriz){
    $response = [];
    foreach ($matriz as $row => $arrayRow) {
        $number = '';
        $neighbors = [];
        foreach ($arrayRow as $col => $element){
            if (is_numeric($element)){
                $number .= $element;
                if(isset($matriz[$row-1][$col-1])){
                    $neighbors[] = $matriz[$row-1][$col-1];
                }
                if(isset($matriz[$row][$col-1])){
                    $neighbors[] = $matriz[$row][$col-1];
                }
                if(isset($matriz[$row+1][$col-1])){
                    $neighbors[] = $matriz[$row+1][$col-1];
                }
                if(isset($matriz[$row-1][$col])){
                    $neighbors[] = $matriz[$row-1][$col];
                }
                if(isset($matriz[$row+1][$col])){
                    $neighbors[] = $matriz[$row+1][$col];
                }
                if(isset($matriz[$row-1][$col+1])){
                    $neighbors[] = $matriz[$row-1][$col+1];
                }
                if(isset($matriz[$row][$col+1])){
                    $neighbors[] = $matriz[$row][$col+1];
                }
                if(isset($matriz[$row+1][$col+1])){
                    $neighbors[] = $matriz[$row+1][$col+1];
                }
                $neighbors = array_filter($neighbors,function($e) {return (!is_numeric($e) && $e!= '.');});
            }else {
                if ($number != '') {
                    if (!empty($neighbors)) {
                        $response[] = $number;
                    }
                }
                $number = '';
                $neighbors = [];
            }
        }
        if($number != ''){
            if(!empty($neighbors)){
                $response[] = $number;
            }
        }
    }
    return $response;
}