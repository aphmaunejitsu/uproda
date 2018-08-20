<div class='section'>
	<div class='container images'>
	<?php if ( ! empty($images)): ?>
	<?php foreach ($images as $index => $image): ?>
	<a href="<?php echo $build_image_url($image->basename); ?>" alt="<?php echo $image->basename;?>">
		<figure>
			<?php echo \Asset::img('dummy.png', [
				'data-original' => $build_thumbnail_url($image->basename, $image->t_ext),
				'class'         => 'image-item lazy'
			]); ?>
		</figure>
	</a>
	<?php endforeach; ?>
	<?php endif; ?>
	</div>
</div>

