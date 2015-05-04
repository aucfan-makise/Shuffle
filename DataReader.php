<?php
class DataReader{
    private $data_file;
    private $persons = null;
    
    public function __construct(){
        $ini_array = parse_ini_file('shuffle.ini', true);
        $data_json_files = $ini_array['data_json_files'];
        $this->data_file = $data_json_files['data_file'];
    }
    
    public function read(){
        try{
            $data = file_get_contents($this->data_file);
            if ($data === false) throw new Exception('ファイル読み込みエラー');
            $json_data = json_decode($data, true);
            $persons;
            foreach ($json_data as $person){
                $id = $person['id'];
                $new_person['name'] = $person['name'];    
                $new_person['department'] = $person['department'];
                $new_person['position'] = $person['position'];
                $new_person['status'] = $person['status'];
                $persons[$id] = $new_person;
            }
            $this->persons = $persons;
        } catch (Exception $e){
            return false;
        }
        
        return true;
    }
    
    public function getPersonsArray(){
        return $this->persons;
    }
}