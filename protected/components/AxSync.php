<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AxSync
 *
 * @author ardha
 * @created on Jan 21, 2013
 */
class AxSync {

    private $errors = array();
    private $header_sync_id;
    private $modelHeaderSysnc = 'HeaderSinkronisasi';
    private $refNameHeaderSysnc = 'header_sinkronisasi_id';
    private $nm_sekolah;
    private $npsn;
    public $mapbref = array();
    public $isFormatOutput = true;
    public $isIncludeSchema = true;
    private $ignoredFields = array('created_date', 'created_by', 'modified_date', 'modified_by');
    private $mapFields = array(
        't_spectrum_id' => 'rm_spectrum_id',
        't_bidang_studi_id' => 'rm_bidang_studi_id',
        't_program_studi_id' => 'rm_program_studi_id',
    );

    public function __construct() {
        //$this->xmlfile = $filename;
    }

    public function createXMLFromData($filetype = array(), $sid = null) {
        if (!empty($filetype)) {
            if (!empty($sid)) {
                $model = MSekolah::model()->findByPk($sid);
                if ($model !== null) {
                    $this->npsn = $model->npsn;
                    $this->nm_sekolah = $model->nama_sekolah;
                }
            }
            $xml = new SimpleXMLElement('<xml/>');
            $xml->addChild('created', time());
            if (!empty($this->npsn))
                $xml->addChild('npsn', $this->npsn);
            else
                $xml->addChild('npsn', 'unknown');
            $modelsRaw = $xml->addChild('models');
            $file = '';
            $filename = '';

            if (count($filetype) > 1) {
                if (!empty($this->nm_sekolah))
                    $file = str_replace(' ', '_', strtolower($this->nm_sekolah)) . '-';
                $file = 'sync-' . $file . date("d-m-Y_Hi") . '.xml';
            }
            foreach ($filetype as $type) {
                if (empty($file)) {
                    if (!empty($this->nm_sekolah))
                        $file = '-' . str_replace(' ', '_', strtolower($this->nm_sekolah));
                    $file = $type . $file . '.xml';
                }
                if (empty($filename)) {
                    $filename = Yii::getPathOfAlias('webroot') . '/temp/' . $file;
                }
                if ($type == 'umum') {
                    $modelsTypeRaw = $modelsRaw->addChild('typedata');
                    $modelsTypeRaw->addAttribute('name', 'umum');
                    $this->extractDataUmum($modelsTypeRaw, Yii::app()->params['sysnc-file'][$type]);
                } elseif ($type == 'khusus') {
                    $modelsTypeRaw = $modelsRaw->addChild('typedata');
                    $modelsTypeRaw->addAttribute('name', 'khusus');
                    $this->extractDataKhusus($modelsTypeRaw, Yii::app()->params['sysnc-file'][$type], $sid);
                } elseif ($type == 'transaksi') {
                    $modelsTypeRaw = $modelsRaw->addChild('typedata');
                    $modelsTypeRaw->addAttribute('name', 'transaksi');
                    $this->extractDataTransaksi($modelsTypeRaw, Yii::app()->params['sysnc-file'][$type], $sid);
                }
            }
            $dom = new DOMDocument('1.0');
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = $this->isFormatOutput;
            $dom->loadXML($xml->asXML());
            $dom->save($filename);
            return $filename;
        }
    }

    private function extractDataUmum(&$modelsRaw, $models) {
        foreach ($models as $modelName) {
            $modelClass = Yii::import($modelName, true);
            //$class = new $modelClass;
            if (!is_string($modelClass) || !$this->classExists($modelClass))
                $this->errors[$modelClass] = "Class '{$modelClass}' does not exist or has syntax error.";
            else if (!is_subclass_of($modelClass, 'ActiveRecord'))
                $this->errors[$modelClass] = "Class '{$modelClass}' must extend from ActiveRecord.";
            else {
                $class = new $modelClass;
                $modelRaw = $modelsRaw->addChild('model');
                $modelRaw->addAttribute('name', $modelClass);
                $modelRaw->addAttribute('table', $class->tableName());
                $modelRaw->addAttribute('typedata', 'umum');
                //$modelRaw->addChild('name', $modelClass);
                //Schema Table
                if ($this->isIncludeSchema) {
                    $schemaRaw = $modelRaw->addChild('schema');
                    $table = ActiveRecord::model($modelClass)->tableSchema;
                    foreach ($table->columns as $column) {
                        $fieldRaw = $schemaRaw->addChild('field');
                        $fieldRaw->addChild('name', $column->name);
                        $fieldRaw->addChild('type', $column->type);
                        $fieldRaw->addChild('size', $column->size);
                    }
                }
                //End Schema Table
                //Data Table
                $dataRaw = $modelRaw->addChild('data');
                $models = $class->findAll();
                foreach ($models as $model) {
                    $recordRaw = $dataRaw->addChild('record');
                    $attrs = $model->getAttributes(true);
                    foreach ($attrs as $field => $value) {
                        //list($field, $value) = $attr;
                        if (!in_array($field, $this->ignoredFields))
                            $recordRaw->addChild($field, $value);
                    }
                }
                //End Data Table
            }
        }
    }

    private function extractDataKhusus(&$modelsRaw, $models, $sid) {
        $searchId = array();
        foreach ($models as $modelName => $attrs) {
            $modelClass = Yii::import($modelName, true);
            //$class = new $modelClass;
            if (!is_string($modelClass) || !$this->classExists($modelClass))
                $this->errors[$modelClass] = "Class '{$modelClass}' does not exist or has syntax error.";
            else if (!is_subclass_of($modelClass, 'ActiveRecord'))
                $this->errors[$modelClass] = "Class '{$modelClass}' must extend from ActiveRecord.";
            else {
                $class = new $modelClass;
                $modelRaw = $modelsRaw->addChild('model');
                $modelRaw->addAttribute('name', $modelClass);
                $modelRaw->addAttribute('table', $class->tableName());
                $modelRaw->addAttribute('typedata', 'khusus');
                if (isset($attrs['action']))
                    $modelRaw->addAttribute('action', $attrs['action']);
                if (isset($attrs['target']))
                    $modelRaw->addAttribute('target', $attrs['target']);
                if (isset($attrs['bref']))
                    $modelRaw->addAttribute('bref', $attrs['bref']);
                //$modelRaw->addChild('name', $modelClass);
                //Schema Table
                if ($this->isIncludeSchema) {
                    $schemaRaw = $modelRaw->addChild('schema');
                    $table = ActiveRecord::model($modelClass)->tableSchema;
                    foreach ($table->columns as $column) {
                        $fieldRaw = $schemaRaw->addChild('field');
                        $fieldRaw->addChild('name', $column->name);
                        $fieldRaw->addChild('type', $column->type);
                        $fieldRaw->addChild('size', $column->size);
                    }
                }
                //End Schema Table
                //Data Table
                $dataRaw = $modelRaw->addChild('data');
                $ref = '';
                $condition = 'id IS NULL';
                if (!empty($attrs)) {
                    if (isset($attrs['idref']))
                        $ref = $attrs['idref'];
                    if (isset($attrs['needsid']) && $attrs['needsid']) {
                        $condition = "id={$sid}";
                    }
                    if (isset($attrs['mapping'])) {
                        $modelMap = new $attrs['mapping'];
                        $models = $modelMap->findAll("{$attrs['refsid']}={$sid}");
                        $searchId = array();
                        foreach ($models as $model) {
                            if (isset($model->$attrs['refmap']))
                                $searchId[] = $model->$attrs['refmap'];
                        }
                    }
                    elseif (!empty($searchId)) {
                        $condition = $ref . ' IN (' . implode(',', $searchId) . ')';
                        $searchId = array();
                    }
                } elseif (!empty($searchId)) {
                    $condition = 'id IN (' . implode(',', $searchId) . ')';
                    $searchId = array();
                }
                $models = $class->findAll($condition);
                foreach ($models as $model) {
                    if (!isset($attrs['mapping'])) {
                        if (isset($model->id))
                            $searchId[] = $model->id;
                    }
                    $recordRaw = $dataRaw->addChild('record');
                    $attrs = $model->getAttributes(true);
                    foreach ($attrs as $field => $value) {
                        if (!in_array($field, $this->ignoredFields)) {
                            $recordRaw->addChild($field, $value);
                        }
                    }
                }
                //End Data Table
            }
        }
    }

    private function extractDataTransaksi(&$modelsRaw, $models, $sid) {
        $searchId = array();
        $listMapped = array();
        foreach ($models as $modelName => $attrs) {
            $modelClass = Yii::import($modelName, true);
            //$class = new $modelClass;
            if (!is_string($modelClass) || !$this->classExists($modelClass))
                $this->errors[$modelClass] = "Class '{$modelClass}' does not exist or has syntax error.";
            else if (!is_subclass_of($modelClass, 'ActiveRecord'))
                $this->errors[$modelClass] = "Class '{$modelClass}' must extend from ActiveRecord.";
            else {
                $class = new $modelClass;
                $modelRaw = $modelsRaw->addChild('model');
                $modelRaw->addAttribute('name', $modelClass);
                $modelRaw->addAttribute('table', $class->tableName());
                $modelRaw->addAttribute('typedata', 'transaksi');
                if (isset($attrs['action']))
                    $modelRaw->addAttribute('action', $attrs['action']);
                if (isset($attrs['target']))
                    $modelRaw->addAttribute('target', $attrs['target']);
                if (isset($attrs['bref']))
                    $modelRaw->addAttribute('bref', $attrs['bref']);
                //$modelRaw->addChild('name', $modelClass);
                //Schema Table
                if ($this->isIncludeSchema) {
                    $schemaRaw = $modelRaw->addChild('schema');
                    $table = ActiveRecord::model($modelClass)->tableSchema;
                    foreach ($table->columns as $column) {
                        $fieldRaw = $schemaRaw->addChild('field');
                        $fieldRaw->addChild('name', $column->name);
                        $fieldRaw->addChild('type', $column->type);
                        $fieldRaw->addChild('size', $column->size);
                    }
                }
                //End Schema Table
                //Data Table
                $dataRaw = $modelRaw->addChild('data');
                $ref = '';
                $condition = 'id IS NULL';
                if (!empty($attrs)) {
                    if (isset($attrs['idref']))
                        $ref = $attrs['idref'];
                    if (isset($attrs['needsid']) && $attrs['needsid']) {
                        $condition = "id={$sid}";
                    }
                    if (isset($attrs['mapping'])) {
                        $modelMap = new $attrs['mapping'];
                        $models = $modelMap->findAll("{$attrs['refsid']}={$sid}");
                        $searchId = array();
                        foreach ($models as $model) {
                            if (isset($model->$attrs['refmap']))
                                $searchId[] = $model->$attrs['refmap'];
                        }
                    }
                    if (!empty($searchId)) {
                        $condition = 'id IN (' . implode(',', $searchId) . ')';
                        if (isset($attrs['mapped']))
                            $listMapped = $searchId;
                        $searchId = array();
                    }
                } elseif (!empty($searchId)) {
                    $condition = 'id IN (' . implode(',', $searchId) . ')';
                    $searchId = array();
                }
                if (isset($attrs['mapped']))
                    $models = $class->findAll();
                else
                    $models = $class->findAll($condition);
                foreach ($models as $model) {
                    if (isset($attrs['idref'])) {
                        if (isset($model->$attrs['idref']))
                            $searchId[] = $model->$attrs['idref'];
                    }
                    $recordRaw = $dataRaw->addChild('record');
                    $attribs = $model->getAttributes(true);
                    foreach ($attribs as $field => $value) {
                        if (!in_array($field, $this->ignoredFields)) {
                            if (key_exists($field, $this->mapFields))
                                $field = $this->mapFields[$field];
                            $recordRaw->addChild($field, $value);
                            if ($field == 'id' && isset($attrs['mapped'])) {
                                if (in_array($value, $listMapped))
                                    $recordRaw->addChild('mapped', '1');
                                else
                                    $recordRaw->addChild('mapped', '0');
                            }
                        }
                    }
                }
                //End Data Table
            }
        }
    }

    public function extractXMLtoData($filename) {
        //$xml = simplexml_load_file($this->xmlfile);
        $xml = new SimpleXMLElement($filename, null, true);
        //$this->getChildrenRecursive($xml);
        $this->extractXML($xml);
    }

    private function extractXML($xmlObj) {
        foreach ($xmlObj->children() as $child) {
            if ($child->getName() == 'models') {
                foreach ($child->children() as $childModels) {
                    $typedata = '';
                    foreach ($childModels->attributes() as $key => $val) {
                        if ($key == 'name')
                            $typedata = $val;
                    }
                    switch ($typedata) {
                        case 'umum':
                            $this->extractXMLUmum($childModels);
                            break;
                        case 'khusus':
                            $this->extractXMLKhusus($childModels);
                            break;
                        case 'transaksi':
                            $this->extractXMLTransaksi($childModels);
                            break;
                    }
                }
            } elseif (($child->getName() == 'npsn')) {
                if ($child != Yii::app()->user->getState('npsn_sekolah', 'unknown')) {
                    $this->errors['file-not-match'] = "File sinkronisasi tidak sesuai untuk sekolah ini.";
                    break;
                }
            }
        }
    }

    private function extractXMLUmum($objXmlModel) {
        //echo $typedata . "<br />";
        foreach ($objXmlModel->children() as $childModels) {
            $modelName = "";
            $tableName = "";
            $typedata = "";
            $action = "insert";
            $targetModel = "";
            foreach ($childModels->attributes() as $key => $val) {
                if ($key == 'name')
                    $modelName = $val;
                if ($key == 'table')
                    $tableName = $val;
                if ($key == 'typedata')
                    $typedata = $val;
                if ($key == 'action')
                    $action = $val;
                if ($key == 'target')
                    $targetModel = $val;
            }
            if (!empty($targetModel))
                $modelName = $targetModel;
            $fileModel = Yii::getPathOfAlias('application.models') . '/' . $modelName . '.php';
            if (file_exists($fileModel)) {
                $modelClass = Yii::import('application.models.' . $modelName, true);
                foreach ($childModels->children() as $childModel) {
                    if ($childModel->getName() == 'data') {
                        $totalRow = $childModel->count();
                        if ($totalRow > 0) {
                            //Copy
                            Yii::app()->db->createCommand("SET FOREIGN_KEY_CHECKS=0")->execute();
                            $modelClass::model()->truncate();
                            for ($i = 0; $i < $totalRow; $i++) {
                                $model = new $modelClass('insert');
                                $attrs = $model->attributes;
                                $record = $childModel->record[$i];
                                foreach ($record->children() as $childField) {
                                    $fieldName = $childField->getName();
                                    if (key_exists($fieldName, $attrs))
                                        $model->$fieldName = $childField;
                                }
                                try {
                                    $model->save();
                                } catch (Exception $e) {
                                    $this->errors[] = $tableName . ' data id:' . $model->id;
                                    /* echo $e->getMessage() . "<br />";
                                      Ax::print_r($model->attributes);
                                      Yii::app()->end(); */
                                }
                                $model = null;
                            }
                            Yii::app()->db->createCommand("SET FOREIGN_KEY_CHECKS=1")->execute();

                            //Copy End
                        }
                    }
                }
            }
        }
    }

    private function extractXMLKhusus($objXmlModel) {
        Yii::app()->db->createCommand("SET FOREIGN_KEY_CHECKS=0")->execute();
        foreach ($objXmlModel->children() as $childModels) {
            $modelName = "";
            $tableName = "";
            $typedata = "";
            $action = "insert";
            $targetModel = "";
            $bref = 'empty';
            foreach ($childModels->attributes() as $key => $val) {
                if ($key == 'name')
                    $modelName = $val;
                elseif ($key == 'table')
                    $tableName = $val;
                elseif ($key == 'typedata')
                    $typedata = $val;
                elseif ($key == 'action')
                    $action = $val;
                elseif ($key == 'target')
                    $targetModel = $val;
                elseif ($key == 'bref') {
                    $bref = (string) $val;
                    $this->mapbref[$bref] = array();
                }
            }
            //echo $bref."<br />";
            if (!empty($targetModel))
                $modelName = $targetModel;
            $fileModel = Yii::getPathOfAlias('application.models') . '/' . $modelName . '.php';
            if (file_exists($fileModel)) {
                $modelClass = Yii::import('application.models.' . $modelName, true);
                foreach ($childModels->children() as $childModel) {
                    if ($childModel->getName() == 'data') {
                        $totalRow = $childModel->count();
                        if ($totalRow > 0) {
                            //Copy
                            $this->createHeaderSyncId();
                            for ($i = 0; $i < $totalRow; $i++) {
                                if ($action == 'update') {
                                    $model = $modelClass::model()->find(); //

                                    if ($model === NULL) {
                                        $model = new $modelClass('insert');
                                    }
                                }
                                else
                                    $model = new $modelClass('insert');
                                $attrs = $model->attributes;
                                if (key_exists($this->refNameHeaderSysnc, $attrs)) {
                                    $model->{$this->refNameHeaderSysnc} = $this->header_sync_id;
                                }
                                $record = $childModel->record[$i];
                                $idorg = 0;
                                foreach ($record->children() as $childField) {
                                    $fieldName = $childField->getName();
                                    if ($fieldName == 'id')
                                        $idorg = (string) $childField;
                                    if (key_exists($fieldName, $attrs) && $fieldName != 'id') {
                                        $k = (string) $fieldName;
                                        $v = (string) $childField;
                                        if (isset($this->mapbref[$k])) {
                                            if (isset($this->mapbref[$k][$v]))
                                                $model->$fieldName = $this->mapbref[$k][$v];
                                        }
                                        elseif (!empty($childField))
                                            $model->$fieldName = $childField;
                                        else
                                            $model->$fieldName = null;
                                    }
                                }
                                try {
                                    if ($model->validate()) {
                                        $model->save(false);
                                        if (isset($this->mapbref[$bref]))
                                            $this->mapbref[$bref][$idorg] = $model->primaryKey;
                                    }
                                    else {
                                        $arrErrors = $model->getErrors();
                                        foreach ($arrErrors as $a => $es)
                                            foreach ($es as $e)
                                                $this->errors[] = $modelName . '-' . $a . ' : ' . $e;
                                    }
                                } catch (Exception $e) {
                                    //$this->errors[] = $tableName . ' data id:' . $idorg;
                                    $this->errors[] = $e->getMessage();
                                }
                                $model = null;
                            }

                            //Copy End
                        }
                    }
                }
            }
        }
        Yii::app()->db->createCommand("SET FOREIGN_KEY_CHECKS=1")->execute();
    }

    private function extractXMLTransaksi($objXmlModel) {
        Yii::app()->db->createCommand("SET FOREIGN_KEY_CHECKS=0")->execute();
        foreach (Yii::app()->params['sysnc-models']['transaksi'] as $sortmodel) {
            foreach ($objXmlModel->children() as $childModels) {
                $modelName = "";
                $tableName = "";
                $typedata = "";
                $action = "insert";
                $targetModel = "";
                $bref = "";
                foreach ($childModels->attributes() as $key => $val) {
                    if ($key == 'name')
                        $modelName = $val;
                    if ($key == 'table')
                        $tableName = $val;
                    if ($key == 'action')
                        $action = $val;
                    if ($key == 'target')
                        $targetModel = $val;
                    if ($key == 'bref') {
                        $bref = (string) $val;
                        //$this->mapbref[$bref] = array();
                    }
                }
                if ($modelName == $sortmodel) {
                    if(!empty($bref))
                        $this->mapbref[$bref] = array();
                    if (!empty($targetModel))
                        $modelName = $targetModel;
                    $fileModel = Yii::getPathOfAlias('application.models') . '/' . $modelName . '.php';
                    if (file_exists($fileModel)) {
                        $modelClass = Yii::import('application.models.' . $modelName, true);
                        foreach ($childModels->children() as $childModel) {
                            if ($childModel->getName() == 'data') {
                                $totalRow = $childModel->count();
                                if ($totalRow > 0) {
                                    $this->createHeaderSyncId();
                                    for ($i = 0; $i < $totalRow; $i++) {
                                        $model = new $modelClass('insert');
                                        $attrs = $model->attributes;
                                        if (key_exists($this->refNameHeaderSysnc, $attrs))
                                            $model->{$this->refNameHeaderSysnc} = $this->header_sync_id;
                                        $record = $childModel->record[$i];
                                        $idorg = 0;
                                        foreach ($record->children() as $childField) {
                                            $fieldName = $childField->getName();
                                            if ($fieldName == 'id')
                                                $idorg = (string) $childField;
                                            if (key_exists($fieldName, $attrs) && $fieldName != 'id') {
                                                $k = (string) $fieldName;
                                                $v = (string) $childField;
                                                if (isset($this->mapbref[$k])) {
                                                    if (isset($this->mapbref[$k][$v]))
                                                        $model->$fieldName = $this->mapbref[$k][$v];
                                                }
                                                elseif (!empty($childField))
                                                    $model->$fieldName = $childField;
                                                else
                                                    $model->$fieldName = null;
                                            }
                                        }
                                        try {
                                            if ($model->validate()) {
                                                $model->save(false);
                                                if (isset($this->mapbref[$bref])){
                                                    //echo $modelName." => ".$bref." => ".$idorg." => ".$model->primaryKey."<br>";
                                                    $this->mapbref[$bref][$idorg] = $model->primaryKey;
                                                }
                                            }
                                            else {
                                                $arrErrors = $model->getErrors();
                                                foreach ($arrErrors as $a => $es)
                                                    foreach ($es as $e)
                                                        $this->errors[] = $modelName . '-' . $a . ' : ' . $e;
                                            }
                                        } catch (Exception $e) {
                                            $this->errors[] = $e->getMessage();
                                        }
                                        $model = null;
                                    }
                                }
                            }
                        }
                    }
                    break;
                }
            }
        }
        Yii::app()->db->createCommand("SET FOREIGN_KEY_CHECKS=1")->execute();
    }

    private function createHeaderSyncId() {
        if (!isset($this->header_sync_id)) {
            $model = new $this->modelHeaderSysnc();
            $this->header_sync_id = $model->createNew();
        }
    }

    public function isHasUpdated($filename, $filetype) {
        $retVal = true;
        //Some Logic here
        //End Logic
        return $retVal;
    }

    protected function classExists($name) {
        return class_exists($name, false) && in_array($name, get_declared_classes());
    }

    public function getErrors() {
        return $this->errors;
    }

}

?>