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
			<td colspan="<?=($jenispenerima=='SK')?6:7?>" style="border:0px !important;border-bottom:1px solid #000;">
				<table style="width:98%;border:0px !important;">
					<tr>
						<td align="center" style="font-size:20px;width:100%;border:0px;">DAFTAR PENERIMA BANTUAN</td>
					</tr>
					<tr>
						<td align="center" style="font-size:20px;border:0px;"><?=strtoupper($bantuan->nama)?></td>
					</tr>
					<tr>
						<td align="center" style="font-size:20px;border:0px;">TAHUN <?=strtoupper($bantuan->tahun)?></td>
					</tr>
					<tr>
						<td style="border:0px;"><hr></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr style="font-weight:bold">
			<?php if($jenispenerima=='SK'){?>
				<td align="center" style="width:5%;">NO.</td>
				<td align="center" style="width:15%;">PROVINSI</td>
				<td align="center" style="width:20%;">KABUPATEN/KOTA</td>
				<td align="center" style="width:22%;">NAMA</td>
				<td align="center" style="width:8%;">JUMLAH PAKET</td>
				<td align="center" style="width:12%;">JUMLAH BANTUAN</td>
			<?php }else if($jenispenerima=='SS'){?>
				<td align="center" style="width:5%;">NO.</td>
				<td align="center" style="width:15%;">PROVINSI</td>
				<td align="center" style="width:15%;">KABUPATEN/KOTA</td>
				<td align="center" style="width:20%;">NAMA SEKOLAH</td>
				<td align="center" style="width:15%;">NAMA SISWA</td>
				<td align="center" style="width:8%;">JUMLAH PAKET</td>
				<td align="center" style="width:12%;">JUMLAH BANTUAN</td>
			<?php }?>
		</tr>
		</thead>
		<tbody>
		<?php
		$no=0;
		foreach($penerima as $b){
			if($jenispenerima=='SK'){
				echo '<tr>';
				echo '<td align="center">'.($no+1).'.</td>';
				echo '<td>'.$b['provinsi'].'</td>';
				echo '<td>'.$b['kab'].'</td>';
				echo '<td>'.$b['nama'].'</td>';
				echo '<td align="center">'.$b['jumlah_bantuan'].'</td>';
				echo '<td align="center">Rp. '.number_format($b['jumlah_dana'],2).'</td>';
				echo '</tr>';
			
			}else if($jenispenerima=='SS'){
				echo '<tr>';
				echo '<td align="center">'.($no+1).'.</td>';
				echo '<td>'.$b['provinsi'].'</td>';
				echo '<td>'.$b['kab'].'</td>';
				echo '<td>'.$b['nama'].'</td>';
				echo '<td>'.$b['nama_siswa'].'</td>';
				echo '<td align="center">1</td>';
				echo '<td align="center">Rp. '.number_format($b['jumlah_dana'],2).'</td>';
				echo '</tr>';
			}
			$no+=1;
		}
		?>
		</tbody>
	</table>
</div>
</div>