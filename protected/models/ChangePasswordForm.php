<?php

/**
 * ChangePasswordForm class.
 * ChangePasswordForm is the data structure for keeping
 * user Change Password form data. It is used to Change Password.
 */
class ChangePasswordForm extends CFormModel {

    public $oldpassword;
    public $newpassword;
    public $confirmpassword;
    public $filefoto;
    private $record;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            // username and password are required
            array('oldpassword, newpassword, confirmpassword', 'required'),
            // password needs to be authenticated
            array('oldpassword', 'authenticate'),
            array('newpassword', 'checkSameWithConfirmPasswd', 'on' => 'update'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'oldpassword' => 'Password Lama',
            'newpassword' => 'Password Baru',
            'confirmpassword' => 'Konfirmasi Password Baru',
        );
    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate($attribute, $params) {
        if (!$this->hasErrors()) {
            $this->record = User::model()->findByPk(Yii::app()->user->id);
            if ($this->record->password !== crypt($this->oldpassword, $this->record->password))
                $this->addError('oldpassword', 'Password lama salah.');
        }
    }
    public function checkSameWithConfirmPasswd($attribute, $params) {
        if (!$this->hasErrors()) {
            if($this->newpassword != $this->confirmpassword)
                $this->addError('newpassword', 'Password Baru tidak sama dengan Konfirmasi Password Baru');
        }
    }
    public function save(){
        if($this->validate()){
            if(!isset($this->record))
                $this->record = User::model()->findByPk(Yii::app()->user->id);
            $this->record->password = crypt($this->newpassword, Randomness::blowfishSalt());
            return $this->record->save();
            
        }
        return false;
    }

    
    
}
