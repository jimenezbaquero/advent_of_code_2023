<?php

require_once('../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 1;
$times = explode(' ',str_replace('Time: ','',$data[0]));
$distances = explode(' ',str_replace('Distance: ','',$data[1]));
$times = array_values(array_filter($times, function($e){return !in_array($e, [' ','']);}));
$distances = array_values(array_filter($distances, function($e){return !in_array($e, [' ','']);}));

for ($i = 0; $i < count($times); $i++){
    $time = $times[$i];
    $record = $distances[$i];
    $wins = calculateWins($time,$record);
    $solution = $solution*count($wins);
}

echo('Solution: '.$solution);

function calculateWins($time, $record){
    $response = [];
    for ($i = 1; $i < $time; $i++){
        $space = $i*($time-$i);
        if($space > $record){
            $response[] = $space;
        }
    }
    return $response;
}