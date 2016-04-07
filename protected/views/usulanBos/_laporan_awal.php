<?php

?>
<style>

.bos_laporan_awal{
	width:95%;
	height:90%;
	border:1px solid #000000;
	border-spacing:0;
	 border-collapse:collapse;
}
.judul{
	width:95%;
	height:20%;
	border:none;
	border-spacing:0;
	 border-collapse:collapse;
}
.judul td.header{
	border:1px solid #000000;
	padding:6px;
	font-weight:bold;
	width:15%;
}
.judul td.header-title{
	font-size:18px;
	font-weight:bold;
}
.bos_laporan_awal thead{
	font-weight:bold;
	border:0;
}
.bos_laporan_awal tbody{
	
}
.bos_laporan_awal td.garis{
	border-bottom:1px solid #000000;
}
.bos_laporan_awal td{
	padding:5px;
}
.rencana_kegiatan thead{
	text-align:center;
}
.rencana_kegiatan td{
	border-right:1px solid #000000;
	border-bottom:1px solid #000000;
	border-top:1px solid #000000;
	
}
.main_laporan_awal{
	margin:10px;
	border-spacing:0;
	 border-collapse:collapse;
    
}

</style>

<div class="main_laporan_awal">

<table class="judul">
	<tr>
		<td style="width:80%;">&nbsp;</td>
		<td class="header" align="center">Laporan Awal</td>
	</tr>
	<tr>
		<td class="header-title" colspan=2 align="center">LAPORAN AWAL</td>
	</tr>
	<tr>
		<td class="header-title" colspan=2 align="center">BANTUAN OPERASIONAL SEKOLAH (BOS)</td>
	</tr>
	<tr>
		<td class="header-title" colspan=2 align="center">TAHUN <?=$model->tBantuanProgram->tahun?></td>
	</tr>
</table>
<table class="bos_laporan_awal">
	
		<tr>
			<td colspan="4"><b>IDENTITAS</b></td>
		</tr>
		<tr>
			<td style="width:3%;"></td>
			<td style="width:15%;">Nama Sekolah</td><td style="width:1%;">:</td><td style="width:76%;"><?=$model->sekolah->nama?></td>
		</tr>
		<tr>
			<td></td>
			<td>Kabupaten/Kota</td><td>:</td><td><?=$sekolah['kabupaten']?></td>
		</tr>
		<tr>
			<td></td>
			<td>Provinsi</td><td>:</td><td><?=$sekolah['propinsi']?></td>
		</tr>
		<tr>
			<td></td>
			<td>Nama Kepala Sekolah</td><td>:</td><td><?=$model->sekolah->nama?></td>
		</tr>
		<tr>
			<td></td>
			<td>No Telepon/HP</td><td>:</td><td><?=$model->sekolah->nama?></td>
		</tr>
		<tr>
			<td></td>
			<td>Jenis Bantuan</td><td>:</td><td>Bantuan Operasional BOS Tahun <?=$model->tBantuanProgram->tahun?></td>
		</tr>
		<tr>
			<td></td>
			<td>Tahun Anggaran</td><td>:</td><td><?=$model->tBantuanProgram->tahun?></td>
		</tr>
		<tr>
			<td></td>
			<td>Pemberi Bantuan</td><td>:</td><td>Direktorat Pembinaan SMK</td>
		</tr>
		<tr><td colspan="4" class="garis">&nbsp;</td></tr>
		<tr><td colspan="4"><b>PENERIMAAN BANTUAN DANA</b></td></tr>
		<tr>
			<td></td>
			<td>Status</td><td>:</td><td><input type="checkbox" disabled="disabled"/> Sudah diterima <input type="checkbox" disabled="disabled"/> Belum diterima</td>
		</tr>
		<tr>
			<td></td>
			<td>Tanggal Diterima</td><td>:</td><td>Tgl......................../Bln.........................../Tahun...............................</td>
		</tr>
		<tr>
			<td></td>
			<td>Besar Dana Diterima</td><td>:</td><td>Rp...........................................................</td>
		</tr>
		<tr><td colspan="4" class="garis">&nbsp;</td></tr>
		<tr><td colspan="4"><b>PERSIAPAN PELAKSANAAN</b></td></tr>
		<tr>
			<td></td>
			<td>Rapat Awal Tim</td><td>:</td><td><input type="checkbox" disabled="disabled"/> Sudah dilakukan <input type="checkbox" disabled="disabled"/> Belum dilakukan</td>
		</tr>
		<tr>
			<td></td>
			<td>Rencana Kerja</td><td>:</td><td><input type="checkbox" disabled="disabled"/> Sudah dibuat <input type="checkbox" disabled="disabled"/> Belum dibuat</td>
		</tr>
		<tr><td colspan="4" class="garis">&nbsp;</td></tr>
		<tr><td colspan="4"><b>RENCANA KEGIATAN YANG AKAN DILAKUKAN</b></td></tr>
		<tr>
			<td colspan="4">
				<table class="rencana_kegiatan" style="width:100%;">
					<tr>
						<td style="width:3%;" style="border-left:1px solid #000;">No.</td>
						<td style="width:37%;">Jenis Kegiatan</td>
						<td style="width:10%;">Volume</td>
						<td style="width:15%;">Satuan</td>
						<td style="width:15%;">Harga Satuan</td>
						<td style="width:20%;">Jumlah</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="4">
				<table class="judul">
					<tr>
						<td colspan=2 align="center"> </td>
					</tr>
					<tr>
						<td style="width:80%;">&nbsp;</td>
						<td style="width:15%;" align="right">........................,.............<?=$model->tBantuanProgram->tahun?></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td align="left">Kepala SMK,</td>
					</tr>
					<tr>
						<td colspan=2 align="center"> </td>
					</tr>
					<tr>
						<td colspan=2 align="center"> </td>
					</tr>
					<tr>
						<td colspan=2 align="center"> </td>
					</tr>
					<tr>
						<td colspan=2 align="center"> </td>
					</tr>
					<tr>
						<td colspan=2 align="center"> </td>
					</tr>
					<tr>
						<td colspan=2 align="center"> </td>
					</tr>
					<tr>
						<td colspan=2 align="center"> </td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td align="center">(<span style="text-align:center;width:100%;padding:0 60px 0 60px;border-bottom:1px dotted #000;"><?=$sekolah['kepala_sekolah']?></span>)</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td align="center">NIP. <?=($sekolah['nip_kepala_sekolah'])?$sekolah['nip_kepala_sekolah']:'....................................'?></td>
					</tr>
				
				</table>
			</td>
		</tr>
</table>

</div>
