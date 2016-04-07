<?php
class TBantuanProposalController extends Controller
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
		$model=new TBantuanProposal;
		$t_bantuan_program_id=$_POST['t_bantuan_program_id'];
		$data = json_decode(stripslashes($_POST['data']));

        foreach ($data as $key => $val) {
			if($model->hasAttribute($key))
				$model->$key = $val;
        }
				
        if ($model->save()) {
			$jawaban=$_POST['tkuesionerjawaban'];
			$bantuan=TBantuanProgram::model()->findByPk($t_bantuan_program_id);
			$dbname=Yii::app()->params['dbname'];
			//jika pernah mengisi kuesioner yang sama,maka yang lama diedit
			$cek=TKuesionerJawaban::model()->findAll(array(
				'condition'=>'user_id=:uid and deleted=0 and t_kuesioner_pilihan_jawaban_id 
					in(select pj.id from '.$dbname.'.t_kuesioner_pilihan_jawaban pj inner join '.$dbname.'.t_kuesioner_pertanyaan kp on pj.t_pertanyaan_id=kp.id
					 where kp.t_kuesioner_id=:kid)',
				'params'=>array(':uid'=>Yii::app()->user->id,':kid'=>$bantuan->t_kuesioner_id)
			));
			foreach($cek as $c){
				$m=TKuesionerJawaban::model()->findByPk($c->id);
				$m->deleted=1;
				$m->save();
			}
			foreach($jawaban as $jw){
				$model=new TKuesionerJawaban;
				foreach($jw as $key=>$j){
					if ($model->hasAttribute($key)) {
						$model->$key = $j;
					}
				}
				if(isset($model->t_kuesioner_pilihan_jawaban_id)){
					$model->user_id=Yii::app()->user->id;
					$model->save();
				}
			}
            echo json_encode(array(
                "success" => true,
				'message'=>'Data Telah Diproses',
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
	/**
        * Read all table content
    */
        
    public function actionRead(){
            $this->layout = false;

            $start = (int) $_GET['start'];
            $limit = (int) $_GET['limit'];
			$q=(isset($_GET['q']))?$_GET['q']:'';
            
            $total = TBantuanProposal::model()->count(array(
				'condition'=>'deleted=0'
			));
			$limit=($limit+$start>$total)?($total-$start):$limit;
            $model = TBantuanProposal::model()->findAll(array(
				'condition'=>'deleted=0',
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
		$model=TBantuanProposal::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='tbantuan-proposal-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
