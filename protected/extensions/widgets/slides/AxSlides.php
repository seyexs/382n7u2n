<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class AxSlides extends CWidget
{
    //Slides Config
    public $preload = 'false';
    public $generateNextPrev = 'true';
    public $containerWidgetId;
    public $classWidget='wtb-cw-ss';
    public $classContainerItems = 'wtb-cw-ss-item';
    public $itemView;
    public $separator;
    public $data =array();
    public $emptyText;
    public $sizeSlidePage = 4;
    protected $jsFile = "slides.min.jquery.js";
    protected $cssFile = "css/slidesgallery.css";
    private $baseUrl;
    public function init(){
        if($this->itemView===null)
            throw new CException(Yii::t('Slides','The property "itemView" cannot be empty.'));        
        $assets = dirname(__FILE__).'/'.'vendor';
        $this->baseUrl = Yii::app()->getAssetManager()->publish($assets);
        Yii::app()->clientScript->registerCssFile($this->baseUrl . '/' . $this->cssFile);
        Yii::app()->clientScript->registerCoreScript('jquery');
        Yii::app()->clientScript->registerScriptFile($this->baseUrl.'/'.$this->jsFile,CClientScript::POS_HEAD);
        parent::init();
    }
    public function run(){
        $this->renderHeader();
        
        $this->renderItems();
        
        $this->renderFooter();
        $this->registerClientScript();
    }
    public function renderItems(){
        $data = $this->data;
        if(($n=count($data)>0)){
            $owner = $this->getOwner();
            $render = $owner instanceof CController ? 'renderPartial' : 'render';
            $j = 0;
            $isPrintCloseTag = false;
            foreach ($data as $item) {
                $owner->$render($this->itemView, array('data'=>$item));
                $j++;
                //echo $this->separator;
            }

        }
        else{
            $this->renderEmptyText();
        }
    }
    public function renderHeader(){
        echo '<div id="slides-main-container">';
        echo '<div id="slides-main-container-exam">';
        
        echo '<div id="'.$this->containerWidgetId.'">';  //
        echo '<div class="slides_container">';
    }
    public function renderFooter(){
        echo '</div> <!-- end slides_container -->';
        echo '<a href="#" class="prev"><img src="'.$this->baseUrl.'/img/arrow-prev.png" width="24" height="43" alt="Arrow Prev"></a>';
        echo '<a href="#" class="next"><img src="'.$this->baseUrl.'/img/arrow-next.png" width="24" height="43" alt="Arrow Next"></a>';
        echo '</div> <!-- end slides -->';
        echo '<img src="'.$this->baseUrl.'/img/example-frame.png" width="739" height="341" alt="Example Frame" id="frame">';
        echo '</div> <!-- end slides-main-container-exam -->';
        echo '</div> <!-- end slides-main-container -->';
   }    
    public function renderEmptyText()
    {
	$emptyText=$this->emptyText===null ? Yii::t('zii','No results found.') : $this->emptyText;
	echo CHtml::tag('span', array('class'=>'empty'), $emptyText);
    }
    public function registerClientScript() {
        $js = <<<EOS
	$('#{$this->containerWidgetId}').slides({		
		preload:{$this->preload},
                preloadImage: '{$this->baseUrl}/img/loading.gif',
		play: 5000,
                pause: 2500,
		hoverPause: true,
		animationStart: function(current){
                    $('.caption').animate({
                        bottom:-35
                    },100);
                    if (window.console && console.log) {
                        // example return of current slide number
                        //console.log('animationStart on slide: ', current);
                    };
		},
		animationComplete: function(current){
                    $('.caption').animate({
                        bottom:0
                    },200);
                    if (window.console && console.log) {
			// example return of current slide number
			//console.log('animationComplete on slide: ', current);
                    };
		},
		slidesLoaded: function() {
                    $('.caption').animate({
			bottom:0
                    },200);
		}
	});
EOS;
        $cs = Yii::app()->getClientScript();
        $cs->registerScript(__CLASS__ . '#' . $this->id, $js, CClientScript::POS_LOAD );
        //parent::registerClientScript();
    }
}
?>