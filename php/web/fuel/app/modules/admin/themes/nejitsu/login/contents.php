<div class="row">
	<div class="col-md-4 col-md-offset-4">
		<div class="login-wrapper">
	        <div class="box">
	            <div class="content-wrap">
    							<?php echo \Form::open(['action' => '/admin/auth', 'id' => 'login', 'method' => 'post']); ?>
  								<?php echo \Form::csrf(); ?>
	                <h6>Sign In</h6>
									<?php echo \Form::input('username', null,  ['class' => 'form-control', 'placeholder' => 'E-mail address']); ?>
									<?php echo \Form::password('password', null, ['class' => 'form-control', 'placeholder' => 'Password']); ?>
	                <div class="action">
    							<?php echo \Form::submit('submit', 'Login', ['class' => 'signup btn button-primary']);?>
	                </div>
    							<?php echo \Form::close(); ?>
	            </div>
	        </div>
	    </div>
	</div>
</div>

