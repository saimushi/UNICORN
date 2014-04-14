<h2>Editing Tuser</h2>
<br>

<?php echo render('admin/tusers/_form'); ?>
<p>
	<?php echo Html::anchor('admin/tusers/view/'.$tuser->id, 'View'); ?> |
	<?php echo Html::anchor('admin/tusers', 'Back'); ?></p>
