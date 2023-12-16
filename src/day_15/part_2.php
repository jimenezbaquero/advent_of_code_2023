<?php

require_once('../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');
$word = '';
$sum = 0;
$boxs = [];

$words = explode(',',$data[0]);

foreach ($words as $word){
    $delete = false;
    if(str_contains($word,'-')){
        $label = substr($word,0,-1);
        $delete = true;
    }else{
        $characters = explode('=',$word);
        $label = $characters[0];
        $length = $characters[1];
    }
    $hash = calculateHash($label);
    $existBox = isset($boxs[$hash]);

    if($delete){
        if($existBox) {
            $boxs[$hash] = deleteElement($label, $boxs[$hash]);
            if(count($boxs[$hash]) == 0){
                unset($boxs[$hash]);
            }
        }
    }else{
        if(!$existBox){
            $boxs[$hash] = [];
        }
        $boxs[$hash] = addElement($label,$length,$boxs[$hash]);
    }
    $word = '';
}

foreach ($boxs as $boxNumber => $box){
    foreach ($box as $key => $lens){
        $length = $lens['length'];
        $power = ($boxNumber + 1) * ($key + 1) * $length;
        $sum += $power;
    }
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

function addElement($label, $length, $box){
    $response = [];
    $i = 0;
    $find = false;
    foreach ($box as $element){
        if($element['label'] != $label){
            $response[$i++] = $element;
        }else{
            $response[$i++] = ['label' => $label, 'length' => $length];
            $find = true;
        }
    }

    if(!$find){
        $response[$i] = ['label' => $label, 'length' => $length];
    }
    return $response;
}

function deleteElement($label, $box){
    $response = [];
    $i = 0;
    foreach ($box as $element){
        if($element['label'] != $label){
            $response[$i++] = $element;
        }
    }
    return $response;
}
