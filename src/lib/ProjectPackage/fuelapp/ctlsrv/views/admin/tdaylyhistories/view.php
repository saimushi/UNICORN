<h2>Viewing #<?php echo $tdaylyhistory->id; ?></h2>

<p>
	<strong>User id:</strong>
	<?php echo $tdaylyhistory->user_id; ?></p>
<p>
	<strong>Day:</strong>
	<?php echo $tdaylyhistory->day; ?></p>
<p>
	<strong>Charaed:</strong>
	<?php echo $tdaylyhistory->charaed; ?></p>
<p>
	<strong>Created:</strong>
	<?php echo $tdaylyhistory->created; ?></p>
<p>
	<strong>Modified:</strong>
	<?php echo $tdaylyhistory->modified; ?></p>
<p>
	<strong>Available:</strong>
	<?php echo $tdaylyhistory->available; ?></p>

<?php echo Html::anchor('admin/tdaylyhistories/edit/'.$tdaylyhistory->id, 'Edit'); ?> |
<?php echo Html::anchor('admin/tdaylyhistories', 'Back'); ?>