<?php
class TKuesionerPertanyaanController extends Controller
{
	private $data = array();
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
            return 'Read,read'; 
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
		$model=new TKuesionerPertanyaan;

		$data = json_decode(stripslashes($_POST['data']));

        foreach ($data as $key => $val) {
			if($model->hasAttribute($key))
				$model->$key = $val;
        }
		$model->parent_id=($model->parent_id=='')?0:$model->parent_id;		
        if ($model->save()) {
			//langsung create pilihan jawaban jika jenis jawaban adalah teks bebas
			if($model->jenis_jawaban=='2'){
				$j=new TKuesionerPilihanJawaban;
				$j->t_pertanyaan_id=$model->id;
				$j->pilihan_jawaban="Tuliskan jawaban pada kolom dibawah ini.";
				$j->urutan=1;
				$j->save();
			}
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
		$model->parent_id=($model->parent_id=="")?0:$model->parent_id;
        if ($model->save()){
			if($model->jenis_jawaban=='2'){
				$cekPilihan=TKuesionerPilihanJawaban::model()->findAll(array(
					'condition'=>'deleted=0 and t_pertanyaan_id=:id',
					'params'=>array(':id'=>$model->id),
					'order'=>'modified_date desc'
				));
				if(count($cekPilihan)==0){
					//buatkan 1 pilihan
					$j=new TKuesionerPilihanJawaban;
					$j->t_pertanyaan_id=$model->id;
					$j->pilihan_jawaban="Tuliskan jawaban pada kolom dibawah ini.";
					$j->urutan=1;
					$j->save();
				}else if(count($cekPilihan)>1){
					//sisakan 1 dan hapus pilihan yang lain
					$total=count($cekPilihan)-1;
					foreach($cekPilihan as $idx=>$p){
						if($idx<$total){
							$m=TKuesionerPilihanJawaban::model()->findByPk($p->id);
							$m->deleted=1;
							$m->save();
						}else{
							break;
						}
							
					}
				}
				
			}
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
			$kid=$_GET['kid'];
            //Use this code for complex query
            /*
            $model = Yii::app()->db->createCommand()
                    ->select('*')
                    ->from('table_name')
                    ->offset($start)
                    ->limit($limit)
                    ->queryAll();
            */
            $displayAll=($q!='')?'':' and parent_id=0';
			$total = TKuesionerPertanyaan::model()->count(array(
				'condition'=>'deleted=0 and t_kuesioner_id=:id and pertanyaan like :q1'.$displayAll,
				'params'=>array(':id'=>$kid,':q1'=>'%'.$q.'%'),
			));
			$limit=($limit+$start>$total)?($total-$start):$limit;
            $model = TKuesionerPertanyaan::model()->findAll(array(
				'condition'=>'deleted=0 and t_kuesioner_id=:id and pertanyaan like :q1'.$displayAll,
				'params'=>array(':id'=>$kid,':q1'=>'%'.$q.'%'),
				'limit'=>$limit, 
				'offset'=>$start,
				'order'=>'parent_id,urutan asc'
			));
			//$model=TKuesionerPertanyaan::model()->findAll('deleted=0 and parent_id=0 and t_kuesioner_id=:id and pertanyaan like :q1',array(':id'=>$kid,':q1'=>'%'.$q.'%'));
			$hasil=$this->aturPertanyaan($model);
            
            echo CJSON::encode(array(
                "success" => true,
                "total" => $total,
                "data" => $hasil
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
		$model=TKuesionerPertanyaan::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='tkuesioner-pertanyaan-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	public function aturPertanyaan($parents)
    {
        global $data;
        $data = array();
        //$data['0'] = '-- ROOT --';
        foreach($parents as $index=>$parent)
        {
			if ( $parent->id >= 1 ){
				//$data[$parent->id] = $parent->title;
				$no=$index+1;
				$options=TKuesionerPilihanJawaban::model()->findAll(array(
					'condition'=>'deleted=0 and t_pertanyaan_id=:id',
					'params'=>array(':id'=>$parent->id)
				));
				$data[]=array(
					'id'=>$parent->id,'t_kuesioner_id'=>$parent->t_kuesioner_id,
					'options'=>$options,
					//'pertanyaan'=>$no.'.'.$parent->pertanyaan,'parent_id'=>$parent->parent_id,
					'pertanyaan'=>$parent->pertanyaan,'parent_id'=>$parent->parent_id,
					'style'=>($parent->jenis_jawaban=='2')?'padding-left:'.(strlen($no)*6+12).'px;padding-top:18px;':'padding-left:'.(strlen($no)*6+12).'px;',
					'penjelasan'=>$parent->penjelasan,'jenis_jawaban'=>$parent->jenis_jawaban,
					'allow_multi_answer'=>$parent->allow_multi_answer,'urutan'=>$parent->urutan,
					'deleted'=>$parent->deleted
					);
				$child=TKuesionerPertanyaan::model()->findAll(array(
					'condition'=>'deleted=0 and parent_id='.$parent->id,
					'order'=>'parent_id,urutan asc'
				));
				$this->subPertanyaan($no,$child);
			}
        }
       return $data;
    }
    
    
    public function subPertanyaan($no,$children,$space = ' ')
    {
        global $data;
        foreach($children as $idx=>$child)
		{
			//$data[$child->id] = $space.$child->title.' ('.$child->sort.')';
			$subno=$no.'.'.($idx+1);
			$options=TKuesionerPilihanJawaban::model()->findAll(array(
				'condition'=>'deleted=0 and t_pertanyaan_id=:id',
				'params'=>array(':id'=>$child->id)
			));
			$data[]=array(
				'id'=>$child->id,'t_kuesioner_id'=>$child->t_kuesioner_id,
				//'pertanyaan'=>$subno.'. '.$space.$child->pertanyaan,
				'pertanyaan'=>$child->pertanyaan,
				'options'=>$options,'parent_id'=>$child->parent_id,'penjelasan'=>$child->penjelasan,
				'style'=>($child->jenis_jawaban=='2')?'padding-left:'.(strlen($subno)*6+12).'px;padding-top:18px;':'padding-left:'.(strlen($subno)*6+12).'px;',
				'jenis_jawaban'=>$child->jenis_jawaban,'allow_multi_answer'=>$child->allow_multi_answer,
				'urutan'=>$child->urutan,'deleted'=>$child->deleted
				);
			$subchild=TKuesionerPertanyaan::model()->findAll(array(
					'condition'=>'deleted=0 and parent_id='.$child->id,
					'order'=>'parent_id,urutan asc'
				));
			$this->subPertanyaan($subno,$subchild,$space.' ');
		}
    }
}
