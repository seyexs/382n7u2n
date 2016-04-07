<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DatePickerCustom
 *
 * @author obi
 */
class DatePickerCustom {
    public $numberOfMonth=4;
    public $elId="";
    public $selectMultiple=true;
    public $startDate="";
    public $endDate="";
    public $month="";
    public $year="";
    public $dateSelected="";
    public $dpMonthChanged="";
    public $renderCallback="function(){}";
    
    public function init(){
        $this->registerAsset();
        $this->setInitScript();
    }
    public function run(){
        
    }
    public function registerAsset(){
        $assets = dirname(__FILE__) . '/' . 'assets';
        $baseUrl = Yii::app()->getAssetManager()->publish($assets);
        
        //Yii::app()->clientScript->registerScriptFile($baseUrl . '/jquery.min.js', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/date.js', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/jquery.datePicker.js', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/jquery.datePickerMultiMonth.js', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerCssFile($baseUrl . '/datePicker.css');
    }
    public function setInitScript(){
        $this->selectMultiple=(!$this->selectMultiple)?'false':$this->selectMultiple;
        $this->renderCallback=($this->renderCallback!="")?$this->renderCallback:"''";
        $js = <<<EOS
	$(function()
            {
				$('#{$this->elId}').datePickerMultiMonth(
					{
						numMonths: {$this->numberOfMonth},
						inline: true,
                                                selectMultiple:{$this->selectMultiple},
                                                startDate:'{$this->startDate}',
                                                endDate:'{$this->endDate}',
                                                month:'{$this->month}',
                                                year:'{$this->year}',
                                                //showHeader:1,
                                                showHeader:$.dpConst.SHOW_HEADER_SHORT,
                                                renderCallback:{$this->renderCallback},
					}
				).bind(
					'dpMonthChanged',
					function(event, displayedMonth, displayedYear)
					{
                                                {$this->dpMonthChanged}
						// uncomment if you have firebug and want to confirm this works as expected...
						//console.log('dpMonthChanged', arguments);
					}
				).bind(
					'dateSelected',
					function(event, date, \$td, status)
					{
                                                {$this->dateSelected}
						// uncomment if you have firebug and want to confirm this works as expected...
						//console.log('dateSelected', arguments);
					}
				);

				$('#getSelected').bind(
					'click',
					function(e)
					{
						alert($('#multimonth').dpmmGetSelected());
						return false;
					}
				);
            });
EOS;
        $cs = Yii::app()->getClientScript();
        $cs->registerScript(__CLASS__ . '#' . $this->elId, $js, CClientScript::POS_END);
        return 'Please wait..';
    }   
}

?>
