<?php echo Form::open(); ?>

	<fieldset>
		<div class="clearfix">
			<?php echo Form::label('User id', 'user_id'); ?>

			<div class="input">
				<?php echo Form::input('user_id', Input::post('user_id', isset($thistory) ? $thistory->user_id : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Puzzle id', 'puzzle_id'); ?>

			<div class="input">
				<?php echo Form::input('puzzle_id', Input::post('puzzle_id', isset($thistory) ? $thistory->puzzle_id : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Game mode', 'game_mode'); ?>

			<div class="input">
				<?php echo Form::input('game_mode', Input::post('game_mode', isset($thistory) ? $thistory->game_mode : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Charaed', 'charaed'); ?>

			<div class="input">
				<?php echo Form::input('charaed', Input::post('charaed', isset($thistory) ? $thistory->charaed : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Time', 'time'); ?>

			<div class="input">
				<?php echo Form::input('time', Input::post('time', isset($thistory) ? $thistory->time : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Cleared', 'cleared'); ?>

			<div class="input">
				<?php echo Form::input('cleared', Input::post('cleared', isset($thistory) ? $thistory->cleared : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Location', 'location'); ?>

			<div class="input">
				<?php echo Form::input('location', Input::post('location', isset($thistory) ? $thistory->location : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Created', 'created'); ?>

			<div class="input">
				<?php echo Form::input('created', Input::post('created', isset($thistory) ? $thistory->created : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Modified', 'modified'); ?>

			<div class="input">
				<?php echo Form::input('modified', Input::post('modified', isset($thistory) ? $thistory->modified : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Available', 'available'); ?>

			<div class="input">
				<?php echo Form::input('available', Input::post('available', isset($thistory) ? $thistory->available : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="actions">
			<?php echo Form::submit('submit', 'Save', array('class' => 'btn btn-primary')); ?>

		</div>
	</fieldset>
<?php echo Form::close(); ?>