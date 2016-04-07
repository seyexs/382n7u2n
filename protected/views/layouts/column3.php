<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="column-container">
    <div class="left-sidebar">
        <?php $this->beginWidget('ext.widgets.XPanel', array('title' => 'Info Sekolah')); ?>
        <?php echo $this->info_sekolah; ?>
        <?php $this->endWidget(); ?>

        <?php $this->beginWidget('ext.widgets.XPanel', array('title' => 'Info Peserta Didik')); ?>

        <?php $this->endWidget(); ?>
        <?php $this->beginWidget('ext.widgets.XPanel', array('title' => 'Kalender Akademik')); ?>
        <style>
            .eventsCalendar-currentTitle .monthTitle{
                text-align: center;
            }
        </style>
        <div style="clear: both;"></div>
        <div id="calender"></div>
        <?php
        if (!Yii::app()->user->isGuest) {
            $year = date("Y");
            $month = date("m");
            $thn=TTahunPelajaran::model()->findAllByAttributes(array(
                'is_active'=>1
            ));
            if(isset($thn[0]->id)){
                $tahun_pelajaran_id = $thn[0]->id;
                $libur_umum=CHariLiburSekolah::model()->findByPk(1);
                $this->widget('ext.widgets.eventcalendar.EventCalendar', array(
                    'elId' => 'calender',
                    'startMonth' => intval($month) - 1,
                    'startYear' => $year,
                    'holiday' => ($libur_umum->hari_libur=="")?'0':$libur_umum->hari_libur,
                    'changeMonth' => true,
                    'showCount' => 1,
                    'eventsLimit' => 10,
                    'ajax' => 'tKalenderAkademik/GetData/' . $tahun_pelajaran_id
                ));
            }else{
                echo 'Belum Ada Tahun Pelajaran yang aktif.';
            }
        }
        ?>
        <script>
            function showAction(){
                document.location="tKalenderAkademik/index";
            }
        </script>
        <?php $this->endWidget(); ?>
    </div>
    <div class="main-content">
        <?php echo $content; ?>
    </div>
    <div class="right-sidebar">
        <?php $this->beginWidget('ext.widgets.XPanel', array('title' => '-')); ?>
		<!-- <table style="width:100%;">
            		<tr>
                		<td><a href="<?=Yii::app()->createUrl('site/DownloadMaster')?>">Download Master EMIS</a></td>
            		</tr>
            		<tr>
                		<td style="padding-top:10px;"><a href="<?=Yii::app()->createUrl('site/DownloadDataBase')?>">Download Database EMIS</a></td>
            		</tr>
        	</table> -->
        <?php $this->endWidget(); ?>

        <?php /*$this->beginWidget('ext.widgets.XPanel', array('title' => 'Chatting'));*/ ?>
        <?php
        /*
        if (!Yii::app()->user->isGuest) {
            $this->widget('ext.cimilis.user.UserChat', array(
                'model' => User::model(),
                'dataUri' => 'forum/Blank',
                'avatarUrl' => '/media/images/',
            ));
            
        }*/
        ?>
        <?php /*$this->endWidget();*/ ?>
    </div>

</div>
<div class="clear"></div>
<?php $this->endContent(); ?>
