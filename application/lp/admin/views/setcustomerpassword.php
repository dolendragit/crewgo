<style type="text/css">
  .help-block p {
    color: #820707;
}
</style>


<div>
   <a class="hiddenanchor" id="signup"></a>
   <a class="hiddenanchor" id="signin"></a>
   <style>
      }
   </style>


   <div class="login_wrapper col-sm-4 col-sm-offset-4">
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

            <?php echo form_open(base_url('lp/auth/setcustomerpassword/'.$code . '/' .$lhc));?>
            
            <input type="hidden" name="lhcuserid" value="<?php echo end(explode('/', current_url()));?>">
            <div>
               <img class="admin-logo" src="<?php echo base_url('assets/images').'/'.CRUVA_ADMIN_LOGO;?>"><span class="after-logo-text">  Reset Password </span>
            </div>
            
            <div style="margin-top: 15px;">         
               <input type="password" name="password" placeholder="New Password" class="form-control">
               <span class="help-block"><?php echo form_error('password'); ?></span>
            </div>
           
            <div style="margin-top: 15px;">
              <input type="password" name="confirm_password" placeholder="Retype Password" class="form-control">
              <span class="help-block"><?php echo form_error('confirm_password'); ?></span>
            </div>
          
            <div style="padding-top:5px;text-align:left;">
               <input type="submit" name="submit" value="Set password"  class="btn btn-primary  active" style="margin-left:0px;">
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
   </div>
</div>


<!-- <script type="text/javascript">
  $('document').ready(function(){
     $('#myModal').modal('show');
  });

</script> -->
