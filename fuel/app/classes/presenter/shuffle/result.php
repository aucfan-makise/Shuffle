<?php
use Fuel\Core\Presenter;
use Shuffle\ShuffleFunction;
use Shuffle\DataReader;
class Presenter_Shuffle_Result extends Presenter{
    public function view(){
        Cookie::set('result', json_encode($this->convertResultObjToGroupsArray($this->result)));
        $this->result = $this->convertResultObjToPersonsArray($this->result);
    }
    
    /**
     * Groupのオブジェクトの配列からグループに所属するIDを用いたグループの配列に変換する。
     * @param unknown $result
     * @return multitype:NULL
     */
    private function convertResultObjToGroupsArray($result){
        $groups_array = array();
        foreach ($result as $group){
            $groups_array[] = $group->getMembersId();
        }
        return $groups_array;
    }
    /**
     * GroupのオブジェクトをPersonのデータを本にした配列に変換する。
     * @param unknown $result
     * @return multitype:Ambigous <unknown, boolean>
     */
    private function convertResultObjToPersonsArray($result){
        $result_array = array();
        foreach ($result as $group){
            $array = array();
            foreach ($group->getMembersId() as $id){
                $array[] = $this->findPersonById($id);
            }
            $result_array[] = $array;
        }
        return $result_array;
    }
    
    /**
     * Personデータからidをもとに検索して返す。
     * @param unknown $id
     * @return unknown|boolean
     */
    private function findPersonById($id){
        foreach ($this->persons_array as $person){
            if ($person['id'] === $id) return $person;
        }
        return false;
    }
}