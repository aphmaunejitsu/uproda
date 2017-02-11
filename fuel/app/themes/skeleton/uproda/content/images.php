<div class='section images'>
	<div class='container image-list'>
	<?php if ( ! empty($images)): ?>
	<?php foreach ($images as $index => $image): ?>
	<a class='image-list' href="<?php echo $build_image_url($image['basename']); ?>" alt="<?php echo $image['basename'];?>">
	<figure>
		<img class='image-item'
			src="<?php echo $build_thumbnail_url($image_dir, $thumbnail_dir, $image['basename']); ?>"
			data-src="<?php echo $build_real_image_url($image_dir, $image['basename'], $image['ext']); ?>"
		>
	</figure>
	</a>
	<?php endforeach; ?>
	<?php endif; ?>
	</div>
</div>
