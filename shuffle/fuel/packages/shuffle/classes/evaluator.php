<?php
namespace Shuffle;
class Evaluator{
    private $eval_array = array();
    
    public function add($k){
        $this->eval_array[] = $k;
    }
    
    public function calcDensity(){
        $kind = array();
        foreach ($this->eval_array as $k){
            if (! array_key_exists($k, $kind)){
                $kind[$k] = 1;
            } else{
                $kind[$k] += 1;
            }
        }
        
        $return_array = array();
        $return_array['value'] = max(array_values($kind));
        $return_array['density'] = $return_array['value'] / count($this->eval_array);
	    $return_array['kind'] = array_keys($kind, $return_array['value']);
        return $return_array;
    }
    
    public function clear(){
        $this->eval_array = array();
    }
}