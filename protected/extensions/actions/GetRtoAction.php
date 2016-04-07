<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GetSubPsbpAction
 *
 * @author ardha
 * @created on Feb 22, 2013
 */
class GetRtoAction extends CAction {

    function run() {
        //$this->getController()->layout = false;
        if (isset($_GET['type']) && isset($_GET['group'])) {
            if ($_GET['type'] == CAuthItem::TYPE_ROLE)
                $model = AuthItem::model()->findAll('type = :type AND name != :name', array(':type' => $_GET['type'], ':name' => $_GET['group']));
            elseif ($_GET['type'] == CAuthItem::TYPE_TASK)
                $model = AuthItem::model()->findAll('type = :type', array(':type' => $_GET['type']));
            else {
                $task = substr($_GET['task'], 0, -1);
                $name = "$task%";
                $model = AuthItem::model()->findAll("type = :type AND name LIKE :name", array(':type' => $_GET['type'], ':name' => $name));
            }
            $modelACs = AuthItemChild::model()->findAll('parent=:parent', array(':parent' => $_GET['group']));
            $ExistingAuthChild = array();
            foreach ($modelACs as $m)
                $ExistingAuthChild[$m->child] = $m->child;
            $this->getController()->renderPartial('ext.actions.views.rto', array('model' => $model, 'ExistingAuthChild' => $ExistingAuthChild));
        } elseif (isset($_GET['type']) && isset($_GET['userid'])) {
            if ($_GET['type'] == CAuthItem::TYPE_ROLE || $_GET['type'] == CAuthItem::TYPE_TASK)
                $model = AuthItem::model()->findAll('type = :type', array(':type' => $_GET['type']));
            else {
                $task = substr($_GET['task'], 0, -1);
                $name = "$task%";
                $model = AuthItem::model()->findAll("type = :type AND name LIKE :name", array(':type' => $_GET['type'], ':name' => $name));
            }
            $modelACs = AuthAssignment::model()->findAll('userid=:userid', array(':userid' => $_GET['userid']));
            $ExistingAuthChild = array();
            foreach ($modelACs as $m)
                $ExistingAuthChild[$m->itemname] = $m->itemname;
            $this->getController()->renderPartial('ext.actions.views.rto', array('model' => $model, 'ExistingAuthChild' => $ExistingAuthChild));
        }
    }

}

?>
