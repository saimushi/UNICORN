<?php echo Form::open(); ?>

	<fieldset>
		<div class="clearfix">
			<?php echo Form::label('User id', 'user_id'); ?>

			<div class="input">
				<?php echo Form::input('user_id', Input::post('user_id', isset($tdevice) ? $tdevice->user_id : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Uiid', 'uiid'); ?>

			<div class="input">
				<?php echo Form::input('uiid', Input::post('uiid', isset($tdevice) ? $tdevice->uiid : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Os', 'os'); ?>

			<div class="input">
				<?php echo Form::input('os', Input::post('os', isset($tdevice) ? $tdevice->os : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Version', 'version'); ?>

			<div class="input">
				<?php echo Form::input('version', Input::post('version', isset($tdevice) ? $tdevice->version : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Created', 'created'); ?>

			<div class="input">
				<?php echo Form::input('created', Input::post('created', isset($tdevice) ? $tdevice->created : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Modified', 'modified'); ?>

			<div class="input">
				<?php echo Form::input('modified', Input::post('modified', isset($tdevice) ? $tdevice->modified : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Available', 'available'); ?>

			<div class="input">
				<?php echo Form::input('available', Input::post('available', isset($tdevice) ? $tdevice->available : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="actions">
			<?php echo Form::submit('submit', 'Save', array('class' => 'btn btn-primary')); ?>

		</div>
	</fieldset>
<?php echo Form::close(); ?>