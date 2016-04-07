<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
return array(
    // this is used in contact page
    'adminEmail' => 'ebantuansmk@psmk.kemdikbud.go.id',
    'namaSekolah' => 'SMKN 46 Jakarta',
    'alamatSekolah' => 'Jl. Balai Pustaka Baru No. 1',
    'dirFotoPegawai' => '/images/person/pegawai',
    'dirFotoSiswa' => '/images/person/siswa',
    'dirFotoUser' => '/images/user',
    'dirFotoTemp' => '/images/temp',
    'dirFotoGallery' => '/images/gallery',
	'image_path'=>'files/photos',
    'dirTemp' => 'media/tmp',
	'dirBantuanDoc'=>'media/mydocuments/program-bantuan',
	'dbname'=>'[siban].[dbo]',
	'dapodikmenDb'=>'Dapodikmen',
	'appDb'=>'siban',
    'direktorat' => array(
        'email' => array(
        ),
        'site' => 'http://122.200.145.202/emisdir',
        'download-rm' => '/download/smk',
    ),
    'alamatSekolah' => 'Jl. Balai Pustaka Baru No. 1',
    'sysnc-models' => array(
        'umum' => array(
            'MPropinsi',
            'MKabupatenKota',
            'MKecamatan',
        ),
        'khusus' => array(
            'MSekolah',
            'RmMataPelajaran', 
            'RmStandarKompetensi',
            'RmKompetensiDasar',
        ),
        'transaksi' => array(
            'TSpectrum',
            'TBidangStudi',
            'TProgramStudi',
            'TProgramKeahlian',
        )
    ),
    'tingkat' => array('1' => 'X', '2' => 'XI', '3' => 'XII', '4' => 'XIII'),
    'sex' => array(
        '1' => 'Pria',
        '2' => 'Wanita',
    ),
    'agama' => array(
        '1' => 'Islam',
        '2' => 'Kristen Protestan',
        '4' => 'Kristen Katolik',
        '5' => 'Budha',
        '6' => 'Hindu',
    ),
    'gol-darah' => array(
        '1' => 'A',
        '2' => 'B',
        '3' => 'AB',
        '4' => 'O',
    ),
    'rhesus_darah' => array(
		'1' => 'Positif',
		'2' => 'Negatif'
    ),
    'pegawai-status' => array(
        '1' => 'PNS',
        '2' => 'Non PNS',
    ),
    'pegawai-status-saat-ini' => array(
        '1' => 'Aktif',
        '2' => 'Cuti',
        '3' => 'Pindah',
        '4' => 'Pensiun',
    ),
    'pegawai-status-ketetapan' => array(
        '1' => 'Guru Tetap',
        '2' => 'Guru Tidak Tetap',
        '3' => 'Pegawai Tetap',
        '4' => 'Pegawai Tidak Tetap',
    ),
    'status_hidup' => array(
        '1' => 'Masih Hidup',
        '2' => 'Sudah Meninggal'
    ),
    'kewarganegaraan' => array(
        '1' => 'WNI',
        '2' => 'WNA'
    ),
    'status-keadaan-barang' => array(
        '1' => 'Baik',
        '2' => 'Rusak Sedang',
        '3' => 'Rusak'
    ),
    'libur' => array(
        '0' => 'Tidak',
        '1' => 'Ya'
    ),
    'hari' => array(
        '1' => 'Senin',
        '2' => 'Selasa',
        '3' => 'Rabu',
        '4' => 'Kamis',
        '5' => 'Jumat',
        '6' => 'Sabtu',
        '0' => 'Minggu',
    ),
    'hari-libur' => '6,0',
    'status-jam-pelajaran'=>array(
        '0'=>'Istirahat',
        '1'=>'Kegiatan Pembelajaran',
        '2'=>'Upacara',
    ),
    'status-tahun-pelajaran' => array(
        '0' => 'Tidak Aktif',
        '1' => 'Aktif',
        '2' => 'Rencana',
    ),
    'grade_asc' => array(
        '1' => 'Tinggi',
        '2' => 'Sedang',
        '3' => 'Rendah'
    ),
    'grade_desc' => array(
        '3' => 'Tinggi',
        '2' => 'Sedang',
        '1' => 'Rendah'
    ),
    'tipe_jabatan_organisasi'=>array(
        'collateral'=>'collateral',
        'subordinate'=>'subordinate',
        'staff'=>'staff',
    ),
    'penandatangan-sk'=>array(
            'Bupati'=>'Bupati',
            'Walikota'=>'Walikota',
            'Kanwil'=>'Kanwil',
            'Dinas Pendidikan'=>'Dinas Pendidikan',
            'Mendiknas'=>'Mendiknas',
            'Menhut'=>'Menhut',
            'Mentan'=>'Mentan',
            'Menkes'=>'Menkes',
        ),
    'jenis_kendaraan'=>array(
        'Kendaraan Roda Dua(Motor)'=>'Kendaraan Roda Dua(Motor)',
        'Kendaraan Roda Empat(Mobil)'=>'Kendaraan Roda Empat(Mobil)'
    ),
    'pangsa-pasar'=>array(
        'Siswa'=>'Siswa',
        'Karyawan'=>'Karyawan',
        'Masyarakat'=>'Masyarakat',
        'Industri'=>'Industri',
    ),
    'status-prakerin'=>array(
        'Sedang Prakerin',
        'Selesai Prakerin',
        'Relokasi Tempat Prakerin'
    ),
    'state'=>array(
        1=>'Aktif',
        0=>'Tidak Aktif',
    ),
    'status-siswa'=>array(
        0=>'Proses Belajar',
        1=>'Naik Kelas',
        2=>'Tinggal Kelas',
        3=>'Mengundurkan Diri / Putus Sekolah',
        4=>'Drop Out',
    ),
    'flag_jenis_mapel'=>array(
        1=>'Adaptif',
        2=>'Normatif',
        3=>'Produktif',
        4=>'Muatan Lokal',
    ),
    'flag_sub_jenis_mapel'=>array(
        1=>'Dasar Kejuruan/Dasar Program Keahlian',
        2=>'Kejuruan/Pekat Keahlian',
        3=>'Dasar Bidang Keahlian'
    ),
    'updateUrl'=>'http://122.200.145.202/emis/',
    'pusdatUrl'=>'http://122.200.145.202/emisdir/Services/',
    'errorMsg'=>array(
        '23000'=>'Data ini masih aktif dipakai pada transaksi data lainnya.'
    ),
    'bulan'=>array(
        1=>'Januari',
        2=>'Februari',
        3=>'Maret',
        4=>'April',
        5=>'Mei',
        6=>'Juni',
        7=>'Juli',
        8=>'Agustus',
        9=>'September',
        10=>'Oktober',
        11=>'November',
        12=>'Desember',
    ),
    'conditionKur13'=>0,
    'flag_kelompok_mapel_kur13'=>array(
        1=>'Kelompok A (Wajib)',
        2=>'Kelompok B (Wajib)',
        3=>'Dasar Bidang Keahlian',
        4=>'Dasar Program Keahlian',
        5=>'Paket Keahlian',
        0=>'Bukan Kelompok Kur13',
    ),
    'flag_kelompok_evaluasi'=>array(
        1=>'Kelompok Pengetahuan',
        2=>'Kelompok Keterampilan',
        3=>'Kelompok Sikap Spiritual & Sosial',
        
    )
);
?>
