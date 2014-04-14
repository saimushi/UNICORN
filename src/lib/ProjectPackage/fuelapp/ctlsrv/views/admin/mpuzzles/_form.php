<?php echo Form::open(); ?>

	<fieldset>
		<div class="clearfix">
			<?php echo Form::label('Level', 'level'); ?>

			<div class="input">
				<?php echo Form::input('level', Input::post('level', isset($mpuzzle) ? $mpuzzle->level : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Type', 'type'); ?>

			<div class="input">
				<?php echo Form::input('type', Input::post('type', isset($mpuzzle) ? $mpuzzle->type : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Licensed', 'licensed'); ?>

			<div class="input">
				<?php echo Form::input('licensed', Input::post('licensed', isset($mpuzzle) ? $mpuzzle->licensed : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Drop', 'drop'); ?>

			<div class="input">
				<?php echo Form::input('drop', Input::post('drop', isset($mpuzzle) ? $mpuzzle->drop : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Mission time', 'mission_time'); ?>

			<div class="input">
				<?php echo Form::input('mission_time', Input::post('mission_time', isset($mpuzzle) ? $mpuzzle->mission_time : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Mission msg', 'mission_msg'); ?>

			<div class="input">
				<?php echo Form::textarea('mission_msg', Input::post('mission_msg', isset($mpuzzle) ? $mpuzzle->mission_msg : ''), array('class' => 'span8', 'rows' => 8)); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Data', 'data'); ?>

			<div class="input">
				<?php echo Form::input('data', Input::post('data', isset($mpuzzle) ? $mpuzzle->data : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Param1', 'param1'); ?>

			<div class="input">
				<?php echo Form::input('param1', Input::post('param1', isset($mpuzzle) ? $mpuzzle->param1 : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Param2', 'param2'); ?>

			<div class="input">
				<?php echo Form::input('param2', Input::post('param2', isset($mpuzzle) ? $mpuzzle->param2 : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Param3', 'param3'); ?>

			<div class="input">
				<?php echo Form::input('param3', Input::post('param3', isset($mpuzzle) ? $mpuzzle->param3 : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Param4', 'param4'); ?>

			<div class="input">
				<?php echo Form::input('param4', Input::post('param4', isset($mpuzzle) ? $mpuzzle->param4 : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Param5', 'param5'); ?>

			<div class="input">
				<?php echo Form::input('param5', Input::post('param5', isset($mpuzzle) ? $mpuzzle->param5 : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Param6', 'param6'); ?>

			<div class="input">
				<?php echo Form::input('param6', Input::post('param6', isset($mpuzzle) ? $mpuzzle->param6 : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Param7', 'param7'); ?>

			<div class="input">
				<?php echo Form::input('param7', Input::post('param7', isset($mpuzzle) ? $mpuzzle->param7 : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Param8', 'param8'); ?>

			<div class="input">
				<?php echo Form::input('param8', Input::post('param8', isset($mpuzzle) ? $mpuzzle->param8 : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Created', 'created'); ?>

			<div class="input">
				<?php echo Form::input('created', Input::post('created', isset($mpuzzle) ? $mpuzzle->created : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Modified', 'modified'); ?>

			<div class="input">
				<?php echo Form::input('modified', Input::post('modified', isset($mpuzzle) ? $mpuzzle->modified : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Available', 'available'); ?>

			<div class="input">
				<?php echo Form::input('available', Input::post('available', isset($mpuzzle) ? $mpuzzle->available : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Scale', 'scale'); ?>

			<div class="input">
				<?php echo Form::input('scale', Input::post('scale', isset($mpuzzle) ? $mpuzzle->scale : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="actions">
			<?php echo Form::submit('submit', 'Save', array('class' => 'btn btn-primary')); ?>

		</div>
	</fieldset>
<?php echo Form::close(); ?>