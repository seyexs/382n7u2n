<?php
/**
 * This is the template for generating an action view file.
 * The following variables are available in this template:
 * - $this: the ControllerCode object
 * - $action: the action ID
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */

<?php
$label=ucwords(trim(strtolower(str_replace(array('-','_','.'),' ',preg_replace('/(?<![A-Z])[A-Z]/', ' \0', basename($this->getControllerID()))))));
if($action==='index')
{
	echo "\$this->breadcrumbs=array(
	'$label',
);";
}
else
{
	$action=ucfirst($action);
	echo "\$this->breadcrumbs=array(
	'$label'=>array('/{$this->uniqueControllerID}'),
	'$action',
);";
}
?>

?>

<?php echo "<?php"; ?> $this->beginWidget('ext.widgets.XPanel', array('title' => '<?php echo $label; ?>', 'width' => 900,'height' => 500)); ?>
<p>
	You may change the content of this page by modifying
	the file <tt><?php echo '<?php'; ?> echo __FILE__; ?></tt>.
</p>
<?php echo "<?php \$this->endWidget(); ?>"; ?>
