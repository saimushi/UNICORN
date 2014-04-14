<h2>Listing Mpuzzles</h2>
<br>
<?php if ($mpuzzles): ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Level</th>
			<th>Type</th>
			<th>Licensed</th>
			<th>Drop</th>
			<th>Mission time</th>
			<th>Mission msg</th>
			<th>Data</th>
			<th>Param1</th>
			<th>Param2</th>
			<th>Param3</th>
			<th>Param4</th>
			<th>Param5</th>
			<th>Param6</th>
			<th>Param7</th>
			<th>Param8</th>
			<th>Created</th>
			<th>Modified</th>
			<th>Available</th>
			<th>Scale</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($mpuzzles as $mpuzzle): ?>		<tr>

			<td><?php echo $mpuzzle->level; ?></td>
			<td><?php echo $mpuzzle->type; ?></td>
			<td><?php echo $mpuzzle->licensed; ?></td>
			<td><?php echo $mpuzzle->drop; ?></td>
			<td><?php echo $mpuzzle->mission_time; ?></td>
			<td><?php echo $mpuzzle->mission_msg; ?></td>
			<td><?php echo $mpuzzle->data; ?></td>
			<td><?php echo $mpuzzle->param1; ?></td>
			<td><?php echo $mpuzzle->param2; ?></td>
			<td><?php echo $mpuzzle->param3; ?></td>
			<td><?php echo $mpuzzle->param4; ?></td>
			<td><?php echo $mpuzzle->param5; ?></td>
			<td><?php echo $mpuzzle->param6; ?></td>
			<td><?php echo $mpuzzle->param7; ?></td>
			<td><?php echo $mpuzzle->param8; ?></td>
			<td><?php echo $mpuzzle->created; ?></td>
			<td><?php echo $mpuzzle->modified; ?></td>
			<td><?php echo $mpuzzle->available; ?></td>
			<td><?php echo $mpuzzle->scale; ?></td>
			<td>
				<?php echo Html::anchor('admin/mpuzzles/view/'.$mpuzzle->id, 'View'); ?> |
				<?php echo Html::anchor('admin/mpuzzles/edit/'.$mpuzzle->id, 'Edit'); ?> |
				<?php echo Html::anchor('admin/mpuzzles/delete/'.$mpuzzle->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>

			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php else: ?>
<p>No Mpuzzles.</p>

<?php endif; ?><p>
	<?php echo Html::anchor('admin/mpuzzles/create', 'Add new Mpuzzle', array('class' => 'btn btn-success')); ?>

</p>
