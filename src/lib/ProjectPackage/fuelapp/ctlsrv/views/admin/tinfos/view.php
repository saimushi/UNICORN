<h2>Viewing #<?php echo $tinfo->id; ?></h2>

<p>
	<strong>Startdate:</strong>
	<?php echo $tinfo->startdate; ?></p>
<p>
	<strong>Enddate:</strong>
	<?php echo $tinfo->enddate; ?></p>
<p>
	<strong>Msg:</strong>
	<?php echo $tinfo->msg; ?></p>
<p>
	<strong>Chara cnt:</strong>
	<?php echo $tinfo->chara_cnt; ?></p>
<p>
	<strong>Assets:</strong>
	<?php echo $tinfo->assets; ?></p>
<p>
	<strong>Created:</strong>
	<?php echo $tinfo->created; ?></p>
<p>
	<strong>Modified:</strong>
	<?php echo $tinfo->modified; ?></p>
<p>
	<strong>Available:</strong>
	<?php echo $tinfo->available; ?></p>
<p>
	<strong>Os:</strong>
	<?php echo $tinfo->os; ?></p>

<?php echo Html::anchor('admin/tinfos/edit/'.$tinfo->id, 'Edit'); ?> |
<?php echo Html::anchor('admin/tinfos', 'Back'); ?>