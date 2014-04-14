<?php echo Form::open(); ?>

	<fieldset>
		<div class="clearfix">
			<?php echo Form::label('Username', 'username'); ?>

			<div class="input">
				<?php echo Form::input('username', Input::post('username', isset($user) ? $user->username : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Password', 'password'); ?>

			<div class="input">
				<?php echo Form::input('password', Input::post('password', isset($user) ? $user->password : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Group', 'group'); ?>

			<div class="input">
				<?php echo Form::input('group', Input::post('group', isset($user) ? $user->group : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Email', 'email'); ?>

			<div class="input">
				<?php echo Form::input('email', Input::post('email', isset($user) ? $user->email : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Last login', 'last_login'); ?>

			<div class="input">
				<?php echo Form::input('last_login', Input::post('last_login', isset($user) ? $user->last_login : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Login hash', 'login_hash'); ?>

			<div class="input">
				<?php echo Form::input('login_hash', Input::post('login_hash', isset($user) ? $user->login_hash : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Profile fields', 'profile_fields'); ?>

			<div class="input">
				<?php echo Form::textarea('profile_fields', Input::post('profile_fields', isset($user) ? $user->profile_fields : ''), array('class' => 'span8', 'rows' => 8)); ?>

			</div>
		</div>
		<div class="actions">
			<?php echo Form::submit('submit', 'Save', array('class' => 'btn btn-primary')); ?>

		</div>
	</fieldset>
<?php echo Form::close(); ?>