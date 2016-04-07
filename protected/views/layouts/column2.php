<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="column-container">
    <div class="left-sidebar">
        <?php $this->beginWidget('ext.widgets.XPanel', array('title' => $title)); ?>
        
        <?php $this->endWidget(); ?>
        
        <?php $this->beginWidget('ext.widgets.XPanel', array('title' => $title)); ?>
        
        <?php $this->endWidget(); ?>
    </div>
    <div class="main-content">
        <?php echo $content; ?>
    </div>

</div>
<?php $this->endContent(); ?>