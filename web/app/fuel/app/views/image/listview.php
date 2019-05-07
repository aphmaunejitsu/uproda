<div class='container images-listview'>
	<?php if ( ! empty($images)): ?>
	<?php foreach ($images as $index => $image): ?>
	<a href="<?php echo $build_image_url($image->basename); ?>" alt="<?php echo $image->original;?>">
	<div class='row'>
		<ul class="images">
		<li class="basename"><?php echo $image->basename ?></li>
		<li class="mimetype"><?php echo $image->mimetype ?></li>
		<li class="size"><?php echo $format_bytes($image->size); ?></li>
		<li class="original"><?php echo $image->original; ?></li>
		<li class="uploaded"><?php echo $format_date($image->created_at); ?></li>
		</ul>
	</div>
	</a>
	<?php endforeach; ?>
	<?php endif; ?>
</div>
