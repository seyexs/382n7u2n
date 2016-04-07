<?php

class SiteController extends Controller {

    /**
     * Declares class-based actions.
     */
	 
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {

		$this->layout = false;
        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
		
        // collect user input data
        if (isset($_POST['LoginForm'])) {
			
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login()){
                //$this->render('index');
				echo CJSON::encode(array(
					'valid'=>true,
					'redirect'=>'./'
				));
				Yii::app()->end();
			}
        }elseif(isset(Yii::app()->user->id)){
			$this->render('index');
			//$this->sendEmail();
			Yii::app()->end();
		}
        // display the login form
		//$model=User::model()->findByPk(1);
		//echo $model->username;exit;
        $this->render('login-ajax');

		
    }
	private function sendEmail(){
		$message = new YiiMailMessage;
		$message->view = 'contoh';
		 
		//userModel is passed to the view
		$userModel=new User;
		$message->setBody(array('userModel'=>$userModel), 'text/html');
		 
		 
		//$message->addTo($userModel->email);
		$message->addTo('robicahyadi@live.com');
		$message->from = Yii::app()->params['adminEmail'];
		Yii::app()->mail->send($message);
	}
	private function mailsend($to,$from,$subject,$message){
        $mail=Yii::app()->Smtpmail;
        $mail->SetFrom($from, 'robicahyadi@psmk.kemdikbud.go.id');
        $mail->Subject    = $subject;
        $mail->MsgHTML($message);
        $mail->AddAddress($to, "");
        if(!$mail->Send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }else {
            echo "Message sent!";
        }
    }
    public function actionTest() {
		$this->layout = 'column1';
        $this->render('test');
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            //$err_msg=Yii::app()->params['errorMsg'][$error['errorCode']];
            //$err_msg=(isset($err_msg))?$err_msg:$error['message'];
            if (Yii::app()->request->isAjaxRequest) {
                //echo $err_msg;
                echo $error['message'];
                //print_r($error);
            } else {
                //$error['message']=$err_msg;
                $this->render('error', $error);
            }
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact() {
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $name = '=?UTF-8?B?' . base64_encode($model->name) . '?=';
                $subject = '=?UTF-8?B?' . base64_encode($model->subject) . '?=';
                $headers = "From: $name <{$model->email}>\r\n" .
                        "Reply-To: {$model->email}\r\n" .
                        "MIME-Version: 1.0\r\n" .
                        "Content-type: text/plain; charset=UTF-8";

                mail(Yii::app()->params['adminEmail'], $subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }
	
    public function actionDetailInfo() {
        $model = new DetailInfo;

        // uncomment the following code to enable ajax-based validation
        /*
          if(isset($_POST['ajax']) && $_POST['ajax']==='detail-info-detailInfo-form')
          {
          echo CActiveForm::validate($model);
          Yii::app()->end();
          }
         */

        if (isset($_POST['DetailInfo'])) {
            $model->attributes = $_POST['DetailInfo'];
            if ($model->validate()) {
                // form inputs are valid, do something here
                return;
            }
        }
        $this->render('detailInfo', array('model' => $model));
    }

    /**
     * Displays the login page
     */
    public function actionLogin() {
        $this->layout = false;
		//echo "login duku";
        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }
        // display the login form
        $this->render('login', array('model' => $model));
		//$this->redirect('login');
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }
	public function actionDownload(){
		$fn=$_GET['fn'];
		$path=Yii::getPathOfAlias('webroot').'/media/mydocuments/uploads/public/files/'.$fn;
		if(file_exists($path)){
			$filecontent = file_get_contents($path);
			header("Content-Type: application-x/force-download");
			header("Content-disposition: attachment; filename=\"".$fn."\"");
			header("Content-length: " . (string)(strlen($filecontent)));
			header("Pragma: no-cache");
			echo $filecontent;
			exit;
		}
	}
    

}