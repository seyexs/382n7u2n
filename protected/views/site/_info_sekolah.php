<style>
    .tabel{
        border:1px solid #333333;
        border-collapse:separate;
        width: 100%;
        margin-bottom:10px;
        font-size:11px;
    }
    .tabel th{
        /*background-color: #99BCE8;*/
        font-family: 'lucida grande',tahoma,verdana,arial,sans-serif;
        font-size: 11px;
        font-weight: bold;
        text-align: left;
        padding:5px;
        border-bottom: 1px solid #cccccc;

    }
    .tabel td{
        border-bottom: 1px solid #cccccc;
        border-right: 1px solid #cccccc;
        padding: 5px;
    }
    .tabel td a:hover{
        background-color:gray;
    }
    .tabel a{
        text-decoration: none;
    }
</style>
<?php
/* @var $this MSekolahController */
/* @var $data MSekolah */
if(!empty($data)){
?>
<table class="tabel">
    <tr>
        <th><?php echo CHtml::encode($data->getAttributeLabel('nama_sekolah')); ?></th>
        <th>:</th>
        <td><?php echo CHtml::encode($data->nama_sekolah); ?></td>
    </tr>
    <tr>
        <th><?php echo CHtml::encode($data->getAttributeLabel('npsn')); ?></th>
        <th>:</th>
        <td><?php echo CHtml::encode($data->npsn); ?></td>
    </tr>
    <tr>
        <th><?php echo CHtml::encode($data->getAttributeLabel('nss')); ?></th>
        <th>:</th>
        <td><?php echo CHtml::encode($data->nss); ?></td>
    </tr>
    <tr>
        <th><?php echo CHtml::encode($data->getAttributeLabel('no_sk_pendirian')); ?></th>
        <th>:</th>
        <td><?php echo CHtml::encode($data->no_sk_pendirian); ?></td>
    </tr>
    <tr>
        <th><?php echo CHtml::encode($data->getAttributeLabel('tanggal_sk')); ?></th>
        <th>:</th>
        <td><?php echo CHtml::encode($data->tanggal_sk); ?></td>
    </tr>
    <tr>
        <th><?php echo CHtml::encode($data->getAttributeLabel('alamat')); ?></th>
        <th>:</th>
        <td><?php echo CHtml::encode($data->alamat); ?></td>
    </tr>
    <tr>
        <th><?php echo CHtml::encode($data->getAttributeLabel('telepon')); ?></th>
        <th>:</th>
        <td><?php echo CHtml::encode($data->telepon); ?></td>
    </tr>
    <tr>
        <th><?php echo CHtml::encode($data->getAttributeLabel('website')); ?></th>
        <th>:</th>
        <td><?php echo CHtml::encode($data->website); ?></td>
    </tr>
    <tr>
        <th><?php echo CHtml::encode($data->getAttributeLabel('email')); ?></th>
        <th>:</th>
        <td><?php echo CHtml::encode($data->email); ?></td>
    </tr>
</table>
        
<?php } ?>