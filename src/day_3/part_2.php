<?php

require_once('../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');
$matrizFile = [];
$directions = [[-1,1],[0,1],[1,1],[-1,0],[1,0],[-1,-1],[0,-1],[1,-1]];
$solution = 0;

foreach ($data as $row => $line) {
    foreach (str_split($line) as $col => $element){
        $matrizFile[$row][$col] = $element;
    }
}

[$symbols,$numbers] = locateSymbolsAndNumbers($matrizFile);

foreach ($symbols as $symbol){
    $solution += array_product(locateNumbers($symbol,$numbers,$matrizFile));
}

echo 'Solution: '.$solution;

function locateNumbers($symbol,$numbers,$matriz){
    global $directions;
    $numberFind = [];
    foreach ($directions as $direction){
        $row = $symbol[0] + $direction[0];
        $col = $symbol[1] + $direction[1];
        foreach ($numbers[$row] as $number){
            if(isset($matriz[$row][$col])){
                if(in_array([$row,$col],$number['set']) && !in_array($number['number'],$numberFind)){
                    $numberFind[] = $number['number'];
                    if(count($numberFind) == 2){
                        return $numberFind;
                    }
                }

            }
        }
    }
    return [0,0];
}

function locateSymbolsAndNumbers($matriz){
    $symbols = [];
    $numbers = [];
    $num = 0;
    foreach ($matriz as $row => $arrayRow){
        $number = '';
        $set = [];
        foreach ($arrayRow as $col => $element){
            if (is_numeric($element)) {
                $number .= $element;
                $set[] = [$row, $col];
            }else{
                if($element == '*'){
                    $symbols[] = [$row,$col];
                }
                if ($number != '') {
                    $numbers[$row][] = ['number' => $number, 'set' => $set];
                    $number = '';
                    $set = [];
                }
            }
        }
        if ($number != '') {
            $numbers[$row][] = ['number' => $number, 'set' => $set];
        }
    }
    return [$symbols,$numbers];
}

