<?php

/**
 * This is the model class for table "r_satuan".
 *
 * The followings are the available columns in table 'r_satuan':
 * @property string $id
 * @property string $nama
 * @property string $created_date
 * @property integer $created_by
 * @property string $modified_date
 * @property integer $modified_by
 * @property integer $deleted
 */
class MstWilayah extends ActiveRecord
{
	public function getDbConnection()
    {
        return self::getDapodikDbConnection();
    }
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RSatuan the static model class
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
		return 'ref.mst_wilayah';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('kode_wilayah, nama,id_level_wilayah, mst_kode_wilayah,soft_delete,expired_date', 'safe', 'on'=>'search'),
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
			'kode_wilayah' => 'ID',
			'nama' => 'Nama',
			'id_level_wilayah' => 'level',
			'mst_kode_wilayah' => 'parent',
			'soft_delete' => 'delete'
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

		$criteria->compare('kode_wilayah',$this->kode_wilayah,true);
		$criteria->compare('nama',$this->nama,true);
		$criteria->compare('id_level_wilayah',$this->id_level_wilayah,true);
		$criteria->compare('mst_kode_wilayah',$this->mst_kode_wilayah);
		$criteria->compare('soft_delete',$this->soft_delete,true);
		$criteria->compare('expired_date',$this->expired_date);
		

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

}