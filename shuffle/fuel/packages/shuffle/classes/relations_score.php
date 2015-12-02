<?php
namespace Shuffle;
class RelationsScore{
    private static $own = null;
    private static $past_data_score;
    
    private function getHistoryScore($year_month){
        $this_month = new \DateTime();
        $datetime = new \DateTime($year_month . '01');
        $diff = date_diff($this_month, $datetime);
        $month_diff = ($diff->y * 12) + $diff->m;
        $score = 1 / $month_diff;
        return $score;
    }
    private function __construct(){
        $reader = new DataReader();
        $dataset = $reader->loadPastData();
        $this_month = new \DateTime();
        $pares_array = array();
        foreach ($dataset as $date => $data){
            $score = $this->getHistoryScore($date);
            
            $pares_array_in_one_file = array();
            foreach ($data as $group){
                $pares_array_in_one_file = array_merge($pares_array_in_one_file, $this->getPareArray($group, $score));
            }
            
            $pares_array = $this->mergePareArray($pares_array, $pares_array_in_one_file);
        }
        self::$past_data_score = $this->sortByScore($pares_array);
    }
    private function mergePareArray($to_array, $from_array){
        if (count($to_array) === 0) return $from_array;

        $merge_array = array();
        foreach ($from_array as $from){
            $in_array = false;
            foreach ($to_array as &$to){
                
                if (in_arrayi($from['pare'][0], $to['pare']) && in_arrayi($from['pare'][1], $to['pare'])){
                    $to['score'] += $from['score'];
                    $in_array = true;
                    break;
                }
            }
            unset($to);
            if (!$in_array) $merge_array[] = $from;
        }
        $to_array = array_merge($to_array, $merge_array);
        return $to_array;
    }
    private function getPareArray($array, $score, $pos = 0){
        $pares_array = array();
        foreach (range($pos + 1, count($array) - 1) as $current){
            $pare_array = array();
            $pare_array['pare'] = array($array[$pos], $array[$current]);
            $pare_array['score'] = $score;
	        $pares_array[] = $pare_array;
        }

       if (($pos + 1) == count($array) - 1){
	       return $pares_array;
        } else{
	       return array_merge($pares_array, $this->getPareArray($array, $score, $pos + 1));
        }
    }
    
    public static function getPastDataScore(){
        if (is_null(self::$own)) self::$own = new RelationsScore();
        return self::$past_data_score;
    }
    
    private function scoreComparer($a, $b){
        if ($a['score'] == $b['score']) return 0;
        return $a['score'] > $b['score'] ? -1 : 1;
    }
    
    private function sortByScore($relations){
        usort($relations, array('Shuffle\RelationsScore', 'scoreComparer'));
        return $relations;
    }
}