<div class="content-box-large">
	<div class="panel-heading">
		<div class="panel-title">Image List</div>
	</div>
 	<div class="panel-body">
		<div class="table-responsive">
  		<table class="table">
				<thead>
				  <tr><th>#</th><th>filename</th><th>hash</th><th>size</th><th>ip</th><th>ng</th><th>date</th><th>Action</th></tr>
				</thead>
				<tbody>
				<?php if ( ! empty($images)): ?>
				<?php foreach ($images as $index => $image): ?>
				<tr>
					<td><?php echo $image->id ?></td>
					<td><a href="<?php echo $build_image_url($image->basename); ?>" target='_blank'><?php echo $image->basename; ?></a></td>
					<td><?php echo $image->hash; ?></td>
					<td><?php echo $format_bytes($image->size); ?></td>
					<td><?php echo $image->ip; ?></td>
					<td><span class="glyphicon <?php echo $ng2str($image->ng); ?>"></span></td>
					<td><?php echo $format_date($image->created_at); ?></td>
					<td>
						<span class="glyphicon glyphicon-trash image-delete">
    				<?php echo \Form::open(['action' => 'nejitsu/image/delete', 'name' => 'image-delete']); ?>
  					<?php echo \Form::csrf(); ?>
						<?php echo \Form::hidden('file', $hash(\Arr::get($image, 'id'))); ?>
						<?php echo \Form::close();?>
						</span>
					</td>
				</tr>
				<?php endforeach;?>
				<?php endif;?>
				</tbody>
			</table>
		</div>
		<div class="row">
			<div class="col-xs-6 total">
				<?php echo $total ?> images
			</div>
			<div class="col-xs-6 data-pagination">
				<?php echo $pagination; ?>
			</div>
		</div>
	</div>
</div>
