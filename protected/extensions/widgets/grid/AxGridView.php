<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AxGridView
 *
 * @author ardha
 * @created on Oct 24, 2012
 */
Yii::import('zii.widgets.grid.CGridView');
Yii::import("ext.widgets.grid.AxLinkListPager");
Yii::import("ext.widgets.grid.AxPageSize");

class AxGridView extends CGridView {

    public $createButtonUrl;
    public $createButtonTitle = 'Buat Baru';
    public $createButtonType = 'normal';
    public $createButtonProperty = array('htmlOptions' => array('class' => 'ax-btn', 'type' => 'button'));
    public $createTopButton = true;
    public $createButtonOptions = array();
    public $createBottomButton = false;
    public $deleteTopButton = true;
    public $deleteBottomButton = false;
    public $deleteButtonUrl = '';
    public $deleteConfirmation = 'Are you sure to delete the Data?';
    public $deleteButtonProperty = array('label' => 'Hapus', 'url' => '#', 'htmlOptions' => array('class' => 'ax-btn-delete', 'type' => 'button'));
    public $deleteUrl = 'delete';
    public $cssButtonClass = 'btn-right';
    public $enablePageSize = true;
    public $pageSize = 10;
    public $notifyTime = 2000;
    public $title;
    public $checkColumn = array('class' => "CCheckBoxColumn");
    public $checkBoxClmId = 'chckbx-clmn';
    private $modulesUrl = '';
    public $enableMassAction = true;
    public $massAction = array();
    public $popupWinWidth = 800;
    public $popupWinHeight = 600;
    public $mergeHeaders = array();
    private $_mergeindeks = array();
    private $_nonmergeindeks = array();
    public $topButtons = array();
    protected $jsFile = "axgridview.js";
    /*public $expandClmId = 'expanded-clmn';
    public $expandColumn = array(
        'htmlOptions' => array('class' => 'x-grid-cell-special'),
        'prefiksId' => 'grid-expand-',
    );*/
    private $isEnabledExpandColumn = false; 

    public function init() {
        if(empty($this->deleteUrl))
            $this->deleteUrl = Yii::app()->controller->createUrl ('delete');
            
        //$this->modulesUrl = Yii::app()->baseUrl . '/' . Yii::app()->controller->uniqueId;
        //$this->deleteUrl = $this->modulesUrl . '/' . $this->deleteUrl;
        if (empty($this->createButtonUrl))
            $this->createButtonUrl = Yii::app()->createUrl($this->controller->id . '/create');
        $this->cssFile = false;
        //$this->checkBoxClmId .= '-' . $this->id;
        //$this->summaryText = false;
        foreach($this->columns as $column){
            if(isset($column['class']) && $column['class']=='ext.widgets.grid.AxExpandColumn'){
                $this->isEnabledExpandColumn = true;
                break;
            }
        }
        if (($this->deleteTopButton || !empty($this->topButtons)) && !$this->isEnabledExpandColumn)
            $this->setCheckBoxColumn();
        $this->pager = array(
            //'class' => 'CLinkPager',
            'class' => 'AxLinkListPager',
            'maxButtonCount' => '8'
        );
        $this->pagerCssClass = "pagination";
        $this->controller->widget('ext.widgets.notify.AxNotify');
        parent::init();
    }

    public function renderContent() {
        $this->renderTitleBar();
        echo '<div class="ax-body-panel">';
        parent::renderContent();
        echo '</div>';
    }

    public function renderSummary() {
        parent::renderSummary();
        if ($this->createTopButton || $this->deleteTopButton || !empty($this->topButtons)) {
            echo CHtml::openTag('div', array('class' => $this->cssButtonClass));
            $this->renderMassAction();
            $this->renderTopButtons();
            if ($this->createTopButton)
                $this->renderCreateButton();
            if ($this->deleteTopButton) {
                //
                //$this->columns = array_merge(array('class' => 'CCheckBoxColumn'), $this->columns);
                echo '&nbsp;';
                $this->renderDeleteButton();
            }

            echo CHtml::closeTag('div');
            echo '<div style="clear: both;"></div>';
        }
    }

    public function renderPager() {
        /* if($this->createBottomButton || $this->deleteBottomButton){
          echo CHtml::openTag('div', array('class' => $this->cssButtonClass, 'style' => 'margin-top:2px;'));
          if ($this->createBottomButton)
          $this->renderCreateButton();
          echo CHtml::closeTag('div');
          } */
        parent::renderPager();
    }

    /* public function renderItems() {
      //$this->renderPageSize();
      parent::renderItems();
      } */

    public function renderItems() {
        if ($this->dataProvider->getItemCount() > 0 || $this->showTableOnEmpty) {
            echo "<table class=\"{$this->itemsCssClass}\">\n";
            if (!empty($this->mergeHeaders)) {
                echo "<thead>\n";

                if ($this->filterPosition === self::FILTER_POS_HEADER)
                    $this->renderFilter();
                $this->renderGroupHeaders();
                if ($this->filterPosition === self::FILTER_POS_BODY)
                    $this->renderFilter();
                echo "</thead>\n";
            } else {
                $this->renderTableHeader();
            }
            $this->renderTableBody();
            $this->renderTableFooter();
            echo "</table>";
        }
        else
            $this->renderEmptyText();
    }

    public function renderGroupHeaders() {
        $this->setMergeIndeks();
        $this->setNonMergeIndeks();
        echo "<tr>\n";

        ob_start();
        echo "<tr>\n";
        $i = 0;
        foreach ($this->columns as $column) {
            if (in_array($i, $this->_mergeindeks)):
                $column->headerHtmlOptions['colspan'] = '1';
                $column->renderHeaderCell();
            endif;
            $i++;
        }
        echo "</tr>\n";
        $header_bottom = ob_get_clean();

        $i = 0;
        foreach ($this->columns as $column) {
            for ($m = 0; $m < count($this->mergeHeaders); $m++) {
                if ($i == $this->mergeHeaders[$m]["start"]):
                    $column->headerHtmlOptions['colspan'] = $this->mergeHeaders[$m]["end"] - $this->mergeHeaders[$m]["start"] + 1;
                    $column->header = $this->mergeHeaders[$m]["name"];
                    $column->id = NULL;
                    $column->renderHeaderCell();
                endif;
            }
            if (in_array($i, $this->_nonmergeindeks)) {
                $column->headerHtmlOptions['rowspan'] = '2';
                $column->renderHeaderCell();
            }
            $i++;
        }
        echo "</tr>\n";

        echo $header_bottom;
    }

    protected function setMergeIndeks() {
        for ($i = 0; $i < count($this->mergeHeaders); $i++)
            for ($j = $this->mergeHeaders[$i]["start"]; $j <= $this->mergeHeaders[$i]["end"]; $j++)
                $this->_mergeindeks[] = $j;
    }

    protected function setNonMergeIndeks() {
        foreach ($this->columns as $key => $val)
            $h[] = $key;
        $this->_nonmergeindeks = array_diff($h, $this->_mergeindeks);
    }

    public function setCheckBoxColumn() {
        $this->checkColumn['htmlOptions'] = array('class' => 'grid-chkbox');
        $this->checkColumn['headerHtmlOptions'] = array('style' => 'text-align:center');
        if (!isset($this->checkColumn['selectableRows']))
            $this->checkColumn['selectableRows'] = 2;
        if (!isset($this->checkColumn["id"]))
            $this->checkColumn["id"] = $this->checkBoxClmId;


        $this->columns = array_merge(array($this->checkColumn), $this->columns);
    }

    public function renderExpandTool() {
        if (!isset($this->expandColumn["id"]))
            $this->expandColumn["id"] = $this->expandClmId;


        $this->columns = array_merge(array($this->expandColumn), $this->columns);
    }

    private function renderCreateButton() {
        if (!empty($this->createButtonOptions)) {
            if (isset($this->createButtonOptions['popupwin']) && $this->createButtonOptions['popupwin']) {
                $width = isset($this->createButtonOptions['width']) ? $this->createButtonOptions['width'] : 400;
                $height = isset($this->createButtonOptions['height']) ? $this->createButtonOptions['height'] : 300;
                $this->createButtonProperty['htmlOptions']['onclick'] = 'PopUpWin("' . $this->createButtonUrl . '","' . $width . '","' . $height . '")';
            }
        }
        else
            $this->createButtonProperty['htmlOptions']['onclick'] = 'MM_goToURL("' . $this->createButtonUrl . '")';
        echo CHtml::button($this->createButtonTitle, $this->createButtonProperty['htmlOptions']);
    }

    private function renderDeleteButton() {
        $this->deleteButtonProperty['htmlOptions']['id'] = 'mass-delete-act-' . $this->id;
        echo CHtml::button($this->deleteButtonProperty['label'], $this->deleteButtonProperty['htmlOptions']);
    }

    private function renderTopButtons() {
        if (empty($this->topButtons))
            return;
        foreach ($this->topButtons as $button) {
            $visible = isset($button['visible']) ? $button['visible'] : true;
            if (is_string($visible))
                eval('$visible=' . $visible . ';');
            if ($visible) {

                $confirm = (isset($button['confirm']) ? "confirm={$button["confirm"]}" : "");
                $ajax = (isset($button["ajax"]) && $button["ajax"] ? "ajax" : "" );
                $popupwin = (isset($button["popupwin"]) && $button["popupwin"] ? "popupwin" : "" );

                $text = "";
                $this->createButtonProperty['htmlOptions']['class'] = 'ax-btn';
                if (!empty($popupwin))
                    $this->createButtonProperty['htmlOptions']['onclick'] = 'PopUpWin("' . $button['url'][0] . '","' . $this->popupWinWidth . '","' . $this->popupWinHeight . '")';
                elseif (!empty($ajax))
                    $this->createButtonProperty['htmlOptions']['onclick'] = 'doAjaxAction("' . $button['url'][0] . '","' . $button['confirm'] . '")';
                else
                    $this->createButtonProperty['htmlOptions']['onclick'] = 'MM_goToURL("' . $button['url'][0] . '")';
                echo CHtml::button($button['label'], $this->createButtonProperty['htmlOptions']);
            }
        }
    }

    public function renderMassAction() {
        if (!$this->enableMassAction || empty($this->massAction))
            return;
        //$arrData = array();
        $arrData = $this->getDataMassAction($this->massAction);
        $this->registerMassActionScript();
        echo CHtml::openTag("div", array('style' => 'float:left;margin-right:20px;'));
        echo('<div style="float:left;padding-top:2px;margin-right:2px;">Action:</div>');
        echo CHtml::dropDownList('massaction', null, $arrData, array('id' => 'grid-mass-actions-' . $this->id, 'class' => 'mass-action-grid', 'empty' => 'Select Action'));
        echo CHtml::closeTag("div");
    }

    public function renderPageSize() {
        if (!$this->enablePageSize)
            return;

        $pageSize = array();
        $class = 'AxPageSize';
        if (is_string($this->pageSize))
            $class = $this->pageSize;
        else if (is_array($this->pageSize)) {
            $pageSize = $this->pageSize;

            if (isset($pageSize['class'])) {
                $class = $pageSize['class'];
                unset($pageSize['class']);
            }
        }
        $pageSize["afterUpdate"] = "
                $.fn.yiiGridView.update('{$this->id}');
            ";
        $pageSize['pages'] = $this->dataProvider->getPagination();
        echo CHtml::openTag("div", array("class" => "ma-actions"));
        $pageSize = $this->widget($class, $pageSize);
        echo CHtml::closeTag("div");
        //$this->renderPager();
    }

    public function renderTitleBar() {
        echo '<div class="x-panel-header-default x-horizontal x-panel-header-horizontal x-panel-header-default-horizontal x-top x-panel-header-top x-panel-header-default-top x-docked-top x-panel-header-docked-top x-panel-header-default-docked-top x-unselectable" style="height:17px;border:0px;border-radius: 4px 4px 4px 4px;">';
        echo '                    <span class="x-panel-header-text x-panel-header-text-default">';
        echo '                        ' . $this->title;
        echo '                    </span>';
        echo '</div>';
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
                            strstr($url, '$data') !== false) {
                        eval('$button["url"]["' . $i . '"] = ' . $url . ';');
                    }
                }
                //if (!isset($button["url"]["id"]))
                //    $button["url"]["id"] = $data->primaryKey;
                $text = "";
                $confirm = (isset($button['confirm']) ? "confirm={$button["confirm"]}" : "");
                $ajax = (isset($button["ajax"]) && $button["ajax"] ? "ajax" : "" );
                $popupwin = (isset($button["popupwin"]) && $button["popupwin"] ? "popupwin" : "" );
                if (!empty($popupwin)) {
                    $text = $popupwin . ":";
                } else {
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

    /**
     * Renders a table body row.
     * @param integer $row the row number (zero-based).
     */
    public function renderTableRow($row) {
        if ($this->rowCssClassExpression !== null) {
            $data = $this->dataProvider->data[$row];
            $class = $this->evaluateExpression($this->rowCssClassExpression, array('row' => $row, 'data' => $data));
        } else if (is_array($this->rowCssClass) && ($n = count($this->rowCssClass)) > 0)
            $class = $this->rowCssClass[$row % $n];
        else
            $class = '';

        echo empty($class) ? '<tr>' : '<tr class="' . $class . '">';
        foreach ($this->columns as $column)
            $column->renderDataCell($row);
        echo "</tr>\n";
        //$data=$this->dataProvider->data[$row];
            $c = count($this->columns);
            if($c > 0){
                $divId = $this->id . '-expand-content-' . $row;
                $rowId = $this->id . '-gridvw-row-' . $row;
                $options = array('id'=>$divId,'style'=>'padding-left:35px;');
                echo '<tr id="'.$rowId.'" class="'.$class.'" style="display: none;">';
                echo '<td colspan="'.$c.'">';
                echo CHtml::openTag('div', $options);
                echo '123<br>';
                echo '234';
                echo CHtml::closeTag('div');
                echo '</td>';
                echo "</tr>\n";
            }
        
        
    }

    protected function createUrl($arr) {
        $route = $arr[0];
        unset($arr[0]);
        return Yii::app()->controller->createUrl($route, $arr);
    }

    public function registerClientScript() {
        parent::registerClientScript();
        $assets = dirname(__FILE__) . '/' . 'js';
        $baseUrl = Yii::app()->getAssetManager()->publish($assets);
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');
        $cs->registerScriptFile($baseUrl . '/' . $this->jsFile, CClientScript::POS_END);
        $js = <<<EOS
$('#mass-delete-act-{$this->id}').live('click',function(){
    var arrVal = $.fn.yiiGridView.getChecked('{$this->id}','{$this->checkBoxClmId}');
    if(arrVal.length == 0){
        alert('Silahkan pilih minimal satu baris data');
        return false;
    }
    if(!confirm('{$this->deleteConfirmation}')) return false;
    var pKey = '';
    for(i=0; i<arrVal.length; i++){
        if(i > 0)
            pKey += ',';
        pKey += arrVal[i];
    };
    var opt = {
        url:'{$this->deleteUrl}/id/'+pKey,
        success:function(data) {
            $.fn.yiiGridView.update('{$this->id}');
            showOnNotify(data.type, data.msg, {$this->notifyTime});
        },
        error:function(XHR) {
            showOnNotify(XHR.status,XHR, {$this->notifyTime});
        }
    };
    $.fn.yiiGridView.update('{$this->id}', opt);
    return false;
});
EOS;

        $cs->registerScript(__CLASS__ . '#' . $this->id, $js, CClientScript::POS_END);
    }

    public function registerMassActionScript() {
        $cs = Yii::app()->clientScript;
        $cs->registerCoreScript("jquery");
        $cs->registerCoreScript("jquery.ui");
        $js = <<<EOS
$('#grid-mass-actions-' . $this->id).live('change',function(){
    var f = $('#{$this->id}').find('form');
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
			                        $.fn.yiiGridView.update('{$this->id}');
			                    }
			                };
			                $.fn.yiiGridView.update('{$this->id}',opt);
			            }else{
			                window.location = $(this).val();
			            }
			        }
		        }else
		            window.location = $(this).val();
		    }
		}
		else{
			$('#grid-mass-actions-' . $this->id).val('');
			alert('Please select at least one row');
        	//return false;
		}
	}
	else
		$('#'grid-mass-actions-' . $this->id).val('');
});
EOS;
        $cs->registerScript(__CLASS__ . '#' . $this->id . "_massaction", $js);
    }

}

?>
