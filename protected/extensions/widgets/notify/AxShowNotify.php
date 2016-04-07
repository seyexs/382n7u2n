<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AxShowNotify
 *
 * @author ardha
 * @created on Mar 13, 2013
 */
class AxShowNotify extends CWidget {

    //put your code here
    public function run() {
        if (Yii::app()->user->hasFlash('success') || Yii::app()->user->hasFlash('error')) {
            foreach (Yii::app()->user->getFlashes() as $key => $msg) {
                $this->controller->widget('ext.widgets.notify.AxNotify');
                $js = <<<EOS
                    showOnNotify('{$key}', '{$msg}', 2000); 
EOS;
                $cs = Yii::app()->getClientScript();
                $cs->registerScript(__CLASS__ . '#' . $this->id, $js, CClientScript::POS_READY);
                break;
            }
        }
    }

}

?>
