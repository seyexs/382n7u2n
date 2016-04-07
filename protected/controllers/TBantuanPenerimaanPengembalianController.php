<?php
class TBantuanPenerimaanPengembalianController extends Controller
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
            return 'GetBuktiPenerimaanPengembalian,Hapus'; 
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
	public function actionGetBuktiPenerimaanPengembalian(){
		$fn=$_GET['fn'];
		$tbpid=$_GET['tbpid'];
		$load=TBantuanPenerima::model()->findByPk($tbpid);
		$user_id=Yii::app()->user->id;
		$user=User::model()->findByPk($user_id);
		$upload_path='p'.$load->t_bantuan_program_id.'/bukti-penerimaan-pengeluaran/U'.$user_id.'-'.$user->displayname.'/';
		$file_path = Yii::app()->params['dirBantuanDoc'];
		$file_path=Yii::getPathOfAlias('webroot').'/'.$file_path .'/'. $upload_path;
		$file_path = str_replace(' ','-',$file_path);
		$file_path.=$fn;
		echo file_get_contents($file_path);
	}
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new TBantuanPenerimaanPengembalian;

		if(isset($_POST['t_bantuan_penerima_id'],$_POST['jumlah_bantuan'])){
			$load=TBantuanPenerima::model()->findByPk($_POST['t_bantuan_penerima_id']);
			if(!isset($load->id))
				Yii::app()->end();
			
			$model->t_bantuan_penerima_id=$_POST['t_bantuan_penerima_id'];
			$model->jumlah_bantuan=$_POST['jumlah_bantuan'];
			$model->tanggal_diterima_dikembalikan=$_POST['tanggal_diterima_dikembalikan'];
			$model->status=$_POST['status'];
			$user_id=Yii::app()->user->id;
			$user=User::model()->findByPk($user_id);
			if(isset($_FILES['bukti_diterima_dikembalikan']['size'])){
				$upload_path='p'.$load->t_bantuan_program_id.'/bukti-penerimaan-pengeluaran/U'.$user_id.'-'.$user->displayname.'/';
				$file = $_FILES['bukti_diterima_dikembalikan'];
				$allowedExts = array('jpg','bip','png','gif');
				$extension = end(explode(".", $_FILES["bukti_diterima_dikembalikan"]["name"]));
				
				//mencetak error tidak boleh mengupload file dengan ekstensi selain yang disebutkan diatas
				if (!in_array($extension, $allowedExts)) {
					echo '{success:false, errors:[], message: "Format tidak diperbolehkan"}';
					return;
				}
						
				$file_to_upload = $file['tmp_name'];
				$file_name = str_replace(' ','-',$load->tBantuanProgram->nama).'.'.$extension;
				
				$file_path = Yii::app()->params['dirBantuanDoc'];
				$file_path=Yii::getPathOfAlias('webroot').'/'.$file_path .'/'. $upload_path;
				$file_path = str_replace(' ','-',$file_path);
				if(!is_dir($file_path)){
					mkdir($file_path, 0777, true);
				}
					
				$file_full_path = $file_path.$file_name;
				//$file_full_path = str_replace(' ','-',$file_full_path);
				$success=move_uploaded_file($file_to_upload, $file_full_path);
				
				if($success){
					$model->bukti_diterima_dikembalikan=$file_name;
					
				}
			
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
	public function actionHapus(){
		$tppid=$_POST['tppid'];
		$model=TBantuanPenerimaanPengembalian::model()->findByPk($tppid);
		if(isset($model->id)){
			/*cros cek seklah*/
			$user=User::model()->findByPk(Yii::app()->user->id);
			if($user->kode_kepemilikan=='SP' && $user->pemilik_id==$model->tBantuanPenerima->sekolah_id){
				$model->deleted=1;
				$model->save();
			}
		}
	}
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		
		$this->render('index');
	}
	public function actionGetPenerimaanPengembalian(){
		$tbpid=(isset($_POST['tbpid']))?$_POST['tbpid']:$_GET['tbpid'];
		$model = TBantuanPenerimaanPengembalian::model()->findAll(array(
				'condition'=>'deleted=0 and t_bantuan_penerima_id=:id',
				'params'=>array(':id'=>$tbpid),
				'order'=>'tanggal_diterima_dikembalikan asc'
			));
		$this->renderPartial('_view',array(
			'model'=>$model,
			'tbpid'=>$tbpid
		));
	}
	/**
        * Read all table content
    */
        
    public function actionRead(){
            $this->layout = false;
			$tbpid=(isset($_POST['tbpid']))?$_POST['tbpid']:$_GET['tbpid'];
            $start = (int) $_GET['start'];
            $limit = (int) $_GET['limit'];
			$q=(isset($_GET['q']))?$_GET['q']:'';
            
            $total = TBantuanPenerimaanPengembalian::model()->count(array(
				'condition'=>'deleted=0 and t_bantuan_penerima_id=:id',
				'params'=>array(':id'=>$tbpid),
			));
			$limit=($limit+$start>$total)?($total-$start):$limit;
            $model = TBantuanPenerimaanPengembalian::model()->findAll(array(
				'condition'=>'deleted=0 and t_bantuan_penerima_id=:id',
				'params'=>array(':id'=>$tbpid),
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
		$model=TBantuanPenerimaanPengembalian::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='tbantuan-penerimaan-pengembalian-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
