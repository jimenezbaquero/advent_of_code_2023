<?php

require_once('../functions.php');

$data = loadFile('input.txt');
$data = loadFile('test.txt');
$numbers = [];

foreach ($data as $line){
    $first = firstNumber($line);
    $last = firstNumber(strrev($line));
    $numbers[] = $first*10 + $last;
}

echo 'Solution: '.array_sum($numbers);

function firstNumber($string){
    $arrayString = str_split($string);
        foreach ($arrayString as $char){
            if(is_numeric($char)){
                return $char;
            }
        }
    return null;
}