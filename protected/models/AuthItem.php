<?php

/**
 * This is the model class for table "AuthItem".
 *
 * The followings are the available columns in table 'AuthItem':
 * @property string $name
 * @property integer $type
 * @property string $description
 * @property string $bizrule
 * @property string $data
 */
class AuthItem extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return AuthItem the static model class
     */
    public $typeDef = '';
    public $nameByOptionList;

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'AuthItem';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required'),
            array('type', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 128),
            array('description, bizrule, data, nameByOptionList', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('name, type, description, bizrule, data, nameByOptionList', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'name' => 'Code',
            'type' => 'Tipe',
            'description' => 'Nama Modul Aplikasi',
            'bizrule' => 'Bizrule',
            'data' => 'Data',
        );
    }

    /**
     * @return array the scope definition.
     */
    public function scopes() {
        return array(
            'roles' => array(
                'condition' => 'type=:type',
                'params' => array(':type' => CAuthItem::TYPE_ROLE),
            ),
            'task' => array(
                'condition' => 'type=:type',
                'params' => array(':type' => CAuthItem::TYPE_TASK),
            ),
            'operation' => array(
                'condition' => 'type=:type',
                'params' => array(':type' => CAuthItem::TYPE_OPERATION),
            ),
        );
    }

    /**
     * This method is invoked after each record is instantiated by a find method.
     */
    public function afterFind() {

        parent::afterFind();
        if (isset($this->type)) {
            switch ($this->type) {
                case CAuthItem::TYPE_TASK:
                    $this->typeDef = 'Aplikasi';
                    break;
                case CAuthItem::TYPE_OPERATION:
                    $this->typeDef = 'Operasi';
                    break;
                case CAuthItem::TYPE_ROLE:
                    $this->typeDef = 'Group';
                    break;
                default:
                    $this->typeDef = '';
                    break;
            }
        }
    }

    /**
     * This method is invoked before validation starts.
     * @return boolean whether validation should be executed. Defaults to true.
     */
    protected function beforeValidate() {
        if(!isset($this->name)){
            if(isset($this->nameByOptionList))
                $this->name = $this->nameByOptionList;
        }
        return parent::beforeValidate();
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('name', $this->name, true);
        //$criteria->compare('type', $this->type);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('bizrule', $this->bizrule, true);
        $criteria->compare('data', $this->data, true);
        $condition = sprintf("type = '%d' OR type = '%d'", CAuthItem::TYPE_TASK, CAuthItem::TYPE_OPERATION);
        $criteria->addCondition($condition);
        //$criteria->addCondition('type =:type_task OR type =:type_operation');
        //$criteria->params = array(':type_task' => CAuthItem::TYPE_TASK, ':type_operation' => CAuthItem::TYPE_OPERATION);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public function searchGroup() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('name', $this->name, true);
        //$criteria->compare('type', $this->type);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('bizrule', $this->bizrule, true);
        $criteria->compare('data', $this->data, true);
        $condition = sprintf("type = '%d'", CAuthItem::TYPE_ROLE);
        $criteria->addCondition($condition);
        //$criteria->addCondition('type =:type');
        //$criteria->params = array(':type' => CAuthItem::TYPE_ROLE);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}