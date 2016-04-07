<?php

/**
 * This is the model class for table "r_bantuan_daftar_pelaporan".
 *
 * The followings are the available columns in table 'r_bantuan_daftar_pelaporan':
 * @property integer $id
 * @property integer $r_bantuan_id
 * @property string $nama
 * @property string $kode_module
 * @property string $created_date
 * @property integer $created_by
 * @property string $modified_date
 * @property integer $modified_by
 * @property integer $deleted
 */
class RBantuanDaftarPelaporan extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RBantuanDaftarPelaporan the static model class
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
		return 'r_bantuan_daftar_pelaporan';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('r_bantuan_id', 'required'),
			array('created_by, modified_by, deleted', 'numerical', 'integerOnly'=>true),
			array('nama,properties', 'length', 'max'=>500),
			array('kode_module', 'length', 'max'=>100),
			array('created_date, modified_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, r_bantuan_id, nama, kode_module, created_date, created_by, modified_date, modified_by, deleted,properties', 'safe', 'on'=>'search'),
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
			'rBantuan'=>array(self::BELONGS_TO,'RBantuan','r_bantuan_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'r_bantuan_id' => 'R Bantuan',
			'nama' => 'Nama',
			'kode_module' => 'Kode Module',
			'created_date' => 'Created Date',
			'created_by' => 'Created By',
			'modified_date' => 'Modified Date',
			'modified_by' => 'Modified By',
			'deleted' => 'Deleted',
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
		$criteria->compare('r_bantuan_id',$this->r_bantuan_id);
		$criteria->compare('nama',$this->nama,true);
		$criteria->compare('kode_module',$this->kode_module,true);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('modified_date',$this->modified_date,true);
		$criteria->compare('modified_by',$this->modified_by);
		$criteria->compare('deleted',$this->deleted);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	protected function beforeSave() {
		parent::beforeSave();
		$this->id=new CDbExpression('NEWID()');
		return true;
	}
}