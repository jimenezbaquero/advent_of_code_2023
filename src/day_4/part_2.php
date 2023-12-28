<?php

require_once('../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 0;
$arrayOriginal = [];


foreach ($data as $card) {
    $arrayCard = explode(': ', $card);
    $cardNumber = str_replace(['Card', ' '], '', $arrayCard[0]);
    $arrayNumbers = explode(' | ', $arrayCard[1]);
    $arrayWins = explode(' ', $arrayNumbers[0]);
    $arrayOwns = explode(' ', $arrayNumbers[1]);

    $arrayWins = array_filter($arrayWins, function ($e) {
        return $e != '';
    });
    $arrayOwns = array_filter($arrayOwns, function ($e) {
        return $e != '';
    });

    $finds = array_intersect($arrayWins, $arrayOwns);

    $arrayOriginal[$cardNumber] = ['amount' => 1, 'finds' => count($finds)];
}

foreach ($arrayOriginal as $number => $card) {
    for ($i = 1; $i <= $card['finds']; $i++) {
        $arrayOriginal[$number + $i]['amount'] = $arrayOriginal[$number + $i]['amount'] + $arrayOriginal[$number]['amount'];
    }
}

foreach ($arrayOriginal as $card) {
    $solution += $card['amount'];
}

echo 'Solution: '.$solution;