<?php

/**
 * This is the model class for table "t_bantuan_penerimaan_pengembalian".
 *
 * The followings are the available columns in table 't_bantuan_penerimaan_pengembalian':
 * @property integer $id
 * @property integer $t_bantuan_penerima_id
 * @property double $jumlah_bantuan
 * @property string $tanggal_diterima_dikembalikan
 * @property string $bukti_diterima_dikembalikan
 * @property integer $status
 * @property string $created_date
 * @property integer $created_by
 * @property string $modified_date
 * @property integer $modified_by
 * @property integer $deleted
 */
class TBantuanPenerimaanPengembalian extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TBantuanPenerimaanPengembalian the static model class
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
		return 't_bantuan_penerimaan_pengembalian';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('t_bantuan_penerima_id', 'required'),
			array('t_bantuan_penerima_id, status, created_by, modified_by, deleted', 'numerical', 'integerOnly'=>true),
			array('jumlah_bantuan', 'numerical'),
			array('bukti_diterima_dikembalikan', 'length', 'max'=>145),
			array('tanggal_diterima_dikembalikan, created_date, modified_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, t_bantuan_penerima_id, jumlah_bantuan, tanggal_diterima_dikembalikan, bukti_diterima_dikembalikan, status, created_date, created_by, modified_date, modified_by, deleted', 'safe', 'on'=>'search'),
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
			'tBantuanPenerima'=>array(SELF::BELONGS_TO,'TBantuanPenerima','t_bantuan_penerima_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			't_bantuan_penerima_id' => 'T Bantuan Penerima',
			'jumlah_bantuan' => 'Jumlah Bantuan',
			'tanggal_diterima_dikembalikan' => 'Tanggal Diterima Dikembalikan',
			'bukti_diterima_dikembalikan' => 'Bukti Diterima Dikembalikan',
			'status' => 'Status',
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
		$criteria->compare('t_bantuan_penerima_id',$this->t_bantuan_penerima_id);
		$criteria->compare('jumlah_bantuan',$this->jumlah_bantuan);
		$criteria->compare('tanggal_diterima_dikembalikan',$this->tanggal_diterima_dikembalikan,true);
		$criteria->compare('bukti_diterima_dikembalikan',$this->bukti_diterima_dikembalikan,true);
		$criteria->compare('status',$this->status);
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