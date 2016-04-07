<?php

class TMessageController extends Controller
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
            return 'AdaPesanBaru'; 
        }
	public function actionAdaPesanBaru(){

		$status=TMessage::model()->count(array(
			'condition'=>'message_to=:u and message_status=0',
			'params'=>array(':u'=>Yii::app()->user->id)
		));
		echo $status;
	}
	public function actionIndex()
	{
		/*$model=TForum::model()->findAll();
		$count=TForum::model()->count();
		$str = '{"success":true'.
        ',"total":' . $count .
        ',"rows":' . json_encode($model)
        . '}';
		*/
		$this->render('index');
	}
	public function actionMessageList(){
		$filter=(isset($_GET['filter']))?$_GET['filter']:"";
		$start = (int) $_GET['start'];
        $limit = (int) $_GET['limit'];
		//echo $filter;exit;
		$sort=($_GET['sort'])?CJSON::decode($_GET['sort']):array(array('property'=>'','direction'=>''));
		$total=TMessage::model()->count(array(
			//'select'=>'t.*,u.displayname as message_content',
			'join'=>'inner join '.$this->getFormatJoinMSSQL('user','u').' on t.message_from=u.id',
			'condition'=>'(t.message_content like :f1 or u.displayname like :f2) and message_to=:userid and message_delete_receive=0',
			'params'=>array(':f1'=>'%'.$filter.'%',':f2'=>'%'.$filter.'%',':userid'=>Yii::app()->user->id),
		));
		$limit=($limit+$start>$total)?($total-$start):$limit;
		$model = TMessage::model()->findAll(array(
			'select'=>'t.message_id,t.message_title,t.message_from,t.message_to,t.message_status,
				t.message_delete_send,t.message_delete_receive,
				u.displayname as message_content,convert(varchar, message_date, 120) as message_date',
			'join'=>'inner join '.$this->getFormatJoinMSSQL('user','u').' on t.message_from=u.id',
			'condition'=>'(t.message_content like :f1 or u.displayname like :f2) and t.message_to=:userid and t.message_delete_receive=0',
			'params'=>array(':f1'=>'%'.$filter.'%',':f2'=>'%'.$filter.'%',':userid'=>Yii::app()->user->id),
			'order'=>$sort[0]['property'].' '.$sort[0]['direction'],
			'limit' => $limit,
			'offset' => $start
		));
		
        echo CJSON::encode(array(
                "success" => true,
                "total" => $total,
                "rows" => $model
            ));
			
	}
	public function actionSendmessage(){
		if($_POST['message_to']!=''){
			$model = new TMessage;
		
            $model->message_content=$_POST['message_content'];
			$model->message_from=Yii::app()->user->id;
			$model->message_to=$_POST['message_to'];
			$model->message_title=$_POST['message_title'];
			$model->message_date=new CDbExpression('GETDATE()');
			$model->message_status=$model->message_delete_send=$model->message_delete_receive=0;
				echo json_encode(array(
                    "success" => ($model->save()),
                    "data" => array(
                        "id" => $model->message_id,
                    )
                ));
            
		}
	}
	public function actionMessagesend(){
		$filter=(isset($_GET['filter']))?$_GET['filter']:"";
		$start=(int)$_GET['start'];
		$limit=(int)$_GET['limit'];
		$total=TMessage::model()->count(array(
			//'select'=>'t.*,u.displayname as message_content',
			'join'=>'inner join '.$this->getFormatJoinMSSQL('user','u').' on t.message_to=u.id',
			'condition'=>'(t.message_content like :l1 or u.displayname like :l2) and message_from=:userid and message_delete_send=0',
			'params'=>array(':l1'=>'%'.$filter.'%',':l2'=>'%'.$filter.'%',':userid'=>Yii::app()->user->id),
		));
		$limit=($limit+$start>$total)?($total-$start):$limit;
		$model = TMessage::model()->findAll(array(
			'select'=>'t.message_id,t.message_title,t.message_from,t.message_to,
				t.message_status,t.message_delete_send,t.message_delete_receive,
				u.displayname as message_content,convert(varchar, t.message_date, 120) as message_date',
			'join'=>'inner join '.$this->getFormatJoinMSSQL('user','u').' on t.message_to=u.id',
			'condition'=>'(t.message_content like :l1 or u.displayname like :l2) and message_from=:userid and message_delete_send=0',
			'params'=>array(':l1'=>'%'.$filter.'%',':l2'=>'%'.$filter.'%',':userid'=>Yii::app()->user->id),
			'order'=>'t.message_date desc',
			'limit' => $limit,
			'offset' => $start
		));
		
        echo CJSON::encode(array(
                "success" => true,
                "total" => $total,
                "rows" => $model
            ));
	}

	public function actionMessageDelete(){
		$model=TMessage::model()->findByPk($_POST['message_id']);
		if($_POST['message_mode']=='receive')
			$model->message_delete_receive=1;
		else
			$model->message_delete_send=1;
		$model->save();
	}
	public function actionMessageDetail(){
		$model=TMessage::model()->findByPk($_POST['message_id']);
		if($_POST['message_mode']=='receive'){
			$model->message_status=1;
			$model->save();
		}
		echo $model->message_content;
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