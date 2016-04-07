<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?=Yii::app()->name?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <!-- Apple devices fullscreen -->
        <meta name="apple-mobile-web-app-capable" content="yes" />
		<?php
			$model=user::model()->findByPk(Yii::app()->user->id);
			$forumInsert=AuthItemChild::model()->count(array(
					"join"=>"inner join authassignment ass on t.parent=ass.itemname",
					"condition"=>"ass.userid=:id and t.child='TForum.Insert'",
					"params"=>array(':id'=>Yii::app()->user->id)
				));
			$user_realname = $model->displayname;
			$role=AuthAssignment::model()->findAll(array(
				'condition'=>'userid=:id',
				'params'=>array(':id'=>Yii::app()->user->id)
			));
			$role_name='';
			foreach($role as $v){
				$role_name.=($role_name=='')?$v->itemname:'|'.$v->itemname;
			}
			$base_url=Yii::app()->request->baseUrl;
			$site_url=Yii::app()->request->baseUrl;
			if(!$base_url){
				$base_url=explode('/', $_SERVER['SCRIPT_NAME']);
				$base_url=$base_url[1];
				$site_url=$base_url;
			}
			$image_path = (is_file($model->avatar_file))?$model->avatar_file:Yii::app()->request->baseUrl.'/'.Yii::app()->params['image_path'].'/avatar_small.png';
			
		?>
        <script type="text/javascript">
            var user_real_name = '<?php echo $user_realname; ?>';
            var role_name = '<?php echo $role_name; ?>';
            var base_url = '<?php echo $base_url; ?>/';
            var site_url = '<?php echo $site_url; ?>/';
            var image_path = '<?php echo $image_path; ?>';

        </script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/ext.4.2.1/ext-all-debug.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/lib/header.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/lib/terbilang.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/lib/dashboard.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/lib/searchfield.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/lib/searchfieldtree.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/lib/combofix.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/lib/treefix.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/lib/scrollfix.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/lib/treestorefix.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/lib/maskfix.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/lib/notification.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/lib/ajaxcron.js"></script>
		
		<script type="text/javascript">
            Ext.Loader.setConfig({
                enabled: true,
                paths: {
                    'Esmk': '<?=Yii::app()->request->baseUrl;?>/assets/js/app'
                }
            });
            Ext.require(['*']); 
            Ext.onReady(function(){ 
                Ext.create('Ext.app.Dashboard',{
					forumInsert:<?=$forumInsert?>
				});
				Ext.window.Window.override({
					modal:true,
					maximize: function(){
					  this.callParent([true]);
					},
					restore:function(){
					  this.callParent([true]);
					}
				});
				Ext.Ajax.timeout = 2000000; // 2000 seconds
				Ext.override(Ext.form.Basic, {
					timeout: Ext.Ajax.timeout / 1000
				});
				Ext.override(Ext.data.proxy.Server, {
					timeout: Ext.Ajax.timeout
				});
				Ext.override(Ext.data.Connection, {
					timeout: Ext.Ajax.timeout
				});
				//Ext.Ajax.timeout = 60000; 
                //crossDomainRequest();
            }); 
        </script>
		
		<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/ext.4.2.1/resources/ext-theme-esmk/ext-theme-esmk-all.css" rel="stylesheet" type="text/css" />
		
		<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/icon.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/default.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/report.css" rel="stylesheet" type="text/css" />
		
    </head>
    <body>

    </body>
</html>
