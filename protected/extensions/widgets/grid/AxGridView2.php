<?php

/*
 * impord class CGridView
 * and open the template in the editor.
 */
Yii::import("zii.widgets.grid.CGridView");
Yii::import("ext.widgets.grid.*");

/**
 * Description of AxGridView
 *
 * @author ACTIN01
 */
class AxGridView extends CGridView {

    /**
     * @var string the URL of the CSS file used by this grid view. Defaults to null, meaning using the integrated
     * CSS outline. If this is set false, you are responsible to explicitly include the necessary CSS file in your page.
     */
    public $outline = true;
    public $pageSize = "AxPageSize";
    public $template = "{pageSize}{buttonAction}{items}{buttonAction}{pager}";
    public $delVar = 'deleteCheckBox';
    public $checkColumn = array('class' => "CCheckBoxColumn");
    public $btActionBar = array();
    public $delButton = true;
    public $comfirmDelete = "Are you sure you want to delete this record(s)?";
    //public $enableSorting = false;
    public $pager = array('class' => 'AxLinkListPager');
    public $formSearchId;
    public $modulesUrlDefault;
	public $deleteAction = 'delete';
	//public $enableMassAction = true;
	//Add for Mass Action
    public $enableMassAction = false;
    public $isMassActionEnable = true;
    public $massAction = array();
    

    /**
     * $var boolean
     */
    public $enablePageSize = true;

    /**
     * Initializes the grid view.
     * This method will initialize required property values and instantiate {@link columns} objects.
     */
    public function init() {
        if($this->isMassActionEnable){
            $this->pager = array(
                'class' => 'AxLinkListPager',
                'enableMassAction' => true,
                'massActionOptions' => $this->massAction,
                'gridId' => $this->id,
            );
        }


        $this->htmlOptions['class'] = "t";
        $this->pagerCssClass = "pagination";
        $this->modulesUrlDefault = Yii::app()->baseUrl . '/' . Yii::app()->controller->uniqueId . '/' . Yii::app()->controller->defaultAction;
        //do not import css file from CGridview
        
        //$this->cssFile = false;

        if ($this->outline) {
            $this->itemsCssClass = "outline";
        }
        if (strstr($this->template, "{buttonAction}") !== false && $this->delButton) {
            $this->checkBoxsColumn();
        }
        parent::init();
    }

    public function checkBoxsColumn() {
       $this->checkColumn['htmlOptions'] = array('align' => 'center', 'valign' => 'top');
       $this->checkColumn['headerHtmlOptions'] = array('style' => 'text-align:center');
       if (!isset($this->checkColumn['selectableRows']))
            $this->checkColumn['selectableRows'] = 2;
        if (!isset($this->checkColumn["id"]))
            $this->checkColumn["id"] = $this->delVar;
       
        
        $this->columns = array_merge(array($this->checkColumn), $this->columns);
    }

    public function renderButtonAction() {

        echo CHtml::openTag('div', array("class" => "ma-actions"));
        if ($this->delButton)
            echo CHtml::button('Delete', array("class" => 'delete am-btn'));
        foreach ($this->btActionBar as $bt) {
            $url = $bt['url'][0];
            unset($bt['url'][0]);
            $url = Yii::app()->controller->createUrl($url, $bt['url']);
            if (isset($bt['htmlOptions'])) {
                $htmlOptions = $bt['htmlOptions'];
                if (!isset($htmlOptions['class']))
                    $htmlOptions["class"] = "am-long-btn";
            }else
                $htmlOptions = array(
                    "class" => 'am-long-btn',
                    'onclick' => "window.location='{$url}'");
            echo CHtml::button($bt["label"], $htmlOptions);
        }

        echo CHtml::closeTag('div');
        $cs = Yii::app()->clientScript;
        $js = <<<EOS
$('#{$this->id} .ma-actions input.delete').live('click',function(){
    var f = $(this).parents('#{$this->id}').find('form');
    var data = $(f).serialize();
	if($(f).find("input:checkbox").length > 1){
		if($(f).find("input:checked").length > 0){
			if(!confirm('{$this->comfirmDelete}'))
				return false;
			var opt = {
				type:'POST',
				url:$(f).attr('action'),
				data:data,
				success:function(data) {
					$.fn.yiiGridView.update('{$this->id}');
					if($.trim(data)!='') {
						var div = $('<div class="postMessage"></div>').html(data);
						$('.main-title').before(div);
						setTimeout(function() {
							$(div).hide("blind");
						}, 4000);
					}
                                        
				},
			};
			$.fn.yiiGridView.update('{$this->id}',opt);
		}
	}
});
EOS;
        $cs->registerScript("{$this->id}_action_button", $js);
    }

    public function renderItems() {
        if (strstr($this->template, "{buttonAction}") !== false) {
            $form = $this->beginWidget("CActiveForm", array(
                "action" => Yii::app()->controller->createUrl($this->deleteAction))
            );
            parent::renderItems();
            $this->endWidget();
        }else
            parent::renderItems();
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
        $this->renderPager();
    }
    public function renderMassAction(){
        if(!$this->enableMassAction)
            return;
        echo CHtml::openTag('div');
        echo CHtml::label('With', null);
        echo CHtml::dropDownList('select_button', null, array(), array());
        echo CHtml::button('GO', array());
        echo CHtml::tag('div', array('class' => 'clearboth'), '');
        echo CHtml::closeTag('div');
        
    }
    public function registerClientScript() {
        parent::registerClientScript();
        $cs = Yii::app()->getClientScript();
        $js = <<<EOS
function onClickSearch(form_id)
{
     var t = $(form_id).serialize();
     $.fn.yiiGridView.update('{$this->id}', {url:'{$this->modulesUrlDefault}',data:t});
   
}\n     
EOS;
        $js .= <<<EOS
function onClickClearSearch()
{
     $.fn.yiiGridView.update('{$this->id}', {url:'{$this->modulesUrlDefault}'});  
}\n     
EOS;
        if (isset($this->formSearchId)) {
            $js .= <<<EOS
$("#{$this->formSearchId}").keypress(function(event) {
  if( event.which == 13 ) {
     event.preventDefault();
     var t = $(this).serialize();
     $.fn.yiiGridView.update('{$this->id}', {url:'{$this->modulesUrlDefault}',data:t});
  }
});
\n  
EOS;
        }
        $cs->registerScript(__CLASS__ . '#' . $this->id, $js, CClientScript::POS_END);
    }

}