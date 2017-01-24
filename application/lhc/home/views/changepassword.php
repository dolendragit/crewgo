<!-- page content -->
<div class="right_col" role="main">
   <!-- top tiles -->
   <div class="page-title">
    <h4>Change Your Password</h4>
   </div>


   <div class="row" style="padding-top: 20px;">
     <?php $this->load->view('alert');?>
      <div class="panel panel-default">
         <div class="panel-heading">Change Password</div>
         <div class="panel-body">
            <!--change password form-->
            <form method="post" action="<?php echo base_url('lhc/home/changepassword');?>">
             
              <div class="form-group  <?php echo form_error('oldpassword') ? 'has-error' : '' ?>">
                <label for="exampleInputEmail1">Old Password</label>
                <input type="password" class="form-control" id="exampleInputEmail1" placeholder="Old Password" name="oldpassword">
                <?php echo form_error('oldpassword'); ?>
              </div>
            
              <div class="form-group  <?php echo form_error('password') ? 'has-error' : '' ?>">
                <label for="exampleInputPassword1">New Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="password">
                <?php echo form_error('password'); ?>
              </div>


               <div class="form-group  <?php echo form_error('confirm_password') ? 'has-error' : '' ?>">
                <label for="exampleInputPassword1">Confirm Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Retype Password" name="confirm_password">
                <?php echo form_error('confirm_password'); ?>
              </div>
            
              <button type="submit" class="btn btn-default btn-sm">Change Password</button>
            </form>
            <!--change password form-->
         </div>
      </div>
   </div>
</div>
<!-- /page content -->