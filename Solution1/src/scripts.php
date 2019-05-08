<?php

if (isset($_POST['searchParam'])) {
    $query = strtolower($_POST['searchParam']);
    $headerMarker = true;
    $responseArray = [];
    $csv = fopen('data/data.csv', 'r');
    if ($csv) {
        while ($line = fgetcsv($csv)) {
            if (!$headerMarker) {
                if (strpos(strtolower($line[0]), $query) === 0 || strpos(strtolower($line[1]), $query) === 0) {
                    array_push($responseArray, $line);
                }
            } else {
                $headerMarker = false;
            }
        }
        echo json_encode($responseArray);
    } else {
        echo json_encode(["error" => "Error opening data source."]);
    }
} else {
    echo json_encode(["error" => "no parameters"]);
}