<?php
use Fuel\Core\Presenter;
use Shuffle\ShuffleFunction;
class Presenter_Shuffle_Result extends Presenter{
    public function view(){
    	$shuffle_function = new ShuffleFunction();
    	$shuffle_function->solve();
    	$this->result = $shuffle_function->getResult();
    }
}