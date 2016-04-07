<?php

class FunctionRegistrationController extends Controller {

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
    public function filters() {
        return array(
            'rights',
        );
    }

    public function allowedActions() {
        return '';
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

        if (isset($_POST['AuthItem'])) {
            $model->attributes = $_POST['AuthItem'];
            if ($model->save()) {
                $res = array(
                    'success' => 1,
                    'message' => 'Data Telah Berhasil Disimpan',
                    'returnUrl' => 'PengaturanAplikasi/create'
                );
                $this->setFlashSuccess();
            } else {
                $res = array(
                    'success' => 0,
                    'resend' => 1,
                    'message' => 'Data Telah Gagal Disimpan,Apakah anda ingin mencoba menyimpan lagi?',
                    'returnUrl' => 'PengaturanAplikasi/create'
                );
                $this->setFlashFail();
            }
            echo json_encode($res);
            Yii::app()->end();
            
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['AuthItem'])) {
            $model->attributes = $_POST['AuthItem'];
            if ($model->save())
                $this->setFlashSuccess();
            else
                $this->setFlashFail();
                //$this->redirect(array('view', 'id' => $model->name));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete() {
        $id = $_GET['app'];
        $arrId = explode(',', $id);
        foreach ($arrId as $id)
            $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }
	
    /**
     * Lists all models.
     */
    public function actionIndex() {

        $this->render('index');
    }
	public function actionGetUnRegisteredFunction(){
		$mapp = new ManageApp();
        $options = array();
        $options = $mapp->getUnRegisterdApp();
		$data=array();
		foreach($options as $d){
			$data[]=array('name'=>$d);
		}
		echo CJSON::encode(array(
			'success'=>true,
			'total'=>count($data),
			'data'=>$data
		));
	}
	public function actionGetUnRegisteredFunctionForGroup(){
		$name=$_GET['groupname'];
		$limit=$_GET['limit'];
		$start=$_GET['start'];
		$model=AuthItem::model()->findAll(array(
			'condition'=>'name not in(select child from AuthItemChild where parent=:p) and type=0',
			'params'=>array(':p'=>$name),
			'offset'=>$start,
			'limit'=>$limit
		));
		$total=AuthItem::model()->count(array(
			'condition'=>'name not in(select child from AuthItemChild where parent=:p) and type<>2',
			'params'=>array(':p'=>$name)
		));
		echo CJSON::encode(array(
			'success'=>true,
			'total'=>$total,
			'data'=>$model
		));
	}
	public function actionGetRegisteredFunctionForGroup(){
		$name=$_GET['groupname'];
		$limit=$_GET['limit'];
		$start=$_GET['start'];
		$model=AuthItem::model()->findAll(array(
			'condition'=>'name in(select child from AuthItemChild where parent=:p) and type=0',
			'params'=>array(':p'=>$name),
			'offset'=>$start,
			'limit'=>$limit
		));
		$total=AuthItem::model()->count(array(
			'condition'=>'name in(select child from AuthItemChild where parent=:p) and type<>2',
			'params'=>array(':p'=>$name)
		));
		echo CJSON::encode(array(
			'success'=>true,
			'total'=>$total,
			'data'=>$model
		));
	}
    public function actionQuickRegister() {
        $msg = '';
        $status = '';
		$data=explode(",",$_POST["data"]);
		
        if (count($data)) {
            $s = 0;
            foreach ($data as $name) {
                $model = new AuthItem();
                $model->name = $name;
                $model->type = (strpos($name, '*') !== false) ? CAuthItem::TYPE_TASK : CAuthItem::TYPE_OPERATION;
                if($model->save())
                    $s++;
                if($s > 0) {   
                    $msg = "Aplikasi Telah Diregister";
                    $status = "success";
                }
                else{
                    $msg = "Aplikasi Gagal Diregister";
                    $status = "error";
                }

            }
            $res = array(
                    'success' => 1,
                    'message' => $msg,
                    'returnUrl' => 'PengaturanAplikasi/QuickRegister'
                );
            echo json_encode($res);
            Yii::app()->end();
        }
        //$this->render('quickregister_form', array('msg'=>$msg, 'status'=>$status));
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
