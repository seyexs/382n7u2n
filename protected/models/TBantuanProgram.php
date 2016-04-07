<?php

/**
 * This is the model class for table "t_bantuan_program".
 *
 * The followings are the available columns in table 't_bantuan_program':
 * @property integer $id
 * @property integer $tahun
 * @property string $nama
 * @property string $keterangan
 * @property string $file_doc_sk
 * @property string $created_date
 * @property integer $created_by
 * @property string $modified_date
 * @property integer $modified_by
 * @property integer $deleted
 * @property integer $r_bantuan_id
 * @property integer $m_pegawai_nip
 * @property integer $status
 */
class TBantuanProgram extends ActiveRecord
{
	public $r_bantuan_name;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TBantuanProgram the static model class
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
		return 't_bantuan_program';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nama,tahun', 'required'),
			array('tahun, created_by, modified_by, deleted,bentuk_bantuan,status,t_kuesioner_id,nilai_bantuan', 'numerical', 'integerOnly'=>true),
			array('nama, keterangan, file_doc_sk, created_date, modified_date,m_pegawai_nip,r_bantuan_penerima_id,t_kuesioner_id,kode,pengertian,tujuan,sasaran,keterangan_nilai_bantuan,pemanfaatan_dana,t_bantuan_tim_pengelola_nama,kode_wilayah', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, tahun, nama, keterangan, file_doc_sk,bentuk_bantuan, created_date, created_by, modified_date, modified_by, deleted, r_bantuan_id,m_pegawai_nip,status,r_bantuan_penerima_id,t_kuesioner_id,kode,pengertian,tujuan,sasaran,nilai_bantuan,keterangan_nilai_bantuan,pemanfaatan_dana', 'safe', 'on'=>'search'),
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
			'rBantuan'=>array(self::BELONGS_TO,'RBantuan','r_bantuan_id'),
			'mPegawai'=>array(self::BELONGS_TO,'MPegawai','m_pegawai_nip'),
			'rBantuanPenerima'=>array(self::BELONGS_TO,'RBantuanPenerima','r_bantuan_penerima_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'tahun' => 'Tahun',
			'nama' => 'Nama',
			'keterangan' => 'Keterangan',
			'file_doc_sk' => 'File Doc Sk',
			'created_date' => 'Created Date',
			'created_by' => 'Created By',
			'modified_date' => 'Modified Date',
			'modified_by' => 'Modified By',
			'deleted' => 'Deleted',
			'r_bantuan_id' => 'R Bantuan',
			'bentuk_bantuan'=>'Bentuk Bantuan',
			'm_pegawai_nip'=>'PPK',
			'r_bantuan_penerima_id'=>'Penerima Bantuan'
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
		$criteria->compare('tahun',$this->tahun);
		$criteria->compare('nama',$this->nama,true);
		$criteria->compare('keterangan',$this->keterangan,true);
		$criteria->compare('file_doc_sk',$this->file_doc_sk,true);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('modified_date',$this->modified_date,true);
		$criteria->compare('modified_by',$this->modified_by);
		$criteria->compare('deleted',$this->deleted);
		$criteria->compare('r_bantuan_id',$this->r_bantuan_id);
		$criteria->compare('bentuk_bantuan',$this->bentuk_bantuan);
		$criteria->compare('m_pegawai_nip',$this->m_pegawai_nip);
		$criteria->compare('status',$this->status);
		$criteria->compare('r_bantuan_penerima_id',$this->r_bantuan_penerima_id);
		
		

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	protected function beforeSave() {
		parent::beforeSave();
		$this->status=0;
		return true;
	}
}