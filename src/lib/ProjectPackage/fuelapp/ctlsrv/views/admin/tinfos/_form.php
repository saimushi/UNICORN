<?php echo Form::open(); ?>

	<fieldset>
		<div class="clearfix">
			<?php echo Form::label('Startdate', 'startdate'); ?>

			<div class="input">
				<?php echo Form::input('startdate', Input::post('startdate', isset($tinfo) ? $tinfo->startdate : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Enddate', 'enddate'); ?>

			<div class="input">
				<?php echo Form::input('enddate', Input::post('enddate', isset($tinfo) ? $tinfo->enddate : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Msg', 'msg'); ?>

			<div class="input">
				<?php echo Form::textarea('msg', Input::post('msg', isset($tinfo) ? $tinfo->msg : ''), array('class' => 'span8', 'rows' => 8)); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Chara cnt', 'chara_cnt'); ?>

			<div class="input">
				<?php echo Form::input('chara_cnt', Input::post('chara_cnt', isset($tinfo) ? $tinfo->chara_cnt : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Assets', 'assets'); ?>

			<div class="input">
				<?php echo Form::textarea('assets', Input::post('assets', isset($tinfo) ? $tinfo->assets : ''), array('class' => 'span8', 'rows' => 8)); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Created', 'created'); ?>

			<div class="input">
				<?php echo Form::input('created', Input::post('created', isset($tinfo) ? $tinfo->created : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Modified', 'modified'); ?>

			<div class="input">
				<?php echo Form::input('modified', Input::post('modified', isset($tinfo) ? $tinfo->modified : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Available', 'available'); ?>

			<div class="input">
				<?php echo Form::input('available', Input::post('available', isset($tinfo) ? $tinfo->available : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('Os', 'os'); ?>

			<div class="input">
				<?php echo Form::input('os', Input::post('os', isset($tinfo) ? $tinfo->os : ''), array('class' => 'span4')); ?>

			</div>
		</div>
		<div class="actions">
			<?php echo Form::submit('submit', 'Save', array('class' => 'btn btn-primary')); ?>

		</div>
	</fieldset>
<?php echo Form::close(); ?>