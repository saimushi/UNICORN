<h2>Viewing #<?php echo $thistory->id; ?></h2>

<p>
	<strong>User id:</strong>
	<?php echo $thistory->user_id; ?></p>
<p>
	<strong>Puzzle id:</strong>
	<?php echo $thistory->puzzle_id; ?></p>
<p>
	<strong>Game mode:</strong>
	<?php echo $thistory->game_mode; ?></p>
<p>
	<strong>Charaed:</strong>
	<?php echo $thistory->charaed; ?></p>
<p>
	<strong>Time:</strong>
	<?php echo $thistory->time; ?></p>
<p>
	<strong>Cleared:</strong>
	<?php echo $thistory->cleared; ?></p>
<p>
	<strong>Location:</strong>
	<?php echo $thistory->location; ?></p>
<p>
	<strong>Created:</strong>
	<?php echo $thistory->created; ?></p>
<p>
	<strong>Modified:</strong>
	<?php echo $thistory->modified; ?></p>
<p>
	<strong>Available:</strong>
	<?php echo $thistory->available; ?></p>

<?php echo Html::anchor('admin/thistories/edit/'.$thistory->id, 'Edit'); ?> |
<?php echo Html::anchor('admin/thistories', 'Back'); ?>