<?php 
use Fuel\Core\Controller_Rest;
use Shuffle\ValidateException;
use Shuffle\DataWriter;
class Controller_Ajax extends Controller_Rest{
    
    public function action_index(){
        return $this->response(array(
                'result' => false,
                'message' => 'index',
        ));
    }
    
    public function post_result_save(){
        $writer = new DataWriter();
        
        $status = false;
        $message = '';
        try{
            $writer->validateResultData(Input::post('result'));
            $writer->writeResultData(Input::post('result'));
            Cookie::delete('result');
            $status = true;
        }catch (ValidateException $e){
            $message = $e->getMessage();
        }
        return $this->response(array(
                'status' => $status,
                'message' => $message,
        ));
    }
}

