<?php

/**
 * This is the model class for table "t_data_cutoff".
 *
 * The followings are the available columns in table 't_data_cutoff':
 * @property string $id
 * @property string $peserta_didik_id
 * @property string $sekolah_id
 * @property string $kode_wilayah_sekolah
 * @property integer $tingkat_pendidikan_id
 * @property string $jurusan_id
 * @property string $created_date
 * @property integer $created_by
 * @property string $modified_date
 * @property integer $modified_by
 * @property integer $deleted
 */
class TDataCutoff extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TDataCutoff the static model class
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
		return 't_data_cutoff';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id', 'required'),
			array('tingkat_pendidikan_id, created_by, modified_by, deleted', 'numerical', 'integerOnly'=>true),
			array('kode_wilayah_sekolah', 'length', 'max'=>8),
			array('jurusan_id', 'length', 'max'=>25),
			array('peserta_didik_id, sekolah_id, created_date, modified_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, peserta_didik_id, sekolah_id, kode_wilayah_sekolah, tingkat_pendidikan_id, jurusan_id, created_date, created_by, modified_date, modified_by, deleted', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'peserta_didik_id' => 'Peserta Didik',
			'sekolah_id' => 'Sekolah',
			'kode_wilayah_sekolah' => 'Kode Wilayah Sekolah',
			'tingkat_pendidikan_id' => 'Tingkat Pendidikan',
			'jurusan_id' => 'Jurusan',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('peserta_didik_id',$this->peserta_didik_id,true);
		$criteria->compare('sekolah_id',$this->sekolah_id,true);
		$criteria->compare('kode_wilayah_sekolah',$this->kode_wilayah_sekolah,true);
		$criteria->compare('tingkat_pendidikan_id',$this->tingkat_pendidikan_id);
		$criteria->compare('jurusan_id',$this->jurusan_id,true);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('modified_date',$this->modified_date,true);
		$criteria->compare('modified_by',$this->modified_by);
		$criteria->compare('deleted',$this->deleted);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}