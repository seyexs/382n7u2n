<?php

class TBantuanProgramController extends Controller
{

	/**
	 * @return array action filters
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
	public function allowedActions() {
		return 'read,GetDetailBantuan,GetBantuanByRBantuan,GetBantuanBOS';
	}
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new TBantuanProgram;

                $data = json_decode(stripslashes($_POST['data']));
				if($data){
					foreach ($data as $key => $val) {
						if ($model->hasAttribute($key)) {
							$model->$key = $val;
						}
							
					}
					$userid=Yii::app()->user->id;
					$cek_user=TUserAccessData::model()->findAll(array(
						'condition'=>'deleted=0 and userid=:uid',
						'params'=>array(':uid'=>$userid)
					));
					if(isset($cek_user[0]->id)){
						$model->kode_wilayah=$cek_user[0]->wilayah;
					}else{
						$model->kode_wilayah="";
					}
					if ($model->save()) {
						echo json_encode(array(
							"success" => true,
							"data" => array(
								"id" => $model->id,
							)
						));
					}
				}else{
					
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
            }else{
				$this->debug($model);
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
		$userid=Yii::app()->user->id;
		$cek_user=TUserAccessData::model()->findAll(array(
			'condition'=>'deleted=0 and userid=:uid',
			'params'=>array(':uid'=>$userid)
		));
		if(isset($cek_user[0]->id) && substr($cek_user[0]->wilayah,0,4)!='0000'){
			$isProp=1;
		}else{
			$isProp=0;
		}
		$this->render('index',array(
			'isProp'=>$isProp
		));
                
	}
    public function actionGetDetailBantuan(){
		$tbid=(isset($_POST['tbid']))?$_POST['tbid']:$_GET['tbid'];
		
		$model=$this->loadModel($tbid);
		$persyaratan=TBantuanPersyaratanPenerima::model()->findAll(array(
			'condition'=>'deleted=0 and t_bantuan_program_id=:id',
			'params'=>array(':id'=>$tbid)
		));
		$syarat='';
		foreach($persyaratan as $p){
			$syarat.='<li>'.$p->keterangan.'</li>';
		}
		$datajadwal=TBantuanJadwalKegiatan::model()->findAll(array(
			'condition'=>'deleted=0 and t_bantuan_program_id=:id',
			'params'=>array(':id'=>$tbid)
		));
		$jadwal='';
		foreach($datajadwal as $j){
			$jadwal.='<li>'.$j->kegiatan.' (<b>'.$j->waktu_pelaksanaan.'</b>)</li>';
		}
		
		$tbl='
			<style>
				.detailbantuan tr{border-bottom:1px solid #ccc;}
				.detailbantuan td{padding: 1em;}
				.detailbantuan li{list-style: inherite !important;}
				.odd{/*background:#fafafa;*/}
			</style>
			<table class="detailbantuan" width="100%">
				<tr>
					<td width="35%">Nama Program</td>
					<td width="5%"></td>
					<td>'.$model->nama.'</td>
				</tr>
				<tr>
					<td width="35%">Tahun</td>
					<td width="5%"></td>
					<td>'.$model->tahun.'</td>
				</tr>
				<tr class="odd">
					<td>Tujuan</td>
					<td></td>
					<td>'.$model->tujuan.'</td>
				</tr>
				<tr>
					<td>Sasaran</td>
					<td></td>
					<td>'.$model->sasaran.'</td>
				</tr>
				<tr class="odd">
					<td>Nilai Bantuan</td>
					<td></td>
					<td>Rp.'.number_format($model->nilai_bantuan,2).',- <br>'.$model->keterangan_nilai_bantuan.'</td>
				</tr>
				<tr>
					<td>Pemanfaatan Dana</td>
					<td></td>
					<td>'.$model->pemanfaatan_dana.'</td>
				</tr>
				<tr>
					<td>Persyaratan Penerima Bantuan</td>
					<td></td>
					<td><ol>'.$syarat.'</ol></td>
				</tr>
				<tr>
					<td>Jadwal Kegiatan</td>
					<td></td>
					<td><ol>'.$jadwal.'</ol></td>
				</tr>
			</table>
		';
		echo $tbl;
	}
	public function actionGetBantuanBOS(){
            $this->layout = false;

            $start = (int) $_GET['start'];
            $limit = (int) $_GET['limit'];
			$q=(isset($_GET['q']))?$_GET['q']:'';
			$appDb=Yii::app()->params['appDb'];
            $rbid='90A69530-96AB-40E1-A315-EF7B0A9DF67A';
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
			$total = TBantuanProgram::model()->count(array(
				'condition'=>'deleted=0 and status=0'.$rbid.$filter_wilayah,
			));
			
			$limit=($limit+$start>$total)?($total-$start):$limit;
            $model = TBantuanProgram::model()->findAll(array(
				'condition'=>'deleted=0 and status=0'.$rbid.$filter_wilayah,
				'order'=>'tahun desc',
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
	public function actionGetBantuanByRBantuan(){
            $this->layout = false;

            $start = (int) $_GET['start'];
            $limit = (int) $_GET['limit'];
			$q=(isset($_GET['q']))?$_GET['q']:'';
			$appDb=Yii::app()->params['appDb'];
            $rbid=(isset($_GET['rbid']) && $_GET['rbid']<>'')?' and r_bantuan_id=\''.$_GET['rbid'].'\' ':' ';
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
			$total = TBantuanProgram::model()->count(array(
				'condition'=>'deleted=0 and status=0'.$rbid.$filter_wilayah,
			));
			
			$limit=($limit+$start>$total)?($total-$start):$limit;
            $model = TBantuanProgram::model()->findAll(array(
				'condition'=>'deleted=0 and status=0'.$rbid.$filter_wilayah,
				'order'=>'tahun desc',
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
        * Read all table content
        */
        
        public function actionRead(){
            $this->layout = false;

            $start = (int) $_GET['start'];
            $limit = (int) $_GET['limit'];
            $model = array();
			$q=(isset($_GET['q']))?$_GET['q']:'';
			$q=(isset($_GET['query']))?$_GET['query']:$q;
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

            $total = TBantuanProgram::model()->count(array(
				'select'=>'t.*',
				'join'=>'inner join'.$this->getFormatJoinMSSQL('r_bantuan','rb').' on t.r_bantuan_id=rb.id',
				'condition'=>'t.deleted=0 and (t.tahun like :q1 or t.nama like :q2 or t.keterangan like :q3)'.$filter_wilayah,
				'params'=>array(':q1'=>'%'.$q.'%',':q2'=>'%'.$q.'%',':q3'=>'%'.$q.'%'),
			));
			$limit=($limit+$start>$total)?($total-$start):$limit;
            $model = TBantuanProgram::model()->findAll(array(
				'join'=>'inner join'.$this->getFormatJoinMSSQL('r_bantuan','rb').' on t.r_bantuan_id=rb.id',
				'together'=>true,
				'condition'=>'t.deleted=0 and (t.tahun like :q1 or t.nama like :q2 or t.keterangan like :q3)'.$filter_wilayah,
				'params'=>array(':q1'=>'%'.$q.'%',':q2'=>'%'.$q.'%',':q3'=>'%'.$q.'%'),
				'order'=>'tahun desc',
				'limit'=>$limit, 
				'offset'=>$start
			));
			
			$data=array();
			foreach($model as $m){
				$queryId=TBantuanPersyaratanPenerimaInquery::model()->findAll(array(
					'condition'=>'deleted=0 and t_bantuan_program_id=:id',
					'params'=>array(':id'=>$m->id)
				));
				$data[]=array(
					'id'=>$m->id,
					'tahun'=>$m->tahun,
					'kode'=>$m->kode,
					'nama'=>$m->nama,
					'pengertian'=>$m->pengertian,
					'tujuan'=>$m->tujuan,
					'sasaran'=>$m->sasaran,
					'nilai_bantuan'=>$m->nilai_bantuan,
					'keterangan_nilai_bantuan'=>$m->keterangan_nilai_bantuan,
					'pemanfaatan_dana'=>$m->pemanfaatan_dana,
					'keterangan'=>$m->keterangan,
					'file_doc_sk'=>$m->file_doc_sk,
					'r_bantuan_id'=>$m->r_bantuan_id,
					'r_bantuan_name'=>(isset($m->rBantuan->name))?$m->rBantuan->name:'-',
					'bentuk_bantuan'=>$m->bentuk_bantuan,
					'm_pegawai_nip'=>$m->m_pegawai_nip,
					'm_pegawai_nama'=>(isset($m->mPegawai->nama))?$m->mPegawai->nama:'<belum ditentukan>',
					'r_bantuan_penerima_id'=>$m->r_bantuan_penerima_id,
					'r_bantuan_penerima_nama'=>(isset($m->rBantuanPenerima->nama))?$m->rBantuanPenerima->nama:'-',
					'r_bantuan_penerima_kode'=>(isset($m->rBantuanPenerima->kode))?$m->rBantuanPenerima->kode:'-',
					'deleted'=>$m->deleted,
					't_kuesioner_id'=>(isset($m->t_kuesioner_id))?$m->t_kuesioner_id:0,
					't_bantuan_tim_pengelola_nama'=>$m->t_bantuan_tim_pengelola_nama,
					'tgl_cutoff_data_dapodikmen'=>$m->tgl_cutoff_data_dapodikmen,
					't_bantuan_persyaratan_penerima_inquery_id'=>(isset($queryId[0]->id))?$queryId[0]->id:0
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
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new TBantuanProgram('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['TBantuanProgram']))
			$model->attributes=$_GET['TBantuanProgram'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return TBantuanProgram the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=TBantuanProgram::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param TBantuanProgram $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='tbantuan-program-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
