<?php
namespace Shuffle;
class ShuffleFunction{
    private $relations_score;
    private $persons;
    private $result;
    private $group_count;
    private $persons_id_array;
    
    public function __construct(){
        $reader = new DataReader();
        $reader->read();
        
//         退社済みの人を除く。
        $this->persons = array();
        foreach ($reader->getPersonsArray() as $person){
            if ($person['status'] !== 'leaved') $this->persons[] = $person;
        }
        RelationsScore::setPersons($this->persons);
        $this->relations_score = RelationsScore::getPastDataScore();

        \Config::load('shuffle', true);
        $properties = \Config::get('shuffle.shuffle_properties');
        $this->group_count = $properties['group_count'];
        
//         IDのみの配列を作る
        $this->persons_id_array = array();
        foreach ($this->persons as $person) $this->persons_id_array[] = $person['id'];
    }
    
    /**
     * スコアが高い人たち(一緒のグループにしちゃいけない人たち)のグループをつくる。
     * @return multitype:multitype:unknown
     */
    private function getNeighborhoodGroups(){
        $groups = array();
        $members = array();
        $added_members = array();
        $member_par_group = $this->group_count;
        $remaining_members = $this->persons_id_array;

//         過去のデータから作る
        foreach ($this->relations_score as $pare){
            if (! (in_arrayi($pare['pare'][0], $remaining_members) && in_arrayi($pare['pare'][1], $remaining_members))) continue;
            foreach ($pare['pare'] as $one_of_pare){
                if (in_arrayi($one_of_pare, $added_members)) continue;

                $members[] = $one_of_pare;
                unset($remaining_members[array_search($one_of_pare, $remaining_members)]);

                if (count($members) == $member_par_group){
                    $groups[] = $members;
                    $members = array();
                }
            }
        }
        
//         残りの人も入れていく
        $remaining_members = array_values($remaining_members);
        foreach ($remaining_members as $member){
            $members[] = $member;
            if (count($members) == $member_par_group){
                $groups[] = $members;
                $members = array();
            }
            
        }
        
        if (! empty($members)) $groups[] = $members;
        return $groups;
    }
    
    /**
     * usortで使うやつ。
     * @param unknown $a
     * @param unknown $b
     * @return number
     */
    private function groupsComparer($a, $b){
        if ($a->getScore() == $b->getScore()) return 0;
        return $a->getScore() > $b->getScore() ? -1 : 1;
    }
    /**
     * Groupのオブジェクトの入った配列をGroupのスコアが高い順に並べる。
     * @param unknown $groups
     * @return unknown
     */
    private function sortGroupsByScore($groups){
        usort($groups, array('Shuffle\ShuffleFunction', 'groupsComparer'));
        return $groups;
    }
    
    public function solve(){
        $neighborhood_groups = $this->getNeighborhoodGroups();
        $remaining_members = $this->persons_id_array;
        $groups = array();
        foreach ($neighborhood_groups as $neighborhood_group){
//             最初のグループは全部別々のグループに入れる
            if (empty($groups)){
                foreach ($neighborhood_group as $neighborhood){
                    $group = new Group();
                    $group->add($neighborhood);
                    $groups[] = $group;
                }
                continue;
            }
            
//             現時点のスコアの高いグループから処理する
            foreach ($groups as $group){
                if (count($neighborhood_group) === 0) break;
                $best_score = '';
                $best_member = '';
                
//                 グループに加入した場合一番スコアが低くなる人を選ぶ
                foreach ($neighborhood_group as $key => $neighborhood){
                    if ($best_score === '' || $best_score > $group->getScore($neighborhood)){
                        $best_score = $group->getScore($neighborhood);
                        $best_member = $key;
                    }
                }
                $group->add($neighborhood_group[$best_member]);
                unset($neighborhood_group[$best_member]);
                $neighborhood_group = array_values($neighborhood_group);
            }
//             スコアの高い順にソート
            $groups = $this->sortGroupsByScore($groups);
        }

        $this->result = $groups;
    }
    
    /**
     * 結果を返す。
     * @return multitype:\Shuffle\multitype:Ambigous
     */
    public function getResult(){
        return $this->result;
    }
}