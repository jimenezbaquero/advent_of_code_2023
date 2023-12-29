<?php

require_once('../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$arrayData = array_filter($data,function($e){return $e!='';});

$seeds = [];
$locations = [];
$mapSeedToSoil = [];
$mapSoilToFertilizer = [];
$mapFertilizerToWater = [];
$mapWaterToLight = [];
$mapLightToTemperature = [];
$mapTemperatureToHumidity = [];
$mapHumidityToLocation = [];
$ranges = [];

$actual = $seeds;
foreach ($arrayData as $line){
    $arrayLinea = explode(' ',$line);
    switch ($arrayLinea[0]){
        case('seeds:'):
            $seeds = array_slice($arrayLinea,1);
            $start = null;
            foreach ($seeds as $seed){
                if(is_null($start)){
                    $start = $seed;
                }else{
                    $ranges[] = ['start' => $start,'end' => $start + $seed - 1];
                    $start = null;
                }
            }
            break;
        case('seed-to-soil'):
            $actual = &$mapSeedToSoil;
            break;
        case('soil-to-fertilizer'):
            $actual = &$mapSoilToFertilizer;
            break;
        case('fertilizer-to-water'):
            $actual = &$mapFertilizerToWater;
            break;
        case('water-to-light'):
            $actual = &$mapWaterToLight;
            break;
        case('light-to-temperature'):
            $actual = &$mapLightToTemperature;
            break;
        case('temperature-to-humidity'):
            $actual = &$mapTemperatureToHumidity;
            break;
        case('humidity-to-location'):
            $actual = &$mapHumidityToLocation;
            break;
        default:
            $actual[] = ['destiny_start' => $arrayLinea[0], 'origin_start' => $arrayLinea[1], 'length' => $arrayLinea[2]];
    }
}

$maps = [$mapSeedToSoil,$mapSoilToFertilizer,$mapFertilizerToWater,$mapWaterToLight,$mapLightToTemperature,$mapTemperatureToHumidity,$mapHumidityToLocation];

foreach ($maps as $map) {
    $newRanges = [];
    foreach ($ranges as $range) {
        $convertedRange = convertRange($range, $map);
        $newRanges = array_merge($newRanges,$convertedRange);
    }
    $ranges = $newRanges;
}

$solution = PHP_INT_MAX;
foreach ($ranges as $range){
    $solution = min($solution, $range['start'], $range['end']);
}

echo 'Solution: '.$solution;

function convertRange($range,$maps){
    $response = [];
    $min = $range['start'];
    $max = $range['end'];
    foreach ($maps as $instruction){
        $originStart = $instruction['origin_start'];
        $destinyStart = $instruction['destiny_start'];
        $length = $instruction['length'];
        $originEnd = $originStart + $length;

        if( $min < $originEnd && $max >= $originStart){
            $mapMin = max($min, $originStart);
            $mapMax = min($max, $originEnd -  1);

            $response[] = ['start' => $destinyStart + $mapMin - $originStart, 'end' => $destinyStart + $mapMax - $originStart];
        }
    }
    if($response == []){
        return [$range];
    }
    return $response;
}