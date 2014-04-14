<h2>Listing Tdaylyhistories</h2>
<br>
<?php if ($tdaylyhistories): ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>User id</th>
			<th>Day</th>
			<th>Charaed</th>
			<th>Created</th>
			<th>Modified</th>
			<th>Available</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($tdaylyhistories as $tdaylyhistory): ?>		<tr>

			<td><?php echo $tdaylyhistory->user_id; ?></td>
			<td><?php echo $tdaylyhistory->day; ?></td>
			<td><?php echo $tdaylyhistory->charaed; ?></td>
			<td><?php echo $tdaylyhistory->created; ?></td>
			<td><?php echo $tdaylyhistory->modified; ?></td>
			<td><?php echo $tdaylyhistory->available; ?></td>
			<td>
				<?php echo Html::anchor('admin/tdaylyhistories/view/'.$tdaylyhistory->id, 'View'); ?> |
				<?php echo Html::anchor('admin/tdaylyhistories/edit/'.$tdaylyhistory->id, 'Edit'); ?> |
				<?php echo Html::anchor('admin/tdaylyhistories/delete/'.$tdaylyhistory->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>

			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php else: ?>
<p>No Tdaylyhistories.</p>

<?php endif; ?><p>
	<?php echo Html::anchor('admin/tdaylyhistories/create', 'Add new Tdaylyhistory', array('class' => 'btn btn-success')); ?>

</p>
