<?php
class Presenter_Shuffle_Staffdata extends Presenter{
    public function view(){
        foreach ($this->json_persons as $key => $json_person){
            $this->json_persons[$key]['position'] = $this->json_positions[$json_person['position']]['name'];
            if ($this->json_persons[$key]['sex'] === 'male'){
                $this->json_persons[$key]['sex'] = '男性';
            } elseif ($this->json_persons[$key]['sex'] === 'female') {
                $this->json_persons[$key]['sex'] = '女性';
            }
            $this->json_persons[$key]['department'] = $this->json_departments[$json_person['department']]['name'];
        }
        $this->persons = $this->json_persons;
        
        $department_form_options = array();
        foreach ($this->json_departments as $key => $value){
            $department_form_options[$key] = $value['name'];
        }
        $this->department_form_options = $department_form_options;
        
        $position_form_options = array();
        foreach ($this->json_positions as $key => $value){
            $position_form_options[$key] = $value['name'];
        }
        $this->position_form_options = $position_form_options;
        
        $this->sex_form_options = array(
            'male' => '男性',
            'female' => '女性'
        );
        $this->status_form_options = array(
            'true' => 'true',
            'false' => 'false'
        );
        
    }
}