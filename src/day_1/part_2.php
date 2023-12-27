<?php

require_once('../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');
$numbers = [];

foreach ($data as $line){
    $first = firstNumber($line);
    $last = firstNumber(strrev($line),'rev');
    $numbers[] = $first*10 + $last;
}

echo 'Solution: '.array_sum($numbers);

function firstNumber($string,$sort = null){
    $arrayString = str_split($string);
    $processed = '';
    foreach ($arrayString as $char){
        if(is_numeric($char)){
            return $char;
        }else{
            if(!is_null($sort)) {
                $processed = $char.$processed;
            }else{
                $processed .= $char;
            }
            $number = findNumber($processed);
            if(!is_null($number)){
                return $number;
            }
        }
    }
    return null;
}

function findNumber($string){
    $numbersLetters = [1=>'one',2=>'two',3=>'three',4=>'four',5=>'five',6=>'six',7=>'seven',8=>'eight',9=>'nine'];
    foreach ($numbersLetters as $number=>$letters){
        if(str_contains($string,$letters)){
            return $number;
        }
    }
    return null;
}