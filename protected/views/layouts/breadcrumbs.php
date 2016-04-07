<?php
if(!Yii::app()->user->isGuest):
?>

<div class="bc-container">
    <?php if (isset($this->breadcrumbs)): ?>
        <?php
        $this->widget('zii.widgets.CBreadcrumbs', array(
            'separator' => '&gt;&nbsp;',
            'tagName' => 'span',
            'links' => $this->breadcrumbs,
        ));
        ?><!-- breadcrumbs -->
    <?php endif ?>
    <?php
    if($this->shownTahunAjaran):
        echo '<div style="float:right;margin-right:4px;">';
        echo '<label style="margin-right:2px;">Tahun Pelajaran: </label>';
        echo CHtml::dropDownList('tahunajaran', '', TTahunPelajaran::model()->getListOptions('m_sekolah_id=1', false), array('id' => 'main-year-of-study'));
        echo '</div>';
    endif
    ?>
</div>
<?php
endif
?>