<?php
namespace Shuffle;
use Fuel\Core\Config;
class DataWriter{
    private $data_dir;
    private $staff_file;
    
    public function __construct(){
        Config::load('shuffle', true);
        $files = Config::get('shuffle.data_json_files');
        $this->data_dir = $files['saved_files_dir'];
        $this->staff_file = $files['file_dir'] . $files['staff_file'];
    }
    
    public function writeStaffFile(array $out_array){
        $reader = new DataReader();
        $reader->read();
        $positions = $reader->getPositionsArray();
        foreach ($out_array as $person_key => $person){
            foreach ($positions as $position_key => $position){
                if ($person['position'] === $position){
                    $out_array[$person_key]['position'] = $position_key;
                    break;
                }   
            }
        }
        file_put_contents ($this->staff_file, json_encode($out_array));
    }
    
    public function writeShuffledData(array $out_array){
        $datetime = new DateTime('NOW');
        $out_file = $this->data_dir.$datetime->format('Y-n-j_HH:MM:II');
        file_put_contents ($out_file, json_encode($out_array));
    }
}
