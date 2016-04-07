<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TablesToXml
 *
 * @author ardha
 * @created on May 19, 2013
 */
class TablesToXml {

    //put your code here
    private $dynamicAr;
    private $xml;
    private $dom;
    private $npsn;
    private $modelsRaw;
    private $isIncludeSchema = true;
    public $filename;
    private $isFormatOutput = true;

    function __construct($dirFile) {
        $this->dynamicAr = new DynamicModel();
        $this->xml = new SimpleXMLElement('<xml/>');
        $this->dom = new DOMDocument('1.0');
        //$this->filename = Yii::getPathOfAlias('application') . '/temp/' . 'emisdata.xml'; //date("d-m-Y_Hi") . '.xml';
        $this->filename = $dirFile . 'emisdata.xml'; //date("d-m-Y_Hi") . '.xml';
        if(file_exists ($this->filename)){
            unlink($this->filename);
        }
    }

    function init_xml() {
        $this->xml->addChild('created', time());
        if (!empty($this->npsn))
            $this->xml->addChild('npsn', $this->npsn);
        else
            $this->xml->addChild('npsn', 'unknown');
        $this->modelsRaw = $this->xml->addChild('tables');
    }
    
    function setNpsn($npsn) {
        $this->npsn = $npsn;
    }

    function getTablesList($filter = array()) {
        $tables = array_keys(Yii::app()->db->schema->getTables());
        return $tables;
    }

    function tablesToXml() {
        $this->init_xml();
        $tables = $this->getTablesList();
        foreach ($tables as $table) {
            $this->tableToXml($table);
        }
        $this->end_xml();
    }

    function tableToXml($tableName) {

        $this->dynamicAr->setTable($tableName);
        $modelRaw = $this->modelsRaw->addChild('tabel');
        $modelRaw->addAttribute('name', $tableName);
        if ($this->isIncludeSchema) {
            $schemaRaw = $modelRaw->addChild('schema');
            //$table = ActiveRecord::model($modelClass)->tableSchema;
            $table = $this->dynamicAr->getTableSchema();
            //$table = ActiveRecord::model($modelClass)->tableSchema;
            foreach ($table->columns as $column) {
                $fieldRaw = $schemaRaw->addChild('field');
                $fieldRaw->addChild('name', $column->name);
                $fieldRaw->addChild('type', $column->type);
                $fieldRaw->addChild('size', $column->size);
            }
        }
        $dataRaw = $modelRaw->addChild('data');
        $models = $this->dynamicAr->findAll();
        foreach ($models as $model) {
            $recordRaw = $dataRaw->addChild('record');
            $attrs = $model->getAttributes(true);
            foreach ($attrs as $field => $value) {
                //list($field, $value) = $attr;
                //if (!in_array($field, $this->ignoredFields))
                $recordRaw->addChild($field, $value);
            }
        }
    }
    
    function end_xml(){
        $this->dom->preserveWhiteSpace = false;
        $this->dom->formatOutput = $this->isFormatOutput;
        $this->dom->loadXML($this->xml->asXML());
        $this->dom->save($this->filename);
    }

}

?>
