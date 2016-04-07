<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ActXML
 *
 * @author ardha
 * @created on Dec 1, 2012
 */
class AxSinkronisasi {

    private $xmlfile;
    private $errors = array();

    public function __construct($filename) {
        $this->xmlfile = $filename;
    }

    public function createXMLFromData() {
        $xml = new SimpleXMLElement('<xml/>');
        $modelsRaw = $xml->addChild('models');
        foreach (Yii::app()->params['sysnc-models'] as $modelName) {
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
                //$modelRaw->addChild('name', $modelClass);
                $schemaRaw = $modelRaw->addChild('schema');
                $table = ActiveRecord::model($modelClass)->tableSchema;
                foreach ($table->columns as $column) {
                    $fieldRaw = $schemaRaw->addChild('field');
                    $fieldRaw->addChild('name', $column->name);
                    $fieldRaw->addChild('type', $column->type);
                    $fieldRaw->addChild('size', $column->size);
                }
                $dataRaw = $modelRaw->addChild('data');

                $models = $class->findAll();
                foreach ($models as $model) {
                    $recordRaw = $dataRaw->addChild('record');
                    $attrs = $model->getAttributes(true);
                    foreach ($attrs as $field => $value) {
                        //list($field, $value) = $attr;
                        $recordRaw->addChild($field, $value);
                    }
                }
            }
        }
        //print($xml->asXML());
        //$fp = fopen($this->xmlfile, "wb");
        //fwrite($fp, $xml->asXML());
        //fclose($fp);
        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());
        $dom->save($this->xmlfile);
    }

    public function extractXMLtoData() {
        //$xml = simplexml_load_file($this->xmlfile);
        $model = new HeaderSinkronisasi();
        $model->nomor_batch = "".strtotime('now');
        $model->status_final = 0;
        if($model->save()){
            $xml = new SimpleXMLElement($this->xmlfile, null, true);
            $this->getChildrenRecursive($xml, 0, $model->primaryKey);
        }
    }

    protected function classExists($name) {
        return class_exists($name, false) && in_array($name, get_declared_classes());
    }

    public function getErrors() {
        return $this->errors;
    }

    private function getChildrenRecursive($xmlObj, $depth=0, $hid=null) {
        foreach ($xmlObj->children() as $child) {
            //echo str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $depth) . " " . $child->getName() . ": " . $subchild . "<br>";
            if($child->getName() == "model"){
                $modelName = "";
                $tableName = "";
                foreach($child->attributes() as $key => $val){
                    if($key == 'name')
                        $modelName = $val;
                    if($key == 'table')
                        $tableName = $val;
                }
                $arrSourceSchema = array();
                foreach($child->children() as $childModel){
                    if($childModel->getName()=='schema')
                        $arrSourceSchema = $this->createArraySourceSchema($childModel);
                    if($childModel->getName()=='data'){
                        foreach($childModel->children() as $childRecord){
                            $model = new $modelName('insert');
                            if(property_exists($modelName, ''))
                            foreach($childRecord->children() as $childField){
                                $fieldName = $childField->getName();
                                if(property_exists($modelName, $childField->getName()))
                                        $model->$fieldName = $childField;
                            }
                            $model->save();
                        }
                    }
                }
                if(checkIfTableChange){
                    //Do Action if Table Change 
                }
            }
            $this->getChildrenRecursive($child, $depth + 1);
        }
    }

    private function checkIfTableChange($modelName, $sourcetTableSchema) {
        $destTableSchema = ActiveRecord::model($modelClass)->tableSchema;
        $arrDestSchema =array(); 
        foreach ($destTableSchema->columns as $column)
            $arrDestSchema[$column->name] = array('type' => $column->type, 'size' => $column->size);
        foreach($sourcetTableSchema as $key => $val){
            if(!array_key_exists($key, $arrDestSchema))
                return false;
       }
       return true;
        
    }
    private function createArraySourceSchema($xmlObj) {
        $arrReturn = array();
        foreach ($xmlObj->children() as $child) {
            //$offset = $child->name;
            $arrReturn["{$child->name}"] = array('type' => "{$child->type}", 'size' => "{$child->size}");
            //echo "{$child->name}<br>";
        }
        return $arrReturn;
    }
}

?>
