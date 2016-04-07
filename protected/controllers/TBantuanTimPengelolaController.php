<?php
class TBantuanTimPengelolaController extends Controller
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
            return 'GetNamaTimPengelola,CreateTimPengelola'; 
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

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new TBantuanTimPengelola;

		$data = json_decode(stripslashes($_POST['data']));

        foreach ($data as $key => $val) {
			if($model->hasAttribute($key))
				$model->$key = $val;
        }
		$userid=Yii::app()->user->id;
		$cek_user=TUserAccessData::model()->findAll(array(
			'condition'=>'deleted=0 and userid=:uid',
			'params'=>array(':uid'=>$userid)
		));
		if(isset($cek_user[0]->id)){
			$model->kode_wilayah=$cek_user[0]->wilayah;
		}else{
			$model->kode_wilayah='';
		}
		$cek=TBantuanTimPengelola::model()->count(array(
			'condition'=>'deleted=0 and nama=:n and user_id=:u',
			'params'=>array(':n'=>$model->nama,':u'=>$model->user_id)
		));
		if($cek==0){
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

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		
		$this->render('index');
	}
	/**
        * Read all table content
    */
        
    public function actionRead(){
            $this->layout = false;

            $start = (int) $_GET['start'];
            $limit = (int) $_GET['limit'];
			$q=(isset($_GET['q']))?$_GET['q']:'';
			$userid=Yii::app()->user->id;
			$cek_user=TUserAccessData::model()->findAll(array(
				'condition'=>'deleted=0 and userid=:uid',
				'params'=>array(':uid'=>$userid)
			));
			if(isset($cek_user[0]->id)){
				$filter_wilayah=" and kode_wilayah='".$cek_user[0]->wilayah."'";
			}else{
				$filter_wilayah=" and (kode_wilayah='' or kode_wilayah is null)";
			}
            $removeJunk=TBantuanTimPengelola::model()->findAll(array(
				'condition'=>'deleted=0 and user_id is null'
			));
			foreach($removeJunk as $j){
				$m=TBantuanTimPengelola::model()->findByPk($j->id);
				$m->deleted=1;
				$m->save();
			}
            $total = TBantuanTimPengelola::model()->count(array(
				'condition'=>'deleted=0'.$filter_wilayah
			));
			$limit=($limit+$start>$total)?($total-$start):$limit;
            $model = TBantuanTimPengelola::model()->findAll(array(
				'condition'=>'deleted=0'.$filter_wilayah,
				'limit'=>$limit, 
				'offset'=>$start
			));
			$data=array();
			foreach($model as $d){
				$data[]=array(
					'id'=>$d->id,
					'nama'=>$d->nama,
					'user_id'=>$d->user_id,
					'deleted'=>$d->deleted,
					'user_displayname'=>$d->user->displayname
				);
			}
            
            echo CJSON::encode(array(
                "success" => true,
                "total" => $total,
                "data" => $data
            ));

            Yii::app()->end();
    }
	public function actionCreateTimPengelola(){
		$nama=$_POST['nama'];
		$cek=TBantuanTimPengelola::model()->count(array(
			'condition'=>'deleted=0 and nama=:n',
			'params'=>array(':n'=>$nama)
		));
		if($cek==0){
			$model=new TBantuanTimPengelola;
			$model->nama=$nama;
			$model->deleted=0;
			$model->save();
		}
	}
	public function actionGetNamaTimPengelola(){
		$this->layout = false;

            $start = (int) $_GET['start'];
            $limit = (int) $_GET['limit'];
			$q=(isset($_GET['q']))?$_GET['q']:'';
            
            $total = TBantuanTimPengelola::model()->count(array(
				'select'=>'nama',
				'condition'=>'deleted=0 and t.nama like :q',
				'params'=>array(':q'=>'%'.$q.'%'),
				'group'=>'nama'
			));
			$limit=($limit+$start>$total)?($total-$start):$limit;
            $model = TBantuanTimPengelola::model()->findAll(array(
				'select'=>'nama',
				'condition'=>'deleted=0 and t.nama like :q',
				'params'=>array(':q'=>'%'.$q.'%'),
				'group'=>'nama',
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
		$model=TBantuanTimPengelola::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='tbantuan-tim-pengelola-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
