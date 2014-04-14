<h2>Listing Tsessions</h2>
<br>
<?php if ($tsessions): ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Token</th>
			<th>Data</th>
			<th>Created</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($tsessions as $tsession): ?>		<tr>

			<td><?php echo $tsession->token; ?></td>
			<td><?php echo $tsession->data; ?></td>
			<td><?php echo $tsession->created; ?></td>
			<td>
				<?php echo Html::anchor('admin/tsessions/view/'.$tsession->id, 'View'); ?> |
				<?php echo Html::anchor('admin/tsessions/edit/'.$tsession->id, 'Edit'); ?> |
				<?php echo Html::anchor('admin/tsessions/delete/'.$tsession->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>

			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php else: ?>
<p>No Tsessions.</p>

<?php endif; ?><p>
	<?php echo Html::anchor('admin/tsessions/create', 'Add new Tsession', array('class' => 'btn btn-success')); ?>

</p>
