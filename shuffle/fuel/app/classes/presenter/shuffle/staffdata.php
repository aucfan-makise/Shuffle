<?php
use Fuel\Core\Fieldset;
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

            if(! isset($this->json_persons[$key]['company'])){
                $this->json_persons[$key]['company'] = '-';
            }else{
                $this->json_persons[$key]['company'] = $json_person['company'];
            }
        }
        $this->persons = $this->json_persons;
        $company_array;
        foreach ($this->json_organization as $key => $json_company){
            $company_array[] = $json_company['name'];
        }
        $this->company = $company_array;
    }
}