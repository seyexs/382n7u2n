<?php

/* @var $this SiteController */
/* @var $error array */
$this->breadcrumbs = array(
    'Error',
);
switch ($code) {
    case 403:
        $this->pageTitle = Yii::app()->name . ' - Akses Ditolak';
        $this->breadcrumbs = array(
            'Akses Ditolak',
        );
        echo '<div class="errorpage e-403">';
        break;
    case 404:
        $this->pageTitle = Yii::app()->name . ' - Halaman Tidak Ditemukan';
        $this->breadcrumbs = array(
            'Halaman Tidak Ditemukan',
        );
        echo '<div class="errorpage e-404">';
        break;
    default:
        $this->pageTitle = Yii::app()->name . ' - Error';
        $this->breadcrumbs = array(
            'Error',
        );
        echo '<h2>Error ' . $code . '</h2>';
        echo '<div class="errorpage">';
        break;
}
echo CHtml::encode($message);
echo '</div>';
?>