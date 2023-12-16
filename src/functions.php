<?php
function loadFile($file){
    $rows = [];
    $input = fopen($file,'rb');
    while ($line = fgets($input)) {
        $line = trim($line);
        $rows[] = $line;
    }
    return $rows;
}

function transpose($array) {
    $response = [];
    foreach ($array as $numRow => $row){
        foreach ($row as $numCol => $element){
            $response[$numCol][$numRow] = $element;
        }
    }
    return $response;
}

function paint($array ,$key = null){
    foreach ($array as $row){
        $text = '';
        foreach ($row as $element){
            if(!is_null($key)){
                $text .= $element[$key];
            }else{
                $text .= $element;
            }
        }
        echo $text;
    }
    echo '---------------';
}