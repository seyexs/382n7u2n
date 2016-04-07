<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of XPanel
 *
 * @author ardha
 * @created on Oct 23, 2012
 */
class XPanel extends CWidget {

    //put your code here
    public $showHeader = true;
    public $title = '';
    public $width = 930;
    public $height;
    public $cssClass = 'x-panel x-panel-default-framed';
    public $cssBodyClass = 'ax-body-panel';
    //public $style = '';

    public function init() {
        //echo CHtml::openTag('div', array('class' => $this->cssClass, 'style' => 'width:'.$this->width.'px;height:'.$this->height.'px;'));
        echo '<div class="ax-panel">';
        if ($this->showHeader)
            $this->renderHeader();
        $wd = $this->width-10;
        if(!empty($this->height)){
            $hg = $this->height-49;
            echo '<div class="'.$this->cssBodyClass.'" style="height: '.$hg.'px;overflow-y:auto;">';
        }
        else
            echo '<div class="'.$this->cssBodyClass.'">';
        //echo '<div style="padding: 5px 5px 0px; width: '.$wd.'px; left: 0px; top: 26px; height: '.$hg.'px; overflow:auto;" class="x-panel-body x-panel-body-default-framed x-docked-noborder-top x-docked-noborder-right x-docked-noborder-bottom x-docked-noborder-left">';
    }

    public function renderHeader() {
        echo '<div class="x-panel-header-default x-horizontal x-panel-header-horizontal x-panel-header-default-horizontal x-top x-panel-header-top x-panel-header-default-top x-docked-top x-panel-header-docked-top x-panel-header-default-docked-top x-unselectable" style="height:17px;border:0px;">';
        echo '                    <span class="x-panel-header-text x-panel-header-text-default">';
        echo '                        '.$this->title;
        echo '                    </span>';
        echo '</div>';
    }


    public function run() {
        echo CHtml::closeTag('div');
        echo CHtml::closeTag('div');
    }

}
?>