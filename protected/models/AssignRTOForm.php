<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AssignRTOFrom
 *
 * @author ardha
 * @created on Mar 3, 2013
 */
class AssignRTOForm extends CFormModel {

    //put your code here
    public $type;
    public $role, $task, $operation;
    public $parent, $userid;
    public $rto;

    /**
     * @return array validation rules to be applied when {@link validate()} is called.
     */
    public function rules() {
        return array_merge(parent::rules(), array(
                    array('type, parent, role, task, operation, rto', 'safe'),
                ));
    }

    public function getTypeOptions() {
        return array(
            CAuthItem::TYPE_ROLE => 'Group',
            CAuthItem::TYPE_TASK => 'Aplikasi',
            CAuthItem::TYPE_OPERATION => 'Operasi',
        );
    }

    public function attributeLabels() {
        return array(
            'type' => 'Tipe',
            'role' => 'Group',
            'task' => 'Aplikasi',
            'operation' => 'Operasi',
        );
    }

    public function save() {
        $status = 0;
        if(!empty($this->rto) && isset($this->parent)){
            foreach($this->rto as $child){
                $model = new AuthItemChild();
                $model->parent = $this->parent;
                $model->child = $child;
                if($model->save())
                    $status++;
            }
        }
        return $status;
    }
    public function saveToAuthAssignment(){
        $status = 0;
        if(!empty($this->rto)){
            foreach($this->rto as $assigned){
                $model = new AuthAssignment();
                $model->itemname = $assigned;
                $model->userid = $this->userid;
                if($model->save())
                    $status++;
            }
        }
        return $status;
    }

}

?>
