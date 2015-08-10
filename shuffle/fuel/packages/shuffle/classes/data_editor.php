<?php
namespace Shuffle;
// TODO:状態を変更できるようにする。
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
     * statusの検証フィルタ
     * @param boolean $status
     * @return boolean
     */
    function filterValidateStatus($status){
        return ($status === 'presence' || $status === 'absence' || $status === 'leaved') ? true : false;
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
                if (! filter_var($status, FILTER_CALLBACK, array('options' => array('Shuffle\DataEditor', 'filterValidateStatus')))){
                    throw new \Exception();
                }
            }
        } catch (Exception $e){
            throw new Exception('Status validation error.');
        }
    }
    
    /**
     * メンバーのステータスを'leaved'に変えて保存する。
     * @access private
     */
    private function delete(){
        $reader = new DataReader();
        $reader->read();
        $persons_array = $reader->getPersonsArray();
        foreach ($this->delete_checkbox_array as $pos){
            foreach ($persons_array as $key => &$person){
                if ($person['id'] == $pos) $person['status'] = 'leaved';
                continue;
            }
        }
        $writer = new DataWriter();
        $writer->writeStaffFile($persons_array);
    }
    
    /**
     * 新しくメンバーを加える。
     * @access public
     */
    public function add(){
        $reader = new DataReader();
        $reader->read();
        $persons_array = $reader->getPersonsArray();
        $departments_array = $reader->getDepartmentsArray();

        $max_id = 0;
        foreach($persons_array as $key => $value){
            if ($max_id < $value['id']) $max_id = $value['id'];
        }

        $array = array(
            'id' => $max_id + 1,
            'name' => $_POST['name'],
            'department' => $_POST['department'],
            'position' => $_POST['position'],
            'sex' => $_POST['sex'],
            'status' => $_POST['status']
        );
        $persons_array[] = $array;
        $writer = new DataWriter();
        $writer->writeStaffFile($persons_array);
    }
}