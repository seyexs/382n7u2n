<?php
class SekolahController extends Controller
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

	public function allowedActions() 
	{ 
        return 'GetBiodata,read,GetDetailInfoSekolah'; 
    }
	public function actionGetBiodata(){
		$user=User::model()->findByPk(Yii::app()->user->id);
		if($user->kode_kepemilikan=='SP'){
			$model=Yii::app()->db->createCommand('
					select * from '.$this->dapodikmen.'.dbo.sekolah where sekolah_id=\''.$user->pemilik_id.'\'
			')->queryAll();
            echo CJSON::encode(array(
                "success" => true,
                "total" => 1,
                "data" => $model
            ));
		}
	}
	public function actionGetDetailInfoSekolah(){
		$sid=$_POST['sid'];
		$model =Yii::app()->db->createCommand('
				select  s.*, prop.nama as propinsi,kab.nama as kabupaten from '.$this->dapodikmen.'.dbo.sekolah s 
				inner join '.$this->dapodikmen.'.ref.mst_wilayah kab on left(s.kode_wilayah,4)+\'00\'=kab.kode_wilayah
				inner join '.$this->dapodikmen.'.ref.mst_wilayah prop on left(s.kode_wilayah,2)+\'0000\'=prop.kode_wilayah
				where CONVERT(NVARCHAR(32),HashBytes(\'MD5\',CONVERT(NVARCHAR(36),s.sekolah_id)),2)=\''.$sid.'\'
			')->queryAll();
		echo CJSON::encode(array(
			'success'=>true,
			'data'=>$model
		));
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
			$userid=Yii::app()->user->id;
			$cek_user=TUserAccessData::model()->findAll(array(
				'condition'=>'deleted=0 and userid=:uid and level>0',
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
				'select'=>'prop.nama as propinsi,kab.nama as kabupaten,t.*',
				'join'=>"inner join (select * from Dapodikmen.ref.mst_wilayah where id_level_wilayah=2) as kab on left(t.kode_wilayah,4)+'00'=kab.kode_wilayah
						inner join Dapodikmen.ref.mst_wilayah prop on prop.kode_wilayah=kab.mst_kode_wilayah",
				'condition'=>'t.soft_delete=0 and t.nama like :s and t.bentuk_pendidikan_id=15'.$filter_data,
				'params'=>array(':s'=>'%'.$q.'%')
			));
			$limit=($limit+$start>$total)?($total-$start):$limit;
			$model=Sekolah::model()->findAll(array(
				'select'=>'prop.nama as propinsi,kab.nama as kabupaten,t.*',
				'join'=>"inner join (select * from Dapodikmen.ref.mst_wilayah where id_level_wilayah=2) as kab on left(t.kode_wilayah,4)+'00'=kab.kode_wilayah
						inner join Dapodikmen.ref.mst_wilayah prop on prop.kode_wilayah=kab.mst_kode_wilayah",
				'condition'=>'t.soft_delete=0 and t.nama like :s and t.bentuk_pendidikan_id=15'.$filter_data,
				'params'=>array(':s'=>'%'.$q.'%'),
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
					'kabupaten'=>$d->kabupaten
				));
			}
			echo CJSON::encode(array(
					"success" => true,
					"total" => $total,
					"data" => $data
				));

			Yii::app()->end();
            /*$data=Yii::app()->db->createCommand('
				select s.*, prop.nama as propinsi,kab.nama as kabupaten from '.$this->dapodikmen.'.dbo.sekolah s 
				inner join '.$this->dapodikmen.'.ref.mst_wilayah kab on left(s.kode_wilayah,4)+\'00\'=kab.kode_wilayah
				inner join '.$this->dapodikmen.'.ref.mst_wilayah prop on left(s.kode_wilayah,2)+\'0000\'=prop.kode_wilayah
				where 1=1'.$filter_data.' and s.nama like \'%'.$q.'%\' and s.bentuk_pendidikan_id=15 order by prop.nama,kab.nama asc
			')->queryAll();
            $total = count($data);
			$limit=($limit+$start>$total)?($total-$start):$limit;
            $model =Yii::app()->db->createCommand('
				select CONVERT(NVARCHAR(32),HashBytes(\'MD5\',CONVERT(NVARCHAR(36),s.sekolah_id)),2) as sekolah_id,s.nama,
				case when s.status_sekolah=\'2\' then \'Negeri\' else \'Swasta\' end as status_sekolah,
				s.nomor_fax,s.email,s.website,
				s.alamat_jalan,s.npsn,s.nomor_telepon, prop.nama as propinsi,kab.nama as kabupaten from '.$this->dapodikmen.'.dbo.sekolah s 
				inner join '.$this->dapodikmen.'.ref.mst_wilayah kab on left(s.kode_wilayah,4)+\'00\'=kab.kode_wilayah
				inner join '.$this->dapodikmen.'.ref.mst_wilayah prop on left(s.kode_wilayah,2)+\'0000\'=prop.kode_wilayah
				where 1=1'.$filter_data.' and s.nama like \'%'.$q.'%\' and s.bentuk_pendidikan_id=15 order by prop.nama,kab.nama asc
			')->queryAll();

            
            echo CJSON::encode(array(
                "success" => true,
                "total" => $total,
                "data" => $model
            ));

            Yii::app()->end();
			*/
    }
	public function actionSummarySekolah(){
		$this->render('summary_sekolah');
	}
	public function actionGetDataSummarySekolah123(){
		$kodewilayah=$_POST['k'];
		$namasekolah=$_POST['q'];
		$start = (int) $_GET['start'];
        $limit = (int) $_GET['limit'];
		
		$total=Sekolah::model()->count(array(
			'select'=>'prop.nama as propinsi,kab.nama as kabupaten,t.*',
			'join'=>"inner join (select * from Dapodikmen.ref.mst_wilayah where id_level_wilayah=2) as kab on left(t.kode_wilayah,4)+'00'=kab.kode_wilayah
					inner join Dapodikmen.ref.mst_wilayah prop on prop.kode_wilayah=kab.mst_kode_wilayah",
			'condition'=>'t.kode_wilayah like :kw and t.soft_delete=0 and nama like :s and t.bentuk_pendidikan_id=15',
			'params'=>array(':kw'=>'%'.$kodewilayah.'%',':s'=>'%'.$namasekolah.'%')
		));
		$limit=($limit+$start>$total)?($total-$start):$limit;
		$model=Sekolah::model()->findAll(array(
			'select'=>'prop.nama as propinsi,kab.nama as kabupaten,t.*',
			'join'=>"inner join (select * from Dapodikmen.ref.mst_wilayah where id_level_wilayah=2) as kab on left(t.kode_wilayah,4)+'00'=kab.kode_wilayah
					inner join Dapodikmen.ref.mst_wilayah prop on prop.kode_wilayah=kab.mst_kode_wilayah",
			'condition'=>'t.kode_wilayah like :kw and t.soft_delete=0 and nama like :s and t.bentuk_pendidikan_id=15',
			'params'=>array(':kw'=>'%'.$kodewilayah.'%',':s'=>'%'.$namasekolah.'%'),
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
				'kabupaten'=>$d->kabupaten
			));
		}
		echo CJSON::encode(array(
                "success" => true,
                "total" => $total,
                "data" => $data
            ));

        Yii::app()->end();
	}
	public function actionGetDataSummarySekolah(){
		$kodewilayah=$_POST['k'];
		$namasekolah=$_POST['q'];
		$top='';
		if($namasekolah=='' && $kodewilayah=='')
			$top='top 500';
		
		$query="select ".$top." prop.nama as 'Provinsi',kab.nama as 'Kab/Kota',s.nama as 'Nama Sekolah',
case when s.status_sekolah=1 then 'Negeri' else 'Swasta' end as 'Status', 
s.alamat_jalan as 'Alamat',s.nomor_telepon
from Dapodikmen.dbo.sekolah s inner JOIN
(select * from Dapodikmen.ref.mst_wilayah where id_level_wilayah=2) as kab on left(s.kode_wilayah,4)+'00'=kab.kode_wilayah
inner join Dapodikmen.ref.mst_wilayah prop on prop.kode_wilayah=kab.mst_kode_wilayah
where bentuk_pendidikan_id=15 and soft_delete=0 and s.kode_wilayah like '".substr($kodewilayah,0,4)."%' and s.nama like '%".$namasekolah."%'
order by prop.nama,kab.nama,s.nama";
		//echo $query;exit;
		$this->Execute($query);
	}
	private function Execute($q){
			set_time_limit(0);
			ini_set('memory_limit', '1024M');
			//$id=$_POST['id'];
			$appDb=Yii::app()->params['appDb'];
			$dapodikmenDb=Yii::app()->params['dapodikmenDb'];
			$cmd=Yii::app()->db->createCommand($q);
			$hasildata=$cmd->queryAll();
			if (!empty($hasildata)) {
				$hData = $hasildata[0];
				$numcolumn = count($hData);
				$gridColumn=array();
				$modelColumn=array();
				$data=array();
				//$gridColumn[]=array('xtype'=>'rownumberer','text'=>'No.','sortable'=>false,'flex'=>false,'width'=>40);
				
				foreach ($hData as $h => $v) {
					$label=ucwords(trim(strtolower(str_replace(array('-','_'),' ',preg_replace('/(?<![A-Z])[A-Z]/', ' \0', $h)))));
					$label=preg_replace('/\s+/',' ',$label);
					if(strcasecmp(substr($label,-3),' id')===0)
						$label=substr($label,0,-3);
					if($label==='Id')
						$label='ID';
					
					$sType="function(records,v){Ext.getCmp('bantuan123123').actionSum(records,v,this.dataIndex);}";
					$gridColumn[]=array(
						'dataIndex'=>$h,
						'text'=>$label,
						'flex'=>true,
						//'renderer'=>'=r=',
						//'summaryType'=>'=stype=',
						//'summaryRenderer'=>'=srender=',
					);
					$modelColumn[]=array('name'=>$h);
				}
				
				$json=CJSON::encode(array(
					"success" => true,
					"total" => count($hasildata),
					"gridColumn" => $gridColumn,
					'modelColumn'=>$modelColumn,
					'data'=>$hasildata,//array('total'=>count($model->data),'data'=>$model->data),
					'message'=>''
				));
				$json=str_replace('"=stype="',"function(records,dataIndex,v){var container=(Ext.getCmp('calonpenerimabantuantabitemcontentid'))?Ext.getCmp('calonpenerimabantuantabitemcontentid'):Ext.getCmp('persyaratanpenerimainquerydisplaycontainer');return container.actionSum(records,dataIndex,v);}",$json);
				$json=str_replace('"=srender="',"function(value,summaryData,dataIndex){var container=(Ext.getCmp('calonpenerimabantuantabitemcontentid'))?Ext.getCmp('calonpenerimabantuantabitemcontentid'):Ext.getCmp('persyaratanpenerimainquerydisplaycontainer');return container.actionGroupSum(value,summaryData,dataIndex);}",$json);
				$json=str_replace('"=r="',"function(value){return (!isNaN(parseFloat(value)) && isFinite(value))?Ext.util.Format.number(value,'0,000'):value;}",$json);
				
				echo $json;
				//unset($model);
				Yii::app()->end();
			}else{
				echo CJSON::encode(array(
					"success" => true,
					"total" => 0,
					"result" => '',
					'message'=>'Hasil Query Data Kosong!!'
				));

				Yii::app()->end();
			}
        
    }

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=MPegawai::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='mpegawai-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
