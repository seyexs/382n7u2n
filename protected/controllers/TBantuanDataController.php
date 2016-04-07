<?php
class TBantuanDataController extends Controller
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
		$model=new TBantuanData;

		$data = json_decode(stripslashes($_POST['data']));

        foreach ($data as $key => $val) {
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
	public function actionGetDaftarPenerimaBantuan(){
		$tbpid=$_GET['tbpid'];
		$start = (int) $_GET['start'];
        $limit = (int) $_GET['limit'];
		$q=(isset($_GET['q']))?$_GET['q']:'';
		$total=TBantuanData::model()->count(array(
			'join'=>'inner join'.$this->getFormatJoinMSSQL('t_data_rekap','td').' on td.id=t.t_data_rekap_id inner join'.$this->getFormatJoinMSSQL('t_bantuan_program','bp').' on t.t_bantuan_program_id=bp.id',
			'condition'=>'t.deleted=0 and t.t_bantuan_program_id=:id and td.m_sekolah_text like :q and bp.nama like :q1',
			'params'=>array(':id'=>$tbpid,':q'=>'%'.$q.'%',':q1'=>'%'.$q.'%'),
		));
		$limit=($limit+$start>$total)?($total-$start):$limit;
		$model=TBantuanData::model()->findAll(array(
			'select'=>'t.id,td.m_sekolah_text as t_data_rekap_id',
			'join'=>'inner join'.$this->getFormatJoinMSSQL('t_data_rekap','td').' on td.id=t.t_data_rekap_id inner join'.$this->getFormatJoinMSSQL('t_bantuan_program','bp').' on t.t_bantuan_program_id=bp.id',
			'condition'=>'t.deleted=0 and t.t_bantuan_program_id=:id and td.m_sekolah_text like :q and bp.nama like :q1',
			'params'=>array(':id'=>$tbpid,':q'=>'%'.$q.'%',':q1'=>'%'.$q.'%'),
			'limit'=>$limit, 
			'offset'=>$start
		));
		
		
		echo CJSON::encode(array(
                "success" => true,
                "total" => $total,
                "data" => $model
            ));

	}
	public function actionGetDaftarBukanPenerimaBantuan(){
		$tbpid=$_GET['tbpid'];
		$start = (int) $_GET['start'];
        $limit = (int) $_GET['limit'];
		$q=(isset($_GET['q']))?$_GET['q']:'';
		$total=TDataRekap::model()->count(array(
			'join'=>'inner join'.$this->getFormatJoinMSSQL('r_kabupaten','kab').' on left(t.id_kecamatan,4)=kab.id_kab inner join'.$this->getFormatJoinMSSQL('r_propinsi','prop').' on kab.id_prop=prop.id_prop',
			'condition'=>'deleted=0 and m_sekolah_text like :q and id not in(select t_data_rekap_id from '.Yii::app()->params['dbname'].'.[t_bantuan_data] where t_bantuan_program_id=:id)',
			'params'=>array(':q'=>'%'.$q.'%',':id'=>$tbpid)
		));
		$limit=($limit+$start>$total)?($total-$start):$limit;
		$model=TDataRekap::model()->findAll(array(
			'select'=>'t.id,t.m_sekolah_text,prop.propinsi as propinsi_text,kab.kabupaten as kabupaten_text',
			'join'=>'inner join'.$this->getFormatJoinMSSQL('r_kabupaten','kab').' on left(t.id_kecamatan,4)=kab.id_kab inner join'.$this->getFormatJoinMSSQL('r_propinsi','prop').' on kab.id_prop=prop.id_prop',
			'condition'=>'deleted=0 and m_sekolah_text like :q and id not in(select t_data_rekap_id from '.Yii::app()->params['dbname'].'.[t_bantuan_data] where t_bantuan_program_id=:id)',
			'params'=>array(':q'=>'%'.$q.'%',':id'=>$tbpid),
			'limit'=>$limit, 
			'offset'=>$start
		));
		
		echo CJSON::encode(array(
                "success" => true,
                "total" => $total,
                "data" => $model
            ));
	}
	/**
        * Read all table content
    */
        
    public function actionRead(){
            $this->layout = false;
			exit;
            $start = (int) $_GET['start'];
            $limit = (int) $_GET['limit'];
			$q=(isset($_GET['q']))?$_GET['q']:'';
			$t_bantuan_program_id=(isset($_GET['t_bantuan_program_id']) && $_GET['t_bantuan_program_id']<>'')?' and t.t_bantuan_program_id='.$_GET['t_bantuan_program_id']:'';
            //Use this code for complex query
            /*
            $model = Yii::app()->db->createCommand()
                    ->select('*')
                    ->from('table_name')
                    ->offset($start)
                    ->limit($limit)
                    ->queryAll();
            */
            $total = TBantuanData::model()->count(array(
				'join'=>'inner join'.$this->getFormatJoinMSSQL('t_bantuan_program','bp').' on t.t_bantuan_program_id=bp.id inner join'.$this->getFormatJoinMSSQL('t_data_rekap','dr').' on t.t_data_rekap_id=dr.id',
				'condition'=>'t.deleted=0 and bp.deleted=0 and dr.deleted=0 and dr.m_sekolah_text like :q1'.$t_bantuan_program_id,
				'params'=>array(':q1'=>'%'.$q.'%')
			));
			$limit=($limit+$start>$total)?($total-$start):$limit;
            $model = TBantuanData::model()->findAll(array(
				'select'=>'t.id,t.t_data_rekap_id,t.t_bantuan_program_id,t.jumlah_paket,t.tgl_cetak_sk,t.deleted,dr.m_sekolah_text as m_sekolah_text,bp.nama as nama_bantuan',
				'join'=>'inner join'.$this->getFormatJoinMSSQL('t_bantuan_program','bp').' on t.t_bantuan_program_id=bp.id inner join'.$this->getFormatJoinMSSQL('t_data_rekap','dr').' on t.t_data_rekap_id=dr.id',
				'condition'=>'t.deleted=0 and bp.deleted=0 and dr.deleted=0 and dr.m_sekolah_text like :q1'.$t_bantuan_program_id,
				'params'=>array(':q1'=>'%'.$q.'%'),
				'limit'=>$limit, 
				'offset'=>$start
			));
			$model_array=array();
			foreach($model as $d){
				$row=array(
					'id'=>$d->id,
					't_data_rekap_id'=>$d->t_data_rekap_id,
					't_bantuan_program_id'=>$d->t_bantuan_program_id,
					'jumlah_paket'=>$d->jumlah_paket,
					'tgl_cetak_sk'=>$d->tgl_cetak_sk,
					'deleted'=>$d->deleted,
					'm_sekolah_text'=>$d->m_sekolah_text,
					'nama_bantuan'=>$d->nama_bantuan
				);
				$model_array[]=$row;
			}
            
            echo CJSON::encode(array(
                "success" => true,
                "total" => $total,
                "data" => $model_array
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
		$model=TBantuanData::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='tbantuan-data-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
