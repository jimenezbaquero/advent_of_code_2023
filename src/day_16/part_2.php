<?php

require_once('../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');
$map = [];
$pendingBeans = [];
$directions = ['N','S','W','E'];
$maxTiles = 0;

foreach ($data as $numRow => $row){
    foreach (str_split($row) as $numCol => $element){
        $map[$numRow][$numCol] = ['element' => $element, 'beans' => []];
    }
}

$notRevidedSteps = [];
foreach ($map as $numRow => $row) {
    foreach ($row as $numCol => $tile) {
        if ($numRow > 0 && $numRow < count($map) - 1 && $numCol > 0 && $numCol < count($map) - 1) {
            continue;
        }
        foreach ($directions as $direction) {
            $notRevidedSteps[$numRow.'-'.$numCol.'-'.$direction] = ['row' => $numRow, 'col' =>$numCol, 'direction' => $direction];
        }
    }
}

while(count($notRevidedSteps) > 0){
    $step = array_shift($notRevidedSteps);
    $mapCopy = copyMap($map);
    $pendingBeans[] = ['row' => $step['row'] , 'col' => $step['col'], 'direction' => $step['direction']];

    while (count($pendingBeans) > 0) {
        $actualStep = array_shift($pendingBeans);
        $row = $actualStep['row'];
        $col = $actualStep['col'];
        $direction = $actualStep['direction'];
        $out = false;
        while (!existStep($actualStep, $mapCopy) && !$out) {
            unset($notRevidedSteps[$row.'-'.$col.'-'.$direction]);
            $mapCopy[$row][$col]['beans'][] = $direction;
            $nextStep = calculateNextStep($actualStep, $mapCopy);
            $stepsNumber = count($nextStep);
            if ($stepsNumber > 1) {
                $pendingBeans[] = array_shift($nextStep);
            }
            if ($stepsNumber == 0) {
                $out = true;
            } else {
                $actualStep = $nextStep[0];
                $row = $actualStep['row'];
                $col = $actualStep['col'];
                $direction = $actualStep['direction'];
            }
        }
    }
    $tilesEnergized = tilesEnergized($mapCopy);
    if ($tilesEnergized > $maxTiles){
        $maxTiles = $tilesEnergized;
    }
}

echo 'Solution: '.$maxTiles;


function copyMap($map){
    $response = [];
    foreach ($map as $numRow => $row){
        foreach ($row as $numCol => $tile){
            $response[$numRow][$numCol] = $tile;
        }
    }
    return $response;
}
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



