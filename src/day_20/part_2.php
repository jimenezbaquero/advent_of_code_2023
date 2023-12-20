<?php

require_once('../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');
$instuctions = [];
$modules = [];

foreach ($data as $line) {
    $arrayAux = explode(' -> ',$line);
    $arrayDestinations = explode(', ',$arrayAux[1]);
    $type = substr($arrayAux[0],0,1);
    switch ($type){
        case('%'):
            $type = 'flip-flop';
            $name = str_replace('%','',$arrayAux[0]);
            break;
        case('&'):
            $type = 'conjuntion';
            $name = str_replace('&','',$arrayAux[0]);
            break;
        default:
            $type = 'broadcaster';
            $name = $arrayAux[0];
    }
    $destinations = [];
    foreach ($arrayDestinations as $destination){
        $destinations[$destination] = 0;
    }
    $modules[$name] = ['name' => $name, 'type' => $type, 'state' => 0, 'destinations' => $destinations, 'last_pulse' => 0, 'origins' => []];
}

foreach ($modules as $origin=>$module){
    foreach ($module['destinations'] as $key=>$destination){
        if(!isset($modules[$key])){
            $modules[$key] = ['name' => $name, 'type' => 'end', 'state' => 0, 'destinations' => $destinations, 'last_pulse' => 0, 'origins' => []];
        }
        if ($modules[$key]['type'] == 'conjuntion'){
            $modules[$key]['origins'][$origin] = 0;
        }
    }
}

$pendingDestinations = [['name' => 'broadcaster', 'pulse' => 0, 'last' => null]];

$iterations = 1;
$end = false;
$cicles = [];
$middles = 0;

$previous = array_filter($modules,function ($e) {return $e['destinations'] == ['rx' => 0];});
$previousModule = array_shift($previous);

while(count($pendingDestinations) > 0 && !$end){
    while(count($pendingDestinations) > 0 && !$end){
        $pending = array_shift($pendingDestinations);
        $module = $modules[$pending['name']];
        $module['last_pulse'] = $pending['pulse'];
        if($previousModule['type'] = 'conjuntion'){
            $numberOrigins = count($previousModule['origins']);
            if($pending['name'] == $previousModule['name'] && $pending['pulse'] == 1){
                $cicles[$pending['last']] = $iterations;
                if(count($cicles) == $numberOrigins){
                    $end = true;
                    break;
                }
            }
        }else if($modules[$previousModule['name']]['state'] == 1 && $pending['pulse'] == 0){
            $cicles[]=$iterations;
            $end = true;
            break;
        }
        switch($module['type']){
            case('broadcaster'):
                foreach ($module['destinations'] as $key => $destination){
                    $pendingDestinations[] = ['name' => $key, 'pulse' => $module['last_pulse'],'last' =>$module['name']] ;
                }
                break;
            case('flip-flop'):
                if($module['last_pulse'] == 0){
                    if($module['state']){
                        $pulse = 0;
                    }else{
                        $pulse = 1;
                    }
                    foreach ($module['destinations'] as $key => $destination){
                        $pendingDestinations[] = ['name' => $key, 'pulse' => $pulse, 'last' =>$module['name']] ;
                    }
                    $module['state'] = ($module['state']+1)%2;
                }
                break;
            case('conjuntion'):
                $module['origins'][$pending['last']] = $pending['pulse'];
                $pulse = 0;
                foreach ($module['origins'] as $origin){
                    if($origin == 0){
                        $pulse = 1;
                        break;
                    }
                }
                foreach ($module['destinations'] as $key => $destination){
                    $pendingDestinations[] = ['name' => $key, 'pulse' => $pulse, 'last' =>$module['name']] ;
                }
                break;
            default:
        }
        $modules[$pending['name']] = $module;
    }

    if(!allModulesOff($modules)) {
        $pendingDestinations[] = ['name' => 'broadcaster', 'pulse' => 0, 'last' => null];
    }
    $iterations++;
}

echo 'Solution: '.lcm($cicles);

function allModulesOff($modules){
    $on = array_filter(array_column($modules,'state'), function ($e) { return $e != 0;});
    if (count($on) > 0)
        return false;
    return true;
}

function lcm($numbers)
{
    $a = array_shift($numbers);
    if(count($numbers) == 0){
        return $a;
    }
    $b = array_shift($numbers);
    $c = gmp_lcm($a,$b);
    if(count($numbers) == 0){
        return $c;
    }else{
        array_unshift($numbers,$c);
        return lcm($numbers);
    }
}