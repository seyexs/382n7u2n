<?php

class CustomFormGenerator extends CCodeGenerator
{
	public $codeModel='application.gii.customform.CustomFormCode';
        public $layout='application.views.layouts.generator';
        public $modelClass;
        public $typeFields = array(
            'checkbox' => 'Check Box',
            'combobox' => 'Combo Box',
            'datefield' => 'Date Field',
            'fileupload' => 'File Upload',
            'hiddenfield' => 'Hidden Field',
            'numberfield' => 'Number Field',
            'radio' => 'Radio Button',
            'password' => 'Password Field',
            'textfield' => 'Text Field',
            'textarea' => 'Text Area',
            'timefield' => 'Time Field',
        );
        public $modelsPath = 'application.models';
        public $dirSeparator = '/';
        public function actionIndex() {
        $model = $this->prepare();

        if ($model->files != array() && isset($_POST['generate'], $_POST['answers'])) {
            $model->answers = $_POST['answers'];
            $model->status = $model->save() ? CCodeModel::STATUS_SUCCESS : CCodeModel::STATUS_ERROR;
        }
        $this->render('index', array(
            'model' => $model,
        ));
    }

    protected function prepare() {
        if ($this->codeModel === null)
            throw new CException(get_class($this) . '.codeModel property must be specified.');
        $this->modelClass = Yii::import($this->codeModel, true);
        $model = new $this->modelClass;
        $model->loadStickyAttributes();
        if (isset($_POST[$this->modelClass])) {
            $model->attributes = $_POST[$this->modelClass];
            $model->status = CCodeModel::STATUS_PREVIEW;
            if ($model->validate()) {
                $model->saveStickyAttributes();
                $model->prepare();
            }
        }
        return $model;
    }

    public function getListModel() {
        $listModel = array();
        $dir = Yii::getPathOfAlias($this->modelsPath);
        if ($dir !== false) {
            $files = CFileHelper::findFiles($dir, array('fileTypes' => array('php'), 'level' => 0));
            foreach ($files as $file) {
                //$nmFile = array_pop(explode('//', $file));
                //$nmFile = str_replace('.php', '', $nmFile);
                $arrTmp = explode($this->dirSeparator, $file);
                $nmFile = str_replace('.php', '', array_pop($arrTmp));
                $listModel[$nmFile] = $nmFile;
            }
        }
        return $listModel;
    }
}