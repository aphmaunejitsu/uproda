<div class='section form white-popup mfp-hide' id='uproda-form'>
  <div class='container'>
    <?php echo \Form::open(['action' => 'image/upload', 'enctype' => 'multipart/form-data']); ?>
  	<?php echo \Form::csrf(); ?>
      <div class="row">
		<?php echo \Form::label($filelabel, 'upfile'); ?>
		<?php echo \Form::file('upfile', ['class' => 'u-full-width button-primary']); ?>
		<?php echo \Form::label($dellabel, 'pass'); ?>
		<?php echo \Form::input(['type' => 'text', 'name' => 'pass'], null, ['class' => 'u-full-width', 'maxlength' => 8]); ?>
		<?php echo \Form::label($commentlabel, 'comment'); ?>
		<?php echo \Form::input(['type' => 'text', 'name' => 'comment'], null, ['class' => 'u-full-width']); ?>
      </div>
      <?php echo Form::submit('submit', $buttonlabel, ['class' => 'button-primary']);?>
    <?php echo Form::close(); ?>
  </div>
</div>
