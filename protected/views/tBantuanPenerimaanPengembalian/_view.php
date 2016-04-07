<style>
.pemerimaan_pengembalian{
	margin:5px;
	border-spacing:0;
	border-collapse:collapse;
}
.pemerimaan_pengembalian td{
	padding:5px;
	border-bottom:1px solid #ccc;
}
.perhitungan_debet_kredit{
	margin:5px;
}
.perhitungan_debet_kredit td{
	padding:5px;
	border:1px solid #cccccc;
}
</style>
<div style="border-right:1px solid #000;float:left;width:60%;height:600px;">
<?php
$kredit=array();
$tgl_transaksi_akhir='';
foreach($model as $i=>$d){
	/*cari penggunaan dana sebelum tanggal transaksi penerimaan/pengembalian */
	$data=TBantuanPenggunaanDana::model()->findAll(array(
		'condition'=>'deleted=0 and t_bantuan_penerima_id=:id and tanggal_transaksi <=:tgl',
		'params'=>array(':id'=>$tbpid,':tgl'=>$d->tanggal_diterima_dikembalikan),
		'order'=>'tanggal_transaksi asc'
	));
	$total=0;
	$start_date='';
	$end_date='';
	foreach($data as $idx=>$k){
		if($idx==0)
			$start_date=$k->tanggal_transaksi;
		if($idx==(count($data)-1))
			$end_date=$k->tanggal_transaksi;
		
		$total+=$k->harga_total;
	}
	$data_start_date=explode('-',$start_date);
	$data_end_date=explode('-',$end_date);
	$start_date=$data_start_date[2].' '.Yii::app()->params['bulan'][(int)$data_start_date[1]].' '.$data_start_date[0];
	$end_date=$data_end_date[2].' '.Yii::app()->params['bulan'][(int)$data_end_date[1]].' '.$data_end_date[0];
	$kredit[]=array(
		'tgl'=>($start_date!=$end_date)?$start_date.'</br>s/d</br>'.$end_date:$start_date,
		'total'=>$total,
		'status'=>'K'
	);
	$data_tgl=explode('-',$d->tanggal_diterima_dikembalikan);
	$kredit[]=array(
		'tgl'=>$data_tgl[2].' '.Yii::app()->params['bulan'][(int)$data_tgl[1]].' '.$data_tgl[0],
		'total'=>$d->jumlah_bantuan,
		'status'=>($d->status)?'D':'K'
	);
?>
<table style="width:95%;" class="pemerimaan_pengembalian">
	<tr>
		<td colspan=4 align="left" style="font-weight:bold;font-size:14;background-color:#dddddd;"><?=($d->status)?'PENERIMAAN':'PENGEMBALIAN'?></td>
	</tr>
	<tr>
		<td style="width:10%;border-bottom:1px solid #ccc;" rowspan="4">
			<img src="TBantuanPenerimaanPengembalian/GetBuktiPenerimaanPengembalian/?fn=<?=$d->bukti_diterima_dikembalikan?>&tbpid=<?=$tbpid?>" style="width:200px" />
		</td>
		<td style="width:10%;"><b>Tanggal</b></td>
		<td style="width:1%;">:</td>
		<td style="width:59%;"><?=$d->tanggal_diterima_dikembalikan?></td>
		
	</tr>
	<tr>
		<td><b>Status</b></td>
		<td>:</td>
		<td><?=($d->status)?'Diterima':'Dikembalikan'?></td>
	</tr>
	<tr>
		<td><b>Jumlah</b></td>
		<td>:</td>
		<td>Rp.<?=number_format($d->jumlah_bantuan,2)?>,-</td>
	</tr>
	<tr>
		<td colspan=4><button style="color:red" type="button" onclick="Ext.getCmp('indexpenerimaanpengembalianid').actionHapus(<?=$d->id?>)">Hapus!</button></td>
	</tr>
</table>
<?php
	if($i==(count($model)-1))
		$tgl_transaksi_akhir=$d->tanggal_diterima_dikembalikan;
}
?>
</div>
<?php
	/*cek transaksi penggunaan data >= tanggal akhir penerimaan/pengembalian*/
	$data=TBantuanPenggunaanDana::model()->findAll(array(
		'condition'=>'deleted=0 and t_bantuan_penerima_id=:id and tanggal_transaksi >:tgl',
		'params'=>array(':id'=>$tbpid,':tgl'=>$tgl_transaksi_akhir),
		'order'=>'tanggal_transaksi asc'
	));
	$total=0;
	$start_date='';
	$end_date='';
	if(count($data)>0){
		foreach($data as $idx=>$k){
			if($idx==0)
				$start_date=$k->tanggal_transaksi;
			if($idx==(count($data)-1))
				$end_date=$k->tanggal_transaksi;
			
			$total+=$k->harga_total;
		}
		$data_start_date=explode('-',$start_date);
		$data_end_date=explode('-',$end_date);
		$start_date=$data_start_date[2].' '.Yii::app()->params['bulan'][(int)$data_start_date[1]].' '.$data_start_date[0];
		$end_date=$data_end_date[2].' '.Yii::app()->params['bulan'][(int)$data_end_date[1]].' '.$data_end_date[0];
		$kredit[]=array(
			'tgl'=>($start_date!=$end_date)?$start_date.'</br>s/d</br>'.$end_date:$start_date,
			'total'=>$total,
			'status'=>'K'
		);
	}
?>
<div style="float:left;width:40%;">
<table style="width:100%;" class="perhitungan_debet_kredit">
	<tr style="background-color:#dddddd">
		<td align="center">Tanggal</td>
		<td align="center">Debet</td>
		<td align="center">Kredit</td>
		<td align="center">Saldo</td>
	</tr>
	<?php
		$saldo=0;
		for($j=0;$j<count($kredit);$j++){
			if($kredit[$j]['status']=='D'){
				$saldo+=$kredit[$j]['total'];
			}else{
				$saldo-=$kredit[$j]['total'];
			}
			echo '
			<tr>
				<td align="center">'.$kredit[$j]['tgl'].'</td>
				<td align="right">'.(($kredit[$j]['status']=='D')?number_format($kredit[$j]['total'],2):'').'</td>
				<td align="right">'.(($kredit[$j]['status']=='K')?number_format($kredit[$j]['total'],2):'').'</td>
				<td align="right"></td>
			</tr>
			';
		}
		$today=date('Y-m-d');
		$today=explode('-',$today);
		$today=$today[2].' '.Yii::app()->params['bulan'][(int)$today[1]].' '.$today[0];
	?>
	<tr style="background-color:#dddddd">
		<td align="right" colspan=3>Saldo Akhir per <?=$today?></td>
		<td align="right" style="color:<?=($saldo<0)?'red':'black'?>"><?=number_format($saldo)?></td>
	</tr>
</table>
</div>
<div style="clear:both"></div>

