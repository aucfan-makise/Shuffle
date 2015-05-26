<?php
namespace Shuffle;
class ShuffleFunction{
    private $persons;
    private $result;
    
    private $min_member_count;
    
    function __construct(){
        $reader = new DataReader();
        $reader->read();
        $this->persons = $reader->getPersonsArray();
        
        \Config::load('shuffle', true);
        $properties = \Config::get('shuffle.shuffle_properties');
        $this->min_member_count = $properties['min_member_count'];
    }
    
    function solve(){
        shuffle($this->persons);
        $group_count = floor(count($this->persons) / $this->min_member_count);
        $groups = array();
        foreach (range(1, $group_count) as $c){
            $groups[] = array();
        }
        
        for($i = 0; $i < count($this->persons); ++$i){
            $groups[$i % $group_count][] = $this->persons[$i];
        }
        
        $this->result = $groups;
    }
    
    function getResult(){
        return $this->result;
    }
}