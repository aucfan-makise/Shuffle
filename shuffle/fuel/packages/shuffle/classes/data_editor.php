<?php
namespace Shuffle;
use Fuel\Core\Debug;
use Fuel\Core\Input;
// TODO:状態を変更できるようにする。
class DataEditor{
    private $delete_checkbox_array = null;
    
    public function __construct(){
        $this->initialize();
        //TODO:なんでこんなことしたんだろう
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
     * TODO:統合したらいいのに。馬鹿みたい
     * @throws \Exception
     */
    public function update($company_status_array){
        $reader = new DataReader();
        $reader->read();
        $persons_array = $reader->getPersonsArray();
        foreach ($company_status_array as $pos => $value){
            foreach ($persons_array as &$person){
                if ($person['id'] == $pos) $person['company'] = $value;
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
    public function addMember(){
        $reader = new DataReader();
        $reader->read();
        $persons_array = $reader->getPersonsArray();
        $departments_array = $reader->getDepartmentsArray();

        $max_id = $this->maxId($persons_array);
        $array = array(
            'id' => $max_id + 1,
            'name' => $_POST['name'],
            'company' => Input::get('company'),
            'department' => $_POST['department'],
            'position' => $_POST['position'],
            'sex' => $_POST['sex'],
            'status' => $_POST['status']
        );
        $persons_array[] = $array;
        $writer = new DataWriter();
        $writer->writeStaffFile($persons_array);
    }
    
    /**
     * 新しく組織名を加える。
     * 新しく加えられた組織のidと名前を返す。
     * id, name
     * @return multitype:number Ambigous <string, multitype:>
     */
//     TODO:下位組織があった場合処理が変更になるかも
    public function addOrganization(){
        $new_organization_name = Input::post('new_organization_name');
        $reader = new DataReader();
        $reader->read();
        $organization_array = $reader->getOrganizationArray();
        try{
            $this->validateOrganizationName($new_organization_name, $organization_array);
        } catch (ValidateException $e){
            throw new ValidateException('Adding new organization failed. ' . $e->getThisNodeMessage());
        }
        $max_id = $this->maxId($organization_array);
        $array = array(
            'id' => $max_id + 1,
            'name' => Input::post('new_organization_name'),
            'underlayer' => array()
        );
        $organization_array[] = $array;
        $writer = new DataWriter();
        $writer->writeOrganizationFile($organization_array);
        
        return $array;
    }
    
    private function validateOrganizationName($name, $organization_array){
        $name_index = 'name';
        foreach ($organization_array as $exist_organization){
            if ($name === $exist_organization[$name_index]){
                throw new ValidateException('Same name organization exists.');
            }
        }
        return true;
    }
    
    /**
     * 配列に設定されている'id'の最大値を返す。
     * @param array $array
     * @return Ambigous <number, unknown>
     */
    private function maxId(array $array){
        $max_id = 0;
        foreach ($array as $value){
            if ($max_id < $value['id']) $max_id = $value['id'];
        }
        
        return $max_id;
    }
}