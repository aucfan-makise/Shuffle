<?php
use Fuel\Core\Fieldset_Field;
use Fuel\Core\Fieldset;
class Presenter_Shuffle_Form extends Presenter {
    public function view(){
        $this->prepareFieldset();
        $test = Fieldset::instance('test');
        $this->set_safe('test', $test->build('/ajax/test'));

//         組織編集フォーム
        $this->organization_array = array();
        foreach ($this->json_organization as $organization){
            $this->organization_array[$organization['id']] = $organization['name'];
        }

        $organization_form_fieldset = Fieldset::forge('organization');
        $organization_form_fieldset->add(
                'organization_select', '社名',
                array('type' => 'select',
                      'size' => 5,
                      'options' => $this->organization_array 
                )
        );
        $organization_form_fieldset->add(
                'new_organization_name', '社名',
                array('type' => 'text'),
                array('required')
        );
        $organization_form_fieldset->add('submit', '', array('type' => 'submit', 'value' => '追加'));
        $this->set_safe('organization_form', $organization_form_fieldset);
        
//         メンバー追加フォーム
        $department_form_options = array();
        foreach ($this->json_departments as $key => $value){
            $department_form_options[$key] = $value['name'];
        }
        $this->department_form_options = $department_form_options;

        $position_form_options = array();
        foreach ($this->json_positions as $key => $value){
            $position_form_options[$key] = $value['name'];
        }
        $this->position_form_options = $position_form_options;

        $this->sex_form_options = array(
            'male' => '男性',
            'female' => '女性'
        );
        $this->status_form_options = array(
            'presence' => '出席',
            'absence' => '欠席',
            'leaved' => '退社',
        );
    }
    
    public function prepareFieldset(){
        $test = Fieldset::forge('test');
        $test->add(
                'test', 'test',
                array('type' => 'text'),
                array('required',
                        'validate_string[numeric]'
                )
        );
        $test->add('submit', '', array('type' => 'submit', 'value' => 'tuika'));
    }
}