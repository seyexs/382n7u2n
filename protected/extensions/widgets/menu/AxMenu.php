<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AxMenu2
 *
 * @author ardha
 * @created on Nov 3, 2012
 */
Yii::import('zii.widgets.CMenu');

class AxMenu extends CMenu {

    public $cssClsMainMenu = 'menulink';
    public $cssClsMainMenuHasSub = 'menulink';
    public $cssClsSubMenu = 'sub';
    public $htmlOptions = array('class' => 'menu');
    protected $jsFile = "script.js";
    protected $cssFile = "menu.css";
    public $isNeedContainer = true;
    private $isShow = false;

    public function init() {
        $this->registerAsset();
        parent::init();
    }

    public function run() {
        //if($this->isNeedContainer)
        //    echo '<div id="main-nav">';
        $this->renderMenu($this->items);
//        if($this->isNeedContainer){
//            echo '</div>';
//            echo '<div style="clear:both;"></div>';
//        }
        $this->registerClientScript();
    }

    protected function renderMenu($items) {
        if (count($items)) {
            echo CHtml::openTag('ul', $this->htmlOptions) . "\n";
            $this->renderMenuRecursive($items, true);
            echo CHtml::closeTag('ul');
        }
    }

    protected function renderMenuRecursive($items, $isMain=true) {
        $count = 0;
        //Ax::print_rx($items);
        $n = count($items);
        foreach ($items as $item) {
            $this->isShow = false;
            if (!empty($item['url'][0]) && empty($item['items']))
                $this->isShow = true;

            elseif (!empty($item['items'])) {
                $this->checkIsItemCanBeShowRecursive($item['items']);
            }
            if ($this->isShow) {
                $count++;
                echo CHtml::openTag('li', array());
                if (isset($item['items']) && count($item['items']))
                    $menu = $this->renderMenuItemExt($item, $isMain, true);
                else
                    $menu = $this->renderMenuItemExt($item, $isMain, false);
                if (isset($this->itemTemplate) || isset($item['template'])) {
                    $template = isset($item['template']) ? $item['template'] : $this->itemTemplate;
                    echo strtr($template, array('{menu}' => $menu));
                }
                else
                    echo $menu;

                if (isset($item['items']) && count($item['items'])) {
                    echo "\n" . CHtml::openTag('ul', isset($item['submenuOptions']) ? $item['submenuOptions'] : $this->submenuHtmlOptions) . "\n";
                    $this->renderMenuRecursive($item['items'], false);
                    echo CHtml::closeTag('ul') . "\n";
                }

                echo CHtml::closeTag('li') . "\n";
            }
        }
    }

    private function checkIsItemCanBeShowRecursive($items) {
        foreach ($items as $item) {
            if (!empty($item['url'][0]) && empty($item['items'])) {
                $this->isShow = true;
                break;
            } elseif (!empty($item['items'])) {
                $this->checkIsItemCanBeShowRecursive($item['items']);
            }
        }
    }

    protected function renderMenuRecursive2($items, $isMain=true) {
        $count = 0;
        $n = count($items);
        foreach ($items as $item) {
            $count++;
            echo CHtml::openTag('li', array());
            if (isset($item['items']) && count($item['items']))
                $menu = $this->renderMenuItemExt($item, $isMain, true);
            else
                $menu = $this->renderMenuItemExt($item, $isMain, false);
            if (isset($this->itemTemplate) || isset($item['template'])) {
                $template = isset($item['template']) ? $item['template'] : $this->itemTemplate;
                echo strtr($template, array('{menu}' => $menu));
            }
            else
                echo $menu;

            if (isset($item['items']) && count($item['items'])) {
                echo "\n" . CHtml::openTag('ul', isset($item['submenuOptions']) ? $item['submenuOptions'] : $this->submenuHtmlOptions) . "\n";
                $this->renderMenuRecursive($item['items'], false);
                echo CHtml::closeTag('ul') . "\n";
            }

            echo CHtml::closeTag('li') . "\n";
        }
    }

    /**
     * Renders the content of a menu item.
     * Note that the container and the sub-menus are not rendered here.
     * @param array $item the menu item to be rendered. Please see {@link items} on what data might be in the item.
     * @return string
     * @since 1.1.6
     */
    protected function renderMenuItemExt($item, $isMain, $isSub=false) {
        $options = array();
        //Ax::print_r($item);
        if ($isMain) {
            if ($isSub)
                $options['class'] = $this->cssClsMainMenuHasSub;
            else
                $options['class'] = $this->cssClsMainMenu;
        }
        elseif ($isSub)
            $options['class'] = $this->cssClsSubMenu;

        if (!empty($item['cssclass']))
            $options['class'] .= ' ' . $item['cssclass'];
        if (isset($item['url'])) {
            $label = $this->linkLabelWrapper === null ? $item['label'] : '<' . $this->linkLabelWrapper . '>' . $item['label'] . '</' . $this->linkLabelWrapper . '>';
            return CHtml::link($label, $item['url'], isset($item['linkOptions']) ? $item['linkOptions'] : $options);
        } else {
            $label = $item['label'];
            return $txt . CHtml::link($label, '#', isset($item['linkOptions']) ? $item['linkOptions'] : $options);
        }
    }

    private function registerAsset() {
        $assets = dirname(__FILE__) . '/' . 'vendor';
        $baseUrl = Yii::app()->getAssetManager()->publish($assets);
        //Yii::app()->clientScript->registerCssFile($baseUrl . '/' . $this->cssFile);
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/' . $this->jsFile, CClientScript::POS_HEAD);
    }

    private function registerClientScript() {
        $js = <<<EOS
	//var dropdown=new TINY.dropdown.init("dropdown", {id:'{$this->id}', active:'menuhover'});
        var {$this->id}=new menu.dd("{$this->id}");
	{$this->id}.init("{$this->id}","menuhover");
EOS;
        $cs = Yii::app()->getClientScript();
        $cs->registerScript(__CLASS__ . '#' . $this->id, $js, CClientScript::POS_END);
    }

}

?>
