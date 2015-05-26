<?php
class Presenter_Shuffle_Staffdata extends Presenter{
    public function view(){
        foreach ($this->json_persons as $key => $json_person){
            $this->json_persons[$key]['position'] = $this->json_positions[$json_person['position']]['name'];
        }
        $this->persons = $this->json_persons;
    }
}