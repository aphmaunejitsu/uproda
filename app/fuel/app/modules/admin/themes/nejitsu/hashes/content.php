<div class="content-box-large">
	<div class="panel-heading">
		<div class="panel-title">Hash List
		</div>
	</div>
 	<div class="panel-body">
		<div class="table-responsive">
  		<table class="table table-hover list">
				<thead>
				  <tr><th>hash</th><th>ng</th><th>count</th><th>comment</th></tr>
				</thead>
				<tbody>
				<?php if ( ! empty($hashes)): ?>
				<?php foreach ($hashes as $index => $hash): ?>
				<tr>
					<td class='hash'><a href="<?php echo '/admin/hash/detail/'.$hash->hash;?>"><?php echo $hash->hash; ?></a></td>
					<td class='ng'><span class="glyphicon <?php echo $ng2str($hash->ng); ?>"></span></td>
					<td class='number'><?php echo $hash->image_count; ?></td>
					<td class='comment'><?php echo $hash->comment; ?></td>
				</tr>
				<?php endforeach;?>
				<?php endif;?>
				</tbody>
			</table>
		</div>
		<div class="row">
			<div class="col-xs-6 total">
				<?php echo $total ?> hashes
			</div>
			<div class="col-xs-6 data-pagination">
				<?php echo $pagination; ?>
			</div>
		</div>
	</div>
</div>
