<?php
class TBantuanLaporanPhotoController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column1', meaning
	 * using one-column layout. See 'protected/views/layouts/column1.php'.
	 */
	
        
        public function init() {
            parent::init();
        }
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
                    'rights',
		);
	}

	public function allowedActions() 
        { 
            return 'LoadPost'; 
        }

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}
	public function actionSubmitPost(){
		if(isset($_POST['keterangan_photo'],$_POST['kategori_progres'],$_POST['tbpid'],$_FILES['path_photo'])){
			$penerima=TBantuanPenerima::model()->findByPk($_POST['tbpid']);
			if(!isset($penerima->id))
				return;
			
			$model=new TBantuanLaporanPhoto;
			$model->keterangan_photo=$_POST['keterangan_photo'];
			$model->kategori_progres=$_POST['kategori_progres'];
			$model->t_bantuan_penerima_id=$penerima->id;
			if($_FILES['path_photo']['size'] != 0){
				$user_avatar = $_FILES['path_photo'];

				$allowedExts = array("jpg","gif","bmp","png");
				$extension = end(explode(".", $_FILES["path_photo"]["name"]));

				//mencetak error tidak boleh mengupload file dengan ekstensi selain yang disebutkan diatas
				if (!in_array($extension, $allowedExts)) {
					echo '{success:false, errors:[], message: "Format selain jpg/gif/bmp/png tidak diperbolehkan"}';
					return;
				}
				
				$file_to_upload = $user_avatar['tmp_name'];
				$file_name = $user_avatar['name'];
				$user_id=Yii::app()->user->id;
				$dirDataBantuan='p'.$penerima->t_bantuan_program_id;
				$nama_sekolah=str_replace('-','',str_replace("'",'',str_replace(' ','',$penerima->sekolah->nama)));
				$dirBantuan=Yii::app()->params['dirBantuanDoc'];
				$dest=$dirBantuan.'/'.$dirDataBantuan.'/upload/'.$nama_sekolah.'/laporan/foto';
				if(!is_dir($dest))
					mkdir($dest,0777,true);
				
				$nama_file=$random = substr( md5(rand()), 0, 7);
				$file_full_path =$dest .'/'.$nama_file.'.'. $extension;
				
				if(move_uploaded_file($file_to_upload, $file_full_path)){
					$model->path_photo=$nama_file.'.'. $extension;
					if($model->save()){
						echo CJSON::encode(array(
							'success'=>true,
							'msg'=>'Data Berhasil di Upload'
						));
					}
				}
				
				
			}else{
				echo CJSON::encode(array(
					'success'=>false,
					'msg'=>'Mohon lengkapi form diatas!'
				));
			}
		}else{
			echo CJSON::encode(array(
					'success'=>false,
					'msg'=>'Mohon lengkapi form diatas!'
			));
		}
	}
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new TBantuanLaporanPhoto;

		$data = json_decode(stripslashes($_POST['data']));

        foreach ($data as $key => $val) {
			if($model->hasAttribute($key))
				$model->$key = $val;
        }
				
        if ($model->save()) {
            echo json_encode(array(
                "success" => true,
                "data" => array(
                "id" => $model->id,
                )
            ));
        }
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	
    /**
    * Updates a particular model.
    * If update is successful, the browser will be redirected to the 'view' page.
    * @param integer $id the ID of the model to be updated
    */
    public function actionUpdate($id) {

        $put_var = array();
        parse_str(file_get_contents('php://input'), $put_var);

        foreach ($put_var as $data) {
            $json = CJSON::decode($data, true);
        }

        $model = $this->loadModel($id);

        foreach ($json as $var => $value) {
            // Does model have this attribute? If not, raise an error
            if ($model->hasAttribute($var)) {
                $model->$var = $value;
            }
        }

        if ($model->save()){
            echo json_encode(array(
                "success" => true,
                "data" => array(
                    "id" => $model->id,
                )
            ));
        }
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if ($_SERVER['REQUEST_METHOD'] === "DELETE") {

                $model=$this->loadModel($id);
				$model->deleted=1;
				
                echo json_encode(array(
                    "success" => ($model->save())
                ));
        }
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		
		$this->render('index');
	}
	public function actionLoadPost(){
		$penerima=TBantuanPenerima::model()->findByPk($_POST['tbpid']);
		$dirDataBantuan='p'.$penerima->t_bantuan_program_id;
		$nama_sekolah=str_replace('-','',str_replace("'",'',str_replace(' ','',$penerima->sekolah->nama)));
		$dirBantuan=Yii::app()->params['dirBantuanDoc'];
		$dest=$dirBantuan.'/'.$dirDataBantuan.'/upload/'.$nama_sekolah.'/laporan/foto';
        $model = TBantuanLaporanPhoto::model()->findAll(array(
				'condition'=>'deleted=0 and t_bantuan_penerima_id=:tbpid',
				'params'=>array(':tbpid'=>$_POST['tbpid'])
			));
		$data=array();
		foreach($model as $d){
			$data[]=array(
				'id'=>$d->id,
				'keterangan_photo'=>$d->keterangan_photo,
				'path_photo'=>$dest.'/'.$d->path_photo,
				'kategori_progres'=>$d->kategori_progres
			);
		}
		echo CJSON::encode(array(
			'success'=>true,
			'data'=>$data
		));
		
	}
	/**
        * Read all table content
    */
        
    public function actionRead(){
            $this->layout = false;

            $start = (int) $_GET['start'];
            $limit = (int) $_GET['limit'];
			$q=(isset($_GET['q']))?$_GET['q']:'';
            
			$total = TBantuanLaporanPhoto::model()->count(array(
				'condition'=>'deleted=0 and t_bantuan_penerima_id=:tbpid',
				'params'=>array(':tbpid'=>$_POST['tbpid'])
			));
			$limit=($limit+$start>$total)?($total-$start):$limit;
            $model = TBantuanLaporanPhoto::model()->findAll(array(
				'condition'=>'deleted=0 and t_bantuan_penerima_id=:tbpid',
				'params'=>array(':tbpid'=>$_POST['tbpid']),
				'limit'=>$limit, 
				'offset'=>$start
			));

            
            echo CJSON::encode(array(
                "success" => true,
                "total" => $total,
                "data" => $model
            ));

            Yii::app()->end();
    }
	

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=TBantuanLaporanPhoto::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='tbantuan-laporan-photo-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
