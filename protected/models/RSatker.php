<?php

/**
 * This is the model class for table "r_satker".
 *
 * The followings are the available columns in table 'r_satker':
 * @property string $satker_id
 * @property string $satker_parent
 * @property string $location_id
 * @property string $city_id
 * @property string $kppn_id
 * @property string $departement_id
 * @property string $unit_id
 * @property integer $satker_type_id
 * @property string $satker_name
 * @property string $satker_nomor_sp
 * @property integer $satker_active
 */
class RSatker extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RSatker the static model class
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
		return 'r_satker';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('satker_id', 'required'),
			array('satker_type_id, satker_active', 'numerical', 'integerOnly'=>true),
			array('satker_id, satker_parent', 'length', 'max'=>6),
			array('location_id, kppn_id, departement_id', 'length', 'max'=>3),
			array('city_id, unit_id', 'length', 'max'=>2),
			array('satker_name', 'length', 'max'=>90),
			array('satker_nomor_sp', 'length', 'max'=>4),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('satker_id, satker_parent, location_id, city_id, kppn_id, departement_id, unit_id, satker_type_id, satker_name, satker_nomor_sp, satker_active', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'satker_id' => 'Satker',
			'satker_parent' => 'Satker Parent',
			'location_id' => 'Location',
			'city_id' => 'City',
			'kppn_id' => 'Kppn',
			'departement_id' => 'Departement',
			'unit_id' => 'Unit',
			'satker_type_id' => 'Satker Type',
			'satker_name' => 'Satker Name',
			'satker_nomor_sp' => 'Satker Nomor Sp',
			'satker_active' => 'Satker Active',
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

		$criteria->compare('satker_id',$this->satker_id,true);
		$criteria->compare('satker_parent',$this->satker_parent,true);
		$criteria->compare('location_id',$this->location_id,true);
		$criteria->compare('city_id',$this->city_id,true);
		$criteria->compare('kppn_id',$this->kppn_id,true);
		$criteria->compare('departement_id',$this->departement_id,true);
		$criteria->compare('unit_id',$this->unit_id,true);
		$criteria->compare('satker_type_id',$this->satker_type_id);
		$criteria->compare('satker_name',$this->satker_name,true);
		$criteria->compare('satker_nomor_sp',$this->satker_nomor_sp,true);
		$criteria->compare('satker_active',$this->satker_active);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}