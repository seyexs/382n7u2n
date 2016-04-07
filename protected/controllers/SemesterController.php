<?php
class SemesterController extends Controller
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
			
			$userid=Yii::app()->user->id;
			//echo 'select * from '.$this->dapodikmen.'.ref.mst_wilayah where expired_date is null'.$filter_data.' and nama like \'%'.$q.'%\' and id_level_wilayah='.$l;exit;
            $model=Yii::app()->db->createCommand('
				select * from '.$this->dapodikmen.'.ref.semester where expired_date is null
			')->queryAll();
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