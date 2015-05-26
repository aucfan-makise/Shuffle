<?php
namespace Shuffle;
class ShuffleFunction{
    private $persons;
    private $result;
    
    private $min_member_count;
    
    private $group_count;
    
    function __construct(){
        $reader = new DataReader();
        $reader->read();
        $this->persons = $reader->getPersonsArray();
        
        \Config::load('shuffle', true);
        $properties = \Config::get('shuffle.shuffle_properties');
        $this->min_member_count = $properties['min_member_count'];
        $this->group_count = floor(count($this->persons) / $this->min_member_count);
    }
    
//     function solve(){
//         shuffle($this->persons);
//         $group_count = floor(count($this->persons) / $this->min_member_count);
//         $groups = array();
//         foreach (range(1, $group_count) as $c){
//             $groups[] = array();
//         }
        
//         for($i = 0; $i < count($this->persons); ++$i){
//             $groups[$i % $group_count][] = $this->persons[$i];
//         }
        
//         $this->result = $groups;
//     }
    
    function solve(){
        $relations_score = new RelationsScore();
        shuffle($this->persons);
        $groups = array();
        foreach (range(0, $this->group_count - 1) as $c){
            $group = array($this->persons[$c]);
            unset($this->persons[$c]);
        }
        var_dump($this->persons);exit;
    }
    
    function getResult(){
        return $this->result;
    }
}