<?php

require_once('../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$map = [];
$directions = ['N','S','E','W'];

foreach ($data as $numRow => $row){
    foreach (str_split($row) as $numCol => $element){
        $map[$numRow][$numCol] = ['lost' => $element,  'valueN' => 10000000000, 'valueS' => 10000000000, 'valueE' => 10000000000, 'valueW' => 10000000000];
    }
}

$begin = ['row' => 0, 'col' => 0, 'lost' => 0, 'direction' => ''];
$end = ['row' => count($map)-1, 'col' => count($map[0])-1];
echo (new DateTime())->format('d/m/Y H:i:s').PHP_EOL;
echo 'Solution: '.findPath($map,$begin,$end).PHP_EOL;
echo (new DateTime())->format('d/m/Y H:i:s').PHP_EOL;

function findPath($map, $begin, $end) {
    global $directions;

    $queue = [];
    $queue[] = $begin;
    $visiteds = [];

    while(count($queue) > 0) {
        $actualNode = locateNode($queue);
        $row = $actualNode['row'];
        $col = $actualNode['col'];
        $actualDirection = $actualNode['direction'];
        $actualLost = $actualNode['lost'];

        if ($end == ['row' => $row, 'col' => $col]) {
            return $actualLost;
        }

        if (isset($visiteds[$row][$col][$actualDirection])) {
            continue;
        }

        $visiteds[$row][$col][$actualDirection] = true;


        foreach ($directions as $direction) {
            if ($direction == $actualDirection || isInvertDirection($direction, $actualDirection)) {
                continue;
            }
            $increment = 0;
            $multRow = 0;
            $multCol = 0;
            switch ($direction) {
                case ('N'):
                    $multRow = -1;
                    break;
                case ('S'):
                    $multRow = 1;
                    break;
                case ('E'):
                    $multCol = 1;
                    break;
                case ('W'):
                    $multCol = -1;
                    break;
            }

            for ($i = 1; $i <= 3; $i++) {
                $newRow = $row + $i * $multRow;
                $newCol = $col + $i * $multCol;
                if (($newRow) >= 0 && ($newRow) < count($map) && $newCol >= 0 && $newCol < count($map[0])) {
                    $increment += $map[$newRow][$newCol]['lost'];
                    $newLost = $actualLost + $increment;
                    if ($map[$newRow][$newCol]['value'.$direction] > $newLost) {
                        $map[$newRow][$newCol]['value'.$direction] = $newLost;
                        $queue[] = ['row' => $newRow, 'col' => $newCol, 'direction' => $direction, 'lost' => $newLost];
                    }
                }
            }
        }
    }

    return false;
}

function isInvertDirection($direction, $actualDirection){
    switch ($direction){
        case ('N'):
            if($actualDirection == 'S'){
                return true;
            }
            break;
        case ('S'):
            if($actualDirection == 'N'){
                return true;
            }
            break;
        case ('E'):
            if($actualDirection == 'W'){
                return true;
            }
            break;
        case ('W'):
            if($actualDirection == 'E'){
                return true;
            }
            break;
    }
    return false;
}

function locateNode(&$queue)
{
    $minLost = min(array_column($queue,'lost'));
    foreach ($queue as $key => $node) {
        if ($node['lost'] == $minLost) {
            unset($queue[$key]);
            return $node;
        }
    }
    return null;
}

