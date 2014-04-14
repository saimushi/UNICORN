<h2>Listing Users</h2>
<br>
<?php if ($users): ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Username</th>
			<th>Password</th>
			<th>Group</th>
			<th>Email</th>
			<th>Last login</th>
			<th>Login hash</th>
			<th>Profile fields</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($users as $user): ?>		<tr>

			<td><?php echo $user->username; ?></td>
			<td><?php echo $user->password; ?></td>
			<td><?php echo $user->group; ?></td>
			<td><?php echo $user->email; ?></td>
			<td><?php echo $user->last_login; ?></td>
			<td><?php echo $user->login_hash; ?></td>
			<td><?php echo $user->profile_fields; ?></td>
			<td>
				<?php echo Html::anchor('admin/users/view/'.$user->id, 'View'); ?> |
				<?php echo Html::anchor('admin/users/edit/'.$user->id, 'Edit'); ?> |
				<?php echo Html::anchor('admin/users/delete/'.$user->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>

			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php else: ?>
<p>No Users.</p>

<?php endif; ?><p>
	<?php echo Html::anchor('admin/users/create', 'Add new User', array('class' => 'btn btn-success')); ?>

</p>
