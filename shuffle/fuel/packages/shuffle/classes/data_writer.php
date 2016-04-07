<?php
namespace Shuffle;
use Fuel\Core\Config;
class DataWriter{
    private $saved_result_data_dir;
    private $staff_file;
    private $organization_file;
    
    public function __construct(){
        Config::load('shuffle', true);
        $files = Config::get('shuffle.data_json_files');
        $this->saved_result_data_dir = $files['saved_result_data_dir'];
        $this->staff_file = $files['file_dir'] . $files['staff_file'];
        $this->organization_file = $files['file_dir'] . $files['organization_file'];
    }
    
    /**
     * 社員のデータファイルに書き込む。
     * @param array $out_array
     */
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
    
    /**
     * 組織をファイルに書き込む
     * @param array $out_array
     */
    public function writeOrganizationFile(array $out_array){
        file_put_contents($this->organization_file, json_encode($out_array));
    }
    
    /**
     * 結果を日付名のファイルに書き込む。
     * @param unknown $result
     */
    public function writeResultData($result){
        $datetime = new \DateTime('NOW');
        $out_file = $this->saved_result_data_dir . '/' . \Date::time()->format('mysql');
        file_put_contents($out_file, $result);
    }
    
    /**
     * json形式のシャッフルした結果の配列をバリデーションする。
     * @param unknown $json_result
     * @throws ValidateException
     * @return boolean
     */
    public function validateResultData($json_result){
        $result = json_decode($json_result, true);
        try {
            if (is_null($result)) throw new ValidateException('Result is null.');
            array_walk_recursive($result, array('Shuffle\DataWriter', 'validateNumericArray'));
        }catch (ValidateException $e){
            throw new ValidateException('Result data in cookie. ' . $e->getThisNodeMessage());
        }
        return true;
    }
    
    /**
     * array_walk_recursiveに使う。
     * 配列内のvalueが数字かどうか確認する。
     * エラーじゃなかったらtrueを返す。
     * @param unknown $value
     * @param unknown $key
     * @throws ValidateException
     * @return boolean
     */
    private function validateNumericArray($value, $key){
        $validate_result = filter_var($value, FILTER_VALIDATE_INT);
        if (! $validate_result) throw new ValidateException('Non-numeric is contained in an array.');
        return true;
    }
}
