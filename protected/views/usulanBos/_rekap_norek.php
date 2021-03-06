<?php

?>
<style>

.bos_rekap_norek{
	width:95%;
	height:90%;
	border:1px solid #000000;
}
.judul{
	width:95%;
	height:20%;
	border:none;
}
.judul td.header{
	border:1px solid #000000;
	width:35%;
}

.judul td.header-title{
	font-weight:bold;
}
.judul td{
	padding:2px;
}
.bos_rekap_norek thead{
	font-weight:bold;
	border:0;
}
.bos_rekap_norek tbody{
	
}
.bos_rekap_norek td.garis{
	border-bottom:1px solid #000000;
}
.bos_rekap_norek td{
	padding:5px;
}
.bos_rekap_norek tr:first{
	font-weight:bold;
}
.rencana_kegiatan{
	width:95%;
	margin-bottom: 10em;
}
.rencana_kegiatan thead{
	text-align:center;
}
.rencana_kegiatan td{
	border:1px solid #000000;
	padding:10px;
}
.main_laporan_awal{
	margin:10px;

    
}
@media print {
  div.page-break { 
    page-break-after: always;
  }
}
</style>

<div class="main_laporan_awal">
	<?php
		$kab=$data[0]['kabupaten'];
		$cetak_header=0;
		$no=0;
		for($i=0;$i<count($data);$i++){
			$no++;
			if($cetak_header==0){
				$cetak_header=1;
				$no=0;
	?>
<table class="judul" style="width:95%">
	<tr>
		<td style="width:70%" colspan=5>&nbsp;</td>
		<td class="header" align="center" colspan=2><b>FORMAT-BOS-02</b></td>
	</tr>
	<tr>
		<td colspan=5>&nbsp;</td>
		<td class="header" align="center" colspan=2>Dibuat oleh Tim Manajemen BOS Kab/Kota</td>
	</tr>
	<tr>
		<td colspan=5>&nbsp;</td>
		<td class="header" align="center" colspan=2>Dikirim ke TIM Manajemen BOS Provinsi</td>
	</tr>
	<tr>
		<td class="header-title" colspan=7>REKAPITULASI NAMA DAN NOMOR REKENING SEKOLAH PENERIMA DANA BOS</td>
	</tr>
	<tr>
		<td class="header-title" colspan=7>&nbsp;</td>
	</tr>
	<tr>
		<td class="header-title" colspan=7>&nbsp;</td>
	</tr>
</table>
<table class="judul" style="width:95%">
	<tr>
		<td style="width:10%">Kabupaten/Kota</td>
		<td style="width:1%">:</td>
		<td style="width:84%"><?=$data[$i]['kabupaten']?></td>
	</tr>
	<tr>
		<td>Provinsi</td>
		<td>:</td>
		<td><?=$data[$i]['propinsi']?></td>
	</tr>
	<tr><td colspan=3>&nbsp;</td></tr>
	<tr><td colspan=3>&nbsp;</td></tr>
</table>
<table class="rencana_kegiatan">
	<tr>
		<td style="width:2%" align="center">No.</td>
		<td style="width:10%" align="center">NSS</td>
		<td style="width:20%" align="center">Nama Sekolah</td>
		<td style="width:20%" align="center">Bank Cabang</td>
		<td style="width:20%" align="center">Nama Rekening<br>(Nama Lembaga tdk boleh Rekening Pribadi)</td>
		<td style="width:10%" align="center">Nomor Rekening</td>
		<td style="width:18%" align="center">Penandatangan<br>(2 orang)</td>
	</tr>
<?php
				}					
?>
	<tr>
		<td rowspan="2" align="center"><?=$no?></td>
		<td rowspan="2"><?=$data[$i]['nss']?></td>
		<td rowspan="2"><?=$data[$i]['nama']?></td>
		<td rowspan="2"><?=$data[$i]['nama_bank'].' '.$data[$i]['cabang_kcp_unit']?></td>
		<td rowspan="2"><?=$data[$i]['rekening_atas_nama']?></td>
		<td rowspan="2"><?=$data[$i]['no_rekening']?></td>
		<td style="border:1px solid #000;">1.</td>
	</tr>
	<tr>
		<td style="border:1px solid #000;">2.</td>
	</tr>
	<?php
				
				if(isset($data[($i+1)]['kabupaten'])){
					if($data[($i+1)]['kabupaten']!=$kab){
						
						echo '</table>';
						echo '<table>
									<tr><td></td></tr>
									<tr><td></td></tr>
									<tr><td></td></tr>
									<tr><td></td></tr>
							 </table>';
						$cetak_header=0;
						$kab=$data[($i+1)]['kabupaten'];
					}
				}else{
					echo '</table>';
					echo '<div class="page-break"></div>';
				}
				
	?>

<?php
			
			/*if(count($data)>=($i+1)){
				if($data[($i+1)]['kabupaten']!=$kab){
					$cetak_header=0;
				}
			}*/
			
		}
?>
</div>
