<?php
class LaporanController extends Controller
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
	public function actionGetViewSKPenerimaBantuan(){
		set_time_limit(0);
		$tbid=(int)$_POST['tbid'];
		$bantuan=TBantuanProgram::model()->findByPk($tbid);
		$jenispenerima=$bantuan->rBantuanPenerima->kode;
		$appDb=Yii::app()->params['appDb'];
		$dapodikmenDb=Yii::app()->params['dapodikmenDb'];
		if($jenispenerima=='SK'){
			$penerima=Yii::app()->db->createCommand('
						select prop.nama as \'provinsi\',kab.nama as \'kab\',s.nama,t.jumlah_bantuan,t.jumlah_dana,t.id,t.t_bantuan_program_id
						from '.$appDb.'.dbo.t_bantuan_penerima t inner join '.$dapodikmenDb.'.dbo.sekolah s on s.sekolah_id=t.sekolah_id
						inner join (select * from '.$dapodikmenDb.'.ref.mst_wilayah where id_level_wilayah=2) as kab on left(s.kode_wilayah,4)+\'00\'=kab.kode_wilayah
						inner join '.$dapodikmenDb.'.ref.mst_wilayah prop on prop.kode_wilayah=kab.mst_kode_wilayah
						where t.t_bantuan_program_id='.$bantuan->id.' and t.deleted=0
						order by prop.nama,kab.nama,s.nama
						')->queryAll();
			/*$penerima=TBantuanPenerima::model()->findAll(array(
				'condition'=>'deleted=0 and t_bantuan_program_id=:id',
				'params'=>array(':id'=>$bantuan->id)
			));*/
		}else if($jenispenerima=='SS'){
			$penerima=Yii::app()->db->createCommand('
					select prop.nama as \'provinsi\',kab.nama as \'kab\',s.nama,t.jumlah_bantuan,t.jumlah_dana,t.id,t.t_bantuan_program_id,
					pd.nama as nama_siswa
					from '.$appDb.'.dbo.t_bantuan_penerima t inner join '.$dapodikmenDb.'.dbo.sekolah s on s.sekolah_id=t.sekolah_id
					inner join (select * from '.$dapodikmenDb.'.ref.mst_wilayah where id_level_wilayah=2) as kab on left(s.kode_wilayah,4)+\'00\'=kab.kode_wilayah
					inner join '.$dapodikmenDb.'.ref.mst_wilayah prop on prop.kode_wilayah=kab.mst_kode_wilayah
					inner join '.$appDb.'.dbo.t_bantuan_penerima_siswa tbps on tbps.t_bantuan_penerima_id=t.id
					inner join '.$dapodikmenDb.'.dbo.peserta_didik pd on pd.peserta_didik_id=tbps.peserta_didik_id
					where t.t_bantuan_program_id='.$bantuan->id.' and t.deleted=0 and tbps.deleted=0
					order by prop.nama,kab.nama,s.nama
					')->queryAll();
		}
		if(isset($_POST['pdf'])){
			AxHelpers::toPDF('view_sk_penerima_bantuan',array(
				'bantuan'=>$bantuan,
				'penerima'=>$penerima,
				'jenispenerima'=>$jenispenerima
			));
		}else{
			$this->renderPartial('view_sk_penerima_bantuan',array(
				'bantuan'=>$bantuan,
				'penerima'=>$penerima,
				'jenispenerima'=>$jenispenerima
			));
		}
	}
}
?>