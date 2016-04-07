<?php
class TBantuanPenggunaanDanaController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column1', meaning
	 * using one-column layout. See 'protected/views/layouts/column1.php'.
	 */
	private $appDb;
        
        public function init() {
			$this->appDb=Yii::app()->params['appDb'];
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
	public function actionGetViewPenggunaanDana(){
		$tbpid=$_GET['tbpid'];
		$tanggal_cutoff=$_GET['tanggal'];
		$status=$_GET['s'];
		$model=TBantuanPenerima::model()->findByPk($tbpid);
		$cutoff=TDataTanggalCutoff::model()->findAll(array(
			'condition'=>'deleted=0 and tanggal=:tgl',
			'params'=>array(':tgl'=>$tanggal_cutoff)
		));
		$next_cutoff=TDataTanggalCutoff::model()->findAll(array(
			'condition'=>'deleted=0 and tanggal>:tgl',
			'params'=>array(':tgl'=>$tanggal_cutoff),
			'order'=>'tanggal asc',
			'limit'=>1
		));
		if(isset($next_cutoff[0]->id)){
			$tgl_next_cutoff=$next_cutoff[0]->tanggal;
		}else{
			/* tahun +1*/
			$datatgl=explode('-',$tanggal_cutoff);
			$tgl_next_cutoff=((int)$datatgl[0]+1).'-'.$datatgl[1].'-'.$datatgl[2];
		}
		$dana=TBantuanPenggunaanDana::model()->findAll(array(
			'join'=>'inner join '.$this->appDb.'.dbo.r_peruntukan_dana_bos rp on rp.id=t.r_peruntukan_dana_bos_id',
			'condition'=>'t.deleted=0 and status_data=:s and t_bantuan_penerima_id=:id and tanggal_transaksi between :tgl_awal and :tgl_akhir',
			'params'=>array(':s'=>$status,':id'=>$model->id,':tgl_awal'=>$tanggal_cutoff,':tgl_akhir'=>$tgl_next_cutoff)
		));
		if(isset($_GET['pdf'])){
			AxHelpers::toPDF('_laporan_penggunaan_dana',array(
				'cutoff'=>$cutoff[0],
				'dana'=>$dana,
				'pdf'=>1
			));
		}else{
			$this->renderPartial('_laporan_penggunaan_dana',array(
				'cutoff'=>$cutoff[0],
				'dana'=>$dana
			));
		}
		
	}
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new TBantuanPenggunaanDana;

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
        }else{
			//$this->debug($model);
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
			$s=(isset($_GET['s']))?$_GET['s']:'(1=1)';
            $user=User::model()->findByPk(Yii::app()->user->id);
			$tbpid=$_GET['tbpid'];
			$model=TBantuanPenerima::model()->findByPk($tbpid);
			
			$wilayah_sekolah=TDataCutoff::model()->findAll(array(
				'condition'=>'sekolah_id=:sid',
				'params'=>array(':sid'=>$model->sekolah_id),
				'limit'=>1
			));
			$wilayah_sekolah=$wilayah_sekolah[0];
			$wilayah_user=($user->kode_kepemilikan!='SP')?$this->getUserWilayah():$wilayah_sekolah->kode_wilayah_sekolah;
			if($user->pemilik_id<>$model->sekolah_id && substr($wilayah_sekolah->kode_wilayah_sekolah,0,strlen($wilayah_user))!=$wilayah_user){
				throw new CHttpException(404,'Maaf, Data yang anda minta diluar data otoritas anda.');
				Yii::app()->end();
			}
            $total = TBantuanPenggunaanDana::model()->count(array(
				'condition'=>'deleted=0 and status_data=:s and t_bantuan_penerima_id=:id and (uraian like :q or satuan_id like :q1 or tanggal_transaksi like :q2)'.$q,
				'params'=>array(':id'=>$model->id,':s'=>$s,':q'=>'%'.$q.'%',':q1'=>'%'.$q.'%',':q2'=>'%'.$q.'%'),
			));
			$limit=($limit+$start>$total)?($total-$start):$limit;
            $model = TBantuanPenggunaanDana::model()->findAll(array(
				'condition'=>'deleted=0 and status_data=:s and t_bantuan_penerima_id=:id and (uraian like :q or satuan_id like :q1 or tanggal_transaksi like :q2)'.$q,
				'params'=>array(':id'=>$model->id,':s'=>$s,':q'=>'%'.$q.'%',':q1'=>'%'.$q.'%',':q2'=>'%'.$q.'%'),
				'limit'=>$limit, 
				'offset'=>$start,
				'order'=>'tanggal_transaksi asc'
			));
			$data=array();
			foreach($model as $d){
				$data_tgl=explode('-',$d->tanggal_transaksi);
				$data[]=array(
					'id'=>$d->id,
					't_bantuan_penerima_id'=>$d->t_bantuan_penerima_id,
					'uraian'=>$d->uraian,
					'qty'=>$d->qty,
					'satuan_id'=>$d->satuan_id,
					'satuan_nama'=>$d->satuan_id,
					'harga_satuan'=>$d->harga_satuan,
					'harga_total'=>$d->harga_total,
					'tanggal_transaksi'=>$d->tanggal_transaksi,
					'bukti_kwitansi'=>$d->bukti_kwitansi,
					'no_bukti'=>$d->no_bukti,
					'status_data'=>$d->status_data,
					'deleted'=>$d->deleted,
					't_bantuan_penerima_nama'=>$d->tBantuanPenerima->sekolah->nama,
					'r_peruntukan_dana_bos_id'=>$d->r_peruntukan_dana_bos_id,
					'r_peruntukan_dana_bos_nama'=>$d->rPeruntukanDanaBos->peruntukan_dana,
					'tanggal_transaksi_bulan'=>(isset($data_tgl[1]))?Yii::app()->params['bulan'][(int)$data_tgl[1]]:'',
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
		$model=TBantuanPenggunaanDana::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='tbantuan-penggunaan-dana-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
