<?php echo Form::open(); ?>

	<fieldset>
		<div class="clearfix">
			<?php echo Form::label('Identifier', 'identifier'); ?>

			<div class="input">
				<?php echo Form::input('identifier', Input::post('identifier', isset($tuser) ? $tuser->identifier : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Docomo id', 'docomo_id'); ?>

			<div class="input">
				<?php echo Form::input('docomo_id', Input::post('docomo_id', isset($tuser) ? $tuser->docomo_id : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Kids id', 'kids_id'); ?>

			<div class="input">
				<?php echo Form::input('kids_id', Input::post('kids_id', isset($tuser) ? $tuser->kids_id : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Drop', 'drop'); ?>

			<div class="input">
				<?php echo Form::input('drop', Input::post('drop', isset($tuser) ? $tuser->drop : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Drop sum', 'drop_sum'); ?>

			<div class="input">
				<?php echo Form::input('drop_sum', Input::post('drop_sum', isset($tuser) ? $tuser->drop_sum : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Projection level', 'projection_level'); ?>

			<div class="input">
				<?php echo Form::input('projection_level', Input::post('projection_level', isset($tuser) ? $tuser->projection_level : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Fourplace level', 'fourplace_level'); ?>

			<div class="input">
				<?php echo Form::input('fourplace_level', Input::post('fourplace_level', isset($tuser) ? $tuser->fourplace_level : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Maze level', 'maze_level'); ?>

			<div class="input">
				<?php echo Form::input('maze_level', Input::post('maze_level', isset($tuser) ? $tuser->maze_level : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Param1', 'param1'); ?>

			<div class="input">
				<?php echo Form::input('param1', Input::post('param1', isset($tuser) ? $tuser->param1 : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Param2', 'param2'); ?>

			<div class="input">
				<?php echo Form::input('param2', Input::post('param2', isset($tuser) ? $tuser->param2 : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Param3', 'param3'); ?>

			<div class="input">
				<?php echo Form::input('param3', Input::post('param3', isset($tuser) ? $tuser->param3 : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Param4', 'param4'); ?>

			<div class="input">
				<?php echo Form::input('param4', Input::post('param4', isset($tuser) ? $tuser->param4 : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Param5', 'param5'); ?>

			<div class="input">
				<?php echo Form::input('param5', Input::post('param5', isset($tuser) ? $tuser->param5 : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Param6', 'param6'); ?>

			<div class="input">
				<?php echo Form::input('param6', Input::post('param6', isset($tuser) ? $tuser->param6 : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Param7', 'param7'); ?>

			<div class="input">
				<?php echo Form::input('param7', Input::post('param7', isset($tuser) ? $tuser->param7 : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Param8', 'param8'); ?>

			<div class="input">
				<?php echo Form::input('param8', Input::post('param8', isset($tuser) ? $tuser->param8 : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Tutorial1', 'tutorial1'); ?>

			<div class="input">
				<?php echo Form::input('tutorial1', Input::post('tutorial1', isset($tuser) ? $tuser->tutorial1 : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Tutorial2', 'tutorial2'); ?>

			<div class="input">
				<?php echo Form::input('tutorial2', Input::post('tutorial2', isset($tuser) ? $tuser->tutorial2 : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Tutorial3', 'tutorial3'); ?>

			<div class="input">
				<?php echo Form::input('tutorial3', Input::post('tutorial3', isset($tuser) ? $tuser->tutorial3 : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Created', 'created'); ?>

			<div class="input">
				<?php echo Form::input('created', Input::post('created', isset($tuser) ? $tuser->created : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Modified', 'modified'); ?>

			<div class="input">
				<?php echo Form::input('modified', Input::post('modified', isset($tuser) ? $tuser->modified : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Available', 'available'); ?>

			<div class="input">
				<?php echo Form::input('available', Input::post('available', isset($tuser) ? $tuser->available : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="actions">
			<?php echo Form::submit('submit', 'Save', array('class' => 'btn btn-primary')); ?>

		</div>
	</fieldset>
<?php echo Form::close(); ?>