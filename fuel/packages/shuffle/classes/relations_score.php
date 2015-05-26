<?php
namespace Shuffle;
class RelationsScore{
    private $past_data_score;
    
    public function __construct(){
        $reader = new DataReader();
        $data = $reader->loadPastData();
        
        $pares_array = array();
        foreach ($data as $group){
            $pares_array = array_merge($pares_array, $this->getPareArray(0, $group));
        }
    }
    
    public function getPareArray($pos, $array){
        $pares_array = array();
        foreach (range($pos + 1, count($array) - 1) as $current){
            $pare_array = array();
            $pare_array['pare'] = array($array[$pos], $array[$current]);
//             TODO:取り敢えず過去一回分だけだから
            $pare_array['score'] = 1;
            $pares_array[] = $pare_array;
        }
        
        if (($pos + 1) == count($array) - 1){
            return $pares_array;
        } else{
            return array_merge($pares_array, $this->getPareArray($pos + 1, $array));
        }
    }
}