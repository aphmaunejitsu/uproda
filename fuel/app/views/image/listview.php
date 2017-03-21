<div class='section'>
	<div class='container images-listview'>
	<?php if ( ! empty($images)): ?>
	<?php foreach ($images as $index => $image): ?>
	<a href="<?php echo $build_image_url($image->basename); ?>" alt="<?php echo $image->original;?>">
	<div class='row'>
	<ul>
	<li><?php echo $image->basename ?></li>
	<li><?php echo $image->mimetype ?></li>
	<li><?php echo $format_bytes($image->size); ?></li>
	<li><?php echo $image->original; ?></li>
	<li><?php echo $format_date($image->created_at); ?></li>
	</ul>
	</div>
	</a>
	<?php endforeach; ?>
	<?php endif; ?>
	</div>
</div>

