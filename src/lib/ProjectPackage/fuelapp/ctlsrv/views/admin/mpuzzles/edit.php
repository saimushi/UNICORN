<h2>Editing Mpuzzle</h2>
<br>

<?php echo render('admin/mpuzzles/_form'); ?>
<p>
	<?php echo Html::anchor('admin/mpuzzles/view/'.$mpuzzle->id, 'View'); ?> |
	<?php echo Html::anchor('admin/mpuzzles', 'Back'); ?></p>
