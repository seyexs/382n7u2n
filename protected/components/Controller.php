<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends RController {

    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/column1';

    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();
    public $info_sekolah = '';

    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();
    public $title = '';

    public function init() {
        $cs = Yii::app()->getClientScript();
        //$cs->registerCoreScript('jquery');
        //$cs->registerScriptFile(Yii::app()->baseUrl . '/js/system.js', CClientScript::POS_END);
        parent::init();
    }

    public function accessDenied($message=null) {
        if ($message === null) {
            $appname = !empty($this->title) ? $this->title : $this->id . '/' . $this->action->id;
            //$message = Rights::t('core', "Mohon Maaf anda tidak diperkenankan mengakses menu ini.\n(" . $appname.")");
			$message = Rights::t('core', ": Mohon Maaf anda tidak diperkenankan mengakses menu ini.");
			
        }
        $user = Yii::app()->getUser();
        if ($user->isGuest === true)
            $user->loginRequired();
        else
            throw new CHttpException(403, $message);
        //echo $message;
    }

    public function renderJSON($arrData = array()) {
        $this->layout = false;
        header('Content-type: application/json');
        echo CJavaScript::jsonEncode($arrData);
    }

    protected function debug($var) {
        echo "<pre style='background:yellow; padding:10px'>";
        echo "<b>Debug :</b><br/>";
        print_r($var);
        echo "</pre>";
    }

    protected function debugQuery($model) {
        echo "<pre style='background:yellow; padding:10px'>";
        echo "<b>Debug :</b><br/>";
        print_r($model->getErrors());
        echo "</pre>";
    }

    public function setFlashSuccess() {
        Yii::app()->user->setFlash('success', 'Penyimpanan Data Berhasil');
    }

    public function setFlashFail() {
        Yii::app()->user->setFlash('error', 'Penyimpanan Data Gagal');
        
    }
	public function getFormatJoinMSSQL($tbl,$alias){
		return "[dbo].[".$tbl."] [".$alias."]";
	}
	public function getUserWilayah(){
		$w=TUserAccessData::model()->findAll(array(
			'condition'=>'deleted=0 and userid=:id',
			'params'=>array(':id'=>Yii::app()->user->id)
		));
		return $w[0]->wilayah;
	}

}
