<?php

/**
 * This is the model class for table "t_kuesioner_respondent".
 *
 * The followings are the available columns in table 't_kuesioner_respondent':
 * @property integer $id
 * @property integer $t_kuesioner_id
 * @property string $authitem_name
 * @property string $created_date
 * @property integer $created_by
 * @property string $modified_date
 * @property integer $modified_by
 * @property integer $deleted
 */
class TKuesionerRespondent extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TKuesionerRespondent the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 't_kuesioner_respondent';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('t_kuesioner_id, authitem_name', 'required'),
			array('t_kuesioner_id, created_by, modified_by, deleted', 'numerical', 'integerOnly'=>true),
			array('authitem_name', 'length', 'max'=>250),
			array('keterangan', 'length', 'max'=>150),
			array('created_date, modified_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, t_kuesioner_id, authitem_name, created_date, created_by, modified_date, modified_by, deleted,keterangan', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'tKuesioner'=>array(self::BELONGS_TO,'TKuesioner','t_kuesioner_id'),
			'authItem'=>array(self::BELONGS_TO,'AuthItem','authitem_name'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			't_kuesioner_id' => 'Kuesioner',
			'authitem_name' => 'Authitem Name',
			'created_date' => 'Created Date',
			'created_by' => 'Created By',
			'modified_date' => 'Modified Date',
			'modified_by' => 'Modified By',
			'deleted' => 'Deleted',
			'keterangan' => 'Keterangan',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('t_kuesioner_id',$this->t_kuesioner_id);
		$criteria->compare('authitem_name',$this->authitem_name,true);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('modified_date',$this->modified_date,true);
		$criteria->compare('modified_by',$this->modified_by);
		$criteria->compare('deleted',$this->deleted);
		$criteria->compare('keterangan',$this->keterangan);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}