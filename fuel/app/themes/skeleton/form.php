<div class='section form white-popup mfp-hide' id='uproda-form'>
  <div class='container'>
    <?php echo Libs_Form::open(['action' => 'image/upload.json', 'enctype' => 'multipart/form-data', 'id' => 'form-uproda']); ?>
  	<?php echo Libs_Form::csrf(); ?>
    <div class="row">
			<?php echo Libs_Form::label($filelabel, 'upfile'); ?>
			<?php echo Libs_Form::file('upfile', ['class' => 'u-full-width button-primary']); ?>
			<?php echo Libs_Form::label($dellabel, 'pass'); ?>
			<?php echo Libs_Form::input(['type' => 'text', 'name' => 'pass', 'maxlength' => 8, 'class' => 'u-full-width'], null); ?>
			<?php echo Libs_Form::label($commentlabel, 'comment'); ?>
			<?php echo Libs_Form::input(['type' => 'text', 'name' => 'comment', 'maxlength' => 140, 'class' => 'u-full-width'], null); ?>
    </div>
		<div class="row">
    <?php echo Form::submit('submit', $buttonlabel, ['class' => 'button-primary']);?>
		</div>
    <?php echo Form::close(); ?>
  </div>
</div>
