<?php
namespace Shuffle;
class ValidateException extends \Exception{
    private $this_message;

    public function __construct($message = ''){
        $this->this_message = $message;
        $message = 'Validation Error: ' . $message;
        parent::__construct($message);
    }
    
    public function getThisNodeMessage(){
        return $this->this_message;
    }
}