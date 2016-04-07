<?php
if(!Yii::app()->user->isGuest){
    /*$model = Menu::model()->findByPk(1);
    
    //$items = $model->getMenuItems();
    //Ax::print_rx($items);
    $this->widget('ext.widgets.menu.AxMenu', array(
        'items' => $model->getMenuItems()
    ));*/
	

}else{
	echo '
		<li class="active"><span class="nav-icon icon-home"></span>Home</li>
		
	';
}

?>