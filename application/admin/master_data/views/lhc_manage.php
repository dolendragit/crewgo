<!-- page content -->
<div class="right_col" role="main">
    <!-- top tiles -->
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <?php echo getPageActionTitle($page_url, $page_title, $page_action); ?>
            </div>
        </div>

        <div class="clearfix"></div>
        <?php displayMessages(); ?>
        <div class="clearfix"></div>

        <!--Add Form-->
        <?php if ($page_action == "add" || $page_action == "edit") { ?>
        <div id="form_errors" class="alert alert-danger fade in" style="display:none;">
            <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
            <div id="form_errors_msg">

            </div>
        </div>

        <?php if (!empty($form_error)) { ?>
            <div id="form_error" class="alert alert-danger fade in">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <div id="form_error_msg">
                    <?php echo $form_error; ?>
                </div>
            </div>
        <?php } ?>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">

                    <div class="x_content">
                        <br/>
                        <form class="form-horizontal" method="post"
                              action="<?php echo base_url("admin/master_data/lhc_manage"); ?>"
                              enctype="multipart/form-data" onsubmit="return validateForm(this)">

                            <div class="form-group <?php echo form_error('industry[]') ? 'has-error' : '' ?>">
                                <label for="industry[]" class="col-sm-2 control-label">Industry <span
                                            class="required">*</span></label>
                                <div class="col-md-3 col-sm-10">
                                    Hold CTRL to select multiple options:
                                    <select multiple name="industry[]" id="industry" class="form-control"
                                            required="required">
                                        <?php foreach ($select_industry as $industry_opt) {
                                            $industry_id = $industry_opt->id;
                                            $industry_name = $industry_opt->name;
                                            ?>


                                            <option value="<?php echo $industry_id; ?>" <?php echo set_select('industry[]', $industry_id, $industry_id == $selected_industry_id ? true : false); ?>>
                                                <?php echo $industry_name; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <?php echo form_error('industry[]'); ?>
                                </div>
                            </div>


                            <div class="form-group <?php echo form_error('business_name') ? 'has-error' : '' ?>">
                                <label for="business_name" class="col-sm-2 control-label">Business Name <span
                                            class="required">*</span></label>
                                <div class="col-sm-10 col-md-3">
                                    <input type="text" name="business_name" class="form-control" id="business_name"
                                           required="required" placeholder="Business Name"
                                           value="<?php echo set_value("business_name", $lhc_company_edit->name); ?>">
                                    <?php echo form_error('business_name'); ?>
                                </div>
                            </div>

                            <div class="form-group <?php echo form_error('contact_person') ? 'has-error' : '' ?>">
                                <label for="contact_person" class="col-sm-2 control-label">Billing (Contact) Person
                                    <span class="required">*</span></label>
                                <div class="col-sm-10 col-md-3">
                                    <input type="text" name="contact_person" class="form-control" required="required"
                                           id="contact_person" placeholder="Contact Person"
                                           value="<?php echo set_value("contact_person", $lhc_company_edit->contact_person); ?>">
                                    <?php echo form_error('contact_person'); ?>
                                </div>
                            </div>


                            <div class="form-group <?php echo form_error('contact_phoneno') ? 'has-error' : '' ?>">
                                <label for="contact_phoneno" class="col-sm-2 control-label">Contact Phone No. <span
                                            class="required">*</span></label>
                                <div class="col-sm-10 col-md-3">
                                    <input type="text" onkeypress="return isNumber(event)" name="contact_phoneno"
                                           class="form-control" id="contact_phoneno" required="required"
                                           placeholder="Contact Phone No." min="1"
                                           value="<?php echo set_value("contact_phoneno", $lhc_company_edit->phone_number); ?>">
                                    <?php echo form_error('contact_phoneno'); ?>
                                </div>
                            </div>

                            <div class="form-group <?php echo form_error('email') ? 'has-error' : '' ?>">
                                <label for="email" class="col-sm-2 control-label">Email <span class="required">*</span></label>
                                <div class="col-sm-10 col-md-3">
                                    <?php if ($page_action == 'edit') { ?>
                                        <input type="email" name="email" class="form-control" id="email"
                                               placeholder="Email" readonly
                                               value="<?php echo set_value("email", $lhc_company_edit->email); ?>">
                                    <?php } else { ?>
                                        <input type="email" name="email" class="form-control" id="email"
                                               placeholder="Email" required="required"
                                               value="<?php echo set_value("email", $lhc_company_edit->email); ?>">
                                    <?php } ?>
                                    <?php echo form_error('email'); ?>
                                </div>
                            </div>

                            <div class="form-group <?php echo form_error('address') ? 'has-error' : '' ?>">
                                <label for="address" class="col-sm-2 control-label">Address<span
                                            class="required">*</span></label>
                                <div class="col-sm-10 col-md-3">
                                    <input type="text" name="address" class="form-control" id="address"
                                           placeholder="Address" required="required"
                                           value="<?php echo set_value("address", $lhc_company_edit->full_address); ?>">
                                    <?php echo form_error('address'); ?>
                                </div>
                            </div>

                            <div class="form-group <?php echo form_error('abn') ? 'has-error' : '' ?>">
                                <label for="abn" class="col-sm-2 control-label">ABN<span
                                            class="required">*</span></label>
                                <div class="col-sm-10 col-md-3">
                                    <input type="text" name="abn" class="form-control" id="abn" placeholder="ABN"
                                           required="required"
                                           value="<?php echo set_value("abn", $lhc_company_edit->abn); ?>">
                                    <?php echo form_error('abn'); ?>
                                </div>
                            </div>

                            <div class="form-group <?php echo form_error('profile') ? 'has-error' : '' ?>">
                                <label for="profile" class="col-sm-2 control-label">Logo<span class="required">*</span></label>
                                <div class="col-sm-10 col-md-3">
                                    <input type="file" name="profile" id="profile" required="required"
                                           onchange="validateProfile(this)"
                                           value="<?php echo set_value("profile"); ?>">

                                    <?php echo form_error('profile'); ?>
                                </div>


                                <?php if ($page_action == 'edit') {
                                    $counter = 1;
                                    $url = base_url('assets/uploads/lp_doc');
                                    $profile_pic = $lhc_company_edit->profile_image;
                                    $profile_thumb = thumb($profile_pic); ?>
                                    <div class="col-sm-10 col-md-3">
                                        <label for="profile_thumb">Current Logo</label>
                                        <i class="fa fa-file-image-o"></i>
                                        <a href="<?php echo $url . "/" . $profile_pic ?>"><?php echo $profile_pic; ?></a>
                                        <img src="<?php echo $profile_thumb; ?>" name="profile_thumb"
                                             alt="Image Preview" width="50" height="50">
                                    </div>
                                <?php } ?>

                            </div>
                            <hr>
                            <!--Rates Range Design DON'T DELETE-->
                            <!--                      <div class="form-group">
                                                   <label for="rates_range" class="control-label">Rates Range</label>

                                                    <div class="row">

                                                          <div class="col-md-4 form-group">
                                                              <label>Skills</label>
                                                          </div>

                                                          <div class="col-md-3 form-group">
                                                              <label>Rate Range</label>
                                                          </div>

                                                          <div class="col-md-5 form-group">
                                                              <label>Area Serviced</label>
                                                          </div>
                                                    </div>

                                                    <div class="row">

                                                        <div class="col-md-4 form-group">
                                                            <select class="col-md-4">
                                                                  <option>Option 54</option>
                                                            </select>

                                                            <select class="col-md-4">
                                                                  <option class="col-md-4">Option 44</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <input type="text" placeholder="1-90" class="form-group">
                                                        </div>

                                                        <div class="col-md-5">
                                                            <input type="text" placeholder="City" class="form-group">
                                                        </div>
                                                      </div>

                                                    <div class="row">
                                                        <div class="col-md-4 form-group">
                                                            <select class="col-md-4 form-group">
                                                                  <option>Option 1</option>
                                                            </select>
                                                            <select class="col-md-4 form-group">
                                                                <option>Option 4</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-3 form-group">
                                                            <input type="text" placeholder="99-500" class="form-group">
                                                        </div>

                                                        <div class="col-md-5 form-group">
                                                            <input type="text" placeholder="Sub-urb" class="form-group">
                                                        </div>
                                                    </div>

                            </div>-->


                            <p class="strong">Please Upload the necessary documents <br> <span class="required">*</span>(pdf
                                / gif / jpg / png / jpeg) <br> <span class="required">*</span>File size should not
                                exceed 2MB</p>
                            <hr/>


                            <?php
                            foreach ($lhc_doc_list as $docs) {
                                if ($docs->status == 1) { ?>
                                    <div class="form-group ">
                                        <label class="mylabel"><?php echo $counter . ". " . $docs->name; ?></label><br/>

                                        <?php if ($docs->has_expiry_date == 1) {
                                            $expiry_id = "expiry_" . $docs->id;
                                            ?>
                                            <div class="col-sm-3 col-md-3 col-sm-offset-1">
                                                <div class="form-group <?php echo form_error('expiry') ? 'has-error' : '' ?>">
                                                    <label class="inner">Expiry date</label>
                                                    <input type="text" required="required"
                                                           name="<?php echo $expiry_id; ?>"
                                                           id="datepicker" class="form-control"
                                                           value="<?php echo $lhc_user_doc_expiry[$docs->id]; ?>">
                                                    <?php echo form_error('expiry[]'); ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="col-sm-3 col-md-3 col-sm-offset-1">
                                            <div class="form-group <?php $div_id = "allfiles" . $counter;
                                            $document_name = $lhc_user_doc[$docs->id];
                                            echo form_error($div_id) ? 'has-error' : '' ?>">
                                                <label class="inner">Upload Specified Document</label>
                                                <input type="hidden" name="doc_id[]" value="<?php echo $docs->id; ?>">
                                                <input type="file" name="<?php echo $div_id; ?>"
                                                       onchange="validateProfile(this,1)"
                                                       id="<?php echo $div_id; ?>" required="required"
                                                       value="<?php echo set_value($div_id, $document_name); ?>">
                                                <?php echo form_error($div_id); ?>
                                            </div>
                                        </div>
<!--                                        display the document thumbnail in case of edit-->
                                        <?php if ($page_action == 'edit') {
                                            $divId = "preview_div" . $counter; ?>
                                            <div id="<?php echo $divId; ?>" class="col-sm-3 col-md-3 col-sm-offset-1">
                                                <label for name="<?php echo $counter; ?>">Current Document : </label>
                                                <?php $thumb = thumb($document_name);
                                                if ($thumb == NULL) {
                                                    ?>
                                                    <i class="fa fa-file"></i>

                                                    <a href="<?php echo $url . "/" . $document_name ?>"><?php echo $document_name; ?></a>
                                                <?php } else { ?>
                                                    <i class="fa fa-file-image-o"></i>

                                                    <a href="<?php echo $url . "/" . $document_name ?>"><?php echo $document_name; ?></a>
                                                    <img src="<?php echo $thumb; ?>" name="<?php echo $counter; ?>"
                                                         alt="Image Preview" width="50" height="50">

                                                <?php } ?>
                                            </div>
                                        <?php } ?>


                                        <div class="col-sm-4">&nbsp;</div>
                                        <div class="clearfix"></div>
                                    </div>

                                    <?php $counter++;
                                }
                            } ?>
                            <hr>
                            <div class="form-group <?php echo form_error('bio') ? 'has-error' : '' ?>">
                                <div class="col-sm-10 col-md-6 col-sm-offset-1">
                                    <label for="bio" class="inner">Bio</label>
                                    <textarea name="bio" class="form-control" id="bio" placeholder="Max 1000 characters" rows="4"><?php echo set_value("bio", $lhc_company_edit->description); ?></textarea>
                                    <?php echo form_error('bio'); ?>
                                </div>
                            </div>

                            <hr>
                            <!-- EFT Payment Details Design DO NOT DELETE-->
                            <!-- <div class="form-group">
                        <label class="mylabel">Payment Details<span class="required">*</span></label><br/>
                        <div><label class="form-group">EFT Details</label></div>
    
    <div class="row">
                            <div class="col-md-6">
                              
                            <div class="form-group <?php //echo form_error('country_code') ? 'has-error' : '' ?>">
		    <label for="country_code" class="col-sm-2 col-md-6 control-label">Country Code <span class="required">*</span></label>
		    <div class="col-sm-10 col-md-6">
		      <input type="number" name="country_code" class="form-group" id="country_code" placeholder="+977" value="<?php echo set_value("country_code", $lhc_company_edit->name); ?>">
				<?php //echo form_error('country_code'); ?>
		    </div>
		  </div>

		   <div class="form-group <?php //echo form_error('account_name') ? 'has-error' : '' ?>">
		    <label for="account_name" class="col-sm-2 col-md-6 control-label">Account Name <span class="required">*</span></label>
		    <div class="col-sm-10 col-md-6">
		      <input type="text" name="account_name" class="form-group" id="account_name" placeholder="Jon Doe" value="<?php echo set_value("account_name", $lhc_company_edit->contact_person); ?>">
				<?php // echo form_error('account_name'); ?>
		    </div>
		  </div>


		   <div class="form-group <?php //echo form_error('account_number') ? 'has-error' : '' ?>">
		    <label for="account_number" class="col-sm-2 col-md-6 control-label">Account No. <span class="required">*</span></label>
		    <div class="col-sm-10 col-md-6">
		      <input type="number" name="account_number" class="form-group" id="account_number" placeholder="2017 2017" value="<?php echo set_value("account_number", $lhc_company_edit->phone_number); ?>">
				<?php //echo form_error('account_number'); ?>
		    </div>
		  </div>    
                  <div>&nbsp;&nbsp;&nbsp;&nbsp;</div>
                  <div class="form-group <?php //echo form_error('pay_period') ? 'has-error' : '' ?>">
		    <label for="pay_period" class="col-sm-2 col-md-6 control-label">Pay Period <span class="required">*</span></label>
		    <div class="col-sm-10 col-md-6">
                        <select name="pay_period" id="pay_period">
                            <option>Monthly</option>
                            <option>Weekly</option>
                            <option>Daily</option>
                            
				<?php //echo form_error('pay_period'); ?>
                            </select>
                
                    
                        On 
                      
                         <select name="pay_day" id="pay_day">
                            <option>Monday</option>
                            <option>Tuesday</option>
                            <option>Wednesday</option>
                            
				<?php //echo form_error('pay_day');?>
                            </select>
		    </div>
		  </div>
                            </div>
                                     <div class="col-md-6">
                              <div class="form-group <?php //echo form_error('bank') ? 'has-error' : '' ?>">
		    <label for="bank" class="col-sm-2 col-md-6 control-label">Bank <span class="required">*</span></label>
		    <div class="col-sm-10 col-md-6">
		      <input type="text" name="bank" class="form-group" id="bank" placeholder="National Bank" value="<?php echo set_value("bank", $lhc_company_edit->name); ?>">
				<?php //echo form_error('bank'); ?>
		    </div>
		  </div>

		   <div class="form-group <?php //echo form_error('swift_code') ? 'has-error' : '' ?>">
		    <label for="swift_code" class="col-sm-2 col-md-6 control-label">Swift Code <span class="required">*</span></label>
		    <div class="col-sm-10 col-md-6">
                        <input type="number" name="swift_code" class="form-group" id="swift_code" placeholder=" 142541" value="<?php echo set_value("swift_code", $lhc_company_edit->contact_person); ?>">
				<?php //echo form_error('swift_codee'); ?>
		    </div>
		  </div>


		   <div class="form-group <?php //echo form_error('bsb') ? 'has-error' : '' ?>">
		    <label for="bsb" class="col-sm-2 col-md-6 control-label">BSB <span class="required">*</span></label>
		    <div class="col-sm-10 col-md-6">
		      <input type="text" name="bsb" class="form-group" id="bsb" placeholder=" 1AVB42541" value="<?php echo set_value("bsb", $lhc_company_edit->phone_number); ?>">
				<?php //echo form_error('bsb'); ?>
		    </div>
		  </div>
		  	
		  	
		  		
                        </div>
                        
    </div>
</div>-->

                            <div class="ln_solid"></div>

                            <div class="form-group">
                                <div class="col-sm-10">
                                    <input type="hidden" name="page_action" value="<?php echo $page_action; ?>">
                                    <!--                        <input type="hidden" name="page_action" value="-->
                                    <?php //echo $page_action;?><!--">-->
                                    <input type="hidden" name="hidden_id" id="hidden_id"
                                           value="<?php echo $lhc_company_edit->user_id; ?>">

                                    <button type="submit"
                                            class="btn btn-success"><?php echo $page_action == 'edit' ? 'Update' : 'Register'; ?></button>
                                    <button type="button" class="btn btn-primary" onclick="history.go(-1)">Cancel
                                    </button>
                                </div>
                            </div>

                        </form>
                        <!--end of registration form-->
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- END of Add From-->
    <?php } else { ?>
        <!-- Listing Page--->

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">

                    <div class="x_content">

                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>S.N.</th>
                                <th>LHC Name</th>
                                <th>Industry</th>
                                <th>Address</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Subscription Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>


                            <tbody>
                            <?php
                            if ($lhc_companies) {
                                $s = 1;
                                foreach ($lhc_companies as $lhc_data) {
                                    $receivedDate = strtotime($lhc_data->entered_date);
                                    $date = date('Y-m-d', $receivedDate);
                                    ?>
                                    <tr>
                                        <td><?php echo $s; ?></td>
                                        <td><?php echo $lhc_data->name; ?></td>
                                        <td><?php echo $lhc_data->industry_name; ?></td>
                                        <td><?php echo $lhc_data->full_address; ?></td>
                                        <td><?php echo $lhc_data->phone_number; ?></td>
                                        <td><?php echo $lhc_data->email; ?></td>
                                        <td><?php echo $date; ?></td>
                                        <td id="master_data_status_<?php echo $lhc_data->user_id; ?>">

                                            <?php
                                            echo getStatusData($lhc_data->user_id, $lhc_data->active, 'lhc_manage');
                                            ?>

                                        </td>

                                        <td>
                                            <a href="<?php echo base_url('admin/master_data/lhc_manage/edit') . '/' . $lhc_data->user_id; ?>"
                                               title="Edit"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
                                            <a href="<?php echo base_url('admin/master_data/lhc_manage/remove') . '/' . $lhc_data->user_id; ?>"
                                               title="Delete"
                                               onclick="return confirm('Do you really want to delete this data?')"><span
                                                        class="glyphicon glyphicon-remove"></span></a>
                                        </td>
                                    </tr>
                                    <?php
                                    $s++;
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="4">No Records</td>
                                </tr>
                                <?php
                            }
                            ?>


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">

            $('#datatable').DataTable();

        </script>
    <?php } ?>
    <!--END of Listing Page-->


</div>
<!-- /page content -->


<script>
    function validateForm() {
        var error_msg = "";

        //validate the documents to be uploaded
        var input_file_id = [];
        var num = 1;
        //set the file_input field_id
        <?php
        for($i = 1;$i < $counter;$i++){
        ?>
        input_file_id.push('allfiles' + num);
        num++;
        <?php
        }
        ?>

        //validate the selected document size to be uploaded
        for (var i = 0; i < num - 1; i++) {
            var file = document.getElementById(input_file_id[i]);
            var file_detail = file.files[0];

            var file_size = file_detail.size;
            var file_name = file_detail.name;
            var file_type = file_detail.type;


            if (file_size > 2097152) {
                error_msg += file_name + " exceeds 2MB.";
            }

            var ext = file_type.match(/image|pdf/g);

            //if ext is null then error
            if (!ext) {
                error_msg += file_name + "file format is not allowed";
            }

        }


        //fetch required field value

        business_name = $('input[name=business_name]').val();
        contact_person = $('input[name=contact_person]').val();
        contact_phoneno = $('input[name=contact_phoneno]').val();
        email = $('input[name=email]').val();
        full_address = $('input[name=full_address]').val();
        abn = $('input[name=abn]').val();

        //password = $('input[name=password]').val();
        //c_password = $('input[name=c_password]').val();

        var invalid = [];


        if (!jQuery('#industry').val()) {
            error_msg += " -- Please select an industry.</br>";
            invalid.push('industry');
        }
        if (business_name == "") {
            error_msg += " -- Business Name is required.</br>";
            invalid.push('business_name');
        }

        if (contact_person == "") {
            error_msg += " -- Contact Person is required.</br>";
            invalid.push('contact_person');
        }
        if (contact_phoneno == "") {
            error_msg += " -- Contact Number is required.</br>";
            invalid.push('contact_phoneno');
        }
        if (email == "") {
            error_msg += " -- Email is required.</br>";
            invalid.push('email');
        }
//       if(password==""){
//           error_msg += " -- Password is required.</br>";
//            invalid.push('password');
//       }
//       if(c_password==""){
//           error_msg += " -- Confirm Password is required.</br>";
//            invalid.push('c_password');
//       }
//
//      if(password!=c_password){
//           error_msg += " -- Password and Confrim password do not match.</br>";
//            invalid.push('password');
//       }
        if (!jQuery('#profile').val()) {
            error_msg += " -- Please select a logo to upload.</br>";
            invalid.push('profile');
        }


        if (error_msg != "") {

            heading_error = "<div stlye='font-weight:bold;text-align:left;'>Please correct the following errors and try again:</div>";
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
    /**
     * validate each document upload field
     *
     * @param input
     * @param flag
     * @param counter
     */
    function validateProfile(input, flag, counter=0) {
        var file = input.files[0];
        var file_name = file.name;
        var file_size = file.size;
        var file_type = file.type;
        //var valid=true;
        if (file_size > 2097152) {
            alert(file_name + " exceeds 2MB.");
        }

        if (flag == 1) {
            var file_ext = file_type.match(/image|pdf/g);
            if (!file_ext) {
                alert("Please select a supported file type \n" + file_type + " is not supported");
                //valid = false;
            }
        }
        else {
            var file_ext = file_type.match(/image/g);
            if (!file_ext) {
                alert("Please select an image file \n" + file_type + " is not supported");
                //valid = false;
            }
        }
        //if you need to hide the current document
//        if(valid){
//            var previewDiv = 'preview_div'+counter;
//            document.getElementById(previewDiv).style.display='none';
//        }
    }

</script>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


<script>
    $(function () {
        $("#datepicker").datepicker({
            changeYear: true,
            changeMonth: true
        });
    });
</script>