<?php

/**
 * This is the model class for table "t_bantuan_penggunaan_dana".
 *
 * The followings are the available columns in table 't_bantuan_penggunaan_dana':
 * @property integer $id
 * @property integer $t_bantuan_penerima_id
 * @property string $uraian
 * @property string $qty
 * @property string $satuan_id
 * @property string $harga_satuan
 * @property string $harga_total
 * @property string $tanggal_transaksi
 * @property string $bukti_kwitansi
 * @property string $status_data
 * @property string $created_date
 * @property integer $created_by
 * @property string $modified_date
 * @property integer $modified_by
 * @property integer $deleted
 * @property string $r_peruntukan_dana_bos
 */
class TBantuanPenggunaanDana extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TBantuanPenggunaanDana the static model class
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
		return 't_bantuan_penggunaan_dana';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('t_bantuan_penerima_id,status_data,status_pembelian_pengeluaran', 'required'),
			array('t_bantuan_penerima_id, created_by, modified_by, deleted', 'numerical', 'integerOnly'=>true),
			array('uraian,toko_pembelian', 'length', 'max'=>300),
			array('qty, harga_satuan, harga_total', 'length', 'max'=>18),
			array('bukti_kwitansi,no_bukti', 'length', 'max'=>150),
			array('status_data', 'length', 'max'=>1),
			array('satuan_id, tanggal_transaksi,created_date, modified_date, r_peruntukan_dana_bos_id', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, t_bantuan_penerima_id, uraian, qty, satuan_id, harga_satuan, harga_total, tanggal_transaksi, bukti_kwitansi, status_data, created_date, created_by, modified_date, modified_by, deleted, r_peruntukan_dana_bos_id,no_bukti,status_pembelian_pengeluaran,toko_pembelian', 'safe', 'on'=>'search'),
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
			//'rSatuan'=>array(self::BELONGS_TO,'RSatuan','r_satuan_id'),
			'tBantuanPenerima'=>array(self::BELONGS_TO,'TBantuanPenerima','t_bantuan_penerima_id'),
			'rPeruntukanDanaBos'=>array(self::BELONGS_TO,'RPeruntukanDanaBos','r_peruntukan_dana_bos_id')
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
			'uraian' => 'Uraian',
			'qty' => 'Qty',
			'satuan_id' => 'Satuan',
			'harga_satuan' => 'Harga Satuan',
			'harga_total' => 'Harga Total',
			'tanggal_transaksi' => 'Tanggal Transaksi',
			'bukti_kwitansi' => 'Bukti Kwitansi',
			'status_data' => 'Status Data',
			'created_date' => 'Created Date',
			'created_by' => 'Created By',
			'modified_date' => 'Modified Date',
			'modified_by' => 'Modified By',
			'deleted' => 'Deleted',
			'r_peruntukan_dana_bos' => 'R Peruntukan Dana Bos',
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
		$criteria->compare('uraian',$this->uraian,true);
		$criteria->compare('qty',$this->qty,true);
		$criteria->compare('satuan_id',$this->satuan_id,true);
		$criteria->compare('harga_satuan',$this->harga_satuan,true);
		$criteria->compare('harga_total',$this->harga_total,true);
		$criteria->compare('tanggal_transaksi',$this->tanggal_transaksi,true);
		$criteria->compare('bukti_kwitansi',$this->bukti_kwitansi,true);
		$criteria->compare('status_data',$this->status_data,true);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('modified_date',$this->modified_date,true);
		$criteria->compare('modified_by',$this->modified_by);
		$criteria->compare('deleted',$this->deleted);
		$criteria->compare('r_peruntukan_dana_bos',$this->r_peruntukan_dana_bos,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}