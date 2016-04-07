<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DynamicModel
 *
 * @author ardha
 * @created on Jun 19, 2013
 */
class DynamicModel extends ActiveRecord {

    private $tableName = "m_person";
    //put your code here
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return $this->tableName;
    }
    
    public function setTable($tableName){
        $this->tableName = $tableName;
    }

}

?>
