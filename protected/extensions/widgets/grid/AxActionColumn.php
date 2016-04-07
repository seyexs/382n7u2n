<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
Yii::import('zii.widgets.grid.CGridColumn');

/**
 * Description of AxActionColumn
 *
 * @author Ardha
 */
class AxActionColumn extends CGridColumn {

    public $buttons = array();
    public $header = "Action";
    public $htmlOptions = array('class' => 'action-column');
    public $selectOptions = array();
    public $emptyText = "Select Action";
    public $empty = true;
    public $selected = '';
    public $popupWinWidth = 800;
    public $popupWinHeight = 600;

    protected function renderDataCellContent($row, $data) {
        $arr = array();
        foreach ($this->buttons as $button) {
            $visible = isset($button['visible']) ? $button['visible'] : true;
            if (is_string($visible))
                eval('$visible=' . $visible . ';');
            if ($visible) {
                if (isset($button["url"]) && $button["url"] !== false) {
                    foreach ($button["url"] as $i => $url) {
                        if (!is_numeric($i) &&
                                !is_numeric($url) &&
                                strstr($url, '$data') !== false) {
                            eval('$button["url"]["' . $i . '"] = ' . $url . ';');
                        }
                    }
                    if (!isset($button["url"]["id"]))
                        $button["url"]["id"] = $data->primaryKey;
                }
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
                        ($button["url"] !== false ? $this->createUrl($button["url"]) : '');
                $arr[$index] = $button["label"];
                $selected = false;
                if (isset($button['selected'])) {
                    if (!is_bool($button['selected']))
                        eval('$selected = ' . $button['selected'] . ';');
                    else
                        $selected = $button['selected'];
                }
                if ($selected)
                    $this->selected = $index;
            }
        }
        echo CHtml::dropDownList($this->grid->id . "_action[]", $this->selected, $arr, $this->selectOptions);
    }

    public function createUrl($arr) {
        $route = $arr[0];
        unset($arr[0]);
        return Yii::app()->controller->createUrl($route, $arr);
    }

    public function registerScript() {
        $cs = Yii::app()->clientScript;
        $cs->registerCoreScript("jquery");
        $cs->registerCoreScript("jquery.ui");
        $js = <<<EOS
$('#{$this->grid->id} select.{$this->selectOptions['class']}').live('change',function(){
    if($(this).val() !== ''){
        var url = $(this).val().split(':');
        if(url.length >1){
            if(url[0].indexOf('popupwin')!= -1){
                    $(this).val('');
                    $.colorbox({
                        iframe:true, 
                        overlayClose:false, 
                        scrolling:false, 
                        href:url[url.length-1],
                        width:"{$this->popupWinWidth}", 
                        height:"{$this->popupWinHeight}",
                        opacity:0.25,
                        
                    });
            }
            else{
                if(url[0].indexOf('confirm')!==false){
                    var text = url[0].split('|');
                    var confText = text[text.length-1].split('=')[1];
                    if(confText != undefined)
                        if(!confirm(confText))
                            return false;

                }
                if(url[0].indexOf('ajax')!==false){
                    var opt = {
                        type:'get',
                        url:url[url.length-1],
                        success:function(data) {
                            $.fn.yiiGridView.update('{$this->grid->id}');
                            showOnNotify(data.type, data.msg, {$this->grid->notifyTime});
                        },
                        complete:function(){

                        }    
                    };
                    $.fn.yiiGridView.update('{$this->grid->id}',opt);
                }else
                    window.location = $(this).val();
            }
        }else
            window.location = $(this).val();
        $(this).val('');
    }
});
EOS;
        $cs->registerScript($this->grid->id . "_action", $js);
    }

    public function init() {
        if ($this->empty)
            $this->selectOptions['empty'] = $this->emptyText;
        if (!isset($this->selectOptions['class']))
            $this->selectOptions['class'] = "selAction";
        $this->registerScript();
        parent::init();
    }

}

?>
