<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Upload Image
 *
 */
class AxUploadImage extends CComponent {

    protected $_image;
    public $basePath = '/images/';
    public $path;
    public $threshold = 100;
    public $folder = "dmY";
//    array(
//        array('label' => '_L', 'width' => 610, "height" => 400),
//        array('label' => '_M', 'width' => 300, "height" => 250),
//        array('label' => '_T', 'width' => 120, "height" => 90),
//    );

    public $labels;
    public $oLabel = '_O';
    public $width = 1000;
    public $height = 1000;

    public function init() {
        
    }

    public function setDestination($dest, $mkdir = true) {
        $dest = $dest . '/' . date($this->folder);
        if (!file_exists($dest) && $mkdir) {
            mkdir($dest);
        }
        return $dest . '/';
    }

    public function save($filename, $destination) {
        if (!file_exists($filename))
            return false;
        list($this->width, $this->height) = getimagesize($filename);
        $destination = $this->saveOriginal($filename, $destination);
        foreach ($this->labels as $label) {
            $arrDest = explode('.', $destination);
            $ext = array_pop($arrDest);
            $dest = implode('.', $arrDest) . $label['label'] .'.'. $ext;
            //$dest = str_replace($this->oLabel, $label['label'], $destination);
            $this->image = new AxImage();
            $this->image->source($filename);
            if (!isset($label['zoom'])) {
                $label['zoom'] = true;
            }
            if ($label['zoom']) {
                list ($width, $height, $x, $y) = $this->calculateWHXY($label);
                $this->image->crop($x, $y, $width, $height);
            }
            $this->image->create($dest);
            $this->image->source($dest);
            $this->image->resize($label['width'], $label['height']);

            if (isset($label['quality']))
                $this->image->create($dest, $label['quality']);
            else
                $this->image->create($dest);
        }
        @unlink($filename);
    }

    public function saveOriginal($filename, $destination) {
        //$destination = $this->rename($destination, $this->oLabel);
        $this->image = new AxImage();
        $this->image->source($filename);
        $this->image->resize($this->width, $this->height);
        $this->image->create($destination);
        //@unlink($filename);
        return $destination;
    }

    public function calculateWHXY($label) {

        $width = $label['width'];
        $height = $label['height'];
        if ($width > $height) {
            while ($this->width >= $width + $label['width'] / $this->threshold) {
                $width = $width + $label['width'] / $this->threshold;
            }
            $height = ($label['height'] * $width) / $label['width'];
        } else {
            while ($this->height >= $height + $label['height'] / $this->threshold) {
                $height = $height + $label['height'] / $this->threshold;
            }
            $width = ($label['width'] * $height) / $label['height'];
        }
        $x = ($this->width - $width) / 2;
        $y = ($this->height - $height) / 2;
        return array($width, $height, $x, $y);
    }

    public function rename($filename, $label) {
        $filename = explode('.', $filename);
        $ext = end($filename);
        $filename = $filename[0] . $label . '.' . $ext;
        return $filename;
    }

    public function getImage() {
        return $this->_image;
    }

    public function setImage($val) {
        $this->_image = $val;
    }

}

?>  