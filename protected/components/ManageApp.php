<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ManageApp
 *
 * @author ardha
 * @created on Mar 1, 2013
 */
class ManageApp extends CApplicationComponent {

    //put your code here
    public $rights;
    public $_authorizer;

    public function __construct() {
        $this->rights = Yii::app()->getModule('rights');
        $this->_authorizer = $this->rights->getAuthorizer();
    }

    public function registerApp() {
        $generator = $this->rights->getGenerator();
        $items = $generator->getControllerActions();
        $existingItems = $this->getExistingAuthItems();

        //Ax::print_r($existingItems);exit;
        if ($items['controllers'] !== array()) {
            foreach ($items['controllers'] as $key => $item) {
                if (isset($item['actions']) === true && $item['actions'] !== array()) {
                    $controllerKey = isset($moduleName) === true ? ucfirst($moduleName) . '.' . $item['name'] : $item['name'];
                    $cKey = $controllerKey . '.*';
                    $existingChildItems = $this->getExistingAuthItemChild($cKey);
                    //$controllerExists = isset($existingItems[$cKey]);
                    if (!isset($existingItems[$cKey])) {
                        $modelC = new AuthItem('insert');
                        $modelC->name = $cKey;
                        $modelC->type = CAuthItem::TYPE_TASK;
                        $modelC->save();
                        //if($modelC->hasErrors())
                        //    Ax::print_r($modelC->errors);
                    }
                    $i = 0;
                    foreach ($item['actions'] as $action) {
                        $actionKey = $controllerKey . '.' . ucfirst($action['name']);
                        $actionExists = isset($existingItems[$actionKey]);
                        if (!$actionExists) {
                            $modelA = new AuthItem('insert');
                            $modelA->name = $actionKey;
                            $modelA->type = CAuthItem::TYPE_OPERATION;
                            $modelA->save();
                        }
                        $pcExists = isset($existingChildItems[$cKey][$actionKey]);
                        if (!$pcExists) {
                            $modelAI = new AuthItemChild('insert');
                            $modelAI->parent = $cKey;
                            $modelAI->child = $actionKey;
                            $modelAI->save();
                        }
                    }
                }
            }
        }
    }

    private function getExistingAuthItems() {
        $authItems = $this->_authorizer->getAuthItems(array(
            CAuthItem::TYPE_TASK,
            CAuthItem::TYPE_OPERATION,
                ));
        $existingItems = array();
        foreach ($authItems as $itemName => $item)
            $existingItems[$itemName] = $itemName;
        return $existingItems;
    }

    private function getExistingAuthItemChild($parent) {
        $models = AuthItemChild::model()->findAll('parent=:parent', array(':parent' => $parent));

        $existingItems = array();
        if (!empty($models)) {
            foreach ($models as $model) {
                if (!isset($existingItems[$model->parent]))
                    $existingItems[$model->parent] = array();
                $existingItems[$model->parent][$model->child] = $model->parent;
            }
        }
        return $existingItems;
    }

    public function cleanRegisteredGarbageApp() {
        $generator = $this->rights->getGenerator();
        $items = $generator->getControllerActions();
        $existingItems = $this->getExistingAuthItems();
    }

    public function getUnRegisterdApp() {
        $generator = $this->rights->getGenerator();
        $items = $generator->getControllerActions();
        $existingItems = $this->getExistingAuthItems();
        $listOptions = array();
        //Ax::print_r($existingItems);exit;
        if ($items['controllers'] !== array()) {
            foreach ($items['controllers'] as $key => $item) {
                if (isset($item['actions']) === true && $item['actions'] !== array()) {
                    $controllerKey = isset($moduleName) === true ? ucfirst($moduleName) . '.' . $item['name'] : $item['name'];
                    $cKey = $controllerKey . '.*';
                    if (!isset($existingItems[$cKey]))
                         $listOptions[$cKey] = $cKey;
                    foreach ($item['actions'] as $action) {
                        $actionKey = $controllerKey . '.' . ucfirst($action['name']);
                        if(!isset($existingItems[$actionKey]))
                             $listOptions[$actionKey] = $actionKey;
                    }
                        
                }
            }
        }
        return $listOptions;
    }

    public function test() {
        //$this->rights = Yii::app()->getModule('rights');
        //$generator = $this->rights->getGenerator();
        //$items = $generator->getControllerActions();
        $authItems = $this->_authorizer->getAuthItems(array(
            CAuthItem::TYPE_TASK,
            CAuthItem::TYPE_OPERATION,
                ));
        $existingItems = array();
        foreach ($authItems as $itemName => $item)
            $existingItems[$itemName] = $itemName;
        return $existingItems;
    }

}

?>
