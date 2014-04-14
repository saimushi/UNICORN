<h2>Editing Tblacklist</h2>
<br>

<?php echo render('admin/tblacklists/_form'); ?>
<p>
	<?php echo Html::anchor('admin/tblacklists/view/'.$tblacklist->id, 'View'); ?> |
	<?php echo Html::anchor('admin/tblacklists', 'Back'); ?></p>
