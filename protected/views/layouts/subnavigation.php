<!--Cars search sub menus placed here to avoid confusing the widget--> 
<ul id="community">
    <li><?php echo CHtml::link('User Reviews', array('/community/index')); ?></li>
    <li><a href="<?php echo Yii::app()->params['forum'][Yii::app()->params['current_domain']]; ?>">Forum</a></li>
</ul>