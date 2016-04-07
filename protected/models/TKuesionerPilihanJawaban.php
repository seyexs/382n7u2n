<?php

/**
 * This is the model class for table "t_kuesioner_pilihan_jawaban".
 *
 * The followings are the available columns in table 't_kuesioner_pilihan_jawaban':
 * @property integer $id
 * @property integer $t_pertanyaan_id
 * @property string $pilihan_jawaban
 * @property string $keterangan_tambahan
 * @property integer $urutan
 * @property string $created_date
 * @property integer $created_by
 * @property string $modified_date
 * @property integer $modified_by
 * @property integer $deleted
 */
class TKuesionerPilihanJawaban extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TKuesionerPilihanJawaban the static model class
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
		return 't_kuesioner_pilihan_jawaban';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('t_pertanyaan_id', 'required'),
			array('t_pertanyaan_id, urutan, created_by, modified_by, deleted,skor', 'numerical', 'integerOnly'=>true),
			array('pilihan_jawaban, keterangan_tambahan, created_date, modified_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, t_pertanyaan_id, pilihan_jawaban, keterangan_tambahan, urutan,skor, created_date, created_by, modified_date, modified_by, deleted', 'safe', 'on'=>'search'),
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
			'tKuesionerPertanyaan'=>array(self::BELONGS_TO,'TKuesionerPertanyaan','t_pertanyaan_id'),
			'tKuesionerJawaban'=>array(self::HAS_MANY,'TKuesionerJawaban','t_kuesioner_pilihan_jawaban_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			't_pertanyaan_id' => 'T Pertanyaan',
			'pilihan_jawaban' => 'Pilihan Jawaban',
			'keterangan_tambahan' => 'Keterangan Tambahan',
			'urutan' => 'Urutan',
			'skor'=>'Skor',
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
		$criteria->compare('t_pertanyaan_id',$this->t_pertanyaan_id);
		$criteria->compare('pilihan_jawaban',$this->pilihan_jawaban,true);
		$criteria->compare('keterangan_tambahan',$this->keterangan_tambahan,true);
		$criteria->compare('urutan',$this->urutan);
		$criteria->compare('skor',$this->skor);
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