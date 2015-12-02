<?php 
use Fuel\Core\Controller_Rest;
use Shuffle\ValidateException;
use Shuffle\DataWriter;
use Shuffle\DataEditor;
class Controller_Ajax extends Controller_Rest{
    
    public function action_index(){
        return $this->response(array(
                'result' => false,
                'message' => 'index',
        ));
    }
    public function post_test(){
        $test = Fieldset::forge('test');
        $test->add(
                'test', 'test',
                array('type' => 'text'),
                array('required',
                    array('valid_string', array('numeric'))       
                )
        );
        $test->add('submit', '', array('type' => 'submit', 'value' => 'tuika'));
        $test_instance = Fieldset::instance('test');
        $val = $test_instance->validation();
        if ($val->run()){
            var_dump($val->validated());
        } else {
            echo 'error';
        }
        
    }
    
    /**
     * シャッフルした結果の保存。
     * @return object
     */
    public function post_result_save(){
        $writer = new DataWriter();
        
        $status;
        $message = '';
        try{
            $writer->validateResultData(Input::post('result'));
            $writer->writeResultData(Input::post('result'));
            Cookie::delete('result');
            $status = true;
        }catch (ValidateException $e){
            $status = false;
            $message = $e->getMessage();
        }
        return $this->response(array(
                'status' => $status,
                'message' => $message,
        ));
    }
    
    public function post_add_organization(){
        $response_array = array(
                'status' => null,
                'message' => '',
                'id' => null,
                'name' => null
        );
        $editor = new DataEditor();

        try{
            $result = $editor->addOrganization(Input::post('new_organization_name'));
            $response_array['status'] = true;
            $response_array['id'] = $result['id'];
            $response_array['name'] = $result['name'];
        } catch (ValidateException $e){
            $response_array['status'] = false;
            $response_array['message'] = $e->getMessage();
        }
        return $this->response($response_array);
    }
}

