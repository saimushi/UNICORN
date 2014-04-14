<h2>Editing Thistory</h2>
<br>

<?php echo render('admin/thistories/_form'); ?>
<p>
	<?php echo Html::anchor('admin/thistories/view/'.$thistory->id, 'View'); ?> |
	<?php echo Html::anchor('admin/thistories', 'Back'); ?></p>
