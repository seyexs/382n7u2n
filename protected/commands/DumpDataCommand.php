<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DumpDataCommand
 *
 * @author ardha
 * @created on Jun 20, 2013
 */
class DumpDataCommand extends CConsoleCommand{
    public function actionIndex(){
        $obj = new TablesToXml(Yii::app()->params['webRoot'].'/temp/');
        $obj->setNpsn(Yii::app()->params['npsn']);
        $obj->tablesToXml();
        $emailContent = "File sinkronisasi";
        if (AxHelpers::sendEmail(Yii::app()->params['dirEmail'], 'File Sinkronisasi', $emailContent, $obj->filename, 'File Sinkronisasi'))
           echo "Sending Email Success";
        else
            echo "Sending Email Fail";
        //echo $obj->filename;
        
    }
    public function actionTest(){
        $model = new MPerson();
        $models = $model->findAll();
        foreach($models as $m)
            echo $m->id."\n";
        //echo Yii::app()->params['emailer']['mailserver']."\n";
    }
}

?>
