<?php echo Form::open(); ?>

	<fieldset>
		<div class="clearfix">
			<?php echo Form::label('Token', 'token'); ?>

			<div class="input">
				<?php echo Form::input('token', Input::post('token', isset($tsession) ? $tsession->token : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Data', 'data'); ?>

			<div class="input">
				<?php echo Form::input('data', Input::post('data', isset($tsession) ? $tsession->data : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Created', 'created'); ?>

			<div class="input">
				<?php echo Form::input('created', Input::post('created', isset($tsession) ? $tsession->created : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="actions">
			<?php echo Form::submit('submit', 'Save', array('class' => 'btn btn-primary')); ?>

		</div>
	</fieldset>
<?php echo Form::close(); ?>