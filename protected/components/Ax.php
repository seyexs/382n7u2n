<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PrintUtility
 *
 * @author ACTIN02
 */
class Ax{
    //put your code here
    public static function print_r($arrVar = array()){
        //Yii::app()->controller->layout = false;
        if(is_array($arrVar)){
             echo "<pre>";
             print_r($arrVar);
             echo "</pre>";
        }
        else
            echo "Variable is not an array";
    }
    public static function print_rx($arrVar = array()){
        //Yii::app()->controller->layout = false;
        if(is_array($arrVar)){
             echo "<pre>";
             print_r($arrVar);
             echo "</pre>";
             exit;
        }
        echo "Variable is not an array";
    }
    public static function println($text=""){
        echo $text."<br />";
    }

}

?>
