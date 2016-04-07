<?php

/* if(Yii::app()->user->checkAccess('Siswa.*'))
  echo "You Authorized<br />";
  else
  echo "You Not Authorized<br />";
  $roles=Rights::getAssignedRoles(Yii::app()->user->Id);
  foreach($roles as $role)
  echo $role->name."<br />"; */
//var_dump(Yii::app()->getModule("rights")->getAuthorizer()->getSuperusers());
//$authItems = Yii::app()->getAuthManager()->getAuthItems(NULL, Yii::app()->user->Id);
//var_dump($authItems);
/* $authItems = Yii::app()->user->getState('authItems');
  if(is_array($authItems)){
  foreach($authItems as $key => $val){
  echo $val."<br />";
  }
  } */

//$array = array(0 => 'blue', 1 => 'red', 2 => 'green', 3 => 'red');
//
//$key = array_search('green', $array); // $key = 2;
//echo $key.'<br>';
//$key = array_search('red', $array);   // $key = 1;
//echo $key.'<br>';
//
//$this->widget('zii.widgets.jui.CJuiTabs', array(
//    'themeUrl' => Yii::app()->request->baseUrl.'/themes/blues',
//    'theme' => 'css',
//    'tabs'=>array(
//        'StaticTab 1'=>'Content for tab 1',
//        'StaticTab 2'=>'Content for tab 2',
//        'StaticTab 3'=>'Content for tab 3',
//        'StaticTab 4'=>'Content for tab 4',
//        'StaticTab 5'=>'Content for tab 5',
//        'StaticTab 6'=>'Content for tab 6',
//        'StaticTab 7'=>'Content for tab 7',
//        'StaticTab 8'=>'Content for tab 8',
//        'StaticTab 9'=>'Content for tab 9',
//        'StaticTab 10'=>'Content for tab 10',
//        'StaticTab 11'=>'Content for tab 11',
//        'StaticTab 12'=>'Content for tab 12',
//        'StaticTab 13'=>'Content for tab 13',
//        
//        'StaticTab 2'=>array('content'=>'Content for tab 2', 'id'=>'tab2'),
//        // panel 3 contains the content rendered by a partial view
//        'AjaxTab'=>array('ajax'=>$ajaxUrl),
//    ),
//    // additional javascript options for the tabs plugin
//    'options'=>array(
//        'collapsible'=>true,
//    ),
//));



//Ax::print_r(print_r($_SERVER));
    

//echo $GLOBALS['__baseUrl__'];

//Ax::print_r(ConfigHelper::listOptionsFromParamsConfig('agama'));
//$cs=Yii::app()->getClientScript();
//echo $this->assetsBase;

echo Yii::getPathOfAlias('webroot') . '/themes/blues/css/printing.css';

echo Yii::app()->user->id;