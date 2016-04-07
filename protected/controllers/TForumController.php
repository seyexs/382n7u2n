<?php

class TForumController extends Controller
{
        /**
	 * @var string the default layout for the views. Defaults to '//layouts/column1', meaning
	 * using one-column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
        
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
            return 'MessageList,messagelist'; 
        }
	public function actionIndex()
	{
		$model=TForum::model()->findAll();
		$count=TForum::model()->count();
		$str = '{"success":true'.
        ',"total":' . $count .
        ',"rows":' . json_encode($model)
        . '}';
		//$this->render('index');
	}
	public function actionMessageList(){
		$filter=(isset($_GET['filter']))?$_GET['filter']:"";
		$limit=$_GET['limit'];
		$start=$_GET['start'];
		$total = TForum::model()->count(array(
			//'select'=>'t.*,u.displayname as forum_from',
			'join'=>'inner join '.$this->getFormatJoinMSSQL('user','u').' on t.forum_from=u.id',
			'condition'=>'forum_content like :l',
			'params'=>array(':l'=>'%'.$filter.'%')
		));
		$limit=($limit+$start>$total)?($total-$start):$limit;
		$model = TForum::model()->findAll(array(
			'select'=>'t.forum_id,t.forum_content,t.forum_date,u.displayname as forum_from',
			'join'=>'inner join '.$this->getFormatJoinMSSQL('user','u').' on t.forum_from=u.id',
			'condition'=>'forum_content like :l',
			'params'=>array(':l'=>'%'.$filter.'%'),
			'order'=>'always_ontop,forum_date desc',
			'limit' => $limit,
			'offset' => $start
		));
		
        echo CJSON::encode(array(
                "success" => true,
                "total" => $total,
                "rows" => $model
            ));
			
	}
	public function actionInsert(){
		if($_POST['forum_content']!=''){
			$model = new TForum;
			
            $data = stripslashes($_POST['forum_content']);

            $model->forum_content=$data;
			$model->forum_from=Yii::app()->user->id;
			$model->forum_date=new CDbExpression('GETDATE()');

            if ($model->save()) {
                /*echo json_encode(array(
                    "success" => true,
                    "data" => array(
                        "id" => $model->forum_id,
                    )
                ));*/
            }
		}
	}

	// Uncomment the following methods and override them if needed
	/*

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}