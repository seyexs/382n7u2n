<?php

/**
 * This is the model class for table "peserta_didik".
 *
 * The followings are the available columns in table 'peserta_didik':
 * @property string $peserta_didik_id
 * @property string $nama
 * @property string $jenis_kelamin
 * @property string $nisn
 * @property string $nik
 * @property string $tempat_lahir
 * @property string $tanggal_lahir
 * @property integer $agama_id
 * @property string $kewarganegaraan
 * @property integer $kebutuhan_khusus_id
 * @property string $sekolah_id
 * @property string $alamat_jalan
 * @property string $rt
 * @property string $rw
 * @property string $nama_dusun
 * @property string $desa_kelurahan
 * @property string $kode_wilayah
 * @property string $kode_pos
 * @property string $jenis_tinggal_id
 * @property string $alat_transportasi_id
 * @property string $nomor_telepon_rumah
 * @property string $nomor_telepon_seluler
 * @property string $email
 * @property string $penerima_KPS
 * @property string $no_KPS
 * @property integer $status_data
 * @property string $nama_ayah
 * @property string $tahun_lahir_ayah
 * @property string $jenjang_pendidikan_ayah
 * @property integer $pekerjaan_id_ayah
 * @property integer $penghasilan_id_ayah
 * @property integer $kebutuhan_khusus_id_ayah
 * @property string $nama_ibu_kandung
 * @property string $tahun_lahir_ibu
 * @property string $jenjang_pendidikan_ibu
 * @property integer $penghasilan_id_ibu
 * @property integer $pekerjaan_id_ibu
 * @property integer $kebutuhan_khusus_id_ibu
 * @property string $nama_wali
 * @property string $tahun_lahir_wali
 * @property string $jenjang_pendidikan_wali
 * @property integer $pekerjaan_id_wali
 * @property integer $penghasilan_id_wali
 * @property string $Last_update
 * @property string $Soft_delete
 * @property string $last_sync
 * @property string $Updater_ID
 */
class PesertaDidik extends ActiveRecord
{
	public $propinsi;
	public $kabupaten;
	public function getDbConnection()
    {
        return self::getDapodikDbConnection();
    }
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PesertaDidik the static model class
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
		return 'peserta_didik';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('peserta_didik_id, nama, jenis_kelamin, tanggal_lahir, agama_id, kewarganegaraan, kebutuhan_khusus_id, sekolah_id, alamat_jalan, desa_kelurahan, kode_wilayah, penerima_KPS, kebutuhan_khusus_id_ayah, nama_ibu_kandung, kebutuhan_khusus_id_ibu, Soft_delete, Updater_ID', 'required'),
			array('agama_id, kebutuhan_khusus_id, status_data, pekerjaan_id_ayah, penghasilan_id_ayah, kebutuhan_khusus_id_ayah, penghasilan_id_ibu, pekerjaan_id_ibu, kebutuhan_khusus_id_ibu, pekerjaan_id_wali, penghasilan_id_wali', 'numerical', 'integerOnly'=>true),
			array('nama, nama_ayah, nama_ibu_kandung', 'length', 'max'=>60),
			array('jenis_kelamin, penerima_KPS, Soft_delete', 'length', 'max'=>1),
			array('nisn', 'length', 'max'=>10),
			array('nik', 'length', 'max'=>16),
			array('tempat_lahir', 'length', 'max'=>32),
			array('kewarganegaraan, rt, rw, jenis_tinggal_id, alat_transportasi_id, jenjang_pendidikan_ayah, jenjang_pendidikan_ibu, jenjang_pendidikan_wali', 'length', 'max'=>2),
			array('alamat_jalan', 'length', 'max'=>80),
			array('nama_dusun, desa_kelurahan, email, no_KPS', 'length', 'max'=>50),
			array('kode_wilayah', 'length', 'max'=>8),
			array('kode_pos', 'length', 'max'=>5),
			array('nomor_telepon_rumah, nomor_telepon_seluler', 'length', 'max'=>20),
			array('tahun_lahir_ayah, tahun_lahir_ibu, tahun_lahir_wali', 'length', 'max'=>4),
			array('nama_wali', 'length', 'max'=>30),
			array('Last_update, last_sync', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('peserta_didik_id, nama, jenis_kelamin, nisn, nik, tempat_lahir, tanggal_lahir, agama_id, kewarganegaraan, kebutuhan_khusus_id, sekolah_id, alamat_jalan, rt, rw, nama_dusun, desa_kelurahan, kode_wilayah, kode_pos, jenis_tinggal_id, alat_transportasi_id, nomor_telepon_rumah, nomor_telepon_seluler, email, penerima_KPS, no_KPS, status_data, nama_ayah, tahun_lahir_ayah, jenjang_pendidikan_ayah, pekerjaan_id_ayah, penghasilan_id_ayah, kebutuhan_khusus_id_ayah, nama_ibu_kandung, tahun_lahir_ibu, jenjang_pendidikan_ibu, penghasilan_id_ibu, pekerjaan_id_ibu, kebutuhan_khusus_id_ibu, nama_wali, tahun_lahir_wali, jenjang_pendidikan_wali, pekerjaan_id_wali, penghasilan_id_wali, Last_update, Soft_delete, last_sync, Updater_ID', 'safe', 'on'=>'search'),
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
			'sekolah'=>array(self::BELONGS_TO,'Sekolah','sekolah_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'peserta_didik_id' => 'Peserta Didik',
			'nama' => 'Nama',
			'jenis_kelamin' => 'Jenis Kelamin',
			'nisn' => 'Nisn',
			'nik' => 'Nik',
			'tempat_lahir' => 'Tempat Lahir',
			'tanggal_lahir' => 'Tanggal Lahir',
			'agama_id' => 'Agama',
			'kewarganegaraan' => 'Kewarganegaraan',
			'kebutuhan_khusus_id' => 'Kebutuhan Khusus',
			'sekolah_id' => 'Sekolah',
			'alamat_jalan' => 'Alamat Jalan',
			'rt' => 'Rt',
			'rw' => 'Rw',
			'nama_dusun' => 'Nama Dusun',
			'desa_kelurahan' => 'Desa Kelurahan',
			'kode_wilayah' => 'Kode Wilayah',
			'kode_pos' => 'Kode Pos',
			'jenis_tinggal_id' => 'Jenis Tinggal',
			'alat_transportasi_id' => 'Alat Transportasi',
			'nomor_telepon_rumah' => 'Nomor Telepon Rumah',
			'nomor_telepon_seluler' => 'Nomor Telepon Seluler',
			'email' => 'Email',
			'penerima_KPS' => 'Penerima Kps',
			'no_KPS' => 'No Kps',
			'status_data' => 'Status Data',
			'nama_ayah' => 'Nama Ayah',
			'tahun_lahir_ayah' => 'Tahun Lahir Ayah',
			'jenjang_pendidikan_ayah' => 'Jenjang Pendidikan Ayah',
			'pekerjaan_id_ayah' => 'Pekerjaan Id Ayah',
			'penghasilan_id_ayah' => 'Penghasilan Id Ayah',
			'kebutuhan_khusus_id_ayah' => 'Kebutuhan Khusus Id Ayah',
			'nama_ibu_kandung' => 'Nama Ibu Kandung',
			'tahun_lahir_ibu' => 'Tahun Lahir Ibu',
			'jenjang_pendidikan_ibu' => 'Jenjang Pendidikan Ibu',
			'penghasilan_id_ibu' => 'Penghasilan Id Ibu',
			'pekerjaan_id_ibu' => 'Pekerjaan Id Ibu',
			'kebutuhan_khusus_id_ibu' => 'Kebutuhan Khusus Id Ibu',
			'nama_wali' => 'Nama Wali',
			'tahun_lahir_wali' => 'Tahun Lahir Wali',
			'jenjang_pendidikan_wali' => 'Jenjang Pendidikan Wali',
			'pekerjaan_id_wali' => 'Pekerjaan Id Wali',
			'penghasilan_id_wali' => 'Penghasilan Id Wali',
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

		$criteria->compare('peserta_didik_id',$this->peserta_didik_id,true);
		$criteria->compare('nama',$this->nama,true);
		$criteria->compare('jenis_kelamin',$this->jenis_kelamin,true);
		$criteria->compare('nisn',$this->nisn,true);
		$criteria->compare('nik',$this->nik,true);
		$criteria->compare('tempat_lahir',$this->tempat_lahir,true);
		$criteria->compare('tanggal_lahir',$this->tanggal_lahir,true);
		$criteria->compare('agama_id',$this->agama_id);
		$criteria->compare('kewarganegaraan',$this->kewarganegaraan,true);
		$criteria->compare('kebutuhan_khusus_id',$this->kebutuhan_khusus_id);
		$criteria->compare('sekolah_id',$this->sekolah_id,true);
		$criteria->compare('alamat_jalan',$this->alamat_jalan,true);
		$criteria->compare('rt',$this->rt,true);
		$criteria->compare('rw',$this->rw,true);
		$criteria->compare('nama_dusun',$this->nama_dusun,true);
		$criteria->compare('desa_kelurahan',$this->desa_kelurahan,true);
		$criteria->compare('kode_wilayah',$this->kode_wilayah,true);
		$criteria->compare('kode_pos',$this->kode_pos,true);
		$criteria->compare('jenis_tinggal_id',$this->jenis_tinggal_id,true);
		$criteria->compare('alat_transportasi_id',$this->alat_transportasi_id,true);
		$criteria->compare('nomor_telepon_rumah',$this->nomor_telepon_rumah,true);
		$criteria->compare('nomor_telepon_seluler',$this->nomor_telepon_seluler,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('penerima_KPS',$this->penerima_KPS,true);
		$criteria->compare('no_KPS',$this->no_KPS,true);
		$criteria->compare('status_data',$this->status_data);
		$criteria->compare('nama_ayah',$this->nama_ayah,true);
		$criteria->compare('tahun_lahir_ayah',$this->tahun_lahir_ayah,true);
		$criteria->compare('jenjang_pendidikan_ayah',$this->jenjang_pendidikan_ayah,true);
		$criteria->compare('pekerjaan_id_ayah',$this->pekerjaan_id_ayah);
		$criteria->compare('penghasilan_id_ayah',$this->penghasilan_id_ayah);
		$criteria->compare('kebutuhan_khusus_id_ayah',$this->kebutuhan_khusus_id_ayah);
		$criteria->compare('nama_ibu_kandung',$this->nama_ibu_kandung,true);
		$criteria->compare('tahun_lahir_ibu',$this->tahun_lahir_ibu,true);
		$criteria->compare('jenjang_pendidikan_ibu',$this->jenjang_pendidikan_ibu,true);
		$criteria->compare('penghasilan_id_ibu',$this->penghasilan_id_ibu);
		$criteria->compare('pekerjaan_id_ibu',$this->pekerjaan_id_ibu);
		$criteria->compare('kebutuhan_khusus_id_ibu',$this->kebutuhan_khusus_id_ibu);
		$criteria->compare('nama_wali',$this->nama_wali,true);
		$criteria->compare('tahun_lahir_wali',$this->tahun_lahir_wali,true);
		$criteria->compare('jenjang_pendidikan_wali',$this->jenjang_pendidikan_wali,true);
		$criteria->compare('pekerjaan_id_wali',$this->pekerjaan_id_wali);
		$criteria->compare('penghasilan_id_wali',$this->penghasilan_id_wali);
		$criteria->compare('Last_update',$this->Last_update,true);
		$criteria->compare('Soft_delete',$this->Soft_delete,true);
		$criteria->compare('last_sync',$this->last_sync,true);
		$criteria->compare('Updater_ID',$this->Updater_ID,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}