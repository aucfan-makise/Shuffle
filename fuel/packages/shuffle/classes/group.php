<?php
namespace Shuffle;
class Group{
    private $members = array();
    private $score = 0;
    
    /**
     * メンバーの追加をする。
     * @param unknown $id
     */
    public function add($id){
        if (count($this->members) != 0) $this->score = $this->calcScore($id);
        $this->members[] = $id;
    }
    
    /**
     * スコアを再計算して返す。
     * @param unknown $new_member
     * @return number
     */
    private function calcScore($new_member){
        $sum = 0;
        $related_pare = array();
        foreach (RelationsScore::getPastDataScore() as $pare){
            if ($pare['pare'][0] == $new_member || $pare['pare'][1] == $new_member){
                $related_pare[] = $pare;
            }
        }
        foreach ($this->members as $member){
            foreach ($related_pare as $pare){
                if ($pare['pare'][0] == $member || $pare['pare'][1] == $member){
                    $sum += $pare['score'];
                }
            }
        }
        
        return ($this->score * count($this->members) + $sum) / (count($this->members) + 1);
    }
    
    /**
     * 引数がなければ現在のスコアを返す。
     * あればそのidを入れた上でのスコアを返す。
     * @param string $test_member
     * @return unknown|number
     */
    public function getScore($test_member = null){
        if (is_null($test_member)) return $this->score;

        return $this->calcScore($test_member);
    }
    
    /**
     * メンバーのIDの配列を返す
     * @return Ambigous <multitype:, unknown>
     */
    public function getMembersId(){
        return $this->members;
    }
}