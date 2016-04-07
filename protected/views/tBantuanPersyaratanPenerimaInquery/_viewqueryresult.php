<style>

.CSSTableGenerator {
	border:1px solid #000;
	border-spacing:0;
	 border-collapse:collapse;
}
.CSSTableGenerator td{
	border-bottom:1px solid #ccc;
}
</style>

<?php
if (!empty($model->data)) {
    $hData = $model->data[0];
    $numcolumn = count($hData);
	$number=1;
    ?>
    <table class="CSSTableGenerator" style="margin: 2px;">
        <tbody>
            <tr>
                <td colspan="<?php echo ($numcolumn+1); ?>" class="center-middle-noborder"><?php echo $title; ?></td>
            </tr>
            <tr>
                <td colspan="<?php echo ($numcolumn+1); ?>" class="center-middle-noborder">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="<?php echo ($numcolumn+1); ?>" class="novertical-border"></td>
            </tr>
            <tr class="header">
				<td></td>
                <?php foreach ($hData as $h => $v) { 
                        $label=ucwords(trim(strtolower(str_replace(array('-','_'),' ',preg_replace('/(?<![A-Z])[A-Z]/', ' \0', $h)))));
						$label=preg_replace('/\s+/',' ',$label);
						if(strcasecmp(substr($label,-3),' id')===0)
							$label=substr($label,0,-3);
						if($label==='Id')
							$label='ID';
                ?>
                    <td class="left"><?php echo $label; ?></td>
                <?php } ?>
            </tr>
            <?php foreach ($model->data as $data){ ?>
            <tr class="row">
				<td><?=$number?></td>
                <?php foreach ($hData as $h => $v) { ?>
                <td class="left"><?php echo isset($data[$h]) ? $data[$h] : ''; ?></td>
                <?php } ?>
            </tr>
            <?php $number++;} ?>
        </tbody>
    </table>
<?php 
 }
 else
    echo '<h1 style="text-align:center;padding:50px;color:red;">Hasil Query Tidak Ada</h1>';
 
?>
