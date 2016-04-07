<?php

class GroupsController extends Controller {

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

    public function allowedActions() {
        return 'getRto';
    }
    
    public function actions() {
        /*return array(
            'getRto' => 'ext.actions.GetRtoAction',
        );*/
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        /*$this->render('view', array(
            'model' => $this->loadModel($id),
        ));*/
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new AuthItem;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
		$data = json_decode(stripslashes($_POST['data']));

        foreach ($data as $key => $val) {
            $model->$key = $val;
        }
        if ($model->name!='') {
			$model->name=str_replace(' ','',$model->name);
			$cek=AuthItem::model()->count(array(
				'condition'=>'name=:n',
				'params'=>array(':n'=>$model->name)
			));
			if($cek<1){
				$model->type = CAuthItem::TYPE_ROLE;
				$res = array(
						'success' => ($model->save()),
						'data' =>array(
							'id'=>$model->name
						)
				);
				echo CJSON::encode($res);
			}else{
				echo "Data Gagal Disimpan!";
			}
			
            Yii::app()->end();  
        }

        
    }
	public function actionUpdate(){
		$put_var = array();
        parse_str(file_get_contents('php://input'), $put_var);

        foreach ($put_var as $data) {
            $json = CJSON::decode($data, true);
        }
		//print_r($json);exit;
        $model = $this->loadModel($json['name']);

        
		if(isset($model)){
			//$model=$this->loadModel($data->name);
			foreach ($json as $key => $value) {
            // Does model have this attribute? If not, raise an error
				if ($model->hasAttribute($key)) {
					$model->$key = $value;
				}
			}
			
		}
		echo CJSON::encode(array(
				'success'=>$model->save(),
				'total'=>count($model)
			));
	}
    

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete() {
		$name=$_GET['name'];
        if ($_SERVER['REQUEST_METHOD'] === "DELETE") {

                if ($name<>'') {
					AuthItemChild::model()->deleteAll('parent=:parent', array(':parent' => $name));
					AuthAssignment::model()->deleteAll('itemname=:name', array(':name' => $name));
                    $this->loadModel($name)->delete();

                    echo json_encode(array(
                        "success" => true
                    ));
                } else {
                    throw new CHttpException(403, 'You are not authorized to perform this action.!');
                }
            }
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {

        $this->render('index');
    }
	public function actionRead(){
		$start=$_GET['start'];
		$limit=$_GET['limit'];
		$q=(isset($_GET['q']))?$_GET['q']:'';
		$total=AuthItem::model()->count(array(
			'condition'=>'type=2 and name like :q',
			'params'=>array(':q'=>'%'.$q.'%'),
		));
		$limit=($limit+$start>$total)?($total-$start):$limit;
		$model=AuthItem::model()->findAll(array(
			'condition'=>'type=2 and name like :q',
			'params'=>array(':q'=>'%'.$q.'%'),
			'offset'=>$start,
			'limit'=>$limit
		));
		
		echo CJSON::encode(array(
			'success'=>true,
			'total'=>$total,
			'data'=>$model
		));
	}
	public function actionApplyFunction(){
		$data=explode(",",$_POST["data"]);
		$groupname=$_POST['groupname'];
		
        if (count($data)) {
			foreach ($data as $name) {
				$model=new AuthItemChild;
				$model->parent=$groupname;
				$model->child=$name;
				$model->save();
			}
		}
	}
	public function actionRemoveFunction(){
		$data=explode(",",$_POST["data"]);
		$groupname=$_POST['groupname'];
		
        if (count($data)) {
			foreach ($data as $name) {
				AuthItemChild::model()->deleteAll('parent=:parent AND child=:child', array(':parent' => $groupname, ':child' => $name));
			}
		}
	}
	public function actionGetUsersInsideGroup(){
		$groupname=$_GET['groupname'];
		$limit=(isset($_GET['limit']))?$_GET['limit']:25;
		$start=(isset($_GET['start']))?$_GET['start']:0;
		$q=(isset($_GET['q']))?$_GET['q']:'';
		$total=User::model()->count(array(
			'join'=>'inner join [dbo].[AuthAssignment] [a] on t.id=a.userid',
			'condition'=>'t.displayname like :q and a.itemname=:i',
			'params'=>array(':q'=>'%'.$q.'%',':i'=>$groupname)
		));
		$limit=($limit+$start>$total)?($total-$start):$limit;
		$model=User::model()->findAll(array(
			'join'=>'inner join [dbo].[AuthAssignment] [a] on t.id=a.userid',
			'condition'=>'t.displayname like :q and a.itemname=:i',
			'params'=>array(':q'=>'%'.$q.'%',':i'=>$groupname),
			'limit'=>$limit,
			'offset'=>$start
		));
		
		echo CJSON::encode(array(
			'success'=>true,
			'total'=>$total,
			'rows'=>$model
		));
	}
	public function actionGetUsersOutOfGroup(){
		$groupname=$_GET['groupname'];
		$limit=(isset($_GET['limit']))?$_GET['limit']:25;
		$start=(isset($_GET['start']))?$_GET['start']:0;
		$q=(isset($_GET['q']))?$_GET['q']:'';
		$total=User::model()->count(array(
			'select'=>'id,dissplayname',
			'condition'=>'displayname like :q and id not in(select userid from AuthAssignment where itemname=:i)',
			'params'=>array(':q'=>'%'.$q.'%',':i'=>$groupname),
		));
		$limit=($limit+$start>$total)?($total-$start):$limit;
		$model=User::model()->findAll(array(
			'select'=>'id,displayname',
			'condition'=>'displayname like :q and id not in(select userid from AuthAssignment where itemname=:i)',
			'params'=>array(':q'=>'%'.$q.'%',':i'=>$groupname),
			'limit'=>$limit,
			'offset'=>$start
		));
		
		echo CJSON::encode(array(
			'success'=>true,
			'total'=>$total,
			'rows'=>$model
		));
	}
	public function actionAddUserToGroup(){
		$groupname=$_POST['groupname'];
		$users=explode(',',$_POST['data']);
		if($groupname!=''){
			foreach($users as $d){
				$model=new AuthAssignment;
				$model->itemname=$groupname;
				$model->userid=$d;
				$model->save();
			}
		}
	}
	public function actionRemoveUserFromGroup(){
		$groupname=$_POST['groupname'];
		$users=explode(',',$_POST['data']);
		if($groupname!=''){
			foreach($users as $d){
				AuthAssignment::model()->deleteAll('itemname=:i AND userid=:u', array(':i' => $groupname, ':u' => $d));
							
			}
		}
	}
    public function actionRegister() {
        $namagroup = '';
        $panelDefault = 0;
        $modelRTO = new AssignRTOForm();
        
        if(isset($_POST['AssignRTOForm'])){
            $panelDefault = 1;
            $modelRTO->attributes = $_POST['AssignRTOForm'];
            if ($modelRTO->save()) {
                $res = array(
                    'success' => 1,
                    'message' => 'Data Telah Berhasil Disimpan',
                    'returnUrl' => 'PengaturanGroup/register/?group='.$_GET['group']
                );
                $modelRTO->unsetAttributes();
                $this->setFlashSuccess();
            } else {
                $res = array(
                    'success' => 0,
                    'resend' => 1,
                    'message' => 'Data Telah Gagal Disimpan,Apakah anda ingin mencoba menyimpan lagi?',
                    'returnUrl' => 'PengaturanGroup/register/?group='.$_GET['group']
                );
            }
            echo json_encode($res);
            Yii::app()->end();
                
        }
        $models = AuthItemChild::model()->findAll('parent=:parent', array(':parent' => $_GET['group']));
        $apps = AuthItem::model()->findAll('name!=:name', array(':name' => $_GET['group']));
        $group = AuthItem::model()->findByPk($_GET['group']);
        if($group !== null){
            $namagroup = $group->name;
            if(!empty($group->description))
                $namagroup .= " ({$group->description})";
        }
        else
            throw new CHttpException(404, 'Aplikasi yang anda inginkan tidak ada');
        if(!isset($modelRTO->parent)){
            $modelRTO->parent = $_GET['group'];
        }
        $this->render('register', array('model' => $models, 'modelRTO' => $modelRTO, 'apps' => $apps, 'namagroup' => $namagroup, 'panelDefault' => $panelDefault));
    }
    public function actionDeleteChild(){
        if(isset($_GET['child'])){
            if(AuthItemChild::model()->deleteAll('parent=:parent AND child=:child', array(':parent' => $_GET['parent'], ':child' => $_GET['child'])))
                echo "1";
            else
                echo "0";
        }
        else
            echo "0";
        Yii::app()->end();
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = AuthItem::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'auth-item-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
