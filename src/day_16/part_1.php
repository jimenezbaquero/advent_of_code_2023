<?php

require_once('../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');
$map = [];
$pendingBeans = [];
$bean = ['row' => 0, 'col' => 0, 'direction' => 'E'];

foreach ($data as $numRow => $row){
    foreach (str_split($row) as $numCol => $element){
        $map[$numRow][$numCol] = ['element' => $element, 'beans' => []];
    }
}

$pendingBeans[] = $bean;

while (count($pendingBeans) > 0){
    $actualStep = array_shift($pendingBeans);
    $row = $actualStep['row'];
    $col = $actualStep['col'];
    $direction = $actualStep['direction'];
    $out = false;
    while(!existStep($actualStep,$map) && !$out){
        echo 'estamos en '.$row.','.$col.' en direccion '.$direction.PHP_EOL;
        $map[$row][$col]['beans'][] = $direction;
        $nextStep = calculateNextStep($actualStep, $map);
        $stepsNumber = count($nextStep);
        if ($stepsNumber > 1) {
            echo 'se guarda un camino'.PHP_EOL;
            $pendingBeans[] = array_shift($nextStep);
        }
        if ($stepsNumber == 0) {
            $out = true;
            echo 'proximo paso fuera'.PHP_EOL;
        }else{
            $actualStep = $nextStep[0];
            $row = $actualStep['row'];
            $col = $actualStep['col'];
            $direction = $actualStep['direction'];
        }
    }
}

echo 'Solution: '.tilesEnergized($map);

function tilesEnergized($map){
    $response = 0;
    foreach ($map as $row){
        foreach ($row as $tile){
            if(count($tile['beans']) > 0){
                $response++;
            }
        }
    }
    return $response;
}
function existStep($step,$map){
    if(in_array($step['direction'],$map[$step['row']][$step['col']]['beans'])){
        return true;
    }
    return false;
}

function calculateNextStep($step,$map){
    $direction = $step['direction'];
    $nextStep = [];
    switch ($direction){
        case('N'):
            $row = $step['row'] - 1;
            $col = $step['col'];
            if(isset($map[$row][$col])){
                switch ($map[$row][$col]['element']){
                    case('.'):
                    case('|'):
                        $nextStep[] = ['row' => $row,'col' => $col,'direction' => 'N'];
                        break;
                    case('/'):
                        $nextStep[] = ['row' => $row,'col' => $col,'direction' => 'E'];
                        break;
                    case('\\'):
                        $nextStep[] = ['row' => $row,'col' => $col,'direction' => 'W'];
                        break;
                    case('-'):
                        $nextStep[] = ['row' => $row,'col' => $col,'direction' => 'W'];
                        $nextStep[] = ['row' => $row,'col' => $col,'direction' => 'E'];
                        break;
                }
            }
            break;
        case('S'):
            $row = $step['row'] + 1;
            $col = $step['col'];
            if(isset($map[$row][$col])){
                switch ($map[$row][$col]['element']){
                    case('.'):
                    case('|'):
                        $nextStep[] = ['row' => $row,'col' => $col ,'direction' => 'S'];
                        break;
                    case('/'):
                        $nextStep[] = ['row' => $row,'col' => $col,'direction' => 'W'];
                        break;
                    case('\\'):
                        $nextStep[] = ['row' => $row,'col' => $col,'direction' => 'E'];
                        break;
                    case('-'):
                        $nextStep[] = ['row' => $row,'col' => $col,'direction' => 'W'];
                        $nextStep[] = ['row' => $row,'col' => $col,'direction' => 'E'];
                        break;
                }
            }
            break;
        case('W'):
            $row = $step['row'];
            $col = $step['col'] - 1;
            if(isset($map[$row][$col])){
                switch ($map[$row][$col]['element']){
                    case('.'):
                    case('-'):
                        $nextStep[] = ['row' => $row,'col' => $col,'direction' => 'W'];
                        break;
                    case('/'):
                        $nextStep[] = ['row' => $row,'col' => $col,'direction' => 'S'];
                        break;
                    case('\\'):
                        $nextStep[] = ['row' => $row,'col' => $col,'direction' => 'N'];
                        break;
                    case('|'):
                        $nextStep[] = ['row' => $row,'col' => $col,'direction' => 'N'];
                        $nextStep[] = ['row' => $row,'col' => $col,'direction' => 'S'];
                        break;
                }
            }
            break;
        case('E'):
            $row = $step['row'];
            $col = $step['col'] + 1;
            if(isset($map[$row][$col])){
                switch ($map[$row][$col]['element']){
                    case('.'):
                    case('-'):
                        $nextStep[] = ['row' => $row,'col' => $col,'direction' => 'E'];
                        break;
                    case('/'):
                        $nextStep[] = ['row' => $row,'col' => $col,'direction' => 'N'];
                        break;
                    case('\\'):
                        $nextStep[] = ['row' => $row,'col' => $col,'direction' => 'S'];
                        break;
                    case('|'):
                        $nextStep[] = ['row' => $row,'col' => $col,'direction' => 'N'];
                        $nextStep[] = ['row' => $row,'col' => $col,'direction' => 'S'];
                        break;
                }
            }
            break;
    }
    return $nextStep;
}

    

