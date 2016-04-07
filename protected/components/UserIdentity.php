<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    private $userid;
    public function authenticate() {
        $record = User::model()->findByAttributes(array('username' => $this->username,'deleted'=>'0'));
        if ($record === null){
            $this->errorCode = self::ERROR_USERNAME_INVALID;
			/*
			* mencari username di dapodik berdasarakan inputan username dan password
			*/
			$oDbConnection = Yii::app()->db;
			$dbDapodik=Yii::app()->params['dapodikmenDb'];
			$dataDB = $oDbConnection->createCommand("
				select * from ".$dbDapodik.".dbo.pengguna where username='".$this->username."' and Soft_delete=0"
			)->queryAll();
			$dataUser=$dataDB[0];
			$dataDB = $oDbConnection->createCommand("
				select SUBSTRING(sys.fn_sqlvarbasetostr(HASHBYTES('MD5',  '".$this->password."' )),3,32) as password"
			)->queryAll();
			$dataPassword=$dataDB[0];
			if($dataUser['password']==$dataPassword['password']){
				/*
				* copy user dari dapodik ke siban
				*/
				$dataSekolah=$oDbConnection->createCommand("
					select * from ".$dbDapodik.".dbo.sekolah where sekolah_id='".$dataUser['sekolah_id']."' and Soft_delete=0"
				)->queryAll();
				//echo "select * from ".$dbDapodik.".dbo.sekolah where sekolah_id='".$dataUser['sekolah_id']."' and Soft_delete=0";
				//print_r($dataSekolah);exit;
				$dataSekolah=$dataSekolah[0];
				$user=new User();
				$user->username=$this->username;
				$user->displayname=$dataSekolah['nama'];
				$user->password=$this->password;
				$user->email=$dataSekolah['email'];
				$user->deleted=0;
				$user->kode_kepemilikan='SP';
				$user->pemilik_id=$dataSekolah['sekolah_id'];
				if($user->save()){
					/*Daftarkan group sekolah*/
					$group=new AuthAssignment();
					$group->itemname='Sekolah';
					$group->userid=$user->id;
					$group->save();
					/*diarahkan untuk login lagi*/
					$this->authenticate();
				}
			}
        //else if ($record->password !== md5($this->password))
        //else if ($record->password !== $this->password)
        }else if ($record->password !== crypt($this->password, $record->password))
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        else {
            $this->userid = $record->id;
            //$this->setState('title', $record->title);
            $this->errorCode = self::ERROR_NONE;
        }
        return !$this->errorCode;
    }
    public function getUid(){
        return $this->userid;
    }

}