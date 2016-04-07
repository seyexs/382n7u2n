<?php
class UsulanBosController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column1', meaning
	 * using one-column layout. See 'protected/views/layouts/column1.php'.
	 */
	
    public $jmlRowData=0;   
	public $dapodikmen;
	public $appDb;
    public function init() {
		$this->dapodikmen=Yii::app()->params['dapodikmenDb'];
		$this->appDb=Yii::app()->params['appDb'];
		set_time_limit(0);
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
		return 'ReadPropinsi,ReadKabupaten,ReadSekolah,ExportExcel,checkPersetujuan,GetTanggalCutOff'; 
    }
	public function actionGetRekapNoRekeningSekolah(){
		$model=TUserAccessData::model()->findAll(array(
			'condition'=>'deleted=0 and userid=:id',
			'params'=>array(':id'=>Yii::app()->user->id)
		));
		if(isset($model[0]->id)){
				$wil=$model[0]->wilayah;
				$nol=(strlen($wil)==2)?'0000':'00';
				$data=Yii::app()->db->createCommand('
					select w.nama as propinsi,
					(select nama from '.$this->dapodikmen.'.ref.mst_wilayah where kode_wilayah=left(s.kode_wilayah,4)+\'00\') as kabupaten,
					(select kode_wilayah from '.$this->dapodikmen.'.ref.mst_wilayah where kode_wilayah=left(s.kode_wilayah,4)+\'00\') as kode_kab,
					s.nss,s.nama as nama,
					s.sekolah_id,s.rekening_atas_nama,s.no_rekening,s.nama_bank,s.cabang_kcp_unit, w.kode_wilayah 
					from '.$this->dapodikmen.'.dbo.sekolah s 
					inner join '.$this->dapodikmen.'.ref.mst_wilayah w on left(s.kode_wilayah,2)=left(w.kode_wilayah,2)
					where s.bentuk_pendidikan_id=15 and s.Soft_delete=0 and w.kode_wilayah=\''.$wil.$nol.'\'
					order by w.kode_wilayah,kode_kab
				')->queryAll();
				if(count($data)){
					if(isset($_POST['pdf'])){
						/*AxHelpers::toPDF('_rekap_norek',array(
							'data'=>$data,
							'pdf'=>1
						));*/
						AxHelpers::toExcel('_rekap_norek_xls',array(
							'data'=>$data,
							'pdf'=>1
						),'RekapitulasiNoRekening.xls');
					}else{
						$this->renderPartial('_rekap_norek',array(
							'data'=>$data
						));
					}
				}
		}
	}
	public function actionGetPaktaIntegritasPropinsi(){
		$tbid=$_POST['tbid'];
		$tanggal=$_POST['tanggal'];
		$model=TBantuanProgram::model()->findByPk($tbid);
		$tahun=$model->tahun;
		$tgl_cutoff=TDataTanggalCutOff::model()->findAll(array(
			'condition'=>'deleted=0 and tanggal=:tgl',
			'params'=>array(':tgl'=>$tanggal)
		));
		if(isset($tgl_cutoff[0]->id)){
			$user_wilayah=$this->getUserWilayah().'0000';
			if(isset($_POST['pdf'])){
				AxHelpers::toExcel('_pakta_integritas_propinsi',array(
					'data'=>Yii::app()->session['data_pakta_integritas_prop_'.$tanggal],
					'model'=>$model,
					'pdf'=>1
				),'Data_rekap_bos_cut_off'.$tanggal.'.xls');
			
			
			}else{
				$data=Yii::app()->db->createCommand('
						select * from bosGetPaktaIntegritasProvinsi(\''.$user_wilayah.'\',\''.$tanggal.'\',\'\',\''.$tgl_cutoff[0]->keterangan_periode.'\')
						
					')->queryAll();
				Yii::app()->session['data_pakta_integritas_prop_'.$tanggal]=$data;
				$this->renderPartial('_pakta_integritas_propinsi',array(
					'data'=>$data,
					'model'=>$model
				));
			}
		}
	}
	public function actionGetTanggalCutOff(){
		$tbid=$_POST['tbid'];
		$model=TBantuanProgram::model()->findByPk($tbid);
		$tahun=$model->tahun;
		$tgl_cutoff=TDataTanggalCutOff::model()->findAll(array(
			'condition'=>'(year(tanggal)=:thn or (year(tanggal)=:thm1 and month(tanggal)=12)) and deleted=0',
			'params'=>array(':thn'=>$tahun,':thm1'=>($tahun-1)),
			'order'=>'tanggal asc'
		));
		echo CJSON::encode(array(
			'success'=>true,
			'data'=>$tgl_cutoff
		));
	}
	public function actionGetDataPaktaIntegritasSekolah(){
		$tgl=$_GET['tanggal'];
		$tbpid=$_GET['tbpid'];
		$model=TBantuanPenerima::model()->findByPk($tbpid);
		$sid=$model->sekolah_id;
		$tgl_cutoff=TDataTanggalCutOff::model()->findAll(array(
			'condition'=>'tanggal <=:tgl and deleted=0',
			'params'=>array(':tgl'=>$tgl),
			'order'=>'tanggal asc'
		));
		$data=array();
		foreach($tgl_cutoff as $t){
			$sekolah=Yii::app()->db->createCommand('
					select  s.*,\''.$t->tanggal.'\' as tgl_cut_off,\''.$t->keterangan_periode.'\' as keterangan_periode,prop.nama as propinsi,kab.nama as kabupaten,kec.nama as kecamatan,
					(select top 1 ptk.nama from '.$this->dapodikmen.'.dbo.ptk inner join '.$this->dapodikmen.'.dbo.tugas_tambahan tt on tt.ptk_id=ptk.ptk_id where tt.sekolah_id=s.sekolah_id and tt.jabatan_ptk_id=2 and year(tmt_tambahan) between (year(getdate())-5) and year(getdate()) and tt.Soft_delete=0 and ptk.Soft_delete=0 order by tt.tmt_tambahan desc) as kepala_sekolah,
					(select count(*) from '.$this->appDb.'.dbo.t_data_cutoff where deleted=0 and sekolah_id=s.sekolah_id and created_date=\''.$t->tanggal.'\' and tingkat_pendidikan_id=10 and t_bantuan_penerima_id='.$tbpid.') as siswa_10,
					(select count(*) from '.$this->appDb.'.dbo.t_data_cutoff where deleted=0 and sekolah_id=s.sekolah_id and created_date=\''.$t->tanggal.'\' and tingkat_pendidikan_id=11 and t_bantuan_penerima_id='.$tbpid.') as siswa_11,
					(select count(*) from '.$this->appDb.'.dbo.t_data_cutoff where deleted=0 and sekolah_id=s.sekolah_id and created_date=\''.$t->tanggal.'\' and tingkat_pendidikan_id=12 and t_bantuan_penerima_id='.$tbpid.') as siswa_12,
					(select count(*) from '.$this->appDb.'.dbo.t_data_cutoff where deleted=0 and sekolah_id=s.sekolah_id and created_date=\''.$t->tanggal.'\' and tingkat_pendidikan_id=13 and t_bantuan_penerima_id='.$tbpid.') as siswa_13
					
					from '.$this->dapodikmen.'.dbo.sekolah s 
					inner join '.$this->dapodikmen.'.ref.mst_wilayah kab on left(s.kode_wilayah,4)+\'00\'=kab.kode_wilayah
					inner join '.$this->dapodikmen.'.ref.mst_wilayah prop on left(s.kode_wilayah,2)+\'0000\'=prop.kode_wilayah
					inner join '.$this->dapodikmen.'.ref.mst_wilayah kec on left(s.kode_wilayah,6)=kec.kode_wilayah
					where s.sekolah_id=\''.$sid.'\'
				')->queryAll();
			$data[]=$sekolah[0];
		}
		if(isset($_GET['pdf'])){
			AxHelpers::toPDF('_pakta_integritas',array(
				'data'=>$data,
				'model'=>$model,
				'pdf'=>1
			));
		}else{
			$this->renderPartial('_pakta_integritas',array(
				'data'=>$data,
				'model'=>$model
			));
		}
		
	}
	public function actionGetPaktaIntegritas(){
		$tbpid=$_POST['tbpid'];
		$model=TBantuanPenerima::model()->findByPk($tbpid);
		$sid=$model->sekolah_id;
		$tahun=$model->tBantuanProgram->tahun;
		$tgl_cutoff=TDataTanggalCutOff::model()->findAll(array(
			'condition'=>'(year(tanggal)=:thn or (year(tanggal)=:thm1 and month(tanggal)=12)) and deleted=0',
			'params'=>array(':thn'=>$tahun,':thm1'=>($tahun-1)),
			'order'=>'tanggal asc'
		));
		$data=array();
		foreach($tgl_cutoff as $t){
			$sekolah=Yii::app()->db->createCommand('
					select  s.*,\''.$t->tanggal.'\' as tgl_cut_off,\''.$t->keterangan_periode.'\' as keterangan_periode,prop.nama as propinsi,kab.nama as kabupaten,kec.nama as kecamatan,
					(select count(*) from '.$this->appDb.'.dbo.t_data_cutoff where deleted=0 and sekolah_id=s.sekolah_id and created_date=\''.$t->tanggal.'\' and t_bantuan_penerima_id='.$tbpid.') as jml_siswa
					from '.$this->dapodikmen.'.dbo.sekolah s 
					inner join '.$this->dapodikmen.'.ref.mst_wilayah kab on left(s.kode_wilayah,4)+\'00\'=kab.kode_wilayah
					inner join '.$this->dapodikmen.'.ref.mst_wilayah prop on left(s.kode_wilayah,2)+\'0000\'=prop.kode_wilayah
					inner join '.$this->dapodikmen.'.ref.mst_wilayah kec on left(s.kode_wilayah,6)=kec.kode_wilayah
					where s.sekolah_id=\''.$sid.'\'
				')->queryAll();
			$data[]=$sekolah[0];
		}
		echo CJSON::encode(array(
			'success'=>true,
			'data'=>$data
		));
		

		
	}
	public function actionGetLaporanAwal(){
		$tbpid=$_POST['tbpid'];
		$model=TBantuanPenerima::model()->findByPk($tbpid);
		$sid=$model->sekolah_id;
		$sekolah=Yii::app()->db->createCommand('
				select  s.*, prop.nama as propinsi,kab.nama as kabupaten,
				(select top 1 ptk.nama from '.$this->dapodikmen.'.dbo.ptk inner join '.$this->dapodikmen.'.dbo.tugas_tambahan tt on tt.ptk_id=ptk.ptk_id where tt.sekolah_id=s.sekolah_id and tt.jabatan_ptk_id=2 and year(tmt_tambahan) between (year(getdate())-5) and year(getdate()) and tt.Soft_delete=0 and ptk.Soft_delete=0 order by tt.tmt_tambahan desc) as kepala_sekolah,
				(select top 1 ptk.nip from '.$this->dapodikmen.'.dbo.ptk inner join '.$this->dapodikmen.'.dbo.tugas_tambahan tt on tt.ptk_id=ptk.ptk_id where tt.sekolah_id=s.sekolah_id and tt.jabatan_ptk_id=2 and year(tmt_tambahan) between (year(getdate())-5) and year(getdate()) and tt.Soft_delete=0 and ptk.Soft_delete=0 order by tt.tmt_tambahan desc) as nip_kepala_sekolah
				from '.$this->dapodikmen.'.dbo.sekolah s 
				inner join '.$this->dapodikmen.'.ref.mst_wilayah kab on left(s.kode_wilayah,4)+\'00\'=kab.kode_wilayah
				inner join '.$this->dapodikmen.'.ref.mst_wilayah prop on left(s.kode_wilayah,2)+\'0000\'=prop.kode_wilayah
				where s.sekolah_id=\''.$sid.'\'
			')->queryAll();
		if(isset($_POST['pdf'])){
			AxHelpers::toPDF('_laporan_awal',array(
				'model'=>$model,
				'sekolah'=>$sekolah[0],
				'pdf'=>1
			));
		}else{
			$this->renderPartial('_laporan_awal',array(
				'model'=>$model,
				'sekolah'=>$sekolah[0]
			));
		}
		

		
	}
	public function actionRead(){
		$this->layout = false;

        $start = (int) $_GET['start'];
        $limit = (int) $_GET['limit'];
		$q=(isset($_GET['q']))?$_GET['q']:'';
		$appDb=Yii::app()->params['appDb'];
		$data=Yii::app()->db->createCommand('
				select * from '.$this->dapodikmen.'.dbo.sekolah
			')->queryAll();
        $total = count($data);
		$limit=($limit+$start>$total)?($total-$start):$limit;
        $model =Yii::app()->db->createCommand('
				select * from '.$this->dapodikmen.'.dbo.sekolah
			')->queryAll();

            
        echo CJSON::encode(array(
            "success" => true,
            "total" => $total,
            "data" => $model
        ));

        Yii::app()->end();
	}
	public function actionReadSummaryPropinsi(){
		$this->layout = false;
		
		$t_bantuan_program_id=$_GET['tbid'];
		$model=TBantuanProgram::model()->findByPk($t_bantuan_program_id);
		$tgl_cutoff=$model->tgl_cutoff_data_dapodikmen;
		
        $start = (int) $_GET['start'];
        $limit = (int) $_GET['limit'];
		$q=(isset($_GET['q']))?$_GET['q']:'';
		$dapodikmenDb=Yii::app()->params['dapodikmenDb'];
		$userid=Yii::app()->user->id;
		$cek_user=TUserAccessData::model()->findAll(array(
			'condition'=>'deleted=0 and userid=:uid',
			'params'=>array(':uid'=>$userid)
		));
		if(isset($cek_user[0]->id)){
			$filter_wilayah=" and kode_wilayah like '".$cek_user[0]->wilayah."%'";
		}else{
			$filter_wilayah=" and (kode_wilayah='' or kode_wilayah is null)";
		}
		$data=Yii::app()->db->createCommand('
				select w.nama as propinsi,
				(
				select count(*) from '.$dapodikmenDb.'.ref.mst_wilayah where mst_kode_wilayah=w.kode_wilayah
				) as kabupaten,
				(
				select count(*)
				from '.$dapodikmenDb.'.dbo.sekolah
				where left(kode_wilayah,2)=left(w.kode_wilayah,2) and Soft_delete=0
				GROUP BY left(kode_wilayah,2)
				) as jumlah_sekolah,
				(
				select count(*) from '.$dapodikmenDb.'.dbo.peserta_didik p inner join '.$dapodikmenDb.'.dbo.sekolah s on p.sekolah_id=s.sekolah_id
				where s.Soft_delete=0 and left(s.kode_wilayah,2)=left(w.kode_wilayah,2) and p.Soft_delete=0
				) as jumlah_siswa
				from '.$dapodikmenDb.'.ref.mst_wilayah w
				where w.id_level_wilayah=1'.$filter_wilayah.'
			')->queryAll();
        $total = count($data);
		$limit=($limit+$start>$total)?($total-$start):$limit;


            
        echo CJSON::encode(array(
            "success" => true,
            "total" => $total,
            "data" => $data
        ));

        Yii::app()->end();
	}
	
	public function actionReadPropinsi(){
		//set_time_limit(0);
		$this->layout = false;
		
		$t_bantuan_program_id=$_GET['tbid'];
		$model=TBantuanProgram::model()->findByPk($t_bantuan_program_id);
		
		
        $start = (int) $_GET['start'];
        $limit = (int) $_GET['limit'];
		//$q=(isset($_GET['q']))?" and (w.nama like '%".$_GET['q']."%' or kab.nama like '%".$_GET['q']."%')":"";
		$q=(isset($_GET['q']))?$_GET['q']:'';
		$dapodikmenDb=Yii::app()->params['dapodikmenDb'];
		$appDb=Yii::app()->params['appDb'];
		$userid=Yii::app()->user->id;
		$cek_user=TUserAccessData::model()->findAll(array(
			'condition'=>'deleted=0 and userid=:uid',
			'params'=>array(':uid'=>$userid)
		));
		if(isset($cek_user[0]->id)){
			$filter_wilayah=" and w.kode_wilayah like '".$cek_user[0]->wilayah."%'";
			$kode_wilayah=$cek_user[0]->wilayah;
		}else{
			$filter_wilayah=" and (w.kode_wilayah='' or w.kode_wilayah is null)";
			$kode_wilayah='000000';
		}
		$data_tgl_cutoff=TDataTanggalCutOff::model()->findAll(array(
			'condition'=>'deleted=0 and year(tanggal)=:y and tanggal not in(select distinct created_date from '.$appDb.'.dbo.t_data_cutoff where kode_wilayah_sekolah like \''.$model->kode_wilayah.'%\' and t_bantuan_penerima_id is not null)',
			'params'=>array(':y'=>$model->tahun),
			'order'=>'tanggal asc'
		));
		if(isset($data_tgl_cutoff[0]->id)){
			$tgl_cutoff=$data_tgl_cutoff[0]->tanggal;
			/*$data=Yii::app()->db->createCommand('
					select w.nama as propinsi,
					kab.nama as kabupaten,
					(
					select count(*) from (select DISTINCT sekolah_id from siban.dbo.t_data_cutoff dc where dc.deleted=0 and dc.created_date=\''.$tgl_cutoff.'\'
					and left(dc.kode_wilayah_sekolah,4)=left(kab.kode_wilayah,4)) as tbl
					) as jumlah_sekolah,
					(
					select count(*) from '.$appDb.'.dbo.t_data_cutoff t inner join '.$dapodikmenDb.'.dbo.peserta_didik pd1 on pd1.peserta_didik_id=t.peserta_didik_id
					where t.deleted=0 and t.created_date=\''.$tgl_cutoff.'\' and left(t.kode_wilayah_sekolah,4)=left(kab.kode_wilayah,4) and pd1.nisn is null
					) as jumlah_siswa_tdk_bernisn,
					(
					select count(*) from '.$appDb.'.dbo.t_data_cutoff
					where deleted=0 and left(kode_wilayah_sekolah,4)=left(kab.kode_wilayah,4) and created_date=\''.$tgl_cutoff.'\'
					) as jumlah_siswa,
					
					kab.kode_wilayah
					from '.$dapodikmenDb.'.ref.mst_wilayah w inner JOIN Dapodikmen.ref.mst_wilayah kab ON kab.mst_kode_wilayah=w.kode_wilayah
					where w.id_level_wilayah=1'.$filter_wilayah.$q.'
				')->queryAll();
			*/
			$data=Yii::app()->db->createCommand('
				select * from bosGetDataPropinsi(\''.$kode_wilayah.'0000\',\''.$tgl_cutoff.'\',\''.$q.'\')
			')->queryAll();
			Yii::app()->session['rekapProv']=$data;
			$total = count($data);
			$limit=($limit+$start>$total)?($total-$start):$limit;


				
			echo CJSON::encode(array(
				"success" => true,
				"total" => $total,
				"data" => $data
			));
		}
        Yii::app()->end();
	}
	
	public function actionReadKabupaten(){
		//set_time_limit(0);
		$this->layout = false;
		
		$t_bantuan_program_id=$_GET['tbid'];
		$kode_wilayah=$_GET['kdw'];
		$model=TBantuanProgram::model()->findByPk($t_bantuan_program_id);
		$tgl_cutoff=$model->tgl_cutoff_data_dapodikmen;
		
        $start = (int) $_GET['start'];
        $limit = (int) $_GET['limit'];
		$q=(isset($_GET['q']))?" and (w.nama like '%".$_GET['q']."%' or s.nama like '%".$_GET['q']."%')":"";
		$dapodikmenDb=Yii::app()->params['dapodikmenDb'];
		$appDb=Yii::app()->params['appDb'];
		$userid=Yii::app()->user->id;
		$filter_wilayah=" and left(w.kode_wilayah,4)=left('".$kode_wilayah."',4)";
		$data_tgl_cutoff=TDataTanggalCutOff::model()->findAll(array(
			'condition'=>'deleted=0 and year(tanggal)=:y and tanggal not in(select distinct created_date from '.$appDb.'.dbo.t_data_cutoff where kode_wilayah_sekolah like \''.$model->kode_wilayah.'%\' and t_bantuan_penerima_id is not null)',
			'params'=>array(':y'=>$model->tahun),
			'order'=>'tanggal asc'
		));
		if(isset($data_tgl_cutoff[0]->id)){
			$tgl_cutoff=$data_tgl_cutoff[0]->tanggal;
			$data=Yii::app()->db->createCommand('
					select w.nama as kabupaten,
					s.nama as nama,s.sekolah_id,
					(
					select count(*) from '.$appDb.'.dbo.t_data_cutoff t inner join '.$dapodikmenDb.'.dbo.peserta_didik pd1 on pd1.peserta_didik_id=t.peserta_didik_id
					where t.deleted=0 and t.created_date=\''.$tgl_cutoff.'\' and left(t.kode_wilayah_sekolah,4)=left(\''.$kode_wilayah.'\',4) and t.sekolah_id=s.sekolah_id and pd1.nisn is null
					) as jumlah_siswa_tdk_bernisn,
					(
					select count(*) from '.$appDb.'.dbo.t_data_cutoff
					where deleted=0 and created_date=\''.$tgl_cutoff.'\' and left(kode_wilayah_sekolah,4)=left(\''.$kode_wilayah.'\',4) and sekolah_id=s.sekolah_id
					) as jumlah_siswa,
					w.kode_wilayah
					from '.$dapodikmenDb.'.ref.mst_wilayah w inner JOIN (select distinct sekolah_id,kode_wilayah_sekolah from '.$appDb.'.dbo.t_data_cutoff where deleted=0 and created_date=\''.$tgl_cutoff.'\') dc on left(w.kode_wilayah,4)=left(dc.kode_wilayah_sekolah,4)
					inner join '.$dapodikmenDb.'.dbo.sekolah s on s.sekolah_id=dc.sekolah_id 
					where w.id_level_wilayah=2'.$filter_wilayah.$q.'
				')->queryAll();
			Yii::app()->session['rekapKab']=$data;
			$total = count($data);
			$limit=($limit+$start>$total)?($total-$start):$limit;


				
			echo CJSON::encode(array(
				"success" => true,
				"total" => $total,
				"data" => $data
			));
		}
        Yii::app()->end();
	}
	public function actionReadSekolah(){
		
		$t_bantuan_program_id=$_GET['tbid'];
		$kode_wilayah=$_GET['kdw'];
		$sekolah_id=$_GET['sid'];
		$model=TBantuanProgram::model()->findByPk($t_bantuan_program_id);
		$start = (int) $_GET['start'];
        $limit = (int) $_GET['limit'];
		$q=(isset($_GET['q']))?" and (pd.nama like '%".$_GET['q']."%' or pd.alamat_jalan like '%".$_GET['q']."%')":"";
		$dapodikmenDb=Yii::app()->params['dapodikmenDb'];
		$appDb=Yii::app()->params['appDb'];
		$userid=Yii::app()->user->id;
		$filter_wilayah=" and left(w.kode_wilayah,4)=left('".$kode_wilayah."',4)";
		$data_tgl_cutoff=TDataTanggalCutOff::model()->findAll(array(
			'condition'=>'deleted=0 and year(tanggal)=:y and tanggal not in(select distinct created_date from '.$appDb.'.dbo.t_data_cutoff where kode_wilayah_sekolah like \''.$model->kode_wilayah.'%\' and t_bantuan_penerima_id is not null)',
			'params'=>array(':y'=>$model->tahun),
			'order'=>'tanggal asc'
		));
		if(isset($data_tgl_cutoff[0]->id)){
			$tgl_cutoff=$data_tgl_cutoff[0]->tanggal;

			/*$data=Yii::app()->db->createCommand('
					select w.nama as kabupaten,
					s.nama as sekolah,
					pd.nisn,pd.nama,w.kode_wilayah,pd.alamat_jalan
					from '.$dapodikmenDb.'.ref.mst_wilayah w inner JOIN '.$dapodikmenDb.'.dbo.sekolah s on left(w.kode_wilayah,4)=left(s.kode_wilayah,4)
					inner join '.$appDb.'.dbo.t_data_cutoff dc on s.sekolah_id=dc.sekolah_id
					inner join '.$dapodikmenDb.'.dbo.peserta_didik pd on pd.peserta_didik_id=dc.peserta_didik_id
					where w.id_level_wilayah=2 and dc.created_date=\''.$tgl_cutoff.'\''.$filter_wilayah.$q.'
				')->queryAll();
				*/
			$data=Yii::app()->db->createCommand(
			'select * from bosGetSiswaSekolah(\''.$sekolah_id.'\',\''.$kode_wilayah.'\',\''.$tgl_cutoff.'\',\''.$q.'\')'
			)->queryAll();	
			Yii::app()->session['rekapSekolah']=$data;
			$total = count($data);
			$limit=($limit+$start>$total)?($total-$start):$limit;


				
			echo CJSON::encode(array(
				"success" => true,
				"total" => $total,
				"data" => $data
			));
		}
        Yii::app()->end();
	}
	public function actionCheckPersetujuan(){
		$kdw=$_GET['kdw'];
		$data=TDataCutOff::model()->count(array(
			'condition'=>'deleted=0 and kode_wilayah_sekolah like :w and t_bantuan_penerima_id is not null',
			'params'=>array(':w'=>$kdw.'%')
		));
		echo $data;
	}
	public function actionPengeskaan(){
		//set_time_limit(0);
		$appDb=Yii::app()->params['appDb'];
		$tbid=$_POST['tbid'];
		$return=array(
			'success'=>false,
			'message'=>''
		);
		if(isset($tbid)){
			$model=TBantuanProgram::model()->findByPk($tbid);
			$appDb=Yii::app()->params['appDb'];
			$user=TUserAccessData::model()->findAll(array(
				'condition'=>'deleted=0 and userid=:id',
				'params'=>array(':id'=>Yii::app()->user->id)
			));
			if($user[0]->wilayah==$model->kode_wilayah){
				$data_tgl_cutoff=TDataTanggalCutOff::model()->findAll(array(
					'condition'=>'deleted=0 and year(tanggal)=:y and tanggal not in(select distinct created_date from '.$appDb.'.dbo.t_data_cutoff where kode_wilayah_sekolah like \''.$model->kode_wilayah.'%\' and t_bantuan_penerima_id is not null)',
					'params'=>array(':y'=>$model->tahun),
					'order'=>'tanggal asc'
				));
				if(isset($data_tgl_cutoff[0]->id)){
					$tgl_cutoff=$data_tgl_cutoff[0]->tanggal;
					$data=TDataCutOff::model()->findAll(array(
						'select'=>'distinct sekolah_id',
						'condition'=>'deleted=0 and created_date=:d and t_bantuan_penerima_id is null and kode_wilayah_sekolah like :w ',
						'params'=>array(':d'=>$tgl_cutoff,':w'=>$model->kode_wilayah.'%'),
					));
					if(isset($data[0]->sekolah_id)){
						foreach($data as $d){
							if($d->sekolah_id!=''){
								$insert=new TBantuanPenerima;
								$insert->t_bantuan_program_id=$model->id;
								$insert->sekolah_id=$d->sekolah_id;
								if($insert->save()){
									$data_siswa=TDataCutOff::model()->findAll(array(
										'condition'=>'deleted=0 and created_date=:d and sekolah_id=:sid and t_bantuan_penerima_id is null',
										'params'=>array(':sid'=>$insert->sekolah_id,':d'=>$tgl_cutoff),
									));
									Yii::app()->db->createCommand('
										update '.$appDb.'.dbo.t_data_cutoff set t_bantuan_penerima_id='.$insert->id.'
										where sekolah_id=\''.$insert->sekolah_id.'\' and created_date=\''.$tgl_cutoff.'\'
									')->execute();
									/*foreach($data_siswa as $s){
										$update=TDataCutOff::model()->findByPk($s->id);
										$update->t_bantuan_penerima_id=$insert->id;
										$update->save();
									}*/
								}
							}
						}
						$return=array(
							'success'=>true,
							'message'=>''
						);
					}else{
						$return=array(
							'success'=>false,
							'message'=>'Hari ini masih dibawah batas waktu Cut Off data yang telah ditentukan.'
						);
					}
				}else{
					$return=array(
						'success'=>false,
						'message'=>'Pusat belum menentukan tanggal cut off data.'
					);
				}
			}else{
				$return=array(
					'success'=>false,
					'message'=>'Maaf,Data yang anda ingin setujui merupakan data diluar wilayah anda.'
				);
			}
		}
		echo CJSON::encode($return);
	}
	public function actionExportExcel(){
		$mode=$_GET['mode'];
		$this->downloadExport($mode);
	}
	private function downloadExport($mode){
		//set_time_limit(0);
		ini_set('memory_limit', '1024M');
		$dir='./media/tmp/u-'.Yii::app()->user->id.'/';
		$kolom=array();
		$kolom2=array();
		/*sedia 701 kolom :D a-zz */
		for ($col = ord('a'); $col <= ord('z'); $col++){
			$kolom[]=chr($col);
			for ($col2 = ord('a'); $col2 <= ord('z'); $col2++){
				$kolom2[]=chr($col).chr($col2);
			}
		}
		$kolom=array_merge($kolom,$kolom2);
		$start_row=$var_start_row=3;
		$style_row=11;
		$max_column="zz";
		$model=new TBantuanPersyaratanPenerimaInquery;
		if($mode=='1'){
			$model->data=Yii::app()->session['rekapProv'];
		}else if($mode=='2'){
			$model->data=Yii::app()->session['rekapKab'];
		}else if($mode==3){
			$model->data=Yii::app()->session['rekapSekolah'];
		}
		//$dt=Yii::app()->db->createCommand($model->query)->queryAll();
		//exit;
		$excel=$this->buildArrayExcel($kolom,$model,$start_row,'-','-');
		//print_r($excel);exit;
		//$excel['G5']=date("Y-m-d H:i:s");
		$inputFileName = Yii::getPathOfAlias('application.views').DIRECTORY_SEPARATOR.'tBantuanPersyaratanPenerimaInquery/tmp/export.xls';
		echo $this->performDownload('Data Export.xls',$this->createExcel('save',$excel,$inputFileName,'Data Export',$var_start_row,$this->jmlRowData,$max_column,$dir));
		unset($model);
	}
	private function buildArrayExcel($kolom,$model,$start_row,$nama_prop,$nama_kab){
		$no=1;
		$hData = $model->data[0];
		$numcolumn = count($hData);
		$number=1;
		$excel=array();
		$index=1;
		$excel['A2']='No.';
		foreach ($hData as $h => $v) { 
            $label=ucwords(trim(strtolower(str_replace(array('-','_'),' ',preg_replace('/(?<![A-Z])[A-Z]/', ' \0', $h)))));
			$label=preg_replace('/\s+/',' ',$label);
			if(strcasecmp(substr($label,-3),' id')===0)
				$label=substr($label,0,-3);
			if($label==='Id')
				$label='ID';
			
			
			$excel[$kolom[$index].'2']=$label;
			$index++;
		}
		//print_r($excel);exit;
		
		foreach ($model->data as $idx=>$data){
			$index=1;
			/*no*/
			$excel['A'.$start_row]=$no;
			//print_r($data);exit;
			foreach ($data as $h => $v) {
				$nextKolom=($idx+$index);
				$excel[$kolom[$index].$start_row]=isset($data[$h]) ? $data[$h] : '';
				//echo 'excel['.$kolom[$nextKolom].$start_row.']='.$data[$h].'</br>';
				$index++;
				//print_r($excel);exit;
			}
			//print_r($excel);exit;
			$start_row+=1;
			$no+=1;
			
		}
		//print_r($excel);exit;	
		$this->jmlRowData=$no;
		return $excel;
	}
	private function createExcel($mode,$excel,$inputFileName,$nama_file,$start_row,$jml_data,$max_column,$dir){
		$phpExcelPath = Yii::getPathOfAlias('ext.phpexcel.Classes');
        require_once($phpExcelPath. DIRECTORY_SEPARATOR."PHPExcel/IOFactory.php");
        $inputFileType = 'Excel5';
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		
		$objPHPExcel = $objReader->load($inputFileName);
		//echo 'copying style '.'A'.$style_row;
		//$style=$objPHPExcel->getActiveSheet()->g‌etStyle('A'.$style_row);
		//echo ' success!';
		$sheet=$objPHPExcel->getActiveSheet();
		$sheet->insertNewRowBefore($start_row + 1, $jml_data)->getDefaultStyle()->getFont()->setName('Calibri')->setSize(14);
		foreach($excel as $kolom=>$v){
				$objPHPExcel->getActiveSheet()->getCell($kolom)->setValueExplicit($v, PHPExcel_Cell_DataType::TYPE_STRING);
				//$objPHPExcel->getActiveSheet()->setCellValue($kolom,$v);
				//$objPHPExcel->getActiveSheet()->getStyle($kolom)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
				//$objPHPExcel->getActiveSheet()->getColumnDimension($kolom)->setAutoSize(true);
		}
		//$objPHPExcel->getActiveSheet()->getStyle('A'.$start_row.':D'.($start_row+($jml_data-2)))->getProtection()->setLocked( PHPExcel_Style_Protection::PROTECTION_PROTECTED );
		//$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
		//$objPHPExcel->getActiveSheet()->getProtection()->setPassword('verwilditpsmk2014');
		
		 
		// Write out as the new file
		$outputFileType = 'Excel5';
		//$dir='./media/mydocuments/uploads/public/data-verwil-format-1/';
		if(!is_dir($dir)){
			mkdir($dir, 0777, true);
		}
		$outputFileName = $dir.$nama_file.'.xls';
		//echo 'saving '.$nama_file.'.xls ';
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $outputFileType);
		$objWriter->setPreCalculateFormulas(false);
		$objWriter->save($outputFileName);
		return $outputFileName;
	}
	private function performDownload($filename='Data Verifikasi Wilayah.xls',$path){
		$filename=str_replace(' ','.',$filename);
        $filecontent = file_get_contents($path);
        header("Content-Type: application/ms-excel");
        header("Content-disposition: attachment; filename=".$filename);
        header("Pragma: no-cache");
        echo $filecontent;
        exit;
	}
}
?>