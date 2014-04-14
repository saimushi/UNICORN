<h2>Listing Tinfos</h2>
<br>
<?php if ($tinfos): ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Startdate</th>
			<th>Enddate</th>
			<th>Msg</th>
			<th>Chara cnt</th>
			<th>Assets</th>
			<th>Created</th>
			<th>Modified</th>
			<th>Available</th>
			<th>Os</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($tinfos as $tinfo): ?>		<tr>

			<td><?php echo $tinfo->startdate; ?></td>
			<td><?php echo $tinfo->enddate; ?></td>
			<td><?php echo $tinfo->msg; ?></td>
			<td><?php echo $tinfo->chara_cnt; ?></td>
			<td><?php echo $tinfo->assets; ?></td>
			<td><?php echo $tinfo->created; ?></td>
			<td><?php echo $tinfo->modified; ?></td>
			<td><?php echo $tinfo->available; ?></td>
			<td><?php echo $tinfo->os; ?></td>
			<td>
				<?php echo Html::anchor('admin/tinfos/view/'.$tinfo->id, 'View'); ?> |
				<?php echo Html::anchor('admin/tinfos/edit/'.$tinfo->id, 'Edit'); ?> |
				<?php echo Html::anchor('admin/tinfos/delete/'.$tinfo->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>

			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php else: ?>
<p>No Tinfos.</p>

<?php endif; ?><p>
	<?php echo Html::anchor('admin/tinfos/create', 'Add new Tinfo', array('class' => 'btn btn-success')); ?>

</p>
