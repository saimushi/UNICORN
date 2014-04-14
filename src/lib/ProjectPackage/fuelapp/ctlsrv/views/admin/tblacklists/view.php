<h2>Viewing #<?php echo $tblacklist->id; ?></h2>

<p>
	<strong>Device:</strong>
	<?php echo $tblacklist->device; ?></p>
<p>
	<strong>Type:</strong>
	<?php echo $tblacklist->type; ?></p>
<p>
	<strong>Created:</strong>
	<?php echo $tblacklist->created; ?></p>
<p>
	<strong>Modified:</strong>
	<?php echo $tblacklist->modified; ?></p>
<p>
	<strong>Available:</strong>
	<?php echo $tblacklist->available; ?></p>

<?php echo Html::anchor('admin/tblacklists/edit/'.$tblacklist->id, 'Edit'); ?> |
<?php echo Html::anchor('admin/tblacklists', 'Back'); ?>