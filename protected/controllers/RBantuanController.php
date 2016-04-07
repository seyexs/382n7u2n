<?php
class RBantuanController extends Controller
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
		$model=new RBantuan;

		$data = json_decode(stripslashes($_POST['data']));

        foreach ($data as $key => $val) {
            $model->$key = $val;
        }
		//$model->id=new CDbExpression("CONVERT(NVARCHAR(32),HashBytes('MD5',CONVERT(NVARCHAR(36),NEWID())),2)");		
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
		$cek=RBantuan::model()->findAll(array(
			'condition'=>'CONVERT(NVARCHAR(32),HashBytes(\'MD5\',CONVERT(NVARCHAR(36),t.id)),2)=:id',
			'params'=>array(':id'=>$id)
		));
		$id=$cek[0]->id;
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
				$cek=RBantuan::model()->findAll(array(
					'condition'=>'CONVERT(NVARCHAR(32),HashBytes(\'MD5\',CONVERT(NVARCHAR(36),t.id)),2)=:id',
					'params'=>array(':id'=>$id)
				));
				$id=$cek[0]->id;
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
			$filter_data='';
			$bantuanOperasionalid='90A69530-96AB-40E1-A315-EF7B0A9DF67A';
			if(isset($cek_user[0]->id)){
				$filter_data=" and id='".$bantuanOperasionalid."'";
			}
            $total = RBantuan::model()->count(array(
				'condition'=>'deleted=0 and name like :q'.$filter_data,
				'params'=>array(':q'=>'%'.$q.'%')
			));
			$limit=($limit+$start>$total)?($total-$start):$limit;
            $model = RBantuan::model()->findAll(array(
				'select'=>'CONVERT(NVARCHAR(32),HashBytes(\'MD5\',CONVERT(NVARCHAR(36),t.id)),2) as id,name,deleted',
				'condition'=>'deleted=0 and name like :q'.$filter_data,
				'params'=>array(':q'=>'%'.$q.'%'),
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
	public function actionRawRead(){
            $this->layout = false;

            $start = (int) $_GET['start'];
            $limit = (int) $_GET['limit'];
			$q=(isset($_GET['q']))?$_GET['q']:'';
            $userid=Yii::app()->user->id;
			$cek_user=TUserAccessData::model()->findAll(array(
				'condition'=>'deleted=0 and userid=:uid',
				'params'=>array(':uid'=>$userid)
			));
			$filter_data='';
			$bantuanOperasionalid='90A69530-96AB-40E1-A315-EF7B0A9DF67A';
			if(isset($cek_user[0]->id)){
				$filter_data=" and id='".$bantuanOperasionalid."'";
			}
            $total = RBantuan::model()->count(array(
				'condition'=>'deleted=0 and name like :q'.$filter_data,
				'params'=>array(':q'=>'%'.$q.'%')
			));
			$limit=($limit+$start>$total)?($total-$start):$limit;
            $model = RBantuan::model()->findAll(array(
				'condition'=>'deleted=0 and name like :q'.$filter_data,
				'params'=>array(':q'=>'%'.$q.'%'),
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
		$model=RBantuan::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='rbantuan-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
