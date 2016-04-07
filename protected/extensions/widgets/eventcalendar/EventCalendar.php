<?php


/**
 * Description of EventCalendar
 *
 * @author obi
 */
class EventCalendar extends CWidget{
    protected $jsMain = "jquery.eventCalendar.js";
    protected $cssMain = "eventCalendar.css";
    protected $cssAdditional = "eventCalendar_theme_responsive.css";
    public $elId;
    public $startMonth;
    public $startYear;
    public $showCount=1;
    public $changeMonth=true;
    public $ajax;
    public $eventsLimit=4;
    public $data='[]';
    public $holiday='0';
    protected $temp;
    
    
    public function init() {
        $this->registerAsset();
        $this->changeMonth=($this->changeMonth)?"true":"false";
    }
    public function run() {
        $this->renderContent();
        $this->setInitScript();
    }
    public function registerAsset(){
        $assets = dirname(__FILE__) . '/' . 'assets';
        $baseUrl = Yii::app()->getAssetManager()->publish($assets);
        Yii::app()->clientScript->registerCssFile($baseUrl . '/css/'. $this->cssMain);
        Yii::app()->clientScript->registerCssFile($baseUrl . '/css/'. $this->cssAdditional);
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/'. $this->jsMain, CClientScript::POS_END);
        
    }
    public function renderContent(){
        if($this->showCount<1)
            echo "You give me wrong number. don't be silly...!";
        for($i=1;$i<=$this->showCount;$i++){
            if(($this->startMonth+1)>12){
                $this->startMonth=$m=0;
                $this->startYear=$y=($this->startYear+1);
            }else{
                $m=$this->startMonth;
                $y=$this->startYear;
            }
            $this->temp.="<div id='".$this->elId."$i' class='bok' data-init-month='$m' data-init-year='$y' ></div>";
            $this->startMonth+=1;
        }
    }
    public function setInitScript(){
        $js = <<<EOS
	$(document).ready(function() {
                                                $("#{$this->elId}").addClass("calenderContainer");
                                                $("{$this->temp}").appendTo("#{$this->elId}");
                                                
                                                $("#{$this->elId}").find("div").each(function(){
                                                    $(this).eventCalendar({
                                                            changeMonth:{$this->changeMonth},
                                                            setMonth:($(this).attr("data-init-month"))?$(this).attr("data-init-month"):null,
                                                            setYear:($(this).attr("data-init-year"))?$(this).attr("data-init-year"):null,
                                                            eventsjson: '{$this->ajax}', // link to events json
                                                            holiday:'{$this->holiday}',
                                                            eventsLimit:{$this->eventsLimit},
                                                    });
                                                });
						
					});
EOS;
        $cs = Yii::app()->getClientScript();
        $cs->registerScript(__CLASS__ . '#' . $this->id, $js, CClientScript::POS_END);
        return 'Please wait..';
    }
}

?>
