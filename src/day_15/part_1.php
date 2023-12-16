<?php

require_once('../functions.php');

$data = loadFile('input.txt');
$word = '';
$sum = 0;

$words = explode(',',$data[0]);

foreach ($words as $word){
    $hash = calculateHash($word);
    $word = '';
    $sum += $hash;
}

echo 'Solution: '.$sum;

function calculateHash($word){
    $response = 0;
    foreach (str_split($word) as $character){
        $code = ord($character);
        $response += $code;
        $response = $response * 17;
        $response = $response % 256;
    }
    return $response;
}
