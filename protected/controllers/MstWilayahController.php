<?php
class MstWilayahController extends Controller
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
            return 'read'; 
        }
	public function actionRead(){
            $this->layout = false;

            $start = (int) $_GET['start'];
            $limit = (int) $_GET['limit'];
			$q=(isset($_GET['query']))?$_GET['query']:'';
			$l=(isset($_GET['l']))?$_GET['l']:2;// default level kab/kota
			
			$userid=Yii::app()->user->id;
			$cek_user=TUserAccessData::model()->findAll(array(
				'condition'=>'deleted=0 and userid=:uid and level>0',
				'params'=>array(':uid'=>$userid)
			));
			$filter_data='';
			if(isset($cek_user[0]->id)){
				$filter_data=" and kode_wilayah like '".$cek_user[0]->wilayah."%'";
			}
			//echo 'select * from '.$this->dapodikmen.'.ref.mst_wilayah where expired_date is null'.$filter_data.' and nama like \'%'.$q.'%\' and id_level_wilayah='.$l;exit;
            $total=MstWilayah::model()->count(array(
				'condition'=>'expired_date is null and nama like :q1 and id_level_wilayah=:l'.$filter_data,
				'params'=>array(':q1'=>'%'.$q.'%',':l'=>$l)
			));
			/*$data=Yii::app()->db->createCommand('
				select * from '.$this->dapodikmen.'.ref.mst_wilayah where expired_date is null'.$filter_data.' and nama like \'%'.$q.'%\' and id_level_wilayah='.$l.'
			')->queryAll();*/
			$limit=($limit+$start>$total)?($total-$start):$limit;
			$model=MstWilayah::model()->findAll(array(
				'condition'=>'expired_date is null and nama like :q1 and id_level_wilayah=:l'.$filter_data,
				'params'=>array(':q1'=>'%'.$q.'%',':l'=>$l),
				'limit'=>$limit,
				'offset'=>$start
			));
            /*$model =Yii::app()->db->createCommand('
				select * from '.$this->dapodikmen.'.ref.mst_wilayah where expired_date is null'.$filter_data.' and nama like \'%'.$q.'%\' and id_level_wilayah='.$l.'
			')->queryAll();*/

            
            echo CJSON::encode(array(
                "success" => true,
                "total" => $total,
                "data" => $model
            ));

            Yii::app()->end();
    }
}
?>