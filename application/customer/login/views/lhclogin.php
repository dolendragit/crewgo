<div>
   <a class="hiddenanchor" id="signup"></a>
   <a class="hiddenanchor" id="signin"></a>
   <style>
      }
   </style>


   <div class="login_wrapper">
      <div class="animate form login_form">
         <section class="login_content">
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
          <?php echo form_open(base_url('customer/login'), array('name'=>'customer_login','class'=>'form-horizontal'));?>
            <div>
               <img class="admin-logo" src="<?php echo base_url('assets/images').'/'.CRUVA_ADMIN_LOGO;?>"><span class="after-logo-text">  Customer  </span>
            </div>
            <div>
              <div class="form-group">         
                 <input type="email" name="email" placeholder="Email" class="form-control" value="<?php echo set_value('email');?>">
                 <?php echo form_error('email'); ?>
               </div>
            </div>
            <div>
              <div class="form-group">
                 <input type="password" name="password" placeholder="Password" class="form-control">
                 <?php echo form_error('password'); ?>
               </div>
            </div>
            <div class="form-group">
               <a href="<?php base_url('customer/login/forgotpassword');  ?>" data-toggle="modal" data-target="#myModal" class="reset_pass">Lost your password?</a>
               <input style="margin:0px 15px;" type="submit" value="&nbsp;&nbsp;&nbsp;  Login  &nbsp;&nbsp;&nbsp;"  class="btn btn-primary btn-sm active" style="margin-left:0px;">
               <span>
                 <?php echo form_checkbox('remember', '1', FALSE, 'id="remember",class="form-control"');?>
                 <?php echo lang('login_remember_label', 'remember');?>
               </span>
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
         </section>
      </div>
   </div>
</div>

<!-- Forgot password -->
<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"> CREWGO Customer </h4>
         </div>
         <div class="modal-body">
            <section class="login_content">
               <form  name="forgot_password" method="post" action="<?php echo base_url('customer/login/forgotpassword');?>">
                  <!-- <div><img class="admin-logo" src="<?php //echo base_url('assets/images').'/'.CRUVA_ADMIN_LOGO;?>">
                     <span class="after-logo-text">Forgot Password</span>
                  </div> -->
                  <div>
                     <input  type="email" class="form-control" placeholder="Enter your email" name="email" required>
                    
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
               </form>
            </section>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>