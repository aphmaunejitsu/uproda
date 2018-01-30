<div class="container">
  <div class='section image' >
    <div class="row image-box">
		<div class="nine columns">
	  	<a href="<?php echo $src ?>" target="_blank"><img class='u-max-full-width image-detail' src="<?php echo $src; ?>"></a>
		</div>
		<div class="three columns" >
			<ul><?php if ( ! empty(\Arr::get($image, 'comment', null))): ?><li><?php echo \Arr::get($image, 'comment', null);?></li> <?php endif; ?>
			<li><?php echo \Arr::get($image, 'original', null);?></li>
			<li><?php echo \Arr::get($image, 'mimetype', null);?></li>
			<li><?php echo \Num::format_bytes(\Arr::get($image, 'size', null), 1);?></li>
			<li><?php echo \Arr::get($image, 'created_at', null);?></li>
			<li>
				<input id='image-path' type='text' class='u-full-width' value='<?php echo $src;?>'>
				<button class="btn button u-full-width" data-clipboard-target="#image-path">copy</button>
			</li>
			<li>
			</li>
    		<?php echo Libs_Form::open(['action' => 'image/delete', 'name' => 'image-delete']); ?>
  			<?php echo Libs_Form::csrf(); ?>
				<?php echo Libs_Form::hidden('file', $hash(\Arr::get($image, 'id'))); ?>
				<?php echo Libs_Form::input(['type' => 'text', 'name' => 'pass', 'maxlength' => 8, 'class' => 'u-full-width'], null); ?>
				<?php echo Libs_Form::submit('delete', 'delete', ['class' => 'button u-full-width']);?>
				<?php echo Libs_Form::close();?>
			</ul>
		</div>
	</div>
  </div>
</div>
