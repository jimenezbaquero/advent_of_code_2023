<?php

require_once('../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');
$instructions = [];
$row = 0;
$col = 0;
$vertixs = [];
$border = 0;

foreach ($data as $line){
    $instruction = [];
    $arrayLine = explode(' ',$line);
    $code = str_replace(['(',')','#'],'',$arrayLine[2]);
    $instruction['direction'] = getDirection(substr($code,-1));
    $instruction['steps'] = hexdec(substr($code,0,5));
    $instructions[] =  $instruction;
}

foreach ($instructions as $instruction){
    $multRow = 0;
    $multCol = 0;
    switch ($instruction['direction']) {
        case ('U'):
            $multRow = 1;
            break;
        case ('D'):
            $multRow = -1;
            break;
        case ('R'):
            $multCol = 1;
            break;
        case ('L'):
            $multCol = -1;
            break;
    }
    $border += $instruction['steps'];
    $row += $multRow*$instruction['steps'];
    $col += $multCol*$instruction['steps'];
    $points[] = [$row,$col];
}

$numberPoints = count($points);
$points[] = $points[0];
$area = 0;

for ($i = 1; $i< $numberPoints;$i++){
    $area += ($points[$i][0]*($points[$i+1][1] - $points[$i-1][1]));
}
$area = abs($area)/2;
$interior = $area - $border/2 + 1;

echo 'Solution: '.$interior + $border;

function getDirection($direction){
    switch ($direction) {
        case ('0'):
            return 'R';
        case ('1'):
            return 'D';
        case ('2'):
            return 'L';
        case ('3'):
            return 'U';
    }
}

