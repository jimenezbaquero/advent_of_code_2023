<?php

require_once('../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 0;

foreach ($data as $card){
    $arrayCard = explode(': ',$card);
    $arrayNumbers = explode(' | ',$arrayCard[1]);
    $arrayWins = explode(' ',$arrayNumbers[0]);
    $arrayOwns = explode(' ',$arrayNumbers[1]);

    $arrayWins = array_filter($arrayWins,function ($e) {return $e != '';});
    $arrayOwns = array_filter($arrayOwns,function ($e) {return $e != '';});

    $finds = array_intersect($arrayWins,$arrayOwns);

    if(!empty($finds)) {
        $solution += 2 ** (count($finds) - 1);
    }
}

echo 'Solution: '.$solution;