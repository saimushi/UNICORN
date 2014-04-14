<h2>Listing Thistories</h2>
<br>
<?php if ($thistories): ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>User id</th>
			<th>Puzzle id</th>
			<th>Game mode</th>
			<th>Charaed</th>
			<th>Time</th>
			<th>Cleared</th>
			<th>Location</th>
			<th>Created</th>
			<th>Modified</th>
			<th>Available</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($thistories as $thistory): ?>		<tr>

			<td><?php echo $thistory->user_id; ?></td>
			<td><?php echo $thistory->puzzle_id; ?></td>
			<td><?php echo $thistory->game_mode; ?></td>
			<td><?php echo $thistory->charaed; ?></td>
			<td><?php echo $thistory->time; ?></td>
			<td><?php echo $thistory->cleared; ?></td>
			<td><?php echo $thistory->location; ?></td>
			<td><?php echo $thistory->created; ?></td>
			<td><?php echo $thistory->modified; ?></td>
			<td><?php echo $thistory->available; ?></td>
			<td>
				<?php echo Html::anchor('admin/thistories/view/'.$thistory->id, 'View'); ?> |
				<?php echo Html::anchor('admin/thistories/edit/'.$thistory->id, 'Edit'); ?> |
				<?php echo Html::anchor('admin/thistories/delete/'.$thistory->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>

			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php else: ?>
<p>No Thistories.</p>

<?php endif; ?><p>
	<?php echo Html::anchor('admin/thistories/create', 'Add new Thistory', array('class' => 'btn btn-success')); ?>

</p>
