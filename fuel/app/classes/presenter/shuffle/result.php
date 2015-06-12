<?php
use Fuel\Core\Presenter;
use Shuffle\ShuffleFunction;
use Shuffle\DataReader;
class Presenter_Shuffle_Result extends Presenter{
    public function view(){
    	$reader = new DataReader();
    	$reader->read();
    	$this->position_array = $reader->getPositionsArray();
    	$this->department_array = $reader->getDepartmentsArray();
    	$persons_array = $reader->getPersonsArray();
    	
    	$shuffle_function = new ShuffleFunction();
    	$shuffle_function->solve();
    	$this->result = $shuffle_function->getResult();
    	
    }
}