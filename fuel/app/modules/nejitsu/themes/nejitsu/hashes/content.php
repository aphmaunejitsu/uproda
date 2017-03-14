<div class="content-box-large">
	<div class="panel-heading">
		<div class="panel-title">Hash List</div>
	</div>
 	<div class="panel-body">
		<div class="table-responsive">
  		<table class="table">
				<thead>
				  <tr><th>#</th><th>hash</th><th>ng</th><th>comment</th><th>date</th><th>Action</th></tr>
				</thead>
				<tbody>
				<?php if ( ! empty($hashes)): ?>
				<?php foreach ($hashes as $index => $hash): ?>
				<tr>
					<td><?php echo $hash->id ?></td>
					<td><?php echo $hash->hash; ?></td>
					<td><span class="glyphicon <?php echo $ng2str($hash->ng); ?>"></span></td>
					<td><?php echo $hash->comment; ?></td>
					<td><?php echo $format_date($hash->created_at); ?></td>
					<td>
						<span class="glyphicon glyphicon-trash hash-delete">
    				<?php echo \Form::open(['action' => 'nejitsu/hash/delete', 'name' => 'hash-delete']); ?>
  					<?php echo \Form::csrf(); ?>
						<?php echo \Form::hidden('file', $id2hash($hash->id)); ?>
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
				<?php echo $total ?> hashes
			</div>
			<div class="col-xs-6 data-pagination">
				<?php echo $pagination; ?>
			</div>
		</div>
	</div>
</div>
