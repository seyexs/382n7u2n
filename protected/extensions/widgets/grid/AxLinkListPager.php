<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AxLinkListPager
 *
 * @author Ardha
 */
class AxLinkListPager extends CBasePager {
    const CSS_FIRST_PAGE = 'first';
    const CSS_LAST_PAGE = 'last';
    const CSS_PREVIOUS_PAGE = 'previous';
    const CSS_NEXT_PAGE = 'next';
    const CSS_INTERNAL_PAGE = '';
    const CSS_HIDDEN_PAGE = 'hidden';
    const CSS_SELECTED_PAGE = 'current';

    public $maxButtonCount = 10;
    public $nextPageLabel;
    public $prevPageLabel;
    public $firstPageLabel;
    public $lastPageLabel;
    public $header;
    public $footer;
    public $cssFile;
    public $htmlOptions = array();
    public $promptText;
    public $pageTextFormat;
    public $listPagerOnly = false;
    public $linkPagerOnly = false;
    public $classLinkPager = "pg";
    public $classListPager = "page";
    public $enableMassAction = false;
    public $massActionOptions = array();
    public $gridId = null;
    public $popupWinWidth = 800;
    public $popupWinHeight = 600;

    public function init() {
        $theme = Yii::app()->theme;

        if ($this->nextPageLabel === null)
            $this->nextPageLabel = $theme !== null ? CHtml::image($theme->baseUrl . "/images/site/btn-next.png") : Yii::t('yii', 'Next &gt;');
        if ($this->prevPageLabel === null)
            $this->prevPageLabel = $theme !== null ? CHtml::image($theme->baseUrl . "/images/site/btn-prev.png") : Yii::t('yii', '&lt; Previous');
        if ($this->firstPageLabel === null)
            $this->firstPageLabel = $theme !== null ? CHtml::image($theme->baseUrl . "/images/site/btn-prev2.png") : Yii::t('yii', '&lt;&lt; First');
        if ($this->lastPageLabel === null)
            $this->lastPageLabel = $theme !== null ? CHtml::image($theme->baseUrl . "/images/site/btn-next2.png") : Yii::t('yii', 'Last &gt;&gt;');
        if ($this->header === null)
            $this->header = Yii::t('yii', 'Page ');
        if ($this->footer === null)
            $this->footer = " of " . $this->pages->pageCount;

        if (!isset($this->htmlOptions['id']))
            $this->htmlOptions['id'] = $this->getId();
        if (!isset($this->htmlOptions['class']))
            $this->htmlOptions['class'] = 'yiiPager';

        if ($this->promptText !== null)
            $this->htmlOptions['prompt'] = $this->promptText;
        if (!isset($this->htmlOptions['onchange']))
            $this->htmlOptions['onchange'] = "if(this.value!='') {window.location=this.value;};";
    }

    public function run() {

        $this->renderMassAction();
        //$this->registerClientScript();
        if (!$this->listPagerOnly) {
            $buttons = $this->createPageButtons();
            if (!empty($buttons)) {
                echo CHtml::openTag("div", array("class" => $this->classLinkPager));
                echo(CHtml::tag('ul', $this->htmlOptions, implode("\n", $buttons)));
                echo CHtml::closeTag("div");
            }
        }

        if (($pageCount = $this->getPageCount()) <= 1)
            return;
        if ($this->linkPagerOnly)
            return;
        $pages = array();

        for ($i = 0; $i < $pageCount; ++$i)
            $pages[$this->createPageUrl($i)] = $this->generatePageText($i);
        $selection = $this->createPageUrl($this->getCurrentPage());
        echo '&nbsp;';
        echo CHtml::openTag("div", array("class" => $this->classListPager));
        echo($this->header);
        echo CHtml::dropDownList($this->getId(), $selection, $pages, $this->htmlOptions);
        echo($this->footer);
        echo CHtml::closeTag("div");
    }

	public function renderMassAction(){
		if(!$this->enableMassAction)
			return;
		$arrData = $this->getDataMassAction($this->massActionOptions);
		$this->registerMassActionScript();
		echo CHtml::openTag("div", array('class' => 'page','style' => 'float:left;width:auto;'));
        echo('');
        echo CHtml::dropDownList('massaction', null, $arrData, array('id' => '__grid-mass-actions__', 'class' => 'yiiPager', 'empty' => 'Select Action'));
        //echo($this->footer);        
        echo CHtml::button('GO', array('class' => 'am-btn', 'style' => 'float:right;margin:0px 25px 0px 4px;'));
        echo CHtml::tag('div', array('class' => 'clearboth'), '');
        echo CHtml::closeTag("div");

	}
    protected function getDataMassAction($buttons) {
        $arr = array();
        foreach ($buttons as $button) {
            $visible = isset($button['visible']) ? $button['visible'] : true;
            if (is_string($visible))
                eval('$visible=' . $visible . ';');
            if ($visible) {
                foreach ($button["url"] as $i => $url) {
                    if (!is_numeric($i) &&
                            !is_numeric($url) &&
                            strstr($url, '$data') !== false){
                        eval('$button["url"]["' . $i . '"] = ' . $url . ';');
                    }
                }
                //if (!isset($button["url"]["id"]))
                //    $button["url"]["id"] = $data->primaryKey;
                $text = "";
                $confirm = (isset($button['confirm']) ? "confirm={$button["confirm"]}" : "");
                $ajax = (isset($button["ajax"]) && $button["ajax"] ? "ajax" : "" );
                $popupwin = (isset($button["popupwin"]) && $button["popupwin"] ? "popupwin" : "" );
                if(!empty($popupwin)){
                	$text = $popupwin . ":";	
                }
                else{
	                if ($ajax != "" && $confirm != "")
	                    $text = $ajax . "|" . $confirm . ":";
	                else if ($ajax != "")
	                    $text = $ajax . ":";
	                else if ($confirm != "")
	                    $text = $confirm . ":";
	                else
	                    $text = "";
				}
                $index = $text .
                        $this->createUrl($button["url"]);
                $arr[$index] = $button["label"];
            }
        }
        return $arr;
    }
    public function createUrl($arr) {
        $route = $arr[0];
        unset($arr[0]);
        return Yii::app()->controller->createUrl($route, $arr);
    }
    protected function createPageButtons() {
        if (($pageCount = $this->getPageCount()) <= 1)
            return array();

        list($beginPage, $endPage) = $this->getPageRange();
        $currentPage = $this->getCurrentPage(false); // currentPage is calculated in getPageRange()
        $buttons = array();

        // first page
        $buttons[] = $this->createPageButton($this->firstPageLabel, 0, self::CSS_FIRST_PAGE, $currentPage <= 0, false);

        // prev page
        if (($page = $currentPage - 1) < 0)
            $page = 0;
        $buttons[] = $this->createPageButton($this->prevPageLabel, $page, self::CSS_PREVIOUS_PAGE, $currentPage <= 0, false);

        // internal pages
        for ($i = $beginPage; $i <= $endPage; ++$i)
            $buttons[] = $this->createPageButton($i + 1, $i, self::CSS_INTERNAL_PAGE, false, $i == $currentPage);

        // next page
        if (($page = $currentPage + 1) >= $pageCount - 1)
            $page = $pageCount - 1;
        $buttons[] = $this->createPageButton($this->nextPageLabel, $page, self::CSS_NEXT_PAGE, $currentPage >= $pageCount - 1, false);

        // last page
        $buttons[] = $this->createPageButton($this->lastPageLabel, $pageCount - 1, self::CSS_LAST_PAGE, $currentPage >= $pageCount - 1, false);

        return $buttons;
    }

    protected function createPageButton($label, $page, $class, $hidden, $selected) {
        /**
         * Here we do some private tweak-ups to have CLinkPager part looking
         * and working just as we want it to look and work.
         */
        $title = 'Page nr ' . ($page + 1);

        if ($class == self::CSS_FIRST_PAGE) {
            $label = $this->firstPageLabel;
            $title = ($hidden) ? '' : 'First Page';
        }

        if ($class == self::CSS_LAST_PAGE) {
            $label = $this->lastPageLabel;
            $title = ($hidden) ? '' : 'Last Page';
        }

        if ($class == self::CSS_PREVIOUS_PAGE) {
            $label = $this->prevPageLabel;
            $title = ($hidden) ? '' : 'Previous Page';
        }

        if ($class == self::CSS_NEXT_PAGE) {
            $label = $this->nextPageLabel;
            $title = ($hidden) ? '' : 'Next page';
        }

        if ($hidden || $selected)
            $class .= ' ' . ($hidden ? self::CSS_HIDDEN_PAGE : self::CSS_SELECTED_PAGE);

        $button = '<li title="' . $title . '">';
        $button.= (!$hidden) ? CHtml::link($label, $this->createPageUrl($page), ($selected ? array("class" => $class) : array())) : "";
        $button.= '</li>';

        return $button;
    }

    protected function getPageRange() {
        $currentPage = $this->getCurrentPage();
        $pageCount = $this->getPageCount();

        $beginPage = max(0, $currentPage - (int) ($this->maxButtonCount / 2));
        if (($endPage = $beginPage + $this->maxButtonCount - 1) >= $pageCount) {
            $endPage = $pageCount - 1;
            $beginPage = max(0, $endPage - $this->maxButtonCount + 1);
        }
        return array($beginPage, $endPage);
    }

    protected function generatePageText($page) {
        if ($this->pageTextFormat !== null)
            return sprintf($this->pageTextFormat, $page + 1);
        else
            return $page + 1;
    }

    public function registerClientScript() {
        if ($this->cssFile !== false)
            self::registerCssFile($this->cssFile);
    }

    public static function registerCssFile($url = null) {
        if ($url === null)
            $url = CHtml::asset(Yii::getPathOfAlias('ext.LinkListPager.pager') . '.css');
        Yii::app()->getClientScript()->registerCssFile($url);
    }
    public function registerMassActionScript() {
        $cs = Yii::app()->clientScript;
        $cs->registerCoreScript("jquery");
        $cs->registerCoreScript("jquery.ui");
        $js = <<<EOS
$('#__grid-mass-actions__').live('change',function(){
    var f = $('#{$this->gridId}').find('form');
    var data = $(f).serialize();
	if($(f).find("input:checkbox").length > 1){
		var checkedRows = $(f).find("input:checked");
		if(checkedRows.length > 0){
		    var pKey = '';
		    var i = 0;
		    $.each(checkedRows, function(idx, val){
		    	if(i > 0)
		    		pKey += ',';
		    	pKey += $(this).val();
		    	i++;
		    });
		    if($(this).val() !== ''){
		        var url = $(this).val().split(':');
		        if(url.length >1){
		        	alert(url[url.length-1]+'/id/'+pKey);
		        	return;
		        	if(url[0].indexOf('popupwin')!= -1){
		                    $.colorbox({iframe:true, overlayClose:false, scrolling:false, href:url[url.length-1],width:"{$this->popupWinWidth}", height:"{$this->popupWinHeight}"});
		        	}
		        	else{
			            if(url[0].indexOf('confirm')!==false){
			                var text = url[0].split('|');
			                if(!confirm(text[text.length-1].split('=')[1]))
			                    return false;                
			            }
			            if(url[0].indexOf('ajax')!==false){
			                var opt = {
			                    type:'get',
			                    url:url[url.length-1],
			                    success:function(data) {                        
			                        if(data.alert=="visible"){
			                            alert(data.msg);
			                        }
			                        if(data.redirect=="yes"){
			                            window.location=data.url;
			                        }
			                        $.fn.yiiGridView.update('{$this->gridId}');
			                    }
			                };
			                $.fn.yiiGridView.update('{$this->gridId}',opt);
			            }else{
			                window.location = $(this).val();
			            }
			        }
		        }else
		            window.location = $(this).val();
		    }
		}
		else{
			$('#__grid-mass-actions__').val('');
			alert('Please select at least one row');
        	//return false;
		}
	}
	else
		$('#__grid-mass-actions__').val('');
});
EOS;
        $cs->registerScript(__CLASS__ . '#' . $this->id . "_massaction", $js);
    }
}
?>
