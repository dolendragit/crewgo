<div class="col-sm-6 col-sm-offset-3">
	<div class="panel panel-default">
	  <div class="panel-heading text-center">
	    <h3 class="panel-title"> Customer Registration</h3>
	  </div>

	  	<?php if($this->session->flashdata('message') != ""): ?>
	  	<div class="alert alert-success" style="margin-top: 5px;">
	  		<span><?php echo $this->session->flashdata('message'); ?></span>
	  	</div>
	  <?php endif; ?>
	    <div class="panel-body">
	   	<!--start of registration form-->
            <?php echo form_open(customer_interface_url('login/register'), array('class'=>'form-horizontal','name'=>'customer_register') );?>
                <div class="form-group">
                  <label for="name" class="col-sm-3 control-label">Name*</label>
                  <div class="col-sm-9">
                    <!-- <input type="text" class="form-control" id="name" placeholder="Name"> -->
                    <?php
				    	 $name = array(
				    	 		'type'  => 'text',
				    	 		'name'  => 'name',
				    	 		'id'    => 'name',
				    	 		'placeholder' => 'Name',
				    	 		'class' => 'form-control',
				    	 		'value' => set_value('name')
				    	 );
    	 				echo form_input($name);
    	 			?>
                  </div>
                </div>
                <div class="form-group">
                  <label for="address" class="col-sm-3 control-label">Address*</label>
                  <div class="col-sm-9">
                    <!-- <input type="text" class="form-control" id="address" placeholder="Address"> -->
                    <?php
				    	 $address = array(
				    	 		'type'  => 'text',
				    	 		'name'  => 'address',
				    	 		'id'    => 'address',
				    	 		'placeholder' => 'Name',
				    	 		'class' => 'form-control',
				    	 		'value' => set_value('address')
				    	 );
    	 				echo form_input($address);
    	 			?>
                  </div>
                </div>
                <div class="form-group">
                  <label for="email" class="col-sm-3 control-label">Email Id*</label>
                  <div class="col-sm-9">
                    <!-- <input type="email" class="form-control" id="email" placeholder="Email"> -->
                    <?php
				    	 $email = array(
				    	 		'type'  => 'email',
				    	 		'name'  => 'email',
				    	 		'id'    => 'email',
				    	 		'placeholder' => 'Email',
				    	 		'class' => 'form-control',
				    	 		'value' => set_value('email')
				    	 );
    	 				echo form_input($email);
    	 			?>
                  </div>
                </div>
                <div class="form-group">
                  <label for="phone" class="col-sm-3 control-label">Phone*</label>
                  <div class="col-sm-9">
                    <!-- <input type="text" class="form-control" id="phone" placeholder="Phone"> -->
                    <?php
				    	 $phone = array(
				    	 		'type'  => 'text',
				    	 		'name'  => 'phone',
				    	 		'id'    => 'phone',
				    	 		'placeholder' => 'Phone',
				    	 		'class' => 'form-control',
				    	 		'value' => set_value('phone')
				    	 );
    	 				echo form_input($phone);
    	 			?>
                  </div>
                </div>
                <div class="form-group">
                  <label for="password" class="col-sm-3 control-label">Password*</label>
                  <div class="col-sm-9">
                    <!-- <input type="password" class="form-control" id="password" placeholder="********"> -->
                    <?php
				    	 $password = array(
				    	 		'type'  => 'password',
				    	 		'name'  => 'password',
				    	 		'id'    => 'password',
				    	 		'placeholder' => '********',
				    	 		'class' => 'form-control'
				    	 );
    	 				echo form_input($password);
    	 			?>
                  </div>
                </div>
                <div class="form-group">
                  <label for="cpassword" class="col-sm-3 control-label">Confirm Password*</label>
                  <div class="col-sm-9">
                    <!-- <input type="password" class="form-control" id="cpassword" placeholder="********"> -->
                    <?php
				    	 $cpassword = array(
				    	 		'type'  => 'password',
				    	 		'name'  => 'cpassword',
				    	 		'id'    => 'cpassword',
				    	 		'placeholder' => '********',
				    	 		'class' => 'form-control'
				    	 );
    	 				echo form_input($cpassword);
    	 			?>
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <!-- <button type="submit" class="btn btn-default">Cancel</button> -->
                <button type="submit" class="btn btn-primary pull-right"> Register </button>
              </div>
              <!-- /.box-footer -->
            <?php echo form_close(); ?>
	   	<!--end of registration form-->
	  </div>
	</div>
</div>
