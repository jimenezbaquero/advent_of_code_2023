<?php

require_once('../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');
$instructions = [];
$points = [];
$point = 0;

$array = 'instructions';
foreach($data as $line){
    if($line == ''){
        break;
    }
    if($array == 'instructions'){
        $aux = explode('{',str_replace('}','',$line));
        $arrayAux = explode(',',$aux[1]);
        $arrayConditions = [];
        foreach ($arrayAux as $condition){
            if(str_contains($condition,':')) {
                $condAux = explode(':', $condition);
                $arrayConditions[] = ['condition' => $condAux[0], 'next' => $condAux[1]];
            }else{
                $arrayConditions[] = ['condition' => 'true', 'next' => $condition];
            }
        }
        $instructions[$aux[0]] = $arrayConditions;
    }
}

$pendingCombinations = [['condition' => '', 'next' => 'in']];
$combinations = [];

while(count($pendingCombinations) > 0){
    $combination = array_shift($pendingCombinations);
    if(!in_array($combination['next'],['A','R'])) {
        $conditions = $instructions[$combination['next']];
        $aux = '';
        foreach ($conditions as $condition) {
            if ($condition['condition'] == 'true') {
                $pendingCombinations[] = ['condition' => $combination['condition'] . $aux, 'next' => $condition['next']];
            }else{
                $pendingCombinations[] = ['condition' => $combination['condition'] . $aux . ',' . $condition['condition'], 'next' => $condition['next']];
                $aux .= ',!' . $condition['condition'];
            }
        }
    }else if($combination['next'] == 'A'){
        $combinations[] = trim($combination['condition'],',');
    }
}

$total = 0;
foreach ($combinations as $combination) {
    $x = [1, 4000];
    $m = [1, 4000];
    $a = [1, 4000];
    $s = [1, 4000];
    $conditions = explode(',',$combination);
    foreach ($conditions as $condition){
        $negation = false;
        if(str_contains($condition,'!')){
            $negation = true;
            $condition = substr($condition,1);
        }
        $sign = substr($condition,1,1);
        if($negation){
            if($sign == '<'){
                $sign = '>=';
            }else{
                $sign = '<=';
            }
        }
        $variable = substr($condition,0,1);
        $value = (int)substr($condition,2);

        if($sign == '<' && $$variable[1] > $value){
            $$variable[1] = $value - 1;
        }else if($sign == '<=' && $$variable[1] > $value){
            $$variable[1] = $value;
        }else if($sign == '>' && $$variable[0] < $value){
            $$variable[0] = $value + 1;
        }else if($sign == '>=' && $$variable[0] < $value) {
            $$variable[0] = $value;
        }
    }
    $total += ($x[1]-$x[0]+1)*($s[1]-$s[0]+1)*($m[1]-$m[0]+1)*($a[1]-$a[0]+1);
}

echo 'Solution: '.$total;


