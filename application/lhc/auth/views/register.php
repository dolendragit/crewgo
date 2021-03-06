<div class="col-sm-6 col-sm-offset-3">
	<div class="panel panel-default">
	  <div class="panel-heading">
	    <h3 class="panel-title">LHC Registration</h3>
	  </div>

	  	<?php if($this->session->flashdata('success_message') != ""): ?>
	  	<div class="alert alert-success" style="margin-top: 5px;">
	  		<span><?php echo $this->session->flashdata('success_message'); ?></span>
	  	</div>
	  <?php endif; ?>
	  <div class="panel-body">
	   	<!--start of registration form-->
	   	<form class="form-horizontal" method="post" action="<?php echo base_url("lhc/auth/register");?>" enctype="multipart/form-data">
		  <div class="form-group <?php echo form_error('industry[]') ? 'has-error' : '' ?>">
		    <label for="inputEmail3" class="col-sm-2 control-label">Industry <span class="required">*</span></label>
		    <div class="col-sm-10">
		     	<select multiple class="form-control" name="industry[]">
		     		 <?php  
		     		 	if ($industry != ""):
		     		 	foreach($industry as $key => $val): 
		     		 ?>
		     		 	<option value="<?php echo $val->id;?>"><?php echo $val->name;?></option>
		     		 <?php 
		     		 	endforeach; 
		     		 	endif;
		     		 ?>
		     	</select>
				<?php echo form_error('industry[]'); ?>
		    </div>
		  </div>

		

		  <div class="form-group <?php echo form_error('businessname') ? 'has-error' : '' ?>">
		    <label for="inputPassword3" class="col-sm-2 control-label">Business Name <span class="required">*</span></label>
		    <div class="col-sm-10">
		      <input type="text" name="businessname" class="form-control" id="inputPassword3" placeholder="Business Name" value="<?php echo set_value("businessname"); ?>">
				<?php echo form_error('businessname'); ?>
		    </div>
		  </div>

		   <div class="form-group <?php echo form_error('contactperson') ? 'has-error' : '' ?>">
		    <label for="inputPassword3" class="col-sm-2 control-label">Billing (Contact) Person <span class="required">*</span></label>
		    <div class="col-sm-10">
		      <input type="text" name="contactperson" class="form-control" id="inputPassword3" placeholder="Contact Person" value="<?php echo set_value("contactperson"); ?>">
				<?php echo form_error('contactperson'); ?>
		    </div>
		  </div>


		   <div class="form-group <?php echo form_error('contactphoneno') ? 'has-error' : '' ?>">
		    <label for="inputPassword3" class="col-sm-2 control-label">Contact Phone No. <span class="required">*</span></label>
		    <div class="col-sm-10">
		      <input type="text" name="contactphoneno" class="form-control" id="inputPassword3" placeholder="Contact Phone No." value="<?php echo set_value("contactphoneno"); ?>">
				<?php echo form_error('contactphoneno'); ?>
		    </div>
		  </div>

		   <div class="form-group <?php echo form_error('email') ? 'has-error' : '' ?>">
		    <label for="inputPassword3" class="col-sm-2 control-label" >Email <span class="required">*</span></label>
		    <div class="col-sm-10">
		      <input type="email" name="email" class="form-control" id="inputPassword3" placeholder="Email" value="<?php echo set_value("email"); ?>">
				<?php echo form_error('email'); ?>
		    </div>
		  </div>

		   <div class="form-group <?php echo form_error('address') ? 'has-error' : '' ?>">
		    <label for="inputPassword3" class="col-sm-2 control-label">Address<span class="required">*</span></label>
		    <div class="col-sm-10">
		      <input type="text" name="address" class="form-control" id="inputPassword3" placeholder="Address" value="<?php echo set_value("address"); ?>">
				<?php echo form_error('address'); ?>
		    </div>
		  </div>

		   <div class="form-group <?php echo form_error('abn') ? 'has-error' : '' ?>">
		    <label for="inputPassword3" class="col-sm-2 control-label">ABN<span class="required">*</span></label>
		    <div class="col-sm-10">
		      <input type="text" name="abn" class="form-control" id="inputPassword3" placeholder="ABN" value="<?php echo set_value("abn"); ?>">
				<?php echo form_error('abn'); ?>
		    </div>
		  </div>

		   <div class="form-group <?php echo form_error('logo') ? 'has-error' : '' ?>">
		    <label for="inputPassword3" class="col-sm-2 control-label">Logo<span class="required">*</span></label>
		    <div class="col-sm-10">
		      <input type="file" name="profile" class="form-control" id="inputPassword3" value="<?php echo set_value("logo"); ?>">
				<?php echo form_error('logo'); ?>
		    </div>
		  </div>

		
		  <p class="strong">Please Upload the necessary documents <span class="required">*</span></p><hr/>


		  <div class="form-group ">
		  	<label class="mylabel">1. Worksafe Compliance</label><br/>
		  
		  	<div class="col-sm-3 col-sm-offset-1">
				<div class="form-group <?php echo form_error('expiry') ? 'has-error' : '' ?>">
				    <label class="inner">Expiry date</label>
				    <input type="text" name="expiry[]" id="datepicker" class="form-control" id="exampleInputEmail1" placeholder="Expiry Date" value="<?php echo set_value("expiry"); ?>">
					 <?php echo form_error('expiry[]'); ?>
				  </div>
		  	</div>

		  	<div class="col-sm-3 col-sm-offset-1">
		  		 <div class="form-group <?php echo form_error('allfiles1') ? 'has-error' : '' ?>">
				    <label class="inner">Upload Specified Document</label>
				    <input type="file" name="allfiles1" class="form-control" id="exampleInputEmail1" value="<?php echo set_value("allfiles1"); ?>">
					 <?php echo form_error('allfiles1'); ?>
				  </div>
		  	</div>

		  	<div class="col-sm-4">&nbsp;</div>
		  	<div class="clearfix"></div>		
		  </div>



		  <div class="form-group ">
		  	<label class="mylabel">2. Other Certificate</label><br/>
		  
		  	<div class="col-sm-3 col-sm-offset-1">
		  		 <div class="form-group <?php echo form_error('expirydate') ? 'has-error' : '' ?>">
				    <label class="inner">Expiry date</label>
				    <input type="text" name="expiry[]" id="datepicker1" class="form-control" id="exampleInputEmail1" placeholder="Expiry Date" value="<?php echo set_value("expirydate"); ?>">
					 <?php echo form_error('expiry[]'); ?>
				  </div>
		  	</div>

		  	<div class="col-sm-3 col-sm-offset-1">
		  		 <div class="form-group <?php echo form_error('allfiles2') ? 'has-error' : '' ?>">
				    <label class="inner">Upload Specified Document</label>
				    <input type="file" name="allfiles2" class="form-control" id="exampleInputEmail1" value="<?php echo set_value("allfiles2"); ?>">
					 <?php echo form_error('allfiles2'); ?>
				  </div>
		  	</div>

		  	<div class="col-sm-4">&nbsp;</div>
		  	<div class="clearfix"></div>		
		  </div>


		   <div class="form-group <?php echo form_error('bio') ? 'has-error' : '' ?>">
		    <label for="inputPassword3" class="col-sm-2 control-label">Bio</label>
		    <div class="col-sm-10">
		      <textarea name="bio" class="form-control" id="inputPassword3" placeholder="Max 1000 characters" rows="4" value="<?php echo set_value("bio"); ?>"></textarea>
				<?php echo form_error('bio'); ?>
		    </div>
		  </div>


		   <div class="form-group <?php echo form_error('password') ? 'has-error' : '' ?>">
		    <label for="inputPassword3" class="col-sm-2 control-label">Password <span class="required">*</span></label>
		    <div class="col-sm-10">
		      <input type="password" name="password" class="form-control" id="inputPassword3">
				<?php echo form_error('password'); ?>
		    </div>
		  </div>

		   <div class="form-group <?php echo form_error('c_password') ? 'has-error' : '' ?>">
		    <label for="inputPassword3" class="col-sm-2 control-label">Confirm Password <span class="required">*</span></label>
		    <div class="col-sm-10">
		      <input type="password" name="c_password" class="form-control" id="inputPassword3">
				<?php echo form_error('c_password'); ?>
		    </div>
		  </div>
		 

		  
		  <div class="form-group">
		    <div class="col-sm-10">
		      <button type="submit" class="btn btn-default">Register</button>
		    </div>
		  </div>

		</form>
	   	<!--end of registration form-->
	  </div>
	</div>
</div>
 