<?php

require_once('../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$time = str_replace(['Time:',' '],'',$data[0]);
$distance = str_replace(['Distance: ',' '],'',$data[1]);
$wins = calculateWins($time,$distance);

echo('Solution: '.$wins);

function calculateWins($time, $record){
    for ($i = 1; $i < $time; $i++){
        $space = $i*($time-$i);
        if($space > $record){
           $min = $i;
           break;
        }
    }
    for ($i = $time; $i >=1 ; $i--){
        $space = $i*($time-$i);
        if($space > $record){
            $max = $i;
            break;
        }
    }
    return $max - $min + 1;
}