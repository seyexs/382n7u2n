<?php
class TBantuanPenerimaController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column1', meaning
	 * using one-column layout. See 'protected/views/layouts/column1.php'.
	 */
	
        
    public $dapodikmen;
    public $appDb;    
    public function init() {
		$this->dapodikmen=Yii::app()->params['dapodikmenDb'];
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
            return 'GetDaftarLaporanBantuan,GetDaftarBantuanBySekolah,GetDaftarSekolahCalonPenerima'; 
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
	public function actionProsesDataUsulan(){
		$data=$_POST['data'];
		$data=json_decode($data);
		$t_bantuan_program_id=$_POST['tbid'];
		$status_data=$_POST['status_data'];
		$load=TBantuanProgram::model()->findByPk($t_bantuan_program_id);
		if(isset($load->id,$data)){
			if (strpos($status_data,'SP') !== false) {
				foreach($data as $d){
					$cek=TBantuanPenerima::model()->count(array(
						'condition'=>'deleted=0 and t_bantuan_program_id=:tbid and sekolah_id=:sid',
						'params'=>array(':tbid'=>$load->id,':sid'=>$d)
					));
					if($cek==0){
						$model=new TBantuanPenerima;
						$model->t_bantuan_program_id=$load->id;
						$model->sekolah_id=$d;
						$model->jumlah_bantuan=1;
						$model->save();
					}
				}
			}else if(strpos($status_data,'PD') !== false){
				foreach($data as $d){
					$loadPD=PesertaDidik::model()->findAll(array(
						'condition'=>'peserta_didik_id=:id',
						'params'=>array(':id'=>$d)
					));
					if(isset($loadPD[0]->peserta_didik_id)){
						$sekolah_id=$loadPD[0]->sekolah_id;
					}else{
						continue;
					}
					$cek=TBantuanPenerima::model()->findAll(array(
						'condition'=>'deleted=0 and t_bantuan_program_id=:tbid and sekolah_id=:sid',
						'params'=>array(':tbid'=>$load->id,':sid'=>$sekolah_id)
					));
					
					if(!isset($cek[0]->id)){
						$model=new TBantuanPenerima;
						$model->t_bantuan_program_id=$load->id;
						$model->sekolah_id=$sekolah_id;
						$t_bantuan_penerima_id=$model->save();
					}else{
						$t_bantuan_penerima_id=$cek[0]->id;
					}
					/*insert siswa*/
					//foreach($d->pd as $pd){
						$cek_pd=TBantuanPenerimaSiswa::model()->count(array(
							'condition'=>'deleted=0 and t_bantuan_penerima_id=:tbpid and peserta_didik_id=:pdid',
							'params'=>array(':tbpid'=>$t_bantuan_penerima_id,':pdid'=>$d)
						));
						if($cek_pd==0){
							$model=new TBantuanPenerimaSiswa;
							$model->t_bantuan_penerima_id=$t_bantuan_penerima_id;
							$model->peserta_didik_id=$d;
							$model->save();
						}
					//}
				}
			}
		}
	}
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new TBantuanPenerima;

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
				$model->deleted=1;
				/*delete siswa*/
				$data=TBantuanPenerimaSiswa::model()->findAll(array(
					'condition'=>'deleted=0 and t_bantuan_penerima_id=:id',
					'params'=>array(':id'=>$id)
				));
				foreach($data as $d){
					$m=TBantuanPenerimaSiswa::model()->findByPk($d->id);
					$m->deleted=1;
					$m->save();
				}
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
	public function actionIndexBos(){
		$bantuanOperasionalid='90A69530-96AB-40E1-A315-EF7B0A9DF67A';
		$data=TUserAccessData::model()->findAll(array(
			'condition'=>'deleted=0 and userid=:id',
			'params'=>array(':id'=>Yii::app()->user->id)
		));
		if(isset($data[0]->wilayah))
			$this->render('indexBos',array(
				'rbid'=>$bantuanOperasionalid,
				'kode_prop'=>$data[0]->wilayah
			));
	}
	/**
        * Read all table content
    */
        
    public function actionRead(){
			set_time_limit(0);
            $this->layout = false;
			$tbid=(isset($_GET['tbid']))?$_GET['tbid']:'';
			if(isset($_GET['excel'])){
				$bantuan=TBantuanProgram::model()->findByPk($tbid);
				$jenispenerima=$bantuan->rBantuanPenerima->kode;
				$appDb=Yii::app()->params['appDb'];
				$dapodikmenDb=Yii::app()->params['dapodikmenDb'];
				$penerima=Yii::app()->db->createCommand('
					select prop.nama as \'provinsi\',kab.nama as \'kab\',s.nama,t.jumlah_bantuan,t.jumlah_dana,t.id
					from '.$appDb.'.dbo.t_bantuan_penerima t inner join '.$dapodikmenDb.'.dbo.sekolah s on s.sekolah_id=t.sekolah_id
					inner join (select * from '.$dapodikmenDb.'.ref.mst_wilayah where id_level_wilayah=2) as kab on left(s.kode_wilayah,4)+\'00\'=kab.kode_wilayah
					inner join '.$dapodikmenDb.'.ref.mst_wilayah prop on prop.kode_wilayah=kab.mst_kode_wilayah
					where t.t_bantuan_program_id='.$bantuan->id.' and t.deleted=0
					order by prop.nama,kab.nama
					')->queryAll();
				//print_r($penerima);exit;
				/*$penerima=TBantuanPenerima::model()->findAll(array(
					'condition'=>'deleted=0 and t_bantuan_program_id=:id',
					'params'=>array(':id'=>$bantuan->id)
				));*/
				AxHelpers::toExcel('view_excel_penerima',array(
					'bantuan'=>$bantuan,
					'penerima'=>$penerima,
					'jenispenerima'=>$penerima
				),'longlist_penerima_bantuan.xls');
				
			}else{
				$start = (int) $_GET['start'];
				$limit = (int) $_GET['limit'];
				$q=(isset($_GET['q']))?$_GET['q']:'';
				$dapodikDB=Yii::app()->params['dapodikmenDb'];
				$total = TBantuanPenerima::model()->count(array(
					'join'=>'inner join '.$dapodikDB.'.dbo.sekolah s on t.sekolah_id=s.sekolah_id',
					'condition'=>'t.deleted=0 and t.t_bantuan_program_id=:tbid and s.nama like :q',
					'params'=>array(':tbid'=>$tbid,':q'=>'%'.$q.'%')
				));
				$limit=($limit+$start>$total)?($total-$start):$limit;
				$appDb=Yii::app()->params['appDb'];
				$dapodikmenDb=Yii::app()->params['dapodikmenDb'];
				$penerima=Yii::app()->db->createCommand('
					select prop.nama as \'provinsi\',kab.nama as \'kab\',s.nama,t.jumlah_bantuan,t.jumlah_dana,t.id,t.t_bantuan_program_id
					from '.$appDb.'.dbo.t_bantuan_penerima t inner join '.$dapodikmenDb.'.dbo.sekolah s on s.sekolah_id=t.sekolah_id
					inner join (select * from '.$dapodikmenDb.'.ref.mst_wilayah where id_level_wilayah=2) as kab on left(s.kode_wilayah,4)+\'00\'=kab.kode_wilayah
					inner join '.$dapodikmenDb.'.ref.mst_wilayah prop on prop.kode_wilayah=kab.mst_kode_wilayah
					where t.t_bantuan_program_id='.$tbid.' and t.deleted=0 and s.nama like \'%'.$q.'%\'
					order by prop.nama,kab.nama
					')->queryAll();
				/*$model = TBantuanPenerima::model()->findAll(array(
					'join'=>'inner join '.$dapodikDB.'.dbo.sekolah s on t.sekolah_id=s.sekolah_id',
					'condition'=>'deleted=0 and t_bantuan_program_id=:tbid and s.nama like :q',
					'params'=>array(':tbid'=>$tbid,':q'=>'%'.$q.'%'),
					'limit'=>$limit, 
					'offset'=>$start
				));*/
				$data=array();
				foreach($penerima as $d){
					/*$data[]=array(
						'id'=>$d->id,
						't_bantuan_program_id'=>$d->t_bantuan_program_id,
						'nama_sekolah'=>$d->sekolah->nama,
						//'nama_bantuan'=>$d->tBantuanProgram->nama,
						'jumlah_bantuan'=>$d->jumlah_bantuan,
						'jumlah_dana'=>$d->jumlah_dana
					);*/
					$data[]=array(
						'id'=>$d['id'],
						't_bantuan_program_id'=>$d['t_bantuan_program_id'],
						'nama_sekolah'=>$d['nama'],
						'jumlah_bantuan'=>$d['jumlah_bantuan'],
						'jumlah_dana'=>$d['jumlah_dana'],
						'prop'=>$d['provinsi'],
						'kab'=>$d['kab']
					);
				}
				echo CJSON::encode(array(
					"success" => true,
					"total" => $total,
					"data" => $data
				));
			}
            Yii::app()->end();
    }
	public function actionGetDaftarBantuanBySekolah(){
		$sekolah_id=$_POST['sid'];
		//echo $sekolah_id;exit;
		$bantuanOperasionalid='90A69530-96AB-40E1-A315-EF7B0A9DF67A';
		if(isset($sekolah_id)){
			/*$daftarBantuan=TBantuanPenerima::model()->findAll(array(
				'condition'=>'deleted=0 and CONVERT(NVARCHAR(32),HashBytes(\'MD5\',CONVERT(NVARCHAR(36),sekolah_id)),2)=:sid',
				'params'=>array(':sid'=>$sekolah_id)
			));*/
			$daftarBantuan=TBantuanPenerima::model()->findAll(array(
				'condition'=>'deleted=0 and sekolah_id=:sid',
				'params'=>array(':sid'=>$sekolah_id)
			));
			$data=array();
			foreach($daftarBantuan as $d){
				$data[]=array(
					'nama'=>$d->tBantuanProgram->nama,
					'tahun'=>$d->tBantuanProgram->tahun,
					'jenis'=>$d->tBantuanProgram->rBantuan->name,
					'tbid'=>$d->t_bantuan_program_id,
					'id'=>$d->id,
					'isBOS'=>($d->tBantuanProgram->r_bantuan_id==$bantuanOperasionalid)?1:0
					
				);
			}
			echo CJSON::encode(array(
				'success'=>true,
				'data'=>$data
			));
		}
	}
	public function actionGetDaftarBantuanSekolah(){
		$user=User::model()->findByPk(Yii::app()->user->id);
		if($user->kode_kepemilikan=='SP' && $user->pemilik_id!=''){
			$daftarBantuan=TBantuanPenerima::model()->findAll(array(
				'condition'=>'deleted=0 and sekolah_id=:sid',
				'params'=>array(':sid'=>$user->pemilik_id)
			));
			$data=array();
			foreach($daftarBantuan as $d){
				$data[]=array(
					'nama'=>$d->tBantuanProgram->nama,
					'tahun'=>$d->tBantuanProgram->tahun,
					'jenis'=>$d->tBantuanProgram->rBantuan->name,
					'tbid'=>$d->t_bantuan_program_id,
					'id'=>$d->id
					
				);
			}
			echo CJSON::encode(array(
				'success'=>true,
				'data'=>$data
			));
		}
	}
	public function actionGetDaftarLaporanBantuan(){
		$tbpid=$_POST['tbpid'];
		$model=TBantuanPenerima::model()->findByPk($tbpid);
		$user=User::model()->findByPk(Yii::app()->user->id);
		
		if($user->pemilik_id==$model->sekolah_id){
			$daftarLap=RBantuanDaftarPelaporan::model()->findAll(array(
				'condition'=>'deleted=0 and r_bantuan_id=:rbid',
				'params'=>array(':rbid'=>$model->tBantuanProgram->r_bantuan_id)
			));
			echo CJSON::encode(array(
				'success'=>true,
				'data'=>$daftarLap
			));
			
		}else{
			$wilayah_user=$this->getUserWilayah();
			$wilayah_sekolah=TDataCutoff::model()->findAll(array(
				'condition'=>'sekolah_id=:sid',
				'params'=>array(':sid'=>$model->sekolah_id),
				'limit'=>1
			));
			$wilayah_sekolah=$wilayah_sekolah[0];
			if(substr($wilayah_sekolah->kode_wilayah_sekolah,0,strlen($wilayah_user))==$wilayah_user){
				$daftarLap=RBantuanDaftarPelaporan::model()->findAll(array(
					'condition'=>'deleted=0 and r_bantuan_id=:rbid',
					'params'=>array(':rbid'=>$model->tBantuanProgram->r_bantuan_id)
				));
				echo CJSON::encode(array(
					'success'=>true,
					'data'=>$daftarLap
				));
			}
		}
	}
	public function actionSubmitExcel(){
		set_time_limit(0);
		$success=0;
		//upload data hanya untuk user level akses 3 (MKKS Kab/Kota)
		if($_FILES['file_upload_excel_penerima']['size'] != 0){
			$file_upload_excel_penerima = $_FILES['file_upload_excel_penerima'];
			$allowedExts = array("xls");
			$extension = end(explode(".", $_FILES["file_upload_excel_penerima"]["name"]));

			//mencetak error tidak boleh mengupload file dengan ekstensi selain yang disebutkan diatas
			if (!in_array($extension, $allowedExts)) {
				echo '{success:false, errors:[], message: "Format tidak diperbolehkan"}';
				return;
			}
				
			$file_to_upload = $file_upload_excel_penerima['tmp_name'];
			$file_name = $file_upload_excel_penerima['name'];
			$user_id=Yii::app()->user->id;
			$file_path = Yii::app()->params['dirTemp'];
			$file_full_path = Yii::getPathOfAlias('webroot').'/'.$file_path .'/u-'. $user_id;
			if(!is_dir($file_full_path)){
				mkdir($file_full_path, 0777, true);
			}
			$file_full_path.='/Upload_data_penerima.'. $extension;
			$success=move_uploaded_file($file_to_upload, $file_full_path);
				
			if($success){
				$data_sekolah_berhasil_update=$this->readExcel($file_full_path);
			}
				
		}
		echo CJSON::encode(array(
				'success'=>$success,
				'total'=>count($data_sekolah_berhasil_update),
				'message'=>count($data_sekolah_berhasil_update).' Berhasil Di Perbarui!',
				'data_update'=>$data_sekolah_berhasil_update
			));
	}
	private function readExcel($SourceFile) {
		
		set_time_limit(0);
		$start_baris_data = 6;
		$start_colom_data = 2;
		$skip_column=array(3,4,5);
		$sheet_data=0;
		$tempVar="";
		Yii::app()->session['variablee']='';
		$variable = array(
			'no',
			'id',
			'provinsi',
			'kabupaten',
			'nama_sekolah',
			'jumlah_bantuan',
			'jumlah_dana',
		);

		//$kolom_wajib = array(3, 4,8); //id_kec/nama_sekolah/jmlpendaftar
		$kolom_wajib = array(2,6,7); //nama_sekolah/katergori
		//foreach ($arraySourceFile as $d) {
			$src=$SourceFile;
			if ($src == "")
				exit;
			if(! is_readable($src))
				echo "read not ok!\n";
			try{
				$data = new JPhpExcelReader($src);
				$backupData=new JPhpExcelReader($src);
			}catch(Exception $e){
				continue;
			}
			
			if (count($data) > 0) {
				try{
					$ada_gagal = 0;
					$str_param = "";
					$row_exec = 0;
					$total_sudah_ditemukan=0;
					$data_sekolah_berhasil_update=array();
					for ($i = $start_baris_data; $i <= $data->sheets[$sheet_data]['numRows']; $i++) {
						$model=new TBantuanPenerima;
						if($total_sudah_ditemukan==1)
							break;
							
						$str_param = "";
						$counter_kolom=$start_colom_data;
						for ($j = $start_colom_data; $j <= 7; $j++) {
							$isi = (isset($data->sheets[$sheet_data]['cells'][$i][$j])) ? $data->sheets[$sheet_data]['cells'][$i][$j] : "0";
							//load model pada kolom index ke 2 yaitu id
							if($j==2){
								$model=TBantuanPenerima::model()->findByPk($isi);
								//jika data tidak ditemukan,maka skip row,jika ditemukan langsung lanjut ke kolom selanjutnya
								if(!isset($model->id)){
									break;
								}else{
									continue;
								}
								
							}
							//echo $isi;
							/*column yg diskip*/
							if(in_array($j,$skip_column)){
								continue; //lanjut ke kolom selanjutnya
							}
							/* colum yg wajib */
							if (in_array($j, $kolom_wajib)) {
								if ($isi == "") {
									/* lewati baris ini */
									$str_param = "";
									//echo "\nbaris " . $i . " kolom " . $j . " dilewatn";
									break; //krn data yang wajib tidak lengkap jadi harus stop dan lanjut ke baris berikutnya
								}
							}
							$isi=($isi=="")?"0":$isi;
							if ($model->hasAttribute($variable[($j-1)])) {
								$model->$variable[($j-1)]=$isi;
							}
						}
						
						if($model->save()){
							//echo "\n saving data oke=".$model->id;
							$data_sekolah_berhasil_update[]=array('id'=>$model->id);
							unset($model);
							unset($isi);
						}
						
						
						
					}
					/* update status read */
					//$this->updateStatusRead($baseUrl, $ada_gagal, $d->id);
					//echo "\nFile '" . $src . "':  " . $row_exec . " rows run successfull!";
					//echo "\n=====================================\n";
				}catch(Exception $e){
					$this->debug($model);
				}
			}
		//}
		return $data_sekolah_berhasil_update;
	}
	public function actionGetDaftarSekolahCalonPenerima(){
            $this->layout = false;
			$tbid=$_GET['tbid'];
            $start = (int) $_GET['start'];
            $limit = (int) $_GET['limit'];
			$q=(isset($_GET['q']))?$_GET['q']:'';
			$userid=Yii::app()->user->id;
			$cek_user=TUserAccessData::model()->findAll(array(
				'condition'=>'deleted=0 and userid=:uid',
				'params'=>array(':uid'=>$userid)
			));
			$filter_data='';
			if(isset($cek_user[0]->id)){
				$filter_data=" and t.kode_wilayah like '".$cek_user[0]->wilayah."%'";
			}else if(isset($_GET['kw'])){
				//filter saat penambahan data penerima bantuan baru
				$filter_data=" and t.kode_wilayah like '".substr($_GET['kw'],0,4)."%'"; 
			}
			$total=Sekolah::model()->count(array(
				'join'=>'inner join '.$this->dapodikmen.'.ref.mst_wilayah kab on left(t.kode_wilayah,4)+\'00\'=kab.kode_wilayah
						inner join '.$this->dapodikmen.'.ref.mst_wilayah prop on left(t.kode_wilayah,2)+\'0000\'=prop.kode_wilayah',
				'condition'=>'t.nama like :q and t.bentuk_pendidikan_id=15 and t.soft_delete=0 
							'.$filter_data.' and t.sekolah_id not in(select sekolah_id from '.$this->appDb.'.dbo.t_bantuan_penerima where t_bantuan_program_id=:tbid and deleted=0)',
				'params'=>array(':q'=>'%'.$q.'%',':tbid'=>$tbid),
			));
			$limit=($limit+$start>$total)?($total-$start):$limit;
			$model=Sekolah::model()->findAll(array(
				'select'=>'prop.nama as propinsi,kab.nama as kabupaten,t.*',
				'join'=>'inner join '.$this->dapodikmen.'.ref.mst_wilayah kab on left(t.kode_wilayah,4)+\'00\'=kab.kode_wilayah
						inner join '.$this->dapodikmen.'.ref.mst_wilayah prop on left(t.kode_wilayah,2)+\'0000\'=prop.kode_wilayah',
				'condition'=>'t.nama like :q and t.bentuk_pendidikan_id=15 and t.soft_delete=0 
							'.$filter_data.' and t.sekolah_id not in(select sekolah_id from '.$this->appDb.'.dbo.t_bantuan_penerima where t_bantuan_program_id=:tbid and deleted=0)',
				'params'=>array(':q'=>'%'.$q.'%',':tbid'=>$tbid),
				'order'=>'propinsi,kabupaten asc',
				'limit'=>$limit,
				'offset'=>$start
			));
			$data=array();
			foreach($model as $d){
				$record=array();
				foreach($d as $col=>$v)
					$record[$col]=$v;
					
				$data[]=array_merge($record,array(
					'propinsi'=>$d->propinsi,
					'kabupaten'=>$d->kabupaten,
				));
			}
            /*$model =Yii::app()->db->createCommand('
				select s.sekolah_id,s.nama,
				case when s.status_sekolah=\'2\' then \'Negeri\' else \'Swasta\' end as status_sekolah,
				s.nomor_fax,s.email,s.website,
				s.alamat_jalan,s.npsn,s.nomor_telepon, prop.nama as propinsi,kab.nama as kabupaten from '.$this->dapodikmen.'.dbo.sekolah s 
				inner join '.$this->dapodikmen.'.ref.mst_wilayah kab on left(s.kode_wilayah,4)+\'00\'=kab.kode_wilayah
				inner join '.$this->dapodikmen.'.ref.mst_wilayah prop on left(s.kode_wilayah,2)+\'0000\'=prop.kode_wilayah
				where 1=1'.$filter_data.' and s.nama like \'%'.$q.'%\' and s.bentuk_pendidikan_id=15 
				and s.sekolah_id not in(select sekolah_id from t_bantuan_penerima where t_bantuan_program_id='.$tbid.' and deleted=0)
				order by prop.nama,kab.nama asc
			')->queryAll();
            $total = count($model);*/
            echo CJSON::encode(array(
                "success" => true,
                "total" => $total,
                "data" => $data
            ));

            Yii::app()->end();
    }
	public function actionTambahDaftarPenerimaBantuanSiswa(){
		$sekolah_id=$_POST['skid'];
		$tbid=$_POST['tbid'];
		$data=CJSON::decode($sekolah_id);
		//print_r($data);exit;
		foreach($data as $s){
			$cek=TBantuanPenerima::model()->findAll(array(
				'condition'=>'deleted=0 and sekolah_id=:s and t_bantuan_program_id=:tbid',
				'params'=>array(':s'=>$s[0],':tbid'=>$tbid)
			));
			if(isset($cek[0]->id)){
				$t_bantuan_penerima_id=$cek[0]->id;
			}else{
				$model=new TBantuanPenerima;
				$model->t_bantuan_program_id=$tbid;
				$model->sekolah_id=$s[0];
				$model->jumlah_bantuan=0;
				$model->jumlah_dana=0;
				$model->save();
				$t_bantuan_penerima_id=$model->id;
			}
		
			$ceksiswa=TBantuanPenerimaSiswa::model()->count(array(
				'condition'=>'deleted=0 and t_bantuan_penerima_id=:id and peserta_didik_id=:pdid',
				'params'=>array(':id'=>$t_bantuan_penerima_id,':pdid'=>$s[1])
			));
			if($ceksiswa==0){
				$siswa=new TBantuanPenerimaSiswa;
				$siswa->t_bantuan_penerima_id=$t_bantuan_penerima_id;
				$siswa->peserta_didik_id=$s[1];
				$siswa->save();
				
				/*update jumlah penerima pada data penerima sekolah*/
				$update=TBantuanPenerima::model()->findByPk($t_bantuan_penerima_id);
				$update->jumlah_bantuan=intval($update->jumlah_bantuan)+1;
				$update->save();
			}
		}
	}
	public function actionTambahDaftarPenerimaBantuan(){
		$sekolah_id=$_POST['skid'];
		$tbid=$_POST['tbid'];
		$data=CJSON::decode($sekolah_id);
		foreach($data as $s){
			$cek=TBantuanPenerima::model()->count(array(
				'condition'=>'deleted=0 and sekolah_id=:s and t_bantuan_program_id=:tbid',
				'params'=>array(':s'=>$s,':tbid'=>$tbid)
			));
			if($cek>0)
				continue;
			
			$model=new TBantuanPenerima;
			$model->t_bantuan_program_id=$tbid;
			$model->sekolah_id=$s;
			$model->jumlah_bantuan=1;
			$model->jumlah_dana=0;
			$model->save();
		}
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=TBantuanPenerima::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='tbantuan-penerima-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
