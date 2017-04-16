<div class="content-box-large">
	<div class="panel-heading">
		<div class="panel-title">User</div>
	</div>
 	<div class="panel-body">
  <?php echo \Form::open(['action' => '/admin/user/save', 'name' => 'user-save', 'class' => 'form-horizontal', 'autocomplete' => 'nope']); ?>
	<?php echo \Form::csrf(); ?>
		<div class="form-group">
			<label for="user-name" class="col-sm-2" control-label>User Name</label>
			<div class="col-sm-10">
			<input type="text" class="form-control" id="user-name" placeholder="User Name" value="<?php echo $get_name($user);?>">
			</div>
		</div>
		<div class="form-group">
			<label for="user-email" class="col-sm-2" control-label>Email</label>
			<div class="col-sm-10">
			<input type="text" class="form-control" id="user-email" placeholder="User Email" value="<?php echo $get_email($user);?>">
			</div>
		</div>
		<div class="form-group">
			<label for="user-password" class="col-sm-2" control-label>Password</label>
			<div class="col-sm-10">
			<input type="password" class="form-control" id="user-password" placeholder="password" value="">
			</div>
		</div>
	<?php echo \Form::close();?>
	</div>
</div>
