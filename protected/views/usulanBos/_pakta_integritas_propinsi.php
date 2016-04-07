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
		<td colspan=4 align="center" style="font-size:14px;"><b>Rekapitulasi Data Penerima BOS</b></td>
	</tr>
	<tr>
		<td colspan=4></td>
	</tr>
	<tr>
		<td colspan=4></td>
	</tr>
	
	<tr>
		<td colspan=4>
			<table class="rangkuman_siswa">
				<tr>
					<td align="center" style="width:2%">No.</td>
					<td align="center" style="width:25%">Kabupaten/Kota</td>
					<?php 
						$kls=array('X','XI','XII','XIII');
						for($j=0;$j<count($kls);$j++){
					
					?>
						<td align="center" style="width:10%"><?=$kls[$j]?></td>
					<?php }?>
					<td align="center" style="width:10%">Total</td>
				</tr>
				<?php
					$no=0;
					$grand_total=0;
					for($i=0;$i<count($data);$i++){
						$data_tgl=explode('-',$data[$i]['tgl_cut_off']);
						$tgl=$data_tgl[2].' '.Yii::app()->params['bulan'][(int)$data_tgl[1]].' '.$data_tgl[0];
						$no++;
						$total=0;
				?>
				<tr>
					<td align="center"><?=$no?></td>
					<td><?=$data[$i]['kabupaten']?></td>
					<?php for($k=0;$k<count($kls);$k++){
							$total+=(int)$data[$i]['siswa_1'.$k];
					?>
					<td align="center"><?=$data[$i]['siswa_1'.$k]?></td>
					<?php }?>
					<td align="center"><?=$total?></td>
				</tr>
				<?php 
						$grand_total+=$total;
					}
				?>
				<tr>
					<td colspan="<?=(count($kls)+2)?>" align="center">Total : </td>
					<td align="center"><?=$grand_total?></td>
				</tr>
			</table>
		</td>
	</tr>
	
</table>