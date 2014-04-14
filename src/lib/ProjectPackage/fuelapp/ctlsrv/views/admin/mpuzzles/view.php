<h2>Viewing #<?php echo $mpuzzle->id; ?></h2>

<p>
	<strong>Level:</strong>
	<?php echo $mpuzzle->level; ?></p>
<p>
	<strong>Type:</strong>
	<?php echo $mpuzzle->type; ?></p>
<p>
	<strong>Licensed:</strong>
	<?php echo $mpuzzle->licensed; ?></p>
<p>
	<strong>Drop:</strong>
	<?php echo $mpuzzle->drop; ?></p>
<p>
	<strong>Mission time:</strong>
	<?php echo $mpuzzle->mission_time; ?></p>
<p>
	<strong>Mission msg:</strong>
	<?php echo $mpuzzle->mission_msg; ?></p>
<p>
	<strong>Data:</strong>
	<?php echo $mpuzzle->data; ?></p>
<p>
	<strong>Param1:</strong>
	<?php echo $mpuzzle->param1; ?></p>
<p>
	<strong>Param2:</strong>
	<?php echo $mpuzzle->param2; ?></p>
<p>
	<strong>Param3:</strong>
	<?php echo $mpuzzle->param3; ?></p>
<p>
	<strong>Param4:</strong>
	<?php echo $mpuzzle->param4; ?></p>
<p>
	<strong>Param5:</strong>
	<?php echo $mpuzzle->param5; ?></p>
<p>
	<strong>Param6:</strong>
	<?php echo $mpuzzle->param6; ?></p>
<p>
	<strong>Param7:</strong>
	<?php echo $mpuzzle->param7; ?></p>
<p>
	<strong>Param8:</strong>
	<?php echo $mpuzzle->param8; ?></p>
<p>
	<strong>Created:</strong>
	<?php echo $mpuzzle->created; ?></p>
<p>
	<strong>Modified:</strong>
	<?php echo $mpuzzle->modified; ?></p>
<p>
	<strong>Available:</strong>
	<?php echo $mpuzzle->available; ?></p>
<p>
	<strong>Scale:</strong>
	<?php echo $mpuzzle->scale; ?></p>

<?php echo Html::anchor('admin/mpuzzles/edit/'.$mpuzzle->id, 'Edit'); ?> |
<?php echo Html::anchor('admin/mpuzzles', 'Back'); ?>