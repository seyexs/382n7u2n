<?php
class PesertaDidikController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column1', meaning
	 * using one-column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $dapodikmen;
        
    public function init() {
		$this->dapodikmen=Yii::app()->params['dapodikmenDb'];
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
	public function actionRead(){
		set_time_limit(0);
            $this->layout = false;

            $start = (int) $_GET['start'];
            $limit = (int) $_GET['limit'];
			$namasekolah=(isset($_GET['q']))?$_GET['q']:'';
			$semesterid=isset($_GET['smtid'])?$_GET['smtid']:'0';
			$kw=isset($_GET['kw'])?substr($_GET['kw'],0,4):'0000';
			$tbid=$_GET['tbid'];
			$userid=Yii::app()->user->id;
			$cek_user=TUserAccessData::model()->findAll(array(
				'condition'=>'deleted=0 and userid=:uid and level>0',
				'params'=>array(':uid'=>$userid)
			));
			$filter_data="and s.kode_wilayah like '".$kw."%'";
			if(isset($cek_user[0]->id)){
				$filter_data="and s.kode_wilayah like '".$cek_user[0]->wilayah."%'";
			}
			//echo 'select * from '.$this->dapodikmen.'.ref.mst_wilayah where expired_date is null'.$filter_data.' and nama like \'%'.$q.'%\' and id_level_wilayah='.$l;exit;
            /*$str="
				select top 1000 prop.nama as propinsi,kab.nama as kabupaten,s.sekolah_id,s.nama as nama_sekolah,s.npsn,
				pd.nama,pd.nisn,pd.nik,pd.tempat_lahir,pd.tanggal_lahir,
				pd.jenis_kelamin,pdl.tinggi_badan,pdl.berat_badan,pd.nomor_telepon_rumah,pd.nomor_telepon_seluler,
				s.alamat_jalan as 'Alamat',s.nomor_telepon
				from ".$this->dapodikmen.".dbo.peserta_didik pd inner join ".$this->dapodikmen.".dbo.sekolah s on pd.sekolah_id=s.sekolah_id inner JOIN
				(select * from Dapodikmen.ref.mst_wilayah where id_level_wilayah=2) as kab on left(s.kode_wilayah,4)+'00'=kab.kode_wilayah
				inner join Dapodikmen.ref.mst_wilayah prop on prop.kode_wilayah=kab.mst_kode_wilayah
				inner join ".$this->dapodikmen.".dbo.peserta_didik_longitudinal pdl on pd.peserta_didik_id=pdl.peserta_didik_id
				inner join ".$this->dapodikmen.".dbo.anggota_rombel ar on ar.peserta_didik_id=pd.peserta_didik_id
				inner join ".$this->dapodikmen.".dbo.rombongan_belajar rb on rb.rombongan_belajar_id=ar.rombongan_belajar_id and rb.semester_id=pdl.semester_id
				where pd.soft_delete=0 and pdl.semester_id=".$semesterid." and s.bentuk_pendidikan_id=15 and s.soft_delete=0 and (s.nama like '%".$namasekolah."%' or pd.nama like '%".$namasekolah."%') ".$filter_data."
				order by prop.nama,kab.nama,s.nama
			";*/
			$total=PesertaDidik::model()->count(array(
				'select'=>'prop.nama as propinsi,kab.nama as kabupaten,s.*,t.*,pdl.tinggi_badan,pdl.berat_badan',
				'join'=>"inner join ".$this->dapodikmen.".dbo.sekolah s on t.sekolah_id=s.sekolah_id inner JOIN
						(select * from Dapodikmen.ref.mst_wilayah where id_level_wilayah=2) as kab on left(s.kode_wilayah,4)+'00'=kab.kode_wilayah
						inner join Dapodikmen.ref.mst_wilayah prop on prop.kode_wilayah=kab.mst_kode_wilayah
						inner join ".$this->dapodikmen.".dbo.peserta_didik_longitudinal pdl on t.peserta_didik_id=pdl.peserta_didik_id
						inner join ".$this->dapodikmen.".dbo.anggota_rombel ar on ar.peserta_didik_id=t.peserta_didik_id
						inner join ".$this->dapodikmen.".dbo.rombongan_belajar rb on rb.rombongan_belajar_id=ar.rombongan_belajar_id and rb.semester_id=pdl.semester_id",
				'condition'=>"t.soft_delete=0 and pdl.semester_id=:smtid and s.bentuk_pendidikan_id=15 and s.soft_delete=0 and (s.nama like :q1 or t.nama like :q2 )".$filter_data,
				'params'=>array(':smtid'=>$semesterid,':q1'=>'%'.$namasekolah.'%',':q2'=>'%'.$namasekolah.'%')
						
			));
			//$model=Yii::app()->db->createCommand($str)->queryAll();
			$limit=($limit+$start>$total)?($total-$start):$limit;
			$model=PesertaDidik::model()->findAll(array(
				'select'=>'prop.nama as propinsi,kab.nama as kabupaten,t.*,pdl.tinggi_badan,pdl.berat_badan',
				'join'=>"inner join ".$this->dapodikmen.".dbo.sekolah s on t.sekolah_id=s.sekolah_id inner JOIN
						(select * from Dapodikmen.ref.mst_wilayah where id_level_wilayah=2) as kab on left(s.kode_wilayah,4)+'00'=kab.kode_wilayah
						inner join Dapodikmen.ref.mst_wilayah prop on prop.kode_wilayah=kab.mst_kode_wilayah
						inner join ".$this->dapodikmen.".dbo.peserta_didik_longitudinal pdl on t.peserta_didik_id=pdl.peserta_didik_id
						inner join ".$this->dapodikmen.".dbo.anggota_rombel ar on ar.peserta_didik_id=t.peserta_didik_id
						inner join ".$this->dapodikmen.".dbo.rombongan_belajar rb on rb.rombongan_belajar_id=ar.rombongan_belajar_id and rb.semester_id=pdl.semester_id",
				'condition'=>"t.soft_delete=0 and pdl.semester_id=:smtid and s.bentuk_pendidikan_id=15 and s.soft_delete=0 and (s.nama like :q1 or t.nama like :q2 )".$filter_data,
				'params'=>array(':smtid'=>$semesterid,':q1'=>'%'.$namasekolah.'%',':q2'=>'%'.$namasekolah.'%'),
				'order'=>'propinsi,kabupaten',
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
					'sekolah_id'=>$d->sekolah->sekolah_id,
					'nama_sekolah'=>$d->sekolah->nama,
					'npsn'=>$d->sekolah->npsn,
					'nomor_telepon'=>$d->sekolah->nomor_telepon,
					
				));
			}
            echo CJSON::encode(array(
                "success" => true,
                "total" => $total,
                "data" => $data
            ));

            Yii::app()->end();
	}
	public function actionRead123(){
			set_time_limit(0);
            $this->layout = false;

            $start = (int) $_GET['start'];
            $limit = (int) $_GET['limit'];
			$namasekolah=(isset($_GET['q']))?$_GET['q']:'';
			$semesterid=isset($_GET['smtid'])?$_GET['smtid']:'0';
			$kw=isset($_GET['kw'])?substr($_GET['kw'],0,4):'0000';
			$tbid=$_GET['tbid'];
			$userid=Yii::app()->user->id;
			$cek_user=TUserAccessData::model()->findAll(array(
				'condition'=>'deleted=0 and userid=:uid',
				'params'=>array(':uid'=>$userid)
			));
			$filter_data="and s.kode_wilayah like '".$kw."%'";
			if(isset($cek_user[0]->id)){
				$filter_data="and s.kode_wilayah like '".$cek_user[0]->wilayah."%'";
			}
			//echo 'select * from '.$this->dapodikmen.'.ref.mst_wilayah where expired_date is null'.$filter_data.' and nama like \'%'.$q.'%\' and id_level_wilayah='.$l;exit;
            $str="
				select top 1000 prop.nama as propinsi,kab.nama as kabupaten,s.sekolah_id,s.nama as nama_sekolah,s.npsn,
				pd.nama,pd.nisn,pd.nik,pd.tempat_lahir,pd.tanggal_lahir,
				pd.jenis_kelamin,pdl.tinggi_badan,pdl.berat_badan,pd.nomor_telepon_rumah,pd.nomor_telepon_seluler,
				s.alamat_jalan as 'Alamat',s.nomor_telepon
				from ".$this->dapodikmen.".dbo.peserta_didik pd inner join ".$this->dapodikmen.".dbo.sekolah s on pd.sekolah_id=s.sekolah_id inner JOIN
				(select * from Dapodikmen.ref.mst_wilayah where id_level_wilayah=2) as kab on left(s.kode_wilayah,4)+'00'=kab.kode_wilayah
				inner join Dapodikmen.ref.mst_wilayah prop on prop.kode_wilayah=kab.mst_kode_wilayah
				inner join ".$this->dapodikmen.".dbo.peserta_didik_longitudinal pdl on pd.peserta_didik_id=pdl.peserta_didik_id
				inner join ".$this->dapodikmen.".dbo.anggota_rombel ar on ar.peserta_didik_id=pd.peserta_didik_id
				inner join ".$this->dapodikmen.".dbo.rombongan_belajar rb on rb.rombongan_belajar_id=ar.rombongan_belajar_id and rb.semester_id=pdl.semester_id
				where pd.soft_delete=0 and pdl.semester_id=".$semesterid." and s.bentuk_pendidikan_id=15 and s.soft_delete=0 and (s.nama like '%".$namasekolah."%' or pd.nama like '%".$namasekolah."%') ".$filter_data."
				order by prop.nama,kab.nama,s.nama
			";
			$model=Yii::app()->db->createCommand($str)->queryAll();
            $total = count($model);
			$limit=($limit+$start>$total)?($total-$start):$limit;
            echo CJSON::encode(array(
                "success" => true,
                "total" => $total,
                "data" => $model
            ));

            Yii::app()->end();
    }
}
?>