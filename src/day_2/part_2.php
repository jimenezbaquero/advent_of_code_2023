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
    $minBlue = 0;
    $minRed = 0;
    $minGreen = 0;
    $arrayGame = explode(': ',$game);
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
            if ($blue > $minBlue) {
                $minBlue = $blue;
            }
            if ($red > $minRed) {
                $minRed = $red;
            }
            if ($green  > $minGreen) {
                $minGreen = $green;
            }
        }
    }
    $pot = $minBlue*$minGreen*$minRed;
    $sum += $pot;
}

echo 'Solution: '.$sum;