<?php
namespace Shuffle;
class DataEditor{
    private $delete_checkbox_array = null;
    
    public function __construct(){
        $this->initialize();    
        if (! is_null($this->delete_checkbox_array)) $this->delete();
    }
    
    private function initialize(){
        try {
            if (isset($_POST['status_array'])) $this->validateStatus();                
            if (isset($_POST['delete_checkbox_array'])){
                foreach ($_POST['delete_checkbox_array'] as $v){
                    if (! (int)filter_var($v, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)))){
                        throw new Exception();
                    }
                }
                $this->delete_checkbox_array = $_POST['delete_checkbox_array'];
            }
        } catch (Exception $e){
            throw new Exception('入力値が不正。');
        }
    }
    
    /**
     * POSTで渡されたステータスの検査を行う
     * @access private
     * @throws Exception
     */
    private function validateStatus(){
        $reader = new DataReader();
        $reader->read();
        $persons_array = $reader->getPersonsArray();
        try{
            if (count($persons_array) !== count($_POST['status_array'])) throw new \Exception();
            foreach ($persons_array as $value){
                if (! array_key_exists($value['id'], $_POST['status_array'])){
                    throw new Exception();
                }
            }
            foreach ($_POST['status_array'] as $id => $status){
                if (is_null(filter_var($status, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))){
                    throw new Exception();
                }
            }
        } catch (Exception $e){
            throw new Exception('Status validation error.');
        }
    }
    
    /**
     * データファイルからidで消す操作を行う
     * @access private
     */
    private function delete(){
        $reader = new DataReader();
        $reader->read();
        $persons_array = $reader->getPersonsArray();
        foreach ($this->delete_checkbox_array as $pos){
            foreach ($persons_array as $key => $person){
                if ($person['id'] == $pos) unset($persons_array[$key]);
                continue;
            }
        }
        $writer = new DataWriter();
        $writer->writeStaffFile($persons_array);
    }
}