<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $username
 * @property string $displayname
 * @property string $password
 * @property string $email
 * @property string $avatar_file
 * @property integer $state_online
 * @property integer $timestamp
 */
class User extends ActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return User the static model class
     */
    public $confirmpassword;
    public $uploadedFoto;
    public $type;

    private $defaultPassword = '1234';
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'user';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('username, password,email', 'required'),
            array('state_online', 'numerical', 'integerOnly' => true),
            array('username, password, email', 'length', 'max' => 128),
            array('email', 'email'),
            array('displayname', 'length', 'max' => 225),
            array('confirmpassword, type, uploadedFoto, person, personId,kode_kepemilikan,pemilik_id,deleted', 'safe'),
            //array('password', 'checkSameWithConfirmPasswd', 'on' => 'insert,changepassword'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, username, type, displayname, password, email, avatar_file, state_online, timestamp', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
			
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'username' => 'Nama Pengguna',
            'displayname' => 'Nama Lengkap Pengguna',
            'password' => 'Password',
            'confirmpassword' => 'Ketik Ulang Password',
            'email' => 'Email',
            'avatar_file' => 'Avatar File',
            'state_online' => 'State Online',
            'type' => 'Kelompok Pemakai',
            'typeDef' => 'Tipe',
            'person' => 'Pegawai/Siswa',
            'personId' => 'Pegawai/Siswa'
        );
    }

    /**
     * This method is invoked before saving a record (after validation, if any).
     * @return boolean whether the saving should be executed. Defaults to true.
     */
    protected function beforeSave() {
        if ($this->scenario == 'insert' || $this->scenario == 'resetpassword') {
            //if (!empty($this->password))
                $this->password = crypt($this->password, Randomness::blowfishSalt());
        }
        return parent::beforeSave();
    }
    
    public function resetPassword(){
        $this->scenario = 'resetpassword';
        $this->password = $this->defaultPassword;
        return $this->save();
    }
    
    public function checkSameWithConfirmPasswd($attribute, $params) {
        if (!$this->hasErrors()) {
            if($this->password != $this->confirmpassword)
                $this->addError('password', 'Password tidak sama dengan Konfirmasi Password');
        }
    }
    
    public function getTypeUserOptions(){
        return $this->userType;
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('displayname', $this->displayname, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('avatar_file', $this->avatar_file, true);
        $criteria->compare('state_online', $this->state_online);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}