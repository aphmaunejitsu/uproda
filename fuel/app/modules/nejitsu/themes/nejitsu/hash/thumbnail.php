<div class="media">
	<div class='thumbnail'>
	<?php if (($image) !== null): ?>
	<img src="<?php echo $build_thumbnail_url($image->basename); ?>">
	<?php else: ?>
	<img src="<?php echo \Theme::instance()->asset->img('dummy.png'); ?>">
	<?php endif; ?>
	</div>

	<div class='media-body'>
  <?php echo \Libs_Form::open(['action' => 'nejitsu/hash/save', 'name' => 'change-hash-ng-state']); ?>
  <?php echo \Libs_Form::csrf(); ?>
	<?php echo \Libs_Form::hidden('file', $image->hash); ?>
	<ul class='list-group'>
	<li class='list-group-item'><?php echo $image->hash;?></li>
	<li class='list-group-item'>
		<div class="form-group">
		<label>comment</label>
		<input type="text" name="comment" value="<?php echo $image->comment ?>" class="form-control">
		</div>
		<div class="form-group">
		<label>NG</label> <input type="checkbox" name="image-ng" <?php echo $write_ng_state($image->ng); ?>>
		</div>
		<div class="form-group">
		<button type="button" data-loading-text="Saving..." class="form-control btn btn-primary btn-block hash_event" autocomplete="off" name="save" data-action="/nejitsu/hash/save">Save</button>
		</div>
	</li>
	<li class='list-group-item'><button type="button" data-loading-text="Hash Deleting..." class="btn btn-primary btn-block hash_event" autocomplete="off" name="delete_hash" data-action="/nejitsu/hash/delete.json">Delete Hash &amp; Images</button></li>
	<li class="list-group-item"><button type="button" data-loading-text="Image Deleting..." class="btn btn-primary btn-block hash_event" autocomplete="off" name="delete_image" data-action="/nejitsu/hash/delete_images.json">Delete Images</button></li>
	</ul>
	<?php echo \Libs_Form::close();?>
	</div>
</div>
