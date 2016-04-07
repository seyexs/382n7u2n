<?php
class TBantuanPenerimaSiswaController extends Controller
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
		$model=new TBantuanPenerimaSiswa;

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
				$tbpid=$model->t_bantuan_penerima_id;
				$tbid=$model->tBantuanPenerima->t_bantuan_program_id;
				$sekolah_id=$model->tBantuanPenerima->sekolah_id;
				$model->deleted=1;
				if($model->save()){
					$cekSiswaLain=TBantuanPenerimaSiswa::model()->count(array(
						'condition'=>'deleted=0 and t_bantuan_penerima_id=:id',
						'params'=>array(':id'=>$tbpid)
					));
					if($cekSiswaLain==0){
						/*hapus data penerima sekolah*/
						$data=TBantuanPenerima::model()->findAll(array(
							'condition'=>'deleted=0 and t_bantuan_program_id=:id and sekolah_id=:s',
							'params'=>array(':id'=>$tbid,':s'=>$sekolah_id)
						));
						foreach($data as $d){
							$m=TBantuanPenerima::model()->findByPk($d->id);
							$m->deleted=1;
							$m->save();
						}
					}else{
						/*update jumlah data penerima pada data sekolah*/
						$update=TBantuanPenerima::model()->findByPk($tbpid);
						$update->jumlah_bantuan=intval($update->jumlah_bantuan)-1;
						$update->save();
					}
					echo json_encode(array(
						"success" => 1
					));
				}else{
					echo json_encode(array(
						"success" => 0
					));
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
	/**
        * Read all table content
    */
        
    public function actionRead(){
            $this->layout = false;

            $start = (int) $_GET['start'];
            $limit = (int) $_GET['limit'];
			$q=(isset($_GET['q']))?$_GET['q']:'';
            $tbpid=(isset($_GET['tbpid']))?$_GET['tbpid']:0;
            $total = TBantuanPenerimaSiswa::model()->count(array(
				'condition'=>'deleted=0 and t_bantuan_penerima_id=:tbpid',
				'params'=>array(':tbpid'=>$tbpid)
			));
			$limit=($limit+$start>$total)?($total-$start):$limit;
            $model = TBantuanPenerimaSiswa::model()->findAll(array(
				'condition'=>'deleted=0 and t_bantuan_penerima_id=:tbpid',
				'params'=>array(':tbpid'=>$tbpid),
				'limit'=>$limit, 
				'offset'=>$start
			));
			$data=array();
			foreach($model as $d){
				$data[]=array(
					'id'=>$d->id,
					'peserta_didik_id'=>$d->pesertaDidik->nama
				);
			}
            
            echo CJSON::encode(array(
                "success" => true,
                "total" => $total,
                "data" => $data
            ));
    }
	

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=TBantuanPenerimaSiswa::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='tbantuan-penerima-siswa-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
