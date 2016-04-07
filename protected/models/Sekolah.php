<?php

/**
 * This is the model class for table "sekolah".
 *
 * The followings are the available columns in table 'sekolah':
 * @property string $sekolah_id
 * @property string $nama
 * @property string $nama_nomenklatur
 * @property string $nss
 * @property string $npsn
 * @property integer $bentuk_pendidikan_id
 * @property string $alamat_jalan
 * @property string $rt
 * @property string $rw
 * @property string $nama_dusun
 * @property string $desa_kelurahan
 * @property string $kode_wilayah
 * @property string $kode_pos
 * @property string $lintang
 * @property string $bujur
 * @property string $nomor_telepon
 * @property string $nomor_fax
 * @property string $email
 * @property string $website
 * @property integer $kebutuhan_khusus_id
 * @property string $status_sekolah
 * @property string $sk_pendirian_sekolah
 * @property string $tanggal_sk_pendirian
 * @property string $status_kepemilikan_id
 * @property string $yayasan_id
 * @property string $sk_izin_operasional
 * @property string $tanggal_sk_izin_operasional
 * @property string $no_rekening
 * @property string $nama_bank
 * @property string $cabang_kcp_unit
 * @property string $rekening_atas_nama
 * @property string $mbs
 * @property string $luas_tanah_milik
 * @property string $luas_tanah_bukan_milik
 * @property string $kode_registrasi
 * @property string $npwp
 * @property string $flag
 * @property string $pic_id
 * @property string $Last_update
 * @property string $Soft_delete
 * @property string $last_sync
 * @property string $Updater_ID
 */
class Sekolah extends ActiveRecord
{
	public $propinsi;
	public $kabupaten;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Sekolah the static model class
	 */

	public function getDbConnection()
    {
        return self::getDapodikDbConnection();
    }	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'dbo.sekolah';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sekolah_id, nama, bentuk_pendidikan_id, alamat_jalan, desa_kelurahan, kode_wilayah, kebutuhan_khusus_id, status_sekolah, status_kepemilikan_id, mbs, luas_tanah_milik, luas_tanah_bukan_milik, Soft_delete, Updater_ID', 'required'),
			array('bentuk_pendidikan_id, kebutuhan_khusus_id', 'numerical', 'integerOnly'=>true),
			array('nama, nama_nomenklatur, alamat_jalan', 'length', 'max'=>80),
			array('nss', 'length', 'max'=>12),
			array('npsn, kode_wilayah', 'length', 'max'=>8),
			array('rt, rw', 'length', 'max'=>2),
			array('nama_dusun, desa_kelurahan, email, sk_pendirian_sekolah, sk_izin_operasional, cabang_kcp_unit, rekening_atas_nama', 'length', 'max'=>50),
			array('kode_pos', 'length', 'max'=>5),
			array('lintang, bujur', 'length', 'max'=>11),
			array('nomor_telepon, nomor_fax, no_rekening, nama_bank', 'length', 'max'=>20),
			array('website', 'length', 'max'=>100),
			array('status_sekolah, status_kepemilikan_id, mbs, flag, Soft_delete', 'length', 'max'=>1),
			array('luas_tanah_milik, luas_tanah_bukan_milik', 'length', 'max'=>7),
			array('kode_registrasi', 'length', 'max'=>19),
			array('npwp', 'length', 'max'=>15),
			array('tanggal_sk_pendirian, yayasan_id, tanggal_sk_izin_operasional, pic_id, Last_update, last_sync', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('sekolah_id, nama, nama_nomenklatur, nss, npsn, bentuk_pendidikan_id, alamat_jalan, rt, rw, nama_dusun, desa_kelurahan, kode_wilayah, kode_pos, lintang, bujur, nomor_telepon, nomor_fax, email, website, kebutuhan_khusus_id, status_sekolah, sk_pendirian_sekolah, tanggal_sk_pendirian, status_kepemilikan_id, yayasan_id, sk_izin_operasional, tanggal_sk_izin_operasional, no_rekening, nama_bank, cabang_kcp_unit, rekening_atas_nama, mbs, luas_tanah_milik, luas_tanah_bukan_milik, kode_registrasi, npwp, flag, pic_id, Last_update, Soft_delete, last_sync, Updater_ID', 'safe', 'on'=>'search'),
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
			'sekolah_id' => 'Sekolah',
			'nama' => 'Nama',
			'nama_nomenklatur' => 'Nama Nomenklatur',
			'nss' => 'Nss',
			'npsn' => 'Npsn',
			'bentuk_pendidikan_id' => 'Bentuk Pendidikan',
			'alamat_jalan' => 'Alamat Jalan',
			'rt' => 'Rt',
			'rw' => 'Rw',
			'nama_dusun' => 'Nama Dusun',
			'desa_kelurahan' => 'Desa Kelurahan',
			'kode_wilayah' => 'Kode Wilayah',
			'kode_pos' => 'Kode Pos',
			'lintang' => 'Lintang',
			'bujur' => 'Bujur',
			'nomor_telepon' => 'Nomor Telepon',
			'nomor_fax' => 'Nomor Fax',
			'email' => 'Email',
			'website' => 'Website',
			'kebutuhan_khusus_id' => 'Kebutuhan Khusus',
			'status_sekolah' => 'Status Sekolah',
			'sk_pendirian_sekolah' => 'Sk Pendirian Sekolah',
			'tanggal_sk_pendirian' => 'Tanggal Sk Pendirian',
			'status_kepemilikan_id' => 'Status Kepemilikan',
			'yayasan_id' => 'Yayasan',
			'sk_izin_operasional' => 'Sk Izin Operasional',
			'tanggal_sk_izin_operasional' => 'Tanggal Sk Izin Operasional',
			'no_rekening' => 'No Rekening',
			'nama_bank' => 'Nama Bank',
			'cabang_kcp_unit' => 'Cabang Kcp Unit',
			'rekening_atas_nama' => 'Rekening Atas Nama',
			'mbs' => 'Mbs',
			'luas_tanah_milik' => 'Luas Tanah Milik',
			'luas_tanah_bukan_milik' => 'Luas Tanah Bukan Milik',
			'kode_registrasi' => 'Kode Registrasi',
			'npwp' => 'Npwp',
			'flag' => 'Flag',
			'pic_id' => 'Pic',
			'Last_update' => 'Last Update',
			'Soft_delete' => 'Soft Delete',
			'last_sync' => 'Last Sync',
			'Updater_ID' => 'Updater',
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

		$criteria->compare('sekolah_id',$this->sekolah_id,true);
		$criteria->compare('nama',$this->nama,true);
		$criteria->compare('nama_nomenklatur',$this->nama_nomenklatur,true);
		$criteria->compare('nss',$this->nss,true);
		$criteria->compare('npsn',$this->npsn,true);
		$criteria->compare('bentuk_pendidikan_id',$this->bentuk_pendidikan_id);
		$criteria->compare('alamat_jalan',$this->alamat_jalan,true);
		$criteria->compare('rt',$this->rt,true);
		$criteria->compare('rw',$this->rw,true);
		$criteria->compare('nama_dusun',$this->nama_dusun,true);
		$criteria->compare('desa_kelurahan',$this->desa_kelurahan,true);
		$criteria->compare('kode_wilayah',$this->kode_wilayah,true);
		$criteria->compare('kode_pos',$this->kode_pos,true);
		$criteria->compare('lintang',$this->lintang,true);
		$criteria->compare('bujur',$this->bujur,true);
		$criteria->compare('nomor_telepon',$this->nomor_telepon,true);
		$criteria->compare('nomor_fax',$this->nomor_fax,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('website',$this->website,true);
		$criteria->compare('kebutuhan_khusus_id',$this->kebutuhan_khusus_id);
		$criteria->compare('status_sekolah',$this->status_sekolah,true);
		$criteria->compare('sk_pendirian_sekolah',$this->sk_pendirian_sekolah,true);
		$criteria->compare('tanggal_sk_pendirian',$this->tanggal_sk_pendirian,true);
		$criteria->compare('status_kepemilikan_id',$this->status_kepemilikan_id,true);
		$criteria->compare('yayasan_id',$this->yayasan_id,true);
		$criteria->compare('sk_izin_operasional',$this->sk_izin_operasional,true);
		$criteria->compare('tanggal_sk_izin_operasional',$this->tanggal_sk_izin_operasional,true);
		$criteria->compare('no_rekening',$this->no_rekening,true);
		$criteria->compare('nama_bank',$this->nama_bank,true);
		$criteria->compare('cabang_kcp_unit',$this->cabang_kcp_unit,true);
		$criteria->compare('rekening_atas_nama',$this->rekening_atas_nama,true);
		$criteria->compare('mbs',$this->mbs,true);
		$criteria->compare('luas_tanah_milik',$this->luas_tanah_milik,true);
		$criteria->compare('luas_tanah_bukan_milik',$this->luas_tanah_bukan_milik,true);
		$criteria->compare('kode_registrasi',$this->kode_registrasi,true);
		$criteria->compare('npwp',$this->npwp,true);
		$criteria->compare('flag',$this->flag,true);
		$criteria->compare('pic_id',$this->pic_id,true);
		$criteria->compare('Last_update',$this->Last_update,true);
		$criteria->compare('Soft_delete',$this->Soft_delete,true);
		$criteria->compare('last_sync',$this->last_sync,true);
		$criteria->compare('Updater_ID',$this->Updater_ID,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}