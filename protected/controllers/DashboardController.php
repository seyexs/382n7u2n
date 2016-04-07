<?php

class DashboardController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column1', meaning
     * using one-column layout. See 'protected/views/layouts/column1.php'.
     */
    //public $layout = '//layouts/mainbasic';


    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'rights',
        );
    }
	public function actionIndex() {
		$userid=Yii::app()->user->id;
		$db=Yii::app()->params['appDb'];
		$dashboard=TDashboard::model()->findAll(array(
			'join'=>'inner join '.$db.'.dbo.authassignment g on t.authitem_name=g.itemname',
			'condition'=>'t.deleted=0 and g.userid=:uid',
			'params'=>array(':uid'=>$userid)
		));
        $this->render('index',array(
			'dashboard'=>$dashboard
		));
    }
}
?>