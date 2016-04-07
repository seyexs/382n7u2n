<?php

/**
 * This is the model class for table "m_pegawai".
 *
 * The followings are the available columns in table 'm_pegawai':
 * @property string $nip
 * @property string $nama
 * @property string $gelar_depan
 * @property string $gelar_belakang
 * @property integer $jenis_kelamin
 * @property string $foto
 * @property string $tanggal_lahir
 * @property integer $userid
 * @property string $created_date
 * @property integer $created_by
 * @property string $modified_date
 * @property integer $modified_by
 * @property string $last_sync
 * @property integer $deleted
 */
class MPegawai extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MPegawai the static model class
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
		return 'm_pegawai';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('jenis_kelamin, userid, created_by, modified_by, deleted', 'numerical', 'integerOnly'=>true),
			array('nip, foto', 'length', 'max'=>100),
			array('nama', 'length', 'max'=>200),
			array('gelar_depan, gelar_belakang', 'length', 'max'=>20),
			array('tanggal_lahir, created_date, modified_date, last_sync', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('nip, nama, gelar_depan, gelar_belakang, jenis_kelamin, foto, tanggal_lahir, userid, created_date, created_by, modified_date, modified_by, last_sync, deleted', 'safe', 'on'=>'search'),
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
			'nip' => 'Nip',
			'nama' => 'Nama',
			'gelar_depan' => 'Gelar Depan',
			'gelar_belakang' => 'Gelar Belakang',
			'jenis_kelamin' => 'Jenis Kelamin',
			'foto' => 'Foto',
			'tanggal_lahir' => 'Tanggal Lahir',
			'userid' => 'Userid',
			'created_date' => 'Created Date',
			'created_by' => 'Created By',
			'modified_date' => 'Modified Date',
			'modified_by' => 'Modified By',
			'last_sync' => 'Last Sync',
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

		$criteria->compare('nip',$this->nip,true);
		$criteria->compare('nama',$this->nama,true);
		$criteria->compare('gelar_depan',$this->gelar_depan,true);
		$criteria->compare('gelar_belakang',$this->gelar_belakang,true);
		$criteria->compare('jenis_kelamin',$this->jenis_kelamin);
		$criteria->compare('foto',$this->foto,true);
		$criteria->compare('tanggal_lahir',$this->tanggal_lahir,true);
		$criteria->compare('userid',$this->userid);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('modified_date',$this->modified_date,true);
		$criteria->compare('modified_by',$this->modified_by);
		$criteria->compare('last_sync',$this->last_sync,true);
		$criteria->compare('deleted',$this->deleted);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}