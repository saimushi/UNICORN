<h2>Viewing #<?php echo $tsession->id; ?></h2>

<p>
	<strong>Token:</strong>
	<?php echo $tsession->token; ?></p>
<p>
	<strong>Data:</strong>
	<?php echo $tsession->data; ?></p>
<p>
	<strong>Created:</strong>
	<?php echo $tsession->created; ?></p>

<?php echo Html::anchor('admin/tsessions/edit/'.$tsession->id, 'Edit'); ?> |
<?php echo Html::anchor('admin/tsessions', 'Back'); ?>