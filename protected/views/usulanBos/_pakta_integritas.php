<?php

?>
<style>
.pakta_integritas{
	width:80%;
	margin:10px;
}
.pakta_integritas td{
	padding:5px;
}
.rangkuman_siswa{
	width:80%;
	
}
.rangkuman_siswa td{
	border:1px solid #000000;
}
.rangkuman_siswa tr{
	padding:2px;
	border:1px solid #000000;
}
span#justify{
   text-align: justify;
   text-align-last: justify;
   height: 1em;
   line-height: 2;
}

.span#justify :after{
   content: "";
   display: inline-block;
   width: 100%;
}
</style>
<table class="pakta_integritas">
	<tr>
		<td colspan=4 align="center" style="font-size:14px;"><b>PAKTA INTEGRITAS</b></td>
	</tr>
	<tr>
		<td colspan=4></td>
	</tr>
	<tr>
		<td colspan=4></td>
	</tr>
	<tr>
		<td colspan=4>Yang bertanda tangan di bawah ini adalah :</td>
	</tr>
	<tr>
		<td style="width:5%"></td>
		<td style="width:20%">Nama Kepala Sekolah</td>
		<td style="width:1%">:</td>
		<td style="width:74%"><?=$data[0]['kepala_sekolah']?></td>
	</tr>
	<tr>
		<td></td>
		<td>Jabatan</td>
		<td>:</td>
		<td>Kepala Sekolah</td>
	</tr>
	<tr>
		<td></td>
		<td>Nama Sekolah</td>
		<td>:</td>
		<td><?=$data[0]['nama']?></td>
	</tr>
	<tr>
		<td></td>
		<td>NPSN</td>
		<td>:</td>
		<td><?=$data[0]['npsn']?></td>
	</tr>
	<tr>
		<td></td>
		<td>Status Sekolah</td>
		<td>:</td>
		<td><?=($data[0]['status_sekolah']==1)?'Negeri':'Swasta'?></td>
	</tr>
	<tr>
		<td></td>
		<td>Alamat</td>
		<td>:</td>
		<td><?=$data[0]['alamat_jalan']?></td>
	</tr>
	<tr>
		<td></td>
		<td>Kecamatan</td>
		<td>:</td>
		<td><?=$data[0]['kecamatan']?></td>
	</tr>
	<tr>
		<td></td>
		<td>Kabupaten/Kota</td>
		<td>:</td>
		<td><?=$data[0]['kabupaten']?></td>
	</tr>
	<tr>
		<td></td>
		<td>Provinsi</td>
		<td>:</td>
		<td><?=$data[0]['propinsi']?></td>
	</tr>
	<tr>
		<td colspan=4></td>
	</tr>
	<tr>
		<td colspan=4 style="">
			<span id="justify">Dengan ini menyatakan bahwa jumlah siswa tahun pelajaran <?=(($model->tBantuanProgram->tahun)-1).'/'.$model->tBantuanProgram->tahun?> yang dientri dan dikirimkan ke sistem aplikasi
			Data Pokok Pendidikan Dasar dan Menengah (DAPODIKDASMEN) sesuai dengan rekapitulasi sebagai berikut (detail individu siswa terlampir):</span>
		</td>
	</tr>
	<tr>
		<td colspan=4>
			<table class="rangkuman_siswa">
				<tr>
					<td align="center" style="width:2%">No.</td>
					<td align="center" style="width:15%">Tingkat</td>
					<?php for($i=0;$i<count($data);$i++){
							$data_tgl=explode('-',$data[$i]['tgl_cut_off']);
							$tgl=$data_tgl[2].' '.Yii::app()->params['bulan'][(int)$data_tgl[1]].' '.$data_tgl[0];
					?>
						<td align="center" style="width:20%">Jumlah Siswa yang dientri per <?=$tgl?></td>
					<?php }?>
					<td align="center">Total Siswa T.P <?=(($model->tBantuanProgram->tahun)-1).'/'.$model->tBantuanProgram->tahun?></td>
				</tr>
				<?php
					$kls=array('X','XI','XII','XIII');
					for($j=0;$j<4;$j++){
						$total=0;
				?>
				<tr>
					<td align="center"><?=($j+1)?></td>
					<td align="center"><?=$kls[$j]?></td>
					<?php for($i=0;$i<count($data);$i++){
							$total+=(int)$data[$i]['siswa_1'.$j];
					?>
					<td align="center"><?=$data[$i]['siswa_1'.$j]?></td>
					<?php }?>
					<td align="center"><?=$total?></td>
				</tr>
				<?php }?>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan=4 style="">
			<span id="justify">Data tersebut sudah saya periksa kebenaran dan kemutakhiran datanya sesuai dengan fakta di sekolah tanpa ada rekayasa.
			Data ini selanjutnya akan menentukan besaran alokasi dana Bantuan Operasional Sekolah (BOS) SMK yang akan diterima sekolah untuk periode <?=$data[(count($data)-1)]['keterangan_periode']?> Tahun <?=$model->tBantuanProgram->tahun?></span>
		</td>
	</tr>
	<tr>
		<td colspan=4 style=""></td>
	</tr>
	<tr>
		<td colspan=4 style="">
			<span id="justify">Melalui pernyataan ini saya bertanggung jawab penuh jika dikemudian hari ditemukan ketidaksesuaian antara
			data yang dikirim dengan fakta, dan saya siap menerima sanksi dengan peraturan yang berlaku.</span>
		</td>
	</tr>
	<tr>
		<td colspan=4 style=""></td>
	</tr>
	<tr>
		<td colspan=4>Mengetahui,</td>
	</tr>
	<tr>
		<td colspan=4>Kepala Sekolah</td>
	</tr>
	<tr>
		<td colspan=4 style=""></td>
	</tr>
	<tr>
		<td colspan=4 style=""></td>
	</tr>
	<tr>
		<td colspan=4 style=""></td>
	</tr>
	<tr>
		<td colspan=4 style=""></td>
	</tr>
	<tr>
		<td colspan=4 style=""></td>
	</tr>
	<tr>
		<td colspan=4 style=""></td>
	</tr>
	<tr>
		<td colspan=4><?=$data[0]['kepala_sekolah']?></td>
	</tr>
</table>