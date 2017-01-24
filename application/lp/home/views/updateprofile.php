<!-- page content -->
<div class="right_col" role="main">
   <!-- top tiles -->
   <div class="page-title">
    <h4>Update Profile</h4>
   </div>


   <div class="row" style="padding-top: 20px;">
     <?php $this->load->view('alert');?>
      <div class="panel panel-default">
         <div class="panel-heading">Update Your Profile</div>
         <div class="panel-body">
            <!--change password form-->
            <form method="post" action="<?php echo base_url('lp/home/updateprofile');?>">
             
              <div class="form-group  <?php echo form_error('industry') ? 'has-error' : '' ?>">
                <label for="exampleInputEmail1">Industry</label>
               <select multiple class="form-control" name="industry[]">
                     <?php  
                      if ($industry != ""):
                      foreach($industry as $key => $val): 
                     ?>
                      <option value="<?php echo $val->id;?>" <?php echo (in_array($val->id, $assignedIndustry) ? 'selected' : '') ;?>><?php echo $val->name;?></option>
                     <?php 
                      endforeach; 
                      endif;
                     ?>
                  </select>
                <?php echo form_error('industry[]'); ?>
              </div>
            
              <div class="form-group  <?php echo form_error('businessname') ? 'has-error' : '' ?>">
                <label for="exampleInputbusinessname1">Business Name</label>
                <input type="businessname" class="form-control" id="exampleInputbusinessname1" name="businessname" value="<?php echo $userDetail->name ;?>">
                <?php echo form_error('businessname'); ?>
              </div>


              <div class="form-group  <?php echo form_error('contactperson') ? 'has-error' : '' ?>">
                <label for="exampleInputcontactperson1">Billing(Contact) Person</label>
                <input type="text" class="form-control" id="exampleInputcontactperson1" name="contactperson" value="<?php echo $userDetail->contact_person;?>">
                <?php echo form_error('contactperson'); ?>
              </div>

            <div class="form-group  <?php echo form_error('phone_number') ? 'has-error' : '' ?>">
                <label for="exampleInputphone_number1">Contact Phone No.</label>
                <input type="text" class="form-control" id="exampleInputphone_number1" name="phone_number" value="<?php echo $userDetail->phone_number;?>">
                <?php echo form_error('phone_number'); ?>
              </div>

              <div class="form-group  <?php echo form_error('email') ? 'has-error' : '' ?>">
                <label for="exampleInputemail1">Email</label>
                <input type="text" class="form-control" id="exampleInputemail1" name="email" value="<?php echo $userDetail->email;?>">
                <?php echo form_error('email'); ?>
              </div>

               <div class="form-group  <?php echo form_error('address') ? 'has-error' : '' ?>">
                <label for="exampleInputaddress1">Address</label>
                <input type="address" class="form-control" id="exampleInputaddress1" name="address" value="<?php echo $userDetail->full_address;?>">
                <?php echo form_error('address'); ?>
              </div>

               <div class="form-group  <?php echo form_error('abn') ? 'has-error' : '' ?>">
                <label for="exampleInputabn1">ABN</label>
                <input type="abn" class="form-control" id="exampleInputabn1" name="abn" value="<?php echo $userDetail->abn;?>">
                <?php echo form_error('abn'); ?>
              </div>

               <div class="form-group col-sm-4  <?php echo form_error('profile_image') ? 'has-error' : '' ?>">
                <label for="exampleInputlogo1">Change Logo</label>
                <input type="file" name="profile_image" class="form-control" id="exampleInputlogo1">
                <?php echo form_error('profile_image'); ?>
              </div>

              <div class="form-group col-sm-4">
                <img src="<?php echo base_url('assets/uploads/'.$userDetail->profile_image);?>" class="img-thumbnail" style="max-width: 50px;">
              </div>

              <div class="clearfix"></div>

                <div class="form-group  <?php echo form_error('description') ? 'has-error' : '' ?>">
                <label for="exampleInputbio1">Bio</label>
                <textarea name="description" class="form-control"><?php echo $userDetail->description;?></textarea>
                <?php echo form_error('description'); ?>
              </div>


            
              <button type="submit" class="btn btn-default btn-sm">Update Profile</button>
            </form>
            <!--change password form-->
         </div>
      </div>
   </div>
</div>
<!-- /page content -->