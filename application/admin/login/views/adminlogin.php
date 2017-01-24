 <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>
      <style>
         

          </style>
          
      <div class="login_wrapper">
        <div class="animate form login_form">
              
          <section class="login_content">
           <?php echo form_open(base_url('admin/login'));?>
              
               <div><img class="admin-logo" src="<?php echo base_url('assets/images').'/'.CRUVA_ADMIN_LOGO;?>" 
                              ><span class="after-logo-text">  Admin Login </span></div>
			  <?php if($message): ?>
			  <div id="infoMessage" class="alert alert-danger fade in">
			  <span class="close" data-dismiss="alert" aria-label="close">&times;</span>
			  <?php echo $message;?>
			  </div>
			  <?php endif;?>
              <div>
			    
				<?php echo form_input($identity);?>
               
              </div>
              <div >
               <?php echo form_input($password);?>
                  <input type="hidden" name="user_group" value="1">
              </div>
		<?php /*?>	  <div >
			  <select name="user_group" id="user_group" class="form-control" placeholder="Log in as">
			  <option value="">Log in as</option>
			  <?php 
				if(is_array($user_groups))
				{
					foreach($user_groups as $group_id=> $group_desc)
					{
			   ?>
			   <option value="<?php echo $group_id;?>"><?php echo $group_desc;?></option>
			   <?php
					}
				}
			   
			   ?>
			  </select>
               
              </div><?php */ ?>
			  
			  
			  <div style="text-align:left;display:none;">
			 
              <?php echo form_checkbox('remember', '1', FALSE, 'id="remember",class="form-control"');?>
			   <?php echo lang('login_remember_label', 'remember');?>
              </div>
			  
              <div style="padding-top:5px;text-align:left;">
                 
               <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" class="reset_pass">Lost your password?</a>
                <input type="submit" name="submit" value="Login"  class="btn btn-primary  active" style="margin-left:0px;">
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
	
	<!-- Forgot password -->
	
	<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Forgot Password</h4>
        </div>
          <div class="modal-body" ng-app="forgot-app" ng-controller="forgot-appcontrol">
	<section class="login_content">
            
            <form  name="forgot_password" novalidate ng-submit="submitForgot()" novalidate>
              <div><img class="admin-logo" src="<?php echo base_url('assets/images').'/'.CRUVA_ADMIN_LOGO;?>">
                  <span class="after-logo-text">  Admin Login </span>
              </div>
                
              <div>
               <input  type="email" class="form-control" placeholder="Enter your email" ng-model="form.email" name="email" required ng-change="clearMessage()">
                    <div ng-show="forgot_password.email.$touched && forgot_password.email.$invalid" style="color:red;padding-bottom:10px;text-align:left;">
                       Please enter valid email.</div>
               <input type="hidden" name="user_group" value="1">
              </div>
             <?php /* ?> <div>
                <select name="user_group" id="user_group" class="form-control" ng-model="form.user_group" name="user_group" required>
			  <option value="">User Type</option>
			  <?php 
				if(is_array($user_groups))
				{
					foreach($user_groups as $group_id=> $group_desc)
					{
			   ?>
			   <option value="<?php echo $group_id;?>"><?php echo $group_desc;?></option>
			   <?php
					}
				}
			   
			   ?>
			  </select>
			  <div ng-show="forgot_password.user_group.$touched && forgot_password.user_group.$invalid" style="color:red;padding-bottom:10px;text-align:left;">
                       Please select user type.</div>
              </div> <?php */ ?>
               <div style="padding-top:5px;text-align:left;">
                   <span class="reset_pass">   Already a member ?
                       <a href="<?php echo base_url('admin/login');?>" >Login</a></span>
       <input id="mybtn" type="submit"   ng-disabled="forgot_password.$invalid" class="btn btn-primary active" style="margin-left:0px;">
              </div> 
                  
			  <div>
			  <span style="color:#00A8B3" class="error_sucess">{{successMessage}}</span> 
                       <span style="color:red;" class="error_sucess">{{errorMessage}}</span> 
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

<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>

<script>
var app = angular.module('forgot-app', []);
app.controller('forgot-appcontrol', function($scope,$http) {
    
    // Simple GET request example:
      
    $scope.form = {};
    $scope.eamilChecker ="";
    $scope.successMessage = ""; 
    $scope.errorMessage = "";
    $scope.clearMessage = function(){
       // $('.error_sucess').text('');
    }
    
    $scope.submitForgot = function(){
        $scope.successMessage = ""; 
            $scope.errorMessage = "";
        $("#mybtn").text('Processing...');
        $("#mybtn").prop('disabled',true);
         
        $http({
            method  : 'POST',
            url     : '<?php echo  base_url('admin/login/forgotPassword');?>',
            data    : $.param($scope.form),  // pass in data as strings
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' } 
        }).then(function successCallback(response) {
             
             if(response.data.success){
                 $scope.successMessage = response.data.message;
             }else{
                  $scope.errorMessage = response.data.message;
             }
              $("#mybtn").text('Submit');
               $("#mybtn").prop('disabled',false);
              
            // this callback will be called asynchronously
            // when the response is available
          }, function errorCallback(response) {
            // called asynchronously if an error occurs
            // or server returns response with an error status.
          });
    }
});
</script>
 