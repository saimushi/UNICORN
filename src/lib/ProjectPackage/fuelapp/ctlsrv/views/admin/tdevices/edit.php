<h2>Editing Tdevice</h2>
<br>

<?php echo render('admin/tdevices/_form'); ?>
<p>
	<?php echo Html::anchor('admin/tdevices/view/'.$tdevice->id, 'View'); ?> |
	<?php echo Html::anchor('admin/tdevices', 'Back'); ?></p>
