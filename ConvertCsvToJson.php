<?php
$file_name = 'Shuffle_data.csv';
$out_file_name = 'Shuffle_data.json';
$data = file_get_contents($file_name);
$data = explode(PHP_EOL, $data);
$persons;
foreach ($data as $line){
    $person_data;
    $separate_data = explode(',', $line);
    $person_data['id'] = $separate_data[0];
    $person_data['name'] = $separate_data[1];
    $person_data['department'] = $separate_data[3];
    $person_data['position'] = $separate_data[5];
    $person_data['status'] = 'true';
    $persons[] = $person_data;
}

file_put_contents ($out_file_name, json_encode($persons));
?>