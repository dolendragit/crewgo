
        <!-- page content -->
        <div class="right_col" role="main">
          <!-- top tiles -->
         <div class="">
            <div class="page-title">
              <div class="title_left">
               <?php echo getPageActionTitle($page_url,$page_title,$page_action);?>
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
              
                <div class="x_panel">
                   
                  <div class="x_content">
                    <br />
                    <form id="frm_lhc_doc" name="frm_lhc_doc" data-parsley-validate class="form-horizontal form-label-left" onsubmit="return validateForm(this)" method="post">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                        <label class="control-label col-md-4 col-sm-3 col-xs-12 pull-left" for="country">Select a Country<span class="required">*</span>
                        </label>
                        <div class="col-md-5 col-sm-3 col-xs-3" id="master_data_status_<?php echo $lhc_edit->country;?>" style="margin-top:5px;">
                          
                            <select name="country" id="country" required="required" onchange="loadStates(this.value)" class="form-control col-md-4 col-xs-4">
                                <?php foreach($select_country as $country)
                                {
                                    $country_id=$country->id;
                                    $country_name=$country->name;
                                    
                                    ?>
                                <option value="">Select Country</option>
                                <option value="<?php echo $country_id;?>"
                                    <?php echo set_select('country',$country_name,$country_id==$lhc_edit->country_id?true:false); ?>>
                                    <?php echo $country_name;?>
                                </option>
                                 <?php }?>
                            </select>
                                                   
                        </div>
                      </div>
                              <div class="form-group">
                        <label class="control-label col-md-4 col-sm-3 col-xs-12" for="state">Select a State<span class="required">*</span>
                        </label>
                        <div class="col-md-5 col-sm-3 col-xs-3" id="master_data_status_<?php echo $lhc_edit->id;?>" style="margin-top:5px;">
                          
                            <select name="region" id="region" required="required" class="form-control col-md-4 col-xs-4">
                                <option value="">Select a State</option>
                                <?php foreach($select_region as $region)
                                {
                                    $region_id=$region->id;
                                    $region_name=$region->name;
                                    $region_short=$region->short_name;
                                    ?>
                                
                                <option value="<?php echo $region_id;?>"
                                    <?php echo  set_select('region',$region_id,$region_id==$lhc_edit->region_id?true:false); ?>>
                                    <?php echo $region_name;?>
                                </option>
                                 <?php }?>
                            </select>
                                                   
                        </div>
                      </div>
                              <div class="form-group">
                        <label class="control-label col-md-4 col-sm-3 col-xs-12" for="industry">Select Industry<span class="required">*</span>
                        </label>
                        <div class="col-md-5 col-sm-3 col-xs-3" id="master_data_status_<?php echo $lhc_edit->id;?>" style="margin-top:5px;">
                          
                            <select name="industry"  id="industry" class="form-control col-md-4 col-xs-4">
                                <option value="">Select an industry</option>
                                  <?php foreach($select_industry as $industry)
                                {
                                    $industry_id=$industry->id;
                                    $industry_name=$industry->name;
                                    ?>
                                
                                <option value="<?php echo $industry_id;?>"
                                    <?php echo  set_select('industry',$industry_id,$industry_id==$lhc_edit->industry_id?true:false); ?>>
                                    <?php echo $industry_name;?>
                                </option>
                                 <?php }?>
                            </select>
                                                   
                        </div>
                      </div>
                        </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <div class="form-group">

                        <div class="col-md-9 col-sm-6 col-xs-6">
                            <input type="text" id="name" name="name" placeholder="Name" value="<?php echo set_value('name',$lhc_edit->name);?>"   class="form-control col-md-7 col-xs-12" maxlength="300">
                        </div>
                      </div>
                              <div class="form-group">
                                          
                        <div class="col-md-9 col-sm-6 col-xs-2" id="master_data_status_<?php echo $lhc_edit->id;?>" style="margin-top:5px;">
                          
                            <input type="checkbox" name="status" id="status" value="1"
                                <?php echo set_checkbox('status', '1',$lhc_edit->show_in_registration==1?true:false); ?> />
                            <b>Show the document in registration form</b>
                                                   
                        </div>
                      </div>
                          <div class="form-group">
                         <div class="col-md-9 col-sm-6 col-xs-3" id="master_data_status_<?php echo $lhc_edit->id;?>" style="margin-top:5px;">
                          
                             <input type="checkbox" name="has_expiry_date" id="has_expiry-date" onchange="toggleInput(this)" value="1"
                                 <?php echo set_checkbox('has_expiry_date', '1',$lhc_edit->has_expiry_date==1?true:false); ?> />
                            <b>Expiry Date Needed</b>
                        </div>
                      </div>
                  
                           <div class="form-group">
                                          
                        <div class="col-md-9 col-sm-6 col-xs-2" id="master_data_status_<?php echo $lhc_edit->id;?>" style="margin-top:5px;">
                          
                            
                            <b>Warn expiry before <input type="text" onkeypress="return isNumber(event)" maxlength="3" name="warn_expiry_before" id="warn_expiry_before" min="1"
                                <?php if($lhc_edit->has_expiry_date==1) { ?>
                                     value="<?php echo $lhc_edit->warn_expiry_before;?>"
                                                         <?php } else {?>
                                                             disabled="disabled"
                                                         <?php }?>
                                /> (days)</b>
                                                   
                        </div>
                      </div>

                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-9 col-sm-6 col-xs-12">
                            <input type="hidden" name="hidden_id" id="hidden_id" value="<?php echo $lhc_edit->id;?>"/>
                        <input type="hidden" name="page_action" value="<?php echo $page_action;?>">
			<button type="submit" class="btn btn-success"><?php echo $page_action=='edit'?'Update':'Submit';?></button>
                          <button type="button" class="btn btn-primary" onclick="history.go(-1)">Cancel</button>
                          
                        </div>
                      </div>
                        </div>
                    </form>
                
                </div>
              </div>
            </div>
       		<!-- END of Add From-->
        <?php if($page_action!='edit'){?>
			<!-- Listing Page--->
			
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  
                  <div class="x_content">
                     
                    <table id="datatable" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>S.N.</th>
                          <th>Document Name</th>
                          <th>Country</th>
                            <th>State</th>
                            <th>Industry</th>
                          <th>Show/Hide in Form</th>
                          <th>Expiry Warning</th>
                          <th>Action</th>
                        </tr>
                      </thead>


                      <tbody>
                <?php
                if($lhc)
                {
                    $s=1;
                    foreach($lhc as $lhc_data)
                    {
                ?>
                    <tr>
                      <td><?php echo $s;?></td>
                      <td><?php echo $lhc_data->name;?></td>
                      <td><?php echo $lhc_data->country_name;?></td>
                      <td><?php echo $lhc_data->region_name;?></td>
                      <td><?php echo $lhc_data->industry_name;?></td>
                      <td id="master_data_status_<?php echo $lhc_data->id;?>">
                          
                        <?php
                          echo getStatusData($lhc_data->id,$lhc_data->status,'lhc_doc');
                         ?>
                         
                      </td>
                      <td><?php echo $lhc_data->warn_expiry_before;?> days</td>
                      <td>
                        <a href="<?php echo base_url('admin/master_data/lhc_doc/edit').'/'.$lhc_data->id;?>" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
                        <a href="<?php echo base_url('admin/master_data/lhc_doc/remove').'/'.$lhc_data->id;?>" title="Delete" onclick="return confirm('Do you really want to delete this data?')"><span class="glyphicon glyphicon-remove"></span></a>
                      </td>
                    </tr>
                <?php 
                        $s++;
                    }
                }else{
                ?>
                    <tr><td colspan="4">No Records</td></tr>
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
        </div>
		
<script>
 function validateForm(thisForm) {
     
       var error_msg = ""; 
       //fetch required field value
      name = $('input[name=name]').val();
      status = $('input[name=status]').val();
      warn_expiry_before = $('input[name=warn_expiry_before]').val();
      var invalid = [];
          if(name==""){
           error_msg += " -- Name is required.</br>";
            invalid.push('name');
       }
    
      
       if(status==""){
           error_msg += "-- Status is required.</br>";
            
           invalid.push('status');
        } 
        
        if(thisForm.has_expiry_date.checked){
             if(!warn_expiry_before)
             {
                error_msg += "-- Warn Before Expiry is required.</br>";
                invalid.push('warn_before_expiry');
             }
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
 function toggleInput(hasExpiry){
     var warn = document.getElementById("warn_expiry_before");
        if(hasExpiry.checked){
          warn.disabled=false;
          
     }
     else{
          warn.disabled=true;
     }
 }
 //TODO to dynamically populate select states options based on country selection
 function loadStates(countryId){
     //alert(countryId);
 }
 </script>