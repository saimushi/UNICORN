<h2>Editing Tdaylyhistory</h2>
<br>

<?php echo render('admin/tdaylyhistories/_form'); ?>
<p>
	<?php echo Html::anchor('admin/tdaylyhistories/view/'.$tdaylyhistory->id, 'View'); ?> |
	<?php echo Html::anchor('admin/tdaylyhistories', 'Back'); ?></p>
