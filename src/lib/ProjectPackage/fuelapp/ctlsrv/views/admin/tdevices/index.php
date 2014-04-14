<h2>Listing Tdevices</h2>
<br>
<?php if ($tdevices): ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>User id</th>
			<th>Uiid</th>
			<th>Os</th>
			<th>Version</th>
			<th>Created</th>
			<th>Modified</th>
			<th>Available</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($tdevices as $tdevice): ?>		<tr>

			<td><?php echo $tdevice->user_id; ?></td>
			<td><?php echo $tdevice->uiid; ?></td>
			<td><?php echo $tdevice->os; ?></td>
			<td><?php echo $tdevice->version; ?></td>
			<td><?php echo $tdevice->created; ?></td>
			<td><?php echo $tdevice->modified; ?></td>
			<td><?php echo $tdevice->available; ?></td>
			<td>
				<?php echo Html::anchor('admin/tdevices/view/'.$tdevice->id, 'View'); ?> |
				<?php echo Html::anchor('admin/tdevices/edit/'.$tdevice->id, 'Edit'); ?> |
				<?php echo Html::anchor('admin/tdevices/delete/'.$tdevice->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>

			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php else: ?>
<p>No Tdevices.</p>

<?php endif; ?><p>
	<?php echo Html::anchor('admin/tdevices/create', 'Add new Tdevice', array('class' => 'btn btn-success')); ?>

</p>
