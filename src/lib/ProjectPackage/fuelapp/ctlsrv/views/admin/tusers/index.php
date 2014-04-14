<h2>Listing Tusers</h2>
<br>
<?php if ($tusers): ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Identifier</th>
			<th>Docomo id</th>
			<th>Kids id</th>
			<th>Drop</th>
			<th>Drop sum</th>
			<th>Projection level</th>
			<th>Fourplace level</th>
			<th>Maze level</th>
			<th>Param1</th>
			<th>Param2</th>
			<th>Param3</th>
			<th>Param4</th>
			<th>Param5</th>
			<th>Param6</th>
			<th>Param7</th>
			<th>Param8</th>
			<th>Tutorial1</th>
			<th>Tutorial2</th>
			<th>Tutorial3</th>
			<th>Created</th>
			<th>Modified</th>
			<th>Available</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($tusers as $tuser): ?>		<tr>

			<td><?php echo $tuser->identifier; ?></td>
			<td><?php echo $tuser->docomo_id; ?></td>
			<td><?php echo $tuser->kids_id; ?></td>
			<td><?php echo $tuser->drop; ?></td>
			<td><?php echo $tuser->drop_sum; ?></td>
			<td><?php echo $tuser->projection_level; ?></td>
			<td><?php echo $tuser->fourplace_level; ?></td>
			<td><?php echo $tuser->maze_level; ?></td>
			<td><?php echo $tuser->param1; ?></td>
			<td><?php echo $tuser->param2; ?></td>
			<td><?php echo $tuser->param3; ?></td>
			<td><?php echo $tuser->param4; ?></td>
			<td><?php echo $tuser->param5; ?></td>
			<td><?php echo $tuser->param6; ?></td>
			<td><?php echo $tuser->param7; ?></td>
			<td><?php echo $tuser->param8; ?></td>
			<td><?php echo $tuser->tutorial1; ?></td>
			<td><?php echo $tuser->tutorial2; ?></td>
			<td><?php echo $tuser->tutorial3; ?></td>
			<td><?php echo $tuser->created; ?></td>
			<td><?php echo $tuser->modified; ?></td>
			<td><?php echo $tuser->available; ?></td>
			<td>
				<?php echo Html::anchor('admin/tusers/view/'.$tuser->id, 'View'); ?> |
				<?php echo Html::anchor('admin/tusers/edit/'.$tuser->id, 'Edit'); ?> |
				<?php echo Html::anchor('admin/tusers/delete/'.$tuser->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>

			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php else: ?>
<p>No Tusers.</p>

<?php endif; ?><p>
	<?php echo Html::anchor('admin/tusers/create', 'Add new Tuser', array('class' => 'btn btn-success')); ?>

</p>
