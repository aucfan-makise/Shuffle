<?php
namespace Shuffle;
use Fuel\Core\Config;
class DataReader{
    private $staff_file;
    private $position_file;
    private $persons = null;
    private $positions = null;
    
    public function __construct(){
        Config::load('shuffle', true);
        $files = Config::get('shuffle.data_json_files');
        $this->staff_file = $files['file_dir'] . $files['staff_file'];
        $this->position_file = $files['file_dir'] . $files['position_file'];
    }

    public function read(){
        try{
            $staff_data = file_get_contents($this->staff_file);
            $position_data = file_get_contents($this->position_file);
            if ($staff_data === false || $position_data === false) {
                throw new \Exception('ファイル読み込みエラー');
            }
            $this->persons = json_decode($staff_data, true);
            $this->positions = json_decode($position_data, true);
        } catch (Exception $e){
            return false;
        }
        
        return true;
    }
    
    public function getPositionsArray(){
        return $this->positions;
    }
    
    public function getPersonsArray(){
        return $this->persons;
    }
}