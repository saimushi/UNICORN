<h2>Editing Tinfo</h2>
<br>

<?php echo render('admin/tinfos/_form'); ?>
<p>
	<?php echo Html::anchor('admin/tinfos/view/'.$tinfo->id, 'View'); ?> |
	<?php echo Html::anchor('admin/tinfos', 'Back'); ?></p>
