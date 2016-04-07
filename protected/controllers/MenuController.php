<?php
class MenuController extends Controller
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
            return 'data,GetPetunjukPenggunaan'; 
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
		$model=new Menu;

		$data = json_decode(stripslashes($_POST['data']));

        foreach ($data as $key => $val) {
            $model->$key = $val;
        }
		unset($model->id);
		$model->deleted=0;
        if ($model->save()) {
			$update=$this->loadModel($model->parent_id);
			$model->path=$update->path.','.$model->id;
			$model->save();
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
	public function actionGetPetunjukPenggunaan(){
		$id=$_POST['menu'];
		$model=Menu::model()->findByPk($id);
		$petunjuk=(isset($model->petunjuk_penggunaan) && $model->petunjuk_penggunaan!='')?$model->petunjuk_penggunaan:'Petunjuk Penggunaan Belum Tersedia untuk Menu ini.';
		echo '
			<style>
				.detailbantuan{background:#ffffff !important;}
				.detailbantuan td{padding: 2em;}
				.detailbantuan li{list-style: inherite !important;padding-top:10px;}
			</style>
			<table class="detailbantuan" width="100%" height="100%">
				<tr>
					<td>'.$petunjuk.'</td>
				</tr>
				<tr>
					<td></td>
				</tr>
				<tr>
					<td></td>
				</tr>
				<tr>
					<td></td>
				</tr>
				<tr>
					<td></td>
				</tr>
				<tr>
					<td></td>
				</tr>
				<tr>
					<td></td>
				</tr><tr>
					<td></td>
				</tr>
				<tr>
					<td></td>
				</tr>
				<tr>
					<td></td>
				</tr>
			</table>
		';
	}
	/**
        * Read all table content
    */
        
    public function actionRead(){
            $this->layout = false;

            $start = (int) $_GET['start'];
            $limit = (int) $_GET['limit'];
			$q=(isset($_GET['q']))?$_GET['q']:'';
            //Use this code for complex query
            /*
            $model = Yii::app()->db->createCommand()
                    ->select('*')
                    ->from('table_name')
                    ->offset($start)
                    ->limit($limit)
                    ->queryAll();
            */
            $total = Menu::model()->count(array(
				//'condition'=>'deleted=0'
			));
			$limit=($limit+$start>$total)?($total-$start):$limit;
            $model = Menu::model()->findAll(array(
				//'condition'=>'deleted=0',
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
		$model=Menu::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='menu-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	public function actionData(){
		$model = Menu::model()->findByPk(2);
		$data=$model->getMenuItems();
		print_r($data);
		
	}
	
	public function actionGetDropDownData(){
		$parents = Menu::model()->findAll('parent_id = 0');
        $cm = new CommonMethods();
        $data = $cm->makeDropDown($parents);
		//print_r($data);
		echo CJSON::encode($data);
	}
}
