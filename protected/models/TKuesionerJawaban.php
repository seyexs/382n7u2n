<?php

/**
 * This is the model class for table "t_kuesioner_jawaban".
 *
 * The followings are the available columns in table 't_kuesioner_jawaban':
 * @property integer $id
 * @property integer $user_id
 * @property integer $t_kuesioner_pilihan_jawaban_id
 * @property string $catatan_jawaban
 * @property string $created_date
 * @property integer $created_by
 * @property string $modified_date
 * @property integer $modified_by
 * @property integer $deleted
 */
class TKuesionerJawaban extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TKuesionerJawaban the static model class
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
		return 't_kuesioner_jawaban';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, t_kuesioner_pilihan_jawaban_id', 'required'),
			array('user_id, t_kuesioner_pilihan_jawaban_id, created_by, modified_by, deleted', 'numerical', 'integerOnly'=>true),
			array('catatan_jawaban, created_date, modified_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, t_kuesioner_pilihan_jawaban_id, catatan_jawaban, created_date, created_by, modified_date, modified_by, deleted', 'safe', 'on'=>'search'),
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
			'tKuesionerPilihanJawaban'=>array(self::BELONGS_TO,'TKuesionerPilihanJawaban','t_kuesioner_pilihan_jawaban_id'),
			'user'=>array(self::BELONGS_TO,'User','user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			't_kuesioner_pilihan_jawaban_id' => 'T Kuesioner Pilihan Jawaban',
			'catatan_jawaban' => 'Catatan Jawaban',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('t_kuesioner_pilihan_jawaban_id',$this->t_kuesioner_pilihan_jawaban_id);
		$criteria->compare('catatan_jawaban',$this->catatan_jawaban,true);
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