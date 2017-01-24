
        <!-- page content -->
        <div class="right_col" role="main">
          <!-- top tiles -->
         <div class="">
            <div class="page-title">
              <div class="title_left">
               <?php echo "<h3>Change Password";//echo getPageActionTitle($page_url,$page_title,$page_action);?>
              </div>
				 
			
              
            </div>
            <div class="clearfix"></div>
			<?php displayMessages();?>	
            <div class="clearfix"></div>
			
        <!--Add Form-->
        <?php //if($page_action == "add" || $page_action == "edit") { ?>
        <div id="form_errors" class="alert alert-danger fade in" style="display:none;">
	<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>		  
            <div id="form_errors_msg"></div> 
        </div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                   
                  <div class="x_content">
                    <br />
                    <form id="frm_changePassword" name="frm_changePassword" data-parsley-validate class="form-horizontal form-label-left" onsubmit=" return validateForm()" method="post">

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="old_password">Old Password <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="password" required="required" id="old_password" name="old_password" value="<?php //echo set_value('name',@$lhc_edit->name);?>"   class="form-control col-md-7 col-xs-12" maxlength="300">
                        </div>
                      </div>
                        <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="new_password">New Password <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="password" required="required" id="old_password" name="new_password" value="<?php //echo set_value('name',@$lhc_edit->name);?>"   class="form-control col-md-7 col-xs-12" maxlength="300">
                        </div>
                      </div>
                        <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="confirm_password">Confirm Password <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="password" id="old_password" required="required" name="confirm_password" value="<?php //echo set_value('name',@$lhc_edit->name);?>"   class="form-control col-md-7 col-xs-12" maxlength="300">
                        </div>       <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    <!--<input type="hidden" name="hidden_id" id="hidden_id" value="<?php //echo $change_password->id;?>">-->
                                    <!--<input type="hidden" name="page_action" value="<?php //echo $page_action;?>">-->
                                    <button type="submit" class="btn btn-success"><?php echo $page_action='Submit';?></button>
                                    <button type="button" class="btn btn-primary" onclick="history.go(-1)">Cancel</button>

                                </div>
                            </div>
                      </div>
                                             
                        
                     
                       


                    </form>
                  </div>
                </div>
              </div>
            </div>
         </div>
        </div>
     <script>
 function validateForm() {
       
 
    // do stuff with your file

       var error_msg = "";
       old_password = $('input[name=old_password]').val();
      new_password = $('input[name=new_password]').val();
      confirm_password = $('input[name=confirm_password]').val();
       
      var invalid = [];
        if(old_password==""){
           error_msg += " --Old Password is required.</br>";
            invalid.push('old_password');
       }  
        if(new_password==""){
           error_msg += " -- Password is required.</br>";
            invalid.push('new_password');
       }
      
       if(confirm_password==""){
           error_msg += "-- Confirm Password is required.</br>";
            
           invalid.push('confirm_password');
        } 
        
        if(new_password!="" && new_password.length < 5){
             
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
    