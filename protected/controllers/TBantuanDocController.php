<?php

class TBantuanDocController extends Controller
{

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
            return 'Download,GetShareFile'; 
        }
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
                        /*
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
                         * 
                         */
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index', 'view', 'create','update', 'read', 'delete'),
				'users'=>array('@'),
			),
                        /*
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
                         * 
                         */
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
                /*
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
                */
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new TBantuanDoc;

                $data = json_decode(stripslashes($_POST['data']));

                foreach ($data as $key => $val) {
                        $model->$key = $val;
                }

                //$model->attributes = $data;

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
        public function actionDelete($id) {

            if ($_SERVER['REQUEST_METHOD'] === "DELETE") {

                $model=$this->loadModel($id);
				$model->deleted=1;
				if($model->save()){
					//delete all children
					/*$child=TBantuanDoc::model()->findAll(array(
						'condition'=>'deleted=0 and parent_id=:id',
						'params'=>array(':id'=>$model->id)
					));
					foreach($child as $d){
						$m=$this->loadModel($d->id);
						$m->deleted=1;
						$m->save();
					}*/
				}
				
                echo json_encode(array(
                    "success" => true
                ));
            }
        }

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
                /* Default Yii action Index
		$dataProvider=new CActiveDataProvider('TBantuanDoc');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
                */
	}
    public function actionGetShareFile(){
			
            $this->layout = false;
			$id=(isset($_GET['id']))?$_GET['id']:'0';
			$parent_id=(isset($_GET['pid']) && $_GET['pid']<>'')?' and parent_id='.$_GET['pid']:'';
            $start = (int) $_GET['start'];
            $limit = (int) $_GET['limit'];
            $model = array();

            //Use this code for complex query
            /*
            $model = Yii::app()->db->createCommand()
                    ->select('*')
                    ->from('table_name')
                    ->offset($start)
                    ->limit($limit)
                    ->queryAll();
            */
            
            $model = TBantuanDoc::model()->findAll(array(
				'condition'=>'deleted=0 and t_bantuan_program_id=:id and share_file=1'.$parent_id,
				'params'=>array(':id'=>$id),
				'order'=>'is_dir desc',
				'limit'=>$limit, 
				'offset'=>$start
			));
			$data=array();
			foreach($model as $d){
				$data[]=array(
					'id'=>$d->id,
					't_bantuan_program_id'=>$d->t_bantuan_program_id,
					'parent_id'=>$d->parent_id,
					'file_type'=>$d->file_type,
					'filename'=>$d->filename,
					'is_dir'=>$d->is_dir,
					'deleted'=>$d->deleted,
					'path_file'=>($d->parent_id>0)?$this->getPathFile($d):$d->filename,
					'share_file'=>$d->share_file
				);
			}
            $total = count($model);

            echo CJSON::encode(array(
                "success" => true,
                "total" => $total,
                "data" => $data
            ));

            Yii::app()->end();
        }
        /**
        * Read all table content
        */
        
        public function actionRead(){
			
            $this->layout = false;
			$id=(isset($_GET['id']))?$_GET['id']:'0';
			$parent_id=(isset($_GET['pid']) && $_GET['pid']<>'')?' and parent_id='.$_GET['pid']:'';
            $start = (int) $_GET['start'];
            $limit = (int) $_GET['limit'];
            $model = array();

            //Use this code for complex query
            /*
            $model = Yii::app()->db->createCommand()
                    ->select('*')
                    ->from('table_name')
                    ->offset($start)
                    ->limit($limit)
                    ->queryAll();
            */
            
            $model = TBantuanDoc::model()->findAll(array(
				'condition'=>'deleted=0 and t_bantuan_program_id=:id'.$parent_id,
				'params'=>array(':id'=>$id),
				'order'=>'is_dir desc',
				'limit'=>$limit, 
				'offset'=>$start
			));
			$data=array();
			foreach($model as $d){
				$data[]=array(
					'id'=>$d->id,
					't_bantuan_program_id'=>$d->t_bantuan_program_id,
					'parent_id'=>$d->parent_id,
					'file_type'=>$d->file_type,
					'filename'=>$d->filename,
					'is_dir'=>$d->is_dir,
					'deleted'=>$d->deleted,
					'path_file'=>($d->parent_id>0)?$this->getPathFile($d):$d->filename,
					'share_file'=>$d->share_file
				);
			}
            $total = count($model);

            echo CJSON::encode(array(
                "success" => true,
                "total" => $total,
                "data" => $data
            ));

            Yii::app()->end();
        }
	private function getPathFile($model){
		$m=$this->loadModel($model->parent_id);
		$sub='';
		if($m->parent_id>0)
			$sub=$this->getPathFile($m).'/';
		
		$path=($sub<>'')?$sub.$model->filename:$m->filename.'/'.$model->filename;
		
		
		return $path;
	}
	public function actionDebug(){
		echo Yii::app()->session['file_full_path'];
	}
	public function actionUploadDok(){
		if(isset($_FILES['tbantuandoc_file']['size'],$_POST['bantuanId'],$_POST['parentId'])){
				$t_bantuan_program_id=$_POST['bantuanId'];
				$share_file=(isset($_POST['share_file']))?$_POST['share_file']:0;
				
				$parent_id=$_POST['parentId'];
				$load=TBantuanDoc::model()->findAll(array(
					'condition'=>'deleted=0 and t_bantuan_program_id=:i and parent_id=:p',
					'params'=>array(':i'=>$t_bantuan_program_id,':p'=>$parent_id),
					'limit'=>1
				));
				if(isset($load[0]->id)){
					$p=explode('/',$load[0]->path_file);
					$dir=($p[0]!='')?$p[0].'/':'';
					$upload_path='p'.$load[0]->t_bantuan_program_id.'/'.$dir;
					
				}else{
					$dir='';
					$upload_path='p'.$t_bantuan_program_id.'/';
				}
					$instrumen_file = $_FILES['tbantuandoc_file'];
					$allowedExts = array('xls','xlsx','doc','docx','ppt','pptx','pdf','jpg','bip','png','gif','rar','zip','tar.gz','txt','csv');
					$extension = end(explode(".", $_FILES["tbantuandoc_file"]["name"]));

					//mencetak error tidak boleh mengupload file dengan ekstensi selain yang disebutkan diatas
					if (!in_array($extension, $allowedExts)) {
						echo '{success:false, errors:[], message: "Format tidak diperbolehkan"}';
						return;
					}
					
					$file_to_upload = $instrumen_file['tmp_name'];
					$file_name = str_replace(' ','-',$instrumen_file['name']);
					$user_id=Yii::app()->user->id;
					$file_path = Yii::app()->params['dirBantuanDoc'];
					$file_full_path = Yii::getPathOfAlias('webroot').'/'.$file_path .'/'. $upload_path;
					if(!is_dir($file_full_path))
						mkdir($file_full_path,0777,true);
					
					$file_full_path .=$file_name;

					$success=move_uploaded_file($file_to_upload, $file_full_path);
					if($success){
						$cek=TBantuanDoc::model()->findAll(array(
							'condition'=>'deleted=0 and is_dir=0 and t_bantuan_program_id=:id and parent_id=:p and path_file=:pf and filename=:f',
							'params'=>array(':id'=>$t_bantuan_program_id,':p'=>$parent_id,':pf'=>$dir,':f'=>$file_name)
						));
						if(isset($cek[0]->id)){
							$model=$this->loadModel($cek[0]->id);
						}else{
							$model=new TBantuanDoc;
						}
						$model->t_bantuan_program_id=$t_bantuan_program_id;
						$model->parent_id=$parent_id;
						$model->filename=$file_name;
						$model->path_file=$dir;
						$model->is_dir=0;
						$model->file_type=$extension;
						$model->share_file=$share_file;
						if($model->save()){
							echo CJSON::encode(array(
								'success'=>1,
								'message'=>'File Telah Disimpan.'
							));
						}
						
					}
				
		}
	}
	public function actionCreateNewFolder(){
		if(isset($_POST['dirname'],$_POST['id'],$_POST['bantuanId'])){
				
				$load=TBantuanDoc::model()->findByPk($_POST['id']);
				$dir_name=$_POST['dirname'];
				if(isset($load->id)){
					$p=explode('/',$load->path_file);
					$dir=($p[0]!='')?$p[0].'/':'';
					$upload_path='p'.$load->t_bantuan_program_id.'/'.$dir;
					
					$file_path = Yii::app()->params['dirBantuanDoc'];
					$file_full_path = Yii::getPathOfAlias('webroot').'/'.$file_path .'/'. $upload_path.$dir_name;
					if(!is_dir($file_full_path))
						mkdir($file_full_path,0777,true);
					
					$cek=TBantuanDoc::model()->findAll(array(
							'condition'=>'deleted=0 and is_dir=1 and t_bantuan_program_id=:id and parent_id=:p and path_file=:pf and filename=:f',
							'params'=>array(':id'=>$load->t_bantuan_program_id,':p'=>$load->parent_id,':pf'=>$dir,':f'=>$dir_name)
					));
					if(isset($cek[0]->id)){
						$model=$this->loadModel($cek[0]->id);
					}else{
						$model=new TBantuanDoc;
					}
					$model->t_bantuan_program_id=$load->t_bantuan_program_id;
					$model->parent_id=$load->id;
					$model->filename=$dir_name;
					$model->path_file=$dir;
					$model->is_dir=1;
					$model->file_type='folder';
						
					if($model->save()){
						
						echo CJSON::encode(array(
							'success'=>1,
							'message'=>'File Telah Disimpan.'
						));
					}
						
					
				}elseif($_POST['id']=='0'){
					$model=new TBantuanDoc;
					$model->t_bantuan_program_id=$_POST['bantuanId'];
					$model->parent_id=0;
					$model->filename=$_POST['dirname'];
					$model->path_file='';
					$model->is_dir=1;
					$model->file_type='folder';
						
					if($model->save()){
						
						echo CJSON::encode(array(
							'success'=>1,
							'message'=>'File Telah Disimpan.'
						));
					}
				}else{
					echo CJSON::encode(array(
							'success'=>1,
							'message'=>'Bad Request'
						));
				}
		}else{
			echo CJSON::encode(array(
				'success'=>0,
				'message'=>'Maaf,Direktori Tidak Bisa dibuat!'
			));
		}
	}
	public function actionDownload(){
		$fn='p5/juknis.pdf';//$_GET['fn'];
		$path=Yii::getPathOfAlias('webroot').'/media/mydocuments/program-bantuan/'.$fn;
		if(file_exists($path)){
			$filecontent = file_get_contents($path);
			header("Content-Type: application-x/force-download");
			header("Content-disposition: attachment; filename=\"".$fn."\"");
			header("Content-length: " . (string)(strlen($filecontent)));
			header("Pragma: no-cache");
			echo $filecontent;
			exit;
		}
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new TBantuanDoc('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['TBantuanDoc']))
			$model->attributes=$_GET['TBantuanDoc'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return TBantuanDoc the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=TBantuanDoc::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param TBantuanDoc $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='tbantuan-doc-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
