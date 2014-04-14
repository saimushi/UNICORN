<h2>Viewing #<?php echo $tuser->id; ?></h2>

<p>
	<strong>Identifier:</strong>
	<?php echo $tuser->identifier; ?></p>
<p>
	<strong>Docomo id:</strong>
	<?php echo $tuser->docomo_id; ?></p>
<p>
	<strong>Kids id:</strong>
	<?php echo $tuser->kids_id; ?></p>
<p>
	<strong>Drop:</strong>
	<?php echo $tuser->drop; ?></p>
<p>
	<strong>Drop sum:</strong>
	<?php echo $tuser->drop_sum; ?></p>
<p>
	<strong>Projection level:</strong>
	<?php echo $tuser->projection_level; ?></p>
<p>
	<strong>Fourplace level:</strong>
	<?php echo $tuser->fourplace_level; ?></p>
<p>
	<strong>Maze level:</strong>
	<?php echo $tuser->maze_level; ?></p>
<p>
	<strong>Param1:</strong>
	<?php echo $tuser->param1; ?></p>
<p>
	<strong>Param2:</strong>
	<?php echo $tuser->param2; ?></p>
<p>
	<strong>Param3:</strong>
	<?php echo $tuser->param3; ?></p>
<p>
	<strong>Param4:</strong>
	<?php echo $tuser->param4; ?></p>
<p>
	<strong>Param5:</strong>
	<?php echo $tuser->param5; ?></p>
<p>
	<strong>Param6:</strong>
	<?php echo $tuser->param6; ?></p>
<p>
	<strong>Param7:</strong>
	<?php echo $tuser->param7; ?></p>
<p>
	<strong>Param8:</strong>
	<?php echo $tuser->param8; ?></p>
<p>
	<strong>Tutorial1:</strong>
	<?php echo $tuser->tutorial1; ?></p>
<p>
	<strong>Tutorial2:</strong>
	<?php echo $tuser->tutorial2; ?></p>
<p>
	<strong>Tutorial3:</strong>
	<?php echo $tuser->tutorial3; ?></p>
<p>
	<strong>Created:</strong>
	<?php echo $tuser->created; ?></p>
<p>
	<strong>Modified:</strong>
	<?php echo $tuser->modified; ?></p>
<p>
	<strong>Available:</strong>
	<?php echo $tuser->available; ?></p>

<?php echo Html::anchor('admin/tusers/edit/'.$tuser->id, 'Edit'); ?> |
<?php echo Html::anchor('admin/tusers', 'Back'); ?>