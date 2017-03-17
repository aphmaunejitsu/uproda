<div class="content-box-large">
	<div class="panel-heading">
		<div class="panel-title">Image List</div>
	</div>
 	<div class="panel-body">
		<div class="table-responsive">
  		<table class="table table-hover list">
				<thead>
				  <tr><th>#</th><th>filename</th><th>original</th><th>size</th><th>ip</th><th>date</th></tr>
				</thead>
				<tbody>
				<?php if ( ! empty($images)): ?>
				<?php foreach ($images as $index => $image): ?>
				<tr>
					<td class='number'><?php echo $image->id ?></td>
					<td><a href="<?php echo $build_image_url($image->basename); ?>" target='_blank'><?php echo $image->basename; ?></a></td>
					<td class='size'><?php echo $image->original; ?></td>
					<td class='size'><?php echo $format_bytes($image->size); ?></td>
					<td class='ip'><?php echo $image->ip; ?></td>
					<td class='date'><?php echo $format_date($image->created_at); ?></td>
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
