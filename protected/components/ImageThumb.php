<?php

/**
 * Image helper functions
 * 
 */
Yii::import('application.vendors.*');
require "phpthumb/ThumbBase.inc.php";
require "phpthumb/GdThumb.inc.php";
require "phpthumb/PhpThumb.inc.php";
require "phpthumb/ThumbLib.inc.php";
class ImageThumb {
    static function thumb($img, $width, $height){
        $thumb = PhpThumbFactory::create($img);
        $thumb->resize($width, $height);
        $thumb->show();
    }
}