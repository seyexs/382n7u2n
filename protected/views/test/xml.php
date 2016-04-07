<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$xml = new AxSinkronisasi(Yii::getPathOfAlias('webroot')."/temp/test.xml");
//$xml->createXMLFromData();
//if(count($xml->getErrors())){
//    print_r($xml->getErrors());
//}
$xml->extractXMLtoData();
?>
