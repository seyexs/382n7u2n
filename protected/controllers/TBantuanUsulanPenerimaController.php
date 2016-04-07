<?php
class TBantuanUsulanPenerimaController extends Controller
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
            return ''; 
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
		$model=new TBantuanUsulanPenerima;

		$data = json_decode(stripslashes($_POST['data']));

        foreach ($data as $key => $val) {
			if($model->hasAttribute($key))
				$model->$key = $val;
        }
		$cek=TBantuanUsulanPenerima::model()->count(array(
			'condition'=>'deleted=0 and t_bantuan_program_id=:id and sekolah_id=:sid',
			'params'=>array(':id'=>$model->t_bantuan_program_id,':sid'=>$model->sekolah_id),
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
            $appDb=Yii::app()->params['appDb'];
			$dapodikmenDb=Yii::app()->params['dapodikmenDb'];
            $total = TBantuanUsulanPenerima::model()->count(array(
				'join'=>'inner join '.$appDb.'.dbo.t_bantuan_program bp on t.t_bantuan_program_id=bp.id 
						inner join '.$dapodikmenDb.'.dbo.sekolah s on t.sekolah_id=convert(varchar(36),s.sekolah_id)',
				'condition'=>'t.deleted=0 and (bp.nama like :q1 or s.nama like :q2)',
				'params'=>array(':q1'=>'%'.$q.'%',':q2'=>'%'.$q.'%'),
			));
			$limit=($limit+$start>$total)?($total-$start):$limit;
            $model = TBantuanUsulanPenerima::model()->findAll(array(
				'join'=>'inner join '.$appDb.'.dbo.t_bantuan_program bp on t.t_bantuan_program_id=bp.id 
						inner join '.$dapodikmenDb.'.dbo.sekolah s on t.sekolah_id=convert(varchar(36),s.sekolah_id)',
				'condition'=>'t.deleted=0 and (bp.nama like :q1 or s.nama like :q2)',
				'params'=>array(':q1'=>'%'.$q.'%',':q2'=>'%'.$q.'%'),
				'limit'=>$limit, 
				'offset'=>$start
			));
			$data=array();
			foreach($model as $d){
				$data[]=array(
					'id'=>$d->id,
					't_bantuan_program_id'=>$d->t_bantuan_program_id,
					't_bantuan_program_name'=>$d->tBantuanProgram->nama,
					'sekolah_id'=>$d->sekolah_id,
					'sekolah_nama'=>$d->sekolah->nama,
					'deleted'=>$d->deleted,
					'catatan'=>$d->catatan
				);
			}
            
            echo CJSON::encode(array(
                "success" => true,
                "total" => $total,
                "data" => $data
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
		$model=TBantuanUsulanPenerima::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='tbantuan-usulan-penerima-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
