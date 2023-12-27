<?php

require_once('../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');
$arrayMaxs = [];
$redLimit = 12;
$blueLimit = 14;
$greenLimit = 13;
$sum = 0;

foreach ($data as $game){
    $maxBlue = 0;
    $maxRed = 0;
    $maxGreen = 0;
    $arrayGame = explode(': ',$game);
    $numberGame = explode(' ',$arrayGame[0])[1];
    $arrayResults = explode('; ',$arrayGame[1]);
    foreach ($arrayResults as $result){
        $blue = 0;
        $green = 0;
        $red = 0;
        $arrayColors = explode(', ',$result);
        foreach ($arrayColors as $colors) {
            $arrayColor = explode(' ',$colors);
            switch ($arrayColor[1]) {
                case('blue'):
                    $blue = $arrayColor[0];
                    break;
                case('green'):
                    $green = $arrayColor[0];
                    break;
                case('red'):
                    $red = $arrayColor[0];
                    break;
            }
            if ($blue > $maxBlue) {
                $maxBlue = $blue;
            }
            if ($red > $maxRed) {
                $maxRed = $red;
            }
            if ($green > $maxGreen) {
                $maxGreen = $green;
            }
        }
    }
    $arrayMaxs[$numberGame] = ['blue' => $maxBlue, 'red' => $maxRed, 'green' => $maxGreen];

    if($maxBlue<=$blueLimit && $maxGreen <= $greenLimit && $maxRed <= $redLimit){
        $sum += $numberGame;
    }
}

echo 'Solution: '.$sum;