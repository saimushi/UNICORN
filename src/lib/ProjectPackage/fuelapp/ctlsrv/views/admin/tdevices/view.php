<h2>Viewing #<?php echo $tdevice->id; ?></h2>

<p>
	<strong>User id:</strong>
	<?php echo $tdevice->user_id; ?></p>
<p>
	<strong>Uiid:</strong>
	<?php echo $tdevice->uiid; ?></p>
<p>
	<strong>Os:</strong>
	<?php echo $tdevice->os; ?></p>
<p>
	<strong>Version:</strong>
	<?php echo $tdevice->version; ?></p>
<p>
	<strong>Created:</strong>
	<?php echo $tdevice->created; ?></p>
<p>
	<strong>Modified:</strong>
	<?php echo $tdevice->modified; ?></p>
<p>
	<strong>Available:</strong>
	<?php echo $tdevice->available; ?></p>

<?php echo Html::anchor('admin/tdevices/edit/'.$tdevice->id, 'Edit'); ?> |
<?php echo Html::anchor('admin/tdevices', 'Back'); ?>