<?php

require_once('../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');
$instructions = [];
$points = [];
$point = 0;

$array = 'instructions';
foreach($data as $line){
    if($line == ''){
        $array = 'points';
        continue;
    }
    if($array == 'instructions'){
        $aux = explode('{',str_replace('}','',$line));
        $arrayAux = explode(',',$aux[1]);
        $arrayConditions = [];
        foreach ($arrayAux as $condition){
            if(str_contains($condition,':')) {
                $condAux = explode(':', $condition);
                $arrayConditions[] = ['condition' => 'return $'.$condAux[0].';', 'next' => $condAux[1]];
            }else{
                $arrayConditions[] = ['condition' => 'return true;', 'next' => $condition];
            }
        }
        $instructions[$aux[0]] = $arrayConditions;
    }else{
        $arrayCoord = explode(',',str_replace(['{','}'],'',$line));
        foreach ($arrayCoord as $coord){
            $aux = explode('=',$coord);
            $points[$point][$aux[0]] = $aux[1];
        }
        $point++;
    }
}

$total = 0;

foreach ($points as $point){
    $next = 'in';
    while(!in_array($next,['A','R'])){
        $next = execute($point,$next,$instructions);
    }
    if($next == 'A'){
        foreach ($point as $coord){
            $total += $coord;
        }
    }
}

echo 'Solution: '.$total;

function execute($point,$key,$instructions){
    $x = $point['x'];
    $m = $point['m'];
    $a = $point['a'];
    $s = $point['s'];
    foreach ($instructions[$key] as $condition){
        if(eval($condition['condition'])){
            return $condition['next'];
        }
    }
    return $condition['next'];
}
