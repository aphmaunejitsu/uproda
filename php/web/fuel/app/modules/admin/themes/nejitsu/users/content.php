<div class="content-box-large">
	<div class="panel-heading">
		<div class="panel-title">User List</div>
	</div>
 	<div class="panel-body">
		<div class="table-responsive">
  		<table class="table table-hover list">
				<thead>
				  <tr><th>#</th><th>username</th><th>email</th><th>last login</th><th>create date</th></tr>
				</thead>
				<tbody>
				<?php if ( ! empty($users)): ?>
				<?php foreach ($users as $index => $user): ?>
				<tr>
					<td class='number'><?php echo $user->id ?></td>
					<td><a href="/admin/user/<?php echo $id2hash($user->id);?>" ><?php echo $user->username; ?></a></td>
					<td class='ip'><?php echo $user->email; ?></td>
					<td class='date'><?php echo $format_date($user->last_login); ?></td>
					<td class='date'><?php echo $format_date($user->created_at); ?></td>
					<td class='action'>
						<span class="glyphicon glyphicon-trash user-delete">
    				<?php echo \Form::open(['action' => '/admin/user/delete', 'name' => 'user-delete']); ?>
  					<?php echo \Form::csrf(); ?>
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
				<?php echo $total ?> users
			</div>
			<div class="col-xs-6 data-pagination">
				<?php echo $pagination; ?>
			</div>
		</div>
	</div>
</div>
