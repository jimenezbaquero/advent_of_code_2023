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

$actual = $seeds;
foreach ($arrayData as $line){
    $arrayLinea = explode(' ',$line);
    switch ($arrayLinea[0]){
        case('seeds:'):
            $seeds = array_slice($arrayLinea,1);
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
            $actual[] = ['start' => $arrayLinea[1], 'end' => $arrayLinea[1] + $arrayLinea[2] - 1, 'value' => $arrayLinea[0]];
    }
}


foreach ($seeds as $seed){
    $soil = $seed;
    foreach ($mapSeedToSoil as $instruction){
        if($seed >= $instruction['start'] && $seed <= $instruction['end']){
            $soil = $instruction['value'] + $seed -$instruction['start'];
            break;
        }
    }
    $fertilizer = $soil;
    foreach ($mapSoilToFertilizer as $instruction){
        if($soil >= $instruction['start'] && $soil <= $instruction['end']){
            $fertilizer = $instruction['value'] + $soil -$instruction['start'];
            break;
        }
    }
    $water = $fertilizer;
    foreach ($mapFertilizerToWater as $instruction){
        if($fertilizer >= $instruction['start'] && $fertilizer <= $instruction['end']){
            $water = $instruction['value'] + $fertilizer -$instruction['start'];
            break;
        }
    }
    $light = $water;
    foreach ($mapWaterToLight as $instruction){
        if($water >= $instruction['start'] && $water <= $instruction['end']){
            $light = $instruction['value'] + $water -$instruction['start'];
            break;
        }
    }
    $temperature = $light;
    foreach ($mapLightToTemperature as $instruction){
        if($light >= $instruction['start'] && $light <= $instruction['end']){
            $temperature = $instruction['value'] + $light -$instruction['start'];
            break;
        }
    }
    $humidity = $temperature;
    foreach ($mapTemperatureToHumidity as $instruction){
        if($temperature >= $instruction['start'] && $temperature <= $instruction['end']){
            $humidity = $instruction['value'] + $temperature -$instruction['start'];
            break;
        }
    }
    $location = $humidity;
    foreach ($mapHumidityToLocation as $instruction){
        if($humidity >= $instruction['start'] && $humidity <= $instruction['end']){
            $location = $instruction['value'] + $humidity -$instruction['start'];
            break;
        }
    }
    $locations[] = $location;
}


echo 'Solution: '.min($locations);