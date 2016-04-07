<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author obi
 */
class UserChat extends CWidget {
    public $model;
    public $avatarUrl="";
    public $dataUri="";
    private $settings=array();
    public function init() {
        $this->registerSetting();
    }

    public function run() {
        $this->render('index', array('settings'=> $this->settings));
    }
    public function registerSetting(){
        $class_vars = get_class_vars(get_class($this));
        foreach ($class_vars as $name => $value) {
            $this->settings[$name]=$this->$name;
        }
    }
}

?>
