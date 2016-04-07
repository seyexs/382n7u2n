<div class="logo-left"></div>
<div class="logo-center">
    <span>Direktorat Pembinaan Sekolah Menengah Kejuruan</span><br />
    <span>Direktorat Jenderal Pendidikan Menengah Kementerian Pendiddikan dan Kebudayaan</span><br />
    <?php echo CHtml::encode(Yii::app()->name); ?>
    - Educational Management Information System
</div>

<div class="logo-right">
    <div style="float:right;">
        <?php echo CHtml::image(Yii::app()->baseUrl . "/images/logosmk.png"); ?>
    </div>
    <div style="float: right;">
        <?php
        if (Yii::app()->user->getState('nama_sekolah', true)) {
            $model = MSekolah::model()->find();
            if ($model !== null) {
                Yii::app()->user->setState('nama_sekolah', $model->nama_sekolah);
                if (!empty($model->npsn))
                    Yii::app()->user->setState('npsn_sekolah', $model->npsn, 'unknown');
                else
                    Yii::app()->user->setState('npsn_sekolah', 'unknown');
            }
        }
        echo '<span class="title">' . Yii::app()->user->getState('nama_sekolah', 'Tidak Diketahui') . '</span>';
        ?>
        <br />
        <?php
        if (!Yii::app()->user->isGuest) {
            date_default_timezone_set('Asia/Jakarta');
            echo '<span>Hello, ' . ucwords(Yii::app()->user->getName()) . '</span><br /><span>Login Time : ' . Yii::app()->user->getState('logintime') . '</span>';
        }
        ?>
    </div> 
    <div style="clear: both;"></div>
</div>