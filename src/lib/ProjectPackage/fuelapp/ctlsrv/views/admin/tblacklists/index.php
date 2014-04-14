<h2>Listing Tblacklists</h2>
<br>
<?php if ($tblacklists): ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Device</th>
			<th>Type</th>
			<th>Created</th>
			<th>Modified</th>
			<th>Available</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($tblacklists as $tblacklist): ?>		<tr>

			<td><?php echo $tblacklist->device; ?></td>
			<td><?php echo $tblacklist->type; ?></td>
			<td><?php echo $tblacklist->created; ?></td>
			<td><?php echo $tblacklist->modified; ?></td>
			<td><?php echo $tblacklist->available; ?></td>
			<td>
				<?php echo Html::anchor('admin/tblacklists/view/'.$tblacklist->id, 'View'); ?> |
				<?php echo Html::anchor('admin/tblacklists/edit/'.$tblacklist->id, 'Edit'); ?> |
				<?php echo Html::anchor('admin/tblacklists/delete/'.$tblacklist->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>

			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php else: ?>
<p>No Tblacklists.</p>

<?php endif; ?><p>
	<?php echo Html::anchor('admin/tblacklists/create', 'Add new Tblacklist', array('class' => 'btn btn-success')); ?>

</p>
