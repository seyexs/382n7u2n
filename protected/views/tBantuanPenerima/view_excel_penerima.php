<style>
 .main-table td{
	 padding:5px;
	 border:1px solid #000;
 }
 .main-table{
	 width:100%;
	 border-spacing:0;
	 border-collapse:collapse;
 }
</style>
<div style="padding:10px;height:480px;overflow:scroll;">
<div style="padding:10px;width:100%;">
	<table class="main-table">
		<thead>
		<tr>
			<td colspan="4" style="border:0px !important;border-bottom:1px solid #000;">
				<table style="width:98%;border:0px !important;">
					<tr>
						<td style="border:0px;"></td>
						<td style="border:0px;"></td>
						<td align="center" style="font-size:20px;width:100%;border:0px;">DAFTAR PENERIMA BANTUAN</td>
					</tr>
					<tr>
						<td style="border:0px;"></td>
						<td style="border:0px;"></td>
						<td align="center" style="font-size:20px;border:0px;"><?=strtoupper($bantuan->nama)?></td>
					</tr>
					<tr>
						<td style="border:0px;"></td>
						<td style="border:0px;"></td>
						<td align="center" style="font-size:18px;border:0px;">TAHUN <?=strtoupper($bantuan->tahun)?></td>
					</tr>
					<tr>
						<td style="border:0px;"></td>
						<td style="border:0px;"></td>
						<td style="border:0px;">*)Posisi urutan kolom tidak boleh berubah</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr style="font-weight:bold">
			<td align="center" style="width:5%;">NO.</td>
			<td align="center" style="width:5%;">KODE</td>
			<td align="center" style="width:10%;">PROVINSI</td>
			<td align="center" style="width:10%;">KABUPATEN</td>
			<td align="center" style="width:30%;">NAMA</td>
			<td align="center" style="width:20%;">JUMLAH PAKET</td>
			<td align="center" style="width:20%;">JUMLAH BANTUAN</td>
		</tr>
		</thead>
		<tbody>
		<?php
		foreach($penerima as $no=>$b){
			//for($i=1;$i<=5;$i++){
			echo '<tr>';
			echo '<td align="center">'.($no+1).'.</td>';
			echo '<td>'.$b['id'].'</td>';
			echo '<td>'.$b['provinsi'].'</td>';
			echo '<td>'.$b['kab'].'</td>';
			echo '<td>'.$b['nama'].'</td>';
			echo '<td align="center">'.$b['jumlah_bantuan'].'</td>';
			echo '<td align="center">'.$b['jumlah_dana'].'</td>';
			echo '</tr>';
			//}
		}
		?>
		</tbody>
	</table>
</div>
</div>