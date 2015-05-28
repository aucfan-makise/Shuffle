<?php
namespace Shuffle;
class ShuffleFunction{
    private $relations_score;
    private $persons;
    private $result;
    
    private $min_member_count;
    
    private $group_count;
    private $member_count_array;
    
    public function __construct(){
        set_time_limit(0);
        ini_set('memory_limit', '512M');
        $this->relations_score = new RelationsScore();
        $reader = new DataReader();
        $reader->read();
        $this->persons = $reader->getPersonsArray();
foreach (range(0, 67) as $dummy) array_shift($this->persons);
        \Config::load('shuffle', true);
        $properties = \Config::get('shuffle.shuffle_properties');
        $this->min_member_count = $properties['min_member_count'];
        $this->group_count = floor(count($this->persons) / $this->min_member_count);
        
        $this->member_count_array = array();
        if (count($this->persons) % $this->min_member_count != 0){
            foreach (range(1, count($this->persons) % $this->min_member_count) as $dummy){
                $this->member_count_array[] = $this->min_member_count + 1;
            }
        }
        if (count($this->member_count_array) != $this->group_count){
            foreach (range(count($this->member_count_array), $this->group_count)as $dummy){
                echo $dummy;
                $this->member_count_array[] = $this->min_member_count;
            }
        }
    }
    
    public function solve(){
        $id_array = array();
        foreach ($this->persons as $person) $id_array[] = $person['id'];
        $combinations = $this->getCombinationArray($this->member_count_array, $id_array);
        $combination_score_array = $this->calcScore($combinations);
        $result = $this->getBestScoreCombination($combination_score_array, $combinations);
        $groups_array = $this->generateGroupArray($result);
        $this->result = $this->exchengeIdToObject($groups_array);
    }
    
    private function getBestScoreCombination($combination_score_array, $combinations){
        asort($combination_score_array);
        $best_id = array_shift($combination_score_array);
        return $combinations[$best_id];
        
    }
    
    private function exchengeIdToObject($groups_array){
        foreach ($groups_array as $group_key => $group){
            foreach ($group as $member_key => $member){
                foreach ($this->persons as $person){
                    if ($person['id'] == $member){
                        $groups_array[$group_key][$member_key] = $person;
                        break;
                    }
                }
            }
        }
        return $groups_array;
    }
    private function generateGroupArray($combination){
        $groups_array = array();
        $pos = 0;
        foreach ($this->member_count_array as $member_count){
            $group = array();
            $group = array_slice($combination, $pos, $member_count);
            $pos += $member_count;
            $groups_array[] = $group;
        }
        return $groups_array;
    }
    private function calcScore($combinations){
        $person_score_array = $this->relations_score->getPastDataScore();
        $combination_score_array = array();
        foreach ($combinations as $combination){
            $group_score_array = array();
            foreach ($this->generateGroupArray($combination) as $group){
                $group_sum_score = 0;
                $pares = $this->createPare($group);        
                foreach ($pares as $pare){
                    foreach ($person_score_array as $past_data){
                        if (in_arrayi($pare[0], $past_data['pare']) && in_arrayi($pare[1], $past_data['pare'])){
                            $group_sum_score += $past_data['score'];    
                        }
                    }
                }
                $group_score_array[] = $group_sum_score / count($pares);
            }
            $combination_score_array[] = array_sum($group_score_array) / $this->group_count;
        }
        return $combination_score_array;
    }
    
    private function createPare($array, $pos = 0){
        $pares_array = array();
        foreach (range($pos + 1, count($array) - 1) as $current){
            $pare_array = array();
            $pare_array[] = array($array[$pos], $array[$current]);
            $pares_array = $pare_array;
        }
        if (($pos + 1) == count($array) - 1){
            return $pares_array;
        } else{
            return array_merge($pares_array, $this->createPare($array, $pos + 1));
        }
    }

    private function getCombinationArray($member_count_array, $persons, $count_array_pos = 0){
	    if(count($persons) == $member_count_array[$count_array_pos]) return array($persons);
	    
	    $first = array_shift($persons);
	    $combinations = $this->combination($member_count_array[$count_array_pos] - 1, $persons);
	    $return = array();
	    foreach ($combinations as $combination){
		    $array = array();
		    $sub_persons = $persons;
		    foreach ($sub_persons as $person_key => $person){
			    foreach ($combination as $c){
				    if ($c == $person) unset($sub_persons[$person_key]);
			    }
		    }
		    $sub_persons = array_values($sub_persons);
		    $result = $this->getCombinationArray($member_count_array, $sub_persons, $count_array_pos + 1);
		    array_unshift($combination, $first);
		    foreach ($result as $r){
			    $return[] = array_merge($combination, $r);
		    }
	    }
	    return $return;
    }

    private function combination($member_count, $persons, $member_pos = 0, $current = 0){
	    if ($member_count === $member_pos + 1){
		    $array = array();
		    foreach (range($current, count($persons) - 1) as $c){
			    $array[] = array($persons[$c]);
		    }
		    return $array;
	    }
	    
	    $array = array();
	    foreach (range($current + 1, count($persons) - $member_count + $member_pos + 1) as $c){
		    $inner = array($persons[$c - 1]);
			$res = $this->combination($member_count, $persons, $member_pos + 1, $c);
		    foreach ($res as $key => $r){
			    $merge = array();
			    $merge[] = array_merge($inner, $r);
			    $array = array_merge($array, $merge);
		    }
	    }
	    return $array;
    }
    public function getResult(){
        return $this->result;
    }
}