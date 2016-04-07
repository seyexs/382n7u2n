<?php
class ProposalController extends Controller
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
		return 'Create'; 
    }
	public function actionIndex(){
		$user=User::model()->findByPk(Yii::app()->user->id);
		if($user->kode_kepemilikan=='SP'){
			$dapodikmenDb=Yii::app()->params['dapodikmenDb'];
			$model=Yii::app()->db->createCommand('
					select * from '.$dapodikmenDb.'.dbo.sekolah where sekolah_id=\''.$user->pemilik_id.'\'
			')->queryAll();
			//print_r($model);exit;
			foreach($model[0] as $key=>$s){
				
			}
			$prop="{
					xtype: 'propertygrid',
					//disabled:true,
					listeners: { 
						'beforeedit': function (e) {
							return false; 
						}
					},
					source: {
						'(Nama)':'".$model[0]['nama']."',
						'Alamat': '".$model[0]['alamat_jalan']."',
						'Nomor Telepon': '".$model[0]['nomor_telepon']."',
						'Tgl Sk Pendirian': '".$model[0]['tanggal_sk_pendirian']."',
						'No Rekening': '".$model[0]['no_rekening']."',
						'Nama Bank': '".$model[0]['nama_bank']."',
						'Cabang KCP': '".$model[0]['cabang_kcp_unit']."',
					},
					sourceConfig: {
					}
				}";
			$this->render('index',array(
				'biodata'=>$prop
			));
		}else{
			echo "&nbsp;&nbsp;Pengajuan Proposal Bantuan Hanya untuk Sekolah!";
		}
		
	}
	public function actionCreate(){
		$jawaban=$_POST['tkuesionerjawaban'];
		$t_bantuan_program_id=$_POST['t_bantuan_program_id'];
		$uraian=$_POST['uraian'];
		$user_id=Yii::app()->user->id;
		$user=User::model()->findByPk($user_id);
		$bantuan=TBantuanProgram::model()->findByPk($t_bantuan_program_id);
		$dapodikmenDb=Yii::app()->params['dapodikmenDb'];
		//$sekolah=Yii::app()->db->createCommand('select * from '.$dapodikmenDb.'.dbo.sekolah where sekolah_id=\''.$user->pemilik_id.'\'')->queryAll();
		$cekP=TBantuanProposal::model()->findAll(array(
			'condition'=>'deleted=0 and t_bantuan_program_id=:id and user_id=:uid',
			'params'=>array(':id'=>$t_bantuan_program_id,':uid'=>$user_id)
		));
		if(isset($cekP[0]->id)){
			$modelP=TBantuanProposal::model()->findByPk($cekP[0]->id);
		}else{
			$modelP=new TBantuanProposal;
		}
		$modelP->t_bantuan_program_id=$t_bantuan_program_id;
		$modelP->uraian=$uraian;
		$modelP->user_id=$user_id;
		if(isset($_FILES['file_lampiran']['size'])){
			$upload_path='p'.$t_bantuan_program_id.'/Daftar-Proposal/U'.$user_id.'-'.$user->displayname.'/';
			$file = $_FILES['file_lampiran'];
			$allowedExts = array('xls','xlsx','doc','docx','ppt','pptx','pdf','jpg','bip','png','gif','rar','zip','tar.gz');
			$extension = end(explode(".", $_FILES["file_lampiran"]["name"]));
			
			//mencetak error tidak boleh mengupload file dengan ekstensi selain yang disebutkan diatas
			if (!in_array($extension, $allowedExts)) {
				echo '{success:false, errors:[], message: "Format tidak diperbolehkan"}';
				return;
			}
					
			$file_to_upload = $file['tmp_name'];
			$file_name = str_replace(' ','-',$bantuan->nama).'.'.$extension;
			
			$file_path = Yii::app()->params['dirBantuanDoc'];
			$file_path=Yii::getPathOfAlias('webroot').'/'.$file_path .'/'. $upload_path;
			$file_path = str_replace(' ','-',$file_path);
			if(!is_dir($file_path)){
				mkdir($file_path, 0777, true);
			}
				
			$file_full_path = $file_path.$file_name;
			//$file_full_path = str_replace(' ','-',$file_full_path);
			$success=move_uploaded_file($file_to_upload, $file_full_path);
			
			if($success){
				$modelP->file_lampiran=$file_full_path;
				
			}
		
		}
		if($modelP->save()){
			
			$dbname=Yii::app()->params['dbname'];
			//jika pernah mengisi kuesioner yang sama,maka yang lama diedit
			$cek=TKuesionerJawaban::model()->findAll(array(
				'condition'=>'user_id=:uid and deleted=0 and t_kuesioner_pilihan_jawaban_id 
					in(select pj.id from '.$dbname.'.t_kuesioner_pilihan_jawaban pj inner join '.$dbname.'.t_kuesioner_pertanyaan kp on pj.t_pertanyaan_id=kp.id
					 where kp.t_kuesioner_id=:kid)',
				'params'=>array(':uid'=>Yii::app()->user->id,':kid'=>$bantuan->t_kuesioner_id)
			));
			foreach($cek as $c){
				$m=TKuesionerJawaban::model()->findByPk($c->id);
				$m->deleted=1;
				$m->save();
			}
			foreach($jawaban as $jw){
				$model=new TKuesionerJawaban;
				foreach($jw as $key=>$j){
					if ($model->hasAttribute($key)) {
						$model->$key = $j;
					}
				}
				if(isset($model->t_kuesioner_pilihan_jawaban_id)){
					$model->user_id=Yii::app()->user->id;
					$model->save();
				}
			}
			echo CJSON::encode(array(
				'success'=>true,
				'message'=>'Data Telah Diproses'
			));
		}
	}
}
?>