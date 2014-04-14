<h2>Editing Tsession</h2>
<br>

<?php echo render('admin/tsessions/_form'); ?>
<p>
	<?php echo Html::anchor('admin/tsessions/view/'.$tsession->id, 'View'); ?> |
	<?php echo Html::anchor('admin/tsessions', 'Back'); ?></p>
