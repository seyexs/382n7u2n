<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ConfigHelper
 *
 * @author ardha
 * @created on Nov 23, 2012
 */
class ConfigHelper {

    //put your code here
    static public function getPegawaiStatusDef($key) {
        if (!empty($key)) {
            if (array_key_exists($key, Yii::app()->params['pegawai-status']))
                return Yii::app()->params['pegawai-status'][$key];
            else
                return '';
        }
    }

    static public function getPegawaiStatusKey($value) {
        return array_search($value, Yii::app()->params['pegawai-status']);
    }

    static public function getPegawaiStatusSaatIniDef($key) {
        if (!empty($key)) {
            if (array_key_exists($key, Yii::app()->params['pegawai-status-saat-ini']))
                return Yii::app()->params['pegawai-status-saat-ini'][$key];
            else
                return '';
        }
    }

    static public function getPegawaiStatusSaatIniKey($value) {
        return array_search($value, Yii::app()->params['pegawai-status-saat-ini']);
    }

    static public function getPegawaiStatusKetetapanDef($key) {
        if (!empty($key)) {
            if (array_key_exists($key, Yii::app()->params['pegawai-status-ketetapan']))
                return Yii::app()->params['pegawai-status-ketetapan'][$key];
            else
                return '';
        }
    }

    static public function getPegawaiStatusKetetapanKey($value) {
        return array_search($value, Yii::app()->params['pegawai-status-ketetapan']);
    }

    static public function getAgamaDef($key) {
        if (!empty($key)) {
            if (array_key_exists($key, Yii::app()->params['agama']))
                return Yii::app()->params['agama'][$key];
            else
                return '';
        }
    }

    static public function getAgamaKey($value) {
        return array_search($value, Yii::app()->params['agama']);
    }

    static public function getSexDef($key) {
        if (!empty($key)) {
            if (array_key_exists($key, Yii::app()->params['sex']))
                return Yii::app()->params['sex'][$key];
            else
                return '';
        }
    }

    static public function getSexKey($value) {
        return array_search($value, Yii::app()->params['sex']);
    }
    static public function getGolDarahDef($key) {
        if (!empty($key)) {
            if (array_key_exists($key, Yii::app()->params['gol-darah']))
                return Yii::app()->params['gol-darah'][$key];
            else
                return '';
        }
    }
    static public function getGolDarahKey($value) {
        return array_search($value, Yii::app()->params['gol-darah']);
    }
    static public function listOptionsFromParamsConfig($key){
        if(isset(Yii::app()->params[$key])){
            $arrReturn = array();
            foreach(Yii::app()->params[$key] as $val)
                $arrReturn[$val] = $val;
            return $arrReturn;
        }
        return array();
    }

}

?>
