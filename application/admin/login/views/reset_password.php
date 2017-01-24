 <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
	  <?php
			if($is_avalid=="Yes"){
    
    
			?>
        <div class=" form login_form">
          <section class="login_content">
              <div><img class="admin-logo" src="<?php echo base_url('assets/images').'/'.CRUVA_ADMIN_LOGO;?>" 
                              ><span class="after-logo-text">  Admin Login </span></div>
            <form action="<?php echo base_url('admin/login/reset_password');?>" name="reset_password" class="form-signin" method="post" accept-charset="utf-8" onsubmit="return validateForm()">
              
			  <div id="form_errors" class="alert alert-danger fade in" style="display:none;">
			 <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
			  
				  <div id="form_errors_msg"></div> 
			</div>
              <div>
			    
				 <input type="password" class="form-control" placeholder="Password"   autofocus name="new_password" autocomplete="off" value=""       >
               
              </div>
              <div >
              <input type="password" class="form-control"   placeholder="Confirm Password"   name="confirm_password" autocomplete="off" value=""    >
              </div>
			  
			  
			  
              <div style="padding-top:5px;text-align:left;">
                 
               <input type="hidden" name="reset_password_string" value="<?php echo $user_details->forgotten_password_code;?>">
				 <input type="submit" name="submit" value="Submit"  class="btn btn-default submit btn-md" style="margin-left:0px;">
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
	<?php }else{ ?>
	<div   class="form login_form"  >
           
         <section class="login_content">
             <div><img class="admin-logo" src="<?php echo base_url('assets/images').'/'.CRUVA_ADMIN_LOGO;?>" 
                              ><span class="after-logo-text">  Admin Login </span></div>
           <form>
               
			  <div id="infoMessage" class="alert alert-danger fade in">
			   
			  Invalid Link!
			  </div>
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
	<?php } ?>
 
	 </div>
    </div>
	<script>
 function validateForm() {
       
 
    // do stuff with your file

       var error_msg = ""; 
      new_password = $('input[name=new_password]').val();
      confirm_password = $('input[name=confirm_password]').val();
       
      var invalid = [];
          if(new_password==""){
           error_msg += " -- Password is required.</br>";
            invalid.push('new_password');
       }
      
       if(confirm_password==""){
           error_msg += "-- Confirm Password is required.</br>";
            
           invalid.push('confirm_password');
        } 
        
        if(new_password!="" && new_password.length < 7){
             
                error_msg += "-- Password must not be less than 6 character.</br>";
            
           invalid.push('confirm_password');
             
        }
        if(new_password!="" && confirm_password!="" && new_password!=confirm_password ){
             
            error_msg += "-- Password and confirm password does not match";
            
           invalid.push('confirm_password');
            
        }
        
       
       if(error_msg!=""){
       
         heading_error ="<div stlye='font-weight:bold;text-align:left;'>Please correct the following errors and try again:</div>"; 
         heading_error += error_msg;
         $('#form_errors_msg').html(heading_error);
         $('#form_errors').show();
         //alert(heading_error);
         first_invalid = invalid[0];
        // $('input[name='+first_invalid+']').focus();
        $(window).scrollTop(20);
         
         return false;
       }
       
      
        return true;
    }
</script>
 
 

	
	 