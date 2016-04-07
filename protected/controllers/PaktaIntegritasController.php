<?php
class PaktaIntegritasController extends Controller
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
		return ''; 
    }
	public function actionIndex(){
		
	}
}
?>