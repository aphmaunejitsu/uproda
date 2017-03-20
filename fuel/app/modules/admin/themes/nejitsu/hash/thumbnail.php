<div class="media">
	<div class='thumbnail'>
	<?php if (($image) !== null): ?>
	<img src="<?php echo $build_thumbnail_url($image->basename); ?>">
	<?php else: ?>
	<img src="<?php echo \Theme::instance()->asset->img('dummy.png'); ?>">
	<?php endif; ?>
	</div>

	<div class='media-body'>
  <?php echo \Libs_Form::open(['action' => 'admin/hash/save', 'name' => 'change-hash-ng-state']); ?>
  <?php echo \Libs_Form::csrf(); ?>
	<?php echo \Libs_Form::hidden('file', $image->hash); ?>
	<ul class='list-group'>
	<li class='list-group-item'><?php echo $image->hash;?></li>
	<li class='list-group-item'>
		<div class="form-group">
		<label>comment</label>
		<input type="text" name="comment" value="<?php echo $image->comment ?>" class="form-control">
		</div>
		<div class="checkbox">
		<input type="hidden" name="image-ng" value="">
		<label>NG <input type="checkbox" name="image-ng" <?php echo $write_ng_state($image->ng); ?>></label>
		</div>
		<div class="form-group">
			<button type="button"
				data-alert-text="false"
				data-loading-text="Saving..."
				class="form-control btn btn-primary btn-block hash_event"
				autocomplete="off"
				name="save"
				data-action="/admin/hash/save">Save
			</button>
		</div>
	</li>
	<li class="list-group-item">
		<button type="button"
			data-alert-text="delete all images"
			data-loading-text="Image Deleting..."
			class="btn btn-primary btn-block hash_event"
			autocomplete="off"
			name="delete"
			data-action="/admin/hash/delete.json">
			Delete Images
		</button>
	</li>
	</ul>
	<?php echo \Libs_Form::close();?>
	</div>
</div>
