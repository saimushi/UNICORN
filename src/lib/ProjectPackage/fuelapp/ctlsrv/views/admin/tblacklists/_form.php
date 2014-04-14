<?php echo Form::open(); ?>

	<fieldset>
		<div class="clearfix">
			<?php echo Form::label('Device', 'device'); ?>

			<div class="input">
				<?php echo Form::input('device', Input::post('device', isset($tblacklist) ? $tblacklist->device : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Type', 'type'); ?>

			<div class="input">
				<?php echo Form::input('type', Input::post('type', isset($tblacklist) ? $tblacklist->type : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Created', 'created'); ?>

			<div class="input">
				<?php echo Form::input('created', Input::post('created', isset($tblacklist) ? $tblacklist->created : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Modified', 'modified'); ?>

			<div class="input">
				<?php echo Form::input('modified', Input::post('modified', isset($tblacklist) ? $tblacklist->modified : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Available', 'available'); ?>

			<div class="input">
				<?php echo Form::input('available', Input::post('available', isset($tblacklist) ? $tblacklist->available : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="actions">
			<?php echo Form::submit('submit', 'Save', array('class' => 'btn btn-primary')); ?>

		</div>
	</fieldset>
<?php echo Form::close(); ?>