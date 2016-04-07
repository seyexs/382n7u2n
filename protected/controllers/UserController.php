<?php

class UserController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column1', meaning
     * using one-column layout. See 'protected/views/layouts/column1.php'.
     */
    //public $layout = '//layouts/column1';

    public function init() {
        parent::init();
    }

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'rights',
        );
    }
	
    public function allowedActions() {
        return 'GetUserList,GetUserPengelolaBantuan,GetUserLoggedIn,UpdateProfile,getRto,getPerson,updatePassword';
    }
    
    public function actions() {
        return array(
            'getRto' => 'ext.actions.GetRtoAction',
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }
	
    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new User;
		
		if(isset($_POST['username'],$_POST['password'])){
			$cek=User::model()->count(array(
				'condition'=>'username=:u and deleted=0',
				'params'=>array(':u'=>$_POST['username'])
			));
			
			//$model->save();
			//$this->debug($model);
			if($cek){
				echo CJSON::encode(array(
					'success'=>false,
					'message'=>'Username \"'.$_POST['username'].'\" telah digunakan oleh user lain.'
				));
				exit;
			}
			
			$model->username=$_POST['username'];
			$model->password=$_POST['password']; //di encryp di model beforesave
			$model->displayname=$_POST['displayname'];
			$model->email=$_POST['email'];
			$model->deleted=0;
			$model->kode_kepemilikan=$_POST['kode_kepemilikan'];
			$model->pemilik_id=$_POST['pemilik_id'];
			//$user->avatar_file=$_POST['avatar_file'];
			
			if($_FILES['avatar_file']['size'] != 0){
				$user_avatar = $_FILES['avatar_file'];

				$allowedExts = array("jpg");
				$extension = end(explode(".", $_FILES["avatar_file"]["name"]));

				//mencetak error tidak boleh mengupload file dengan ekstensi selain yang disebutkan diatas
				if (!in_array($extension, $allowedExts)) {
					echo '{success:false, errors:[], message: "Format tidak diperbolehkan"}';
					return;
				}
				
				$file_to_upload = $user_avatar['tmp_name'];
				$file_name = $user_avatar['name'];
				$user_id=Yii::app()->user->id;
				$file_path = Yii::app()->params['image_path'];
				$file_full_path = Yii::getPathOfAlias('webroot').'/'.$file_path .'/'. $user_id .'.'. $extension;
				
				move_uploaded_file($file_to_upload, $file_full_path);
				
				if($this->crop_and_copy($file_full_path)){
					$model->avatar_file=$file_path.'/'.$user_id.'-crop.jpg';
				}
				
			}
			
			
		}
		
		echo CJSON::encode(array(
					"success" => ($model->save()),
					"total" => count($model),
					"message"=>$this->debug($model)
		));
		Yii::app()->session['user_save']=$model;
		
    }
	public function actionShowDebug(){
		$this->debug(Yii::app()->session['user_save']);
	}
    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            $uploadedFile = CUploadedFile::getInstance($model, 'uploadedFoto');
            if (!empty($uploadedFile)) {
                $sourceFile = Yii::getPathOfAlias('webroot') . Yii::app()->params['dirFotoTemp'] . "/" . $uploadedFile->name;
                $uploadedFile->saveAs($sourceFile);
                //End Move Uploaded avatar_file to temp
        
                $uploadImg = Yii::app()->uploadImage;
                $tm = time();
                $np = str_replace(' ', '_', trim($model->username));
                $np = str_replace('.','', trim($np));
                $fileName = "{$tm}_{$np}.{$uploadedFile->extensionName}";
                $destPath = $uploadImg->setDestination(Yii::getPathOfAlias('webroot') . Yii::app()->params['dirFotoUser']) . $fileName;
                $destUrl = $uploadImg->setDestination(Yii::app()->params['dirFotoUser'], false);
                $uploadImg->save($sourceFile, $destPath);
                $model->avatar_file = "{$destUrl}{$fileName}";
                
            }
            else
                $model->avatar_file = NULL;
            
            if ($model->save()){
                $this->setFlashSuccess();
                $this->redirect(array('index'));
            }
            else
                $this->setFlashFail();
            
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }
    
    public function actionRenderCombo(){
            $is_pegawai_siswa=$_POST['is_pegawai_siswa'];
            $this->renderPartial('_combo',array(
                'is_pegawai_siswa'=>$is_pegawai_siswa
            ));
        }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $arrId = explode(',', $id);
        $deletedRecord = 0;
        foreach ($arrId as $id){
            $deletedRecord += User::model()->deleteByPk($id);
        }

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        else {
             $arrStatus = array('type' => 'success', 'msg' => $deletedRecord. ' data telah dihapus');
             $this->renderJSON($arrStatus);
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $model = new User('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['User']))
            $model->attributes = $_GET['User'];

        $this->render('index', array(
            'model' => $model,
        ));
    }
    
    public function actionResetPassword($id){
        if($this->loadModel($id)->resetPassword())
            $arrStatus = array('type' => 'success', 'msg' => 'Password telah direset');
        else
            $arrStatus = array('type' => 'error', 'msg' => 'Password gagal direset');
        $this->renderJSON($arrStatus);
    }       
    public function actionSetAkses(){
        //echo $_GET['userid'];
        //exit;
        $panelDefault = 0;
        $model = $this->loadModel($_GET['userid']);
        $modelRTO = new AssignRTOForm();
        if(isset($_POST['AssignRTOForm'])){
            $panelDefault = 1;
            $modelRTO->attributes = $_POST['AssignRTOForm'];
            $modelRTO->userid = $_GET['userid'];
            if($modelRTO->saveToAuthAssignment()){
                $this->setFlashSuccess();
                $modelRTO->unsetAttributes();
            }
            else
                $this->setFlashFail();
            
                
        }
        $modelsAsigns = AuthAssignment::model()->findAll('userid=:userid', array(':userid' => $_GET['userid']));
        $this->render('setakses', array('model' => $model, 'modelsAsigns' => $modelsAsigns, 'modelRTO' => $modelRTO, 'panelDefault' => $panelDefault));
    }
    public function actionDeleteAkses(){
        if(isset($_GET['role'])){
            if(AuthAssignment::model()->deleteAll('userid=:userid AND itemname=:itemname', array(':userid' => $_GET['userid'], ':itemname' => $_GET['role'])))
                echo "1";
            else
                echo "0";
        }
        else
            echo "0";
        Yii::app()->end();
    }
    public function actionGetPerson(){
        $nama = "%{$_GET['name']}%";
        if($_GET['type'] == '1'){
            $models = MPegawai::model()->with(array('mPerson' => array('select' => 'nama')))->findAll('mPerson.nama LIKE :nama', array(':nama' => $nama));
        }
        else{
            $models = MSiswa::model()->with(array('mPerson' => array('select' => 'nama')))->findAll('mPerson.nama LIKE :nama', array(':nama' => $nama));
        }
        $retArr = array();
        if(!empty($models)){
            foreach($models as $i => $model) {
                $no = "";
                if(isset($model->nip))
                     $no = " ({$model->nip})"; 
                elseif(isset($model->nis))
                     $no = " ({$model->nis})";
                $retArr[] = array('id' => $model->id, 'nama' => isset($model->mPerson->nama) ? $model->mPerson->nama.$no:'', 'displayname' => isset($model->mPerson->nama) ? $model->mPerson->nama:'');
            }
        }
        echo json_encode($retArr);
    }
    
    public function actionUpdatePassword(){
        if(Yii::app()->user->isGuest){
            Yii::app()->user->returnUrl = $this->route;
            //$this->redirect($this->createUrl('/site/login'));
            $this->redirect(Yii::app()->user->loginUrl);
        }
        $model = new ChangePasswordForm('update');
	$uploadedFile = CUploadedFile::getInstance($model, 'filefoto');
            if (!empty($uploadedFile)) {
                $sourceFile = Yii::getPathOfAlias('webroot') . Yii::app()->params['dirFotoTemp'] . "/" . $uploadedFile->name;
                $uploadedFile->saveAs($sourceFile);
                //End Move Uploaded avatar_file to temp
        
                $uploadImg = Yii::app()->uploadImage;
                $tm = time();
                $np = str_replace(' ', '_', trim($model->username));
                $np = str_replace('.','', trim($np));
                $fileName = "{$tm}_{$np}.{$uploadedFile->extensionName}";
                $destPath = $uploadImg->setDestination(Yii::getPathOfAlias('webroot') . Yii::app()->params['dirFotoUser']) . $fileName;
                $destUrl = $uploadImg->setDestination(Yii::app()->params['dirFotoUser'], false);
                $uploadImg->save($sourceFile, $destPath);
                $model->filefoto = "{$destUrl}{$fileName}";
                
            }
        if (isset($_POST['ChangePasswordForm'])) {
            $model->attributes = $_POST['ChangePasswordForm'];
            if($model->save()){
                $this->render('formChangePasswdSuccess', array('model' => $model));
                return;
            }
        }
        $this->render('formChangePasswd', array('model' => $model));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
	 
    public function loadModel($id) {
        $model = User::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
	public function actionRead(){
		$this->actionGetUserList();
	}
	public function actionGetUserPengelolaBantuan(){
		$start=(int)$_GET['start'];
		$limit=(int)$_GET['limit'];
		$q=(isset($_GET['query']))?$_GET['query']:"";
		$appDb=Yii::app()->params['appDb'];
		$total=user::model()->count(array(
			'join'=>'inner join '.$appDb.'.dbo.authassignment a on t.id=a.userid inner join authitemchild b on a.itemname=b.parent',
			'condition'=>'t.deleted=0 and b.child like :q and t.displayname like :q2',
			'params'=>array(':q'=>'TBantuanProgram%',':q2'=>'%'.$q.'%')
		));
		$limit=($limit+$start>$total)?($total-$start):$limit;
		$model=User::model()->findAll(array(
			'join'=>'inner join '.$appDb.'.dbo.authassignment a on t.id=a.userid inner join authitemchild b on a.itemname=b.parent',
			'condition'=>'t.deleted=0 and b.child like :q and t.displayname like :q2',
			'params'=>array(':q'=>'TBantuanProgram%',':q2'=>'%'.$q.'%'),
			'offset'=>$start,
			'limit'=>$limit
		));
		
		echo CJSON::encode(array(
                "success" => true,
                "total" => $total,
                "rows" => $model
            ));
	}
	public function actionGetUserList(){
		$start=(int)$_GET['start'];
		$limit=(int)$_GET['limit'];
		$q=(isset($_GET['query']))?$_GET['query']:"";
		$total=user::model()->count(array(
			'select'=>'id,username,email,displayname',
			'condition'=>"(username like :q1 or displayname like :q2) and deleted=0",
			'params'=>array(':q1'=>'%'.$q.'%',':q2'=>'%'.$q.'%'),
		));
		$limit=($limit+$start>$total)?($total-$start):$limit;
		$model=user::model()->findAll(array(
			'select'=>'id,username,email,displayname,kode_kepemilikan,pemilik_id',
			'condition'=>"(username like :q1 or displayname like :q2) and deleted=0",
			'params'=>array(':q1'=>'%'.$q.'%',':q2'=>'%'.$q.'%'),
			'offset'=>$start,
			'limit'=>$limit
		));
		
		echo CJSON::encode(array(
                "success" => true,
                "total" => $total,
                "rows" => $model
            ));
	}
	public function actionSoftDelete(){
		if(!empty($_POST['data'])){
			$data=explode(',',$_POST['data']);
			foreach($data as $d){
				$model=User::model()->findByPk($d);
				if($model->id){
					$model->deleted=1;
					$model->save();
				}
			}
		}
	}
	public function actionGetUserLoggedIn(){
		$model=user::model()->findByPk(Yii::app()->user->id);
		echo CJSON::encode(array(
                $model
            ));
	}

	public function actionUpdateProfile(){
		$model =User::model()->findByPk(Yii::app()->user->id);
		if(isset($_POST['password']) && $_POST['password']<>'')
			$model->password=crypt($_POST['password'], Randomness::blowfishSalt());
		
		$model->displayname=$_POST['displayname'];
		$model->email=$_POST['email'];
		//$user->avatar_file=$_POST['avatar_file'];
		$m=new user();
		
		if($_FILES['avatar_file']['size'] != 0){
            $user_avatar = $_FILES['avatar_file'];

            $allowedExts = array("jpg");
            $extension = end(explode(".", $_FILES["avatar_file"]["name"]));

            //mencetak error tidak boleh mengupload file dengan ekstensi selain yang disebutkan diatas
            if (!in_array($extension, $allowedExts)) {
                echo '{success:false, errors:[], message: "Format tidak diperbolehkan"}';
                return;
            }
            
            $file_to_upload = $user_avatar['tmp_name'];
            $file_name = $user_avatar['name'];
            $user_id=Yii::app()->user->id;
            $file_path = Yii::app()->params['image_path'];
            $file_full_path = Yii::getPathOfAlias('webroot').'/'.$file_path .'/'. $user_id .'.'. $extension;
            
            move_uploaded_file($file_to_upload, $file_full_path);
            
            if($this->crop_and_copy($file_full_path)){
				$model->avatar_file=$file_path.'/'.$user_id.'-crop.jpg';
			}
			
        }
			echo CJSON::encode(array(
					"success" => ($model->save()),
					"total" => count($model)
				));
		//$model->attributes = $_POST['ChangePasswordForm'];
	}
	private function crop_and_copy($img){
        $path_piece = explode('/', $img);
        $path_piece[count($path_piece)-1] = null;
        $path = implode('/', $path_piece);
        
        list($width, $height) = getimagesize($img);
        $file_name_piece = explode(".", $img);
        $file_name = $file_name_piece[count($file_name_piece)-2];
        
        if($height > $width){
            $new_width = 60;
            $new_height = $height/$width * 60;
            if ($new_height > 60){
                $new_height = 60;
                $new_width = $new_height / $height * $width;
            }
        }else{
            $new_height = 60;
            $new_width = $width/$height * 60;
        }
        
        $s_img = imagecreatefromjpeg($img);
        $d_img = imagecreatetruecolor($new_width, $new_height);
        
        imagecopyresampled($d_img, $s_img, 0,0, 0, 0, $new_width, $new_height, $width, $height);
        imagejpeg($d_img, $file_name .'-crop.jpg');
		return true;
    }
    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
