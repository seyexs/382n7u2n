<?php
if($is_pegawai_siswa==0){
    $data=CHtml::listData(MSiswa::model()->findAll(array(
        'condition'=>'id not in(select pegawai_siswa_id from t_user_pegawai_siswa where is_pegawai_siswa=0)'
    )),'id','mPerson.nama');
}else{
    $data=CHtml::listData(MPegawai::model()->findAll(),'id','mPerson.nama');
}
echo CHtml::dropDownList('TUserPegawaiSiswa[pegawai_siswa_id]','pegawai_siswa_id',$data,array('max-width'=>'200px','empty'=>'- Pilih Siswa/Pegawai -'));
?>
