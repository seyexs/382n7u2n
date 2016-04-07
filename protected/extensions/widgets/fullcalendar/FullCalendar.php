<?php

class FullCalendar extends CWidget{
    protected $jsFile = "fullcalendar.js";
    protected $jsLibUI = "jquery-ui-1.8.23.custom.min.js";
    protected $jsLib = "jquery-1.8.1.min.js";
    protected $cssFile = "fullcalendar.css";
    public $start_date;
    public $select;
    public $end_date;
    public function init(){
        $this->registerAsset();
        $this->setInitScript();
    }
    public function run(){
        $this->render('index');
    }
    public function registerAsset(){
        $assets = dirname(__FILE__) . '/' . 'resource';
        $baseUrl = Yii::app()->getAssetManager()->publish($assets);
        Yii::app()->clientScript->registerCssFile($baseUrl . '/'. $this->cssFile);
        //Yii::app()->clientScript->registerScriptFile($baseUrl . '/'. $this->jsLib, CClientScript::POS_HEAD);
        //Yii::app()->clientScript->registerScriptFile($baseUrl . '/'. $this->jsLibUI, CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/'. $this->jsFile, CClientScript::POS_HEAD);
        
    }
    public function setInitScript(){
        $js = <<<EOS
	$(document).ready(function() {
	
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
		
		var calendar = $('#fullcalendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			selectable: true,
			selectHelper: true,
                        startDate:'{$this->start_date}',
                        endDate:'{$this->end_date}',    
			select: {$this->select},
			editable: true,
			events: [
				{
					title: 'All Day Event',
					start: new Date(y, m, 1)
				},
				{
					title: 'Long Event',
					start: new Date(y, m, d-5),
					end: new Date(y, m, d-2),
                                        url:'javascript:eventClick(this)'
				},
				{
					id: 999,
					title: 'Repeating Event',
					start: new Date(y, m, d-3, 16, 0),
					allDay: false
				},
				{
					id: 999,
					title: 'Repeating Event',
					start: new Date(y, m, d+4, 16, 0),
					allDay: false
				},
				{
					title: 'Meeting',
					start: new Date(y, m, d, 10, 30),
					allDay: false
				},
				{
					title: 'Lunch',
					start: new Date(y, m, d, 12, 0),
					end: new Date(y, m, d, 14, 0),
					allDay: false
				},
				{
					title: 'Birthday Party',
					start: new Date(y, m, d+1, 19, 0),
					end: new Date(y, m, d+1, 22, 30),
					allDay: false
				},
				{
					title: 'Click for Google',
					start: new Date(y, m, 28),
					end: new Date(y, m, 29),
					url: 'http://google.com/'
				}
			]
		});
		
	});
        function eventClick(obj){
            alert('ok');
        }
EOS;
        $cs = Yii::app()->getClientScript();
        $cs->registerScript(__CLASS__ . '#' . $this->id, $js, CClientScript::POS_END);
        return 'Please wait..';
    }
}
?>
