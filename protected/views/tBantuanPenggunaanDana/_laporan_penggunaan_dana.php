<?php

?>
<head>
<style>

.bos_laporan_penggunaan_dana{
	width:95%;
	height:90%;
	margin-bottom:50px;
	border-spacing:0;
	 border-collapse:collapse;
}
.judul{
	width:95%;
	height:20%;
	border:none;
	margin-bottom:50px;
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
.bos_laporan_penggunaan_dana thead{
	font-weight:bold;
	border:0;
}
.bos_laporan_penggunaan_dana tbody{
	
}
.bos_laporan_penggunaan_dana td.garis{
	border-bottom:1px solid #000000;
}
.bos_laporan_penggunaan_dana td{
	padding:5px;
}
.rencana_kegiatan{
	width:95%;
}
.rencana_kegiatan thead{
	text-align:center;
}
.rencana_kegiatan td{
	border:1px solid #000000;
	padding:5px;
}
.main_laporan_awal{
	margin:10px;

    
}

</style>
</head>
<body>
<div class="main_laporan_awal">

<table class="judul">
	<tr>
		<td style="width:80%;">&nbsp;</td>
		<td class="header" align="center">Formulir BOS-04</td>
	</tr>
	<tr>
		<td class="header-title" colspan=2 align="center">LAPORAN PENGGUNAAN DANA BOS PERIODE</td>
	</tr>
	<tr>
		<td class="header-title" colspan=2 align="center"><?=$cutoff->keterangan_periode?></td>
	</tr>

</table>
<table class="bos_laporan_penggunaan_dana">
		<tr>
			<td colspan="4"><b>A. Pengeluaran</b></td>
		</tr>
		<tr>
			<td style="width:95%" colspan="4" align="left">
				<table class="rencana_kegiatan">
					<tr>
						<td align="center" style="width:2%;" style="border-left:1px solid #000;">No.</td>
						<td align="center" style="width:50%;">Jenis Pengeluaran</td>
						<td align="center" style="width:15%;">Tanggal/Bulan</td>
						<td align="center"style="width:32%;">Jumlah (Rp)</td>
					</tr>
					<?php
						$i=0;
						foreach($dana as $d){
							if($d->status_pembelian_pengeluaran=='1'){
								$i++;
					?>
					<tr>
						<td align="center"><?=$i?></td>
						<td><?=$d->uraian?></td>
						<td align="center"><?=$d->tanggal_transaksi?></td>
						<td align="center"><?=number_format($d->harga_total,2)?></td>
					</tr>
					<?php
							}
						}
					?>

				</table>
			</td>
		</tr>
		<tr>
			<td colspan="4"><b>B. Pembelian Barang/Jasa</b></td>
		</tr>
		<tr>
			<td colspan="4">
				<table class="rencana_kegiatan">
					<tr>
						<td align="center" style="width:2%;" style="border-left:1px solid #000;">No.</td>
						<td align="center" style="width:50%;">Barang/Jasa yang dibeli</td>
						<td align="center" style="width:15%;">Tanggal/Bulan</td>
						<td align="center" style="width:17%;">Nama Toko/<br>Penyedia Jasa</td>
						<td align="center" style="width:15%;">Jumlah (Rp)</td>
					</tr>
					<?php
						$i=0;
						foreach($dana as $d){
							if($d->status_pembelian_pengeluaran!='1'){
								$i++;
					?>
					<tr>
						<td align="center"><?=$i?></td>
						<td><?=$d->uraian?></td>
						<td align="center"><?=$d->tanggal_transaksi?></td>
						<td><?=$d->toko_pembelian?></td>
						<td align="center"><?=number_format($d->harga_total,2)?></td>
					</tr>
					<?php
							}
						}
					?>

				</table>
			</td>
		</tr>
</table>
<table class="judul">
	<tr>
		<td align="center" style="width:25%;">Ketua Komite Sekolah</td>
		<td style="width:10%;">&nbsp;</td>
		<td align="center" style="width:25%;">Kepala Sekolah</td>
		<td style="width:10%;">&nbsp;</td>
		<td align="center" style="width:25%;">Bendahara</td>
	</tr>
	<tr><td colspan="5">&nbsp;</td></tr>
	<tr><td colspan="5">&nbsp;</td></tr>
	<tr><td colspan="5">&nbsp;</td></tr>
	<tr><td colspan="5">&nbsp;</td></tr>
	<tr>
		<td align="center">(..............................)</td>
		<td></td>
		<td align="center">(..............................)</td>
		<td></td>
		<td align="center">(..............................)</td>
	</tr>
</table>

</div>
</body>