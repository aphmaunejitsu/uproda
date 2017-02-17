<div class="container">
  <div class='section image' >
    <div class="row image-box">
		<div class="nine columns">
	  	<a href="<?php echo $src ?>" target="_blank"><img class='u-max-full-width image-detail' src="<?php echo $src; ?>"></a>
		</div>
		<div class="three columns" style="text-align:left">
			<ul><?php if ( ! empty(\Arr::get($image, 'comment', null))): ?><li><?php echo \Arr::get($image, 'comment', null);?></li> <?php endif; ?>
			<li><?php echo \Arr::get($image, 'original', null);?></li>
			<li><?php echo \Arr::get($image, 'mimetype', null);?></li>
			<li><?php echo \Num::format_bytes(\Arr::get($image, 'size', null), 1);?></li>
			<li><?php echo \Arr::get($image, 'created_at', null);?></li>
			<li><input type='text' class='u-full-width' value='<?php echo $src;?>'></li>
			</ul>
		</div>
	</div>
  </div>
</div>
