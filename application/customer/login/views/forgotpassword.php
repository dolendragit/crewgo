<div class="col-sm-6 col-sm-offset-3">
  <div class="panel panel-default">
      <div class="panel-heading text-center">
        <h3 class="panel-title"> Password Reset </h3>
      </div>
         <?php if($this->session->flashdata('message') != ""):?>
              <div class="alert alert-danger">
                <span><?php echo $this->session->flashdata('message');?></span>
              </div>
            <?php endif;?>
              <?php if($this->session->flashdata('success') != ""):?>
              <div class="alert alert-success">
                <span><?php echo $this->session->flashdata('success');?></span>
              </div>
            <?php endif;?>
            <?php if($this->session->flashdata('error') != ""):?>
              <div class="alert alert-danger">
                <span><?php echo $this->session->flashdata('error');?></span>
              </div>
          <?php endif;?>
      <div class="panel-body">
          <?php echo form_open(base_url('customer/login/forgotpassword'), array('name'=>'forgot_password','class'=>'form-horizontal') );?>
              <div>         
                 <input  type="email" class="form-control" placeholder="Enter your email" name="email" required value="<?php set_value('email');?>">
                 <?php echo form_error('email'); ?>
              </div>
              <div style="padding-top:5px;text-align:left;">
                <span class="reset_pass">   Already a member ?
                  <a href="<?php echo base_url('customer/login');?>" >Login</a></span>
                  <button type="submit" class="btn btn-primary btn-sm">Send Password Reset Link</button>
              </div>
              <div class="clearfix"></div>
              <div class="separator">
                <div class="clearfix"></div>
                <br />
                <div>
                  <p>©<?php echo date('Y');?> All Rights Reserved.</p>
                </div>
              </div>
          <?php echo form_close();?>
      </div>
    </div>
  </div>
<!-- Forgot password -->