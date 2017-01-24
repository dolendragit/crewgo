
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
        <?php if($page_action == "add" || $page_action == "edit") { ?>
        <div id="form_errors" class="alert alert-danger fade in" style="display:none;">
	<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>		  
            <div id="form_errors_msg"></div> 
        </div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                   
                  <div class="x_content">
                    <br />
                    <form id="frm_skill" name="frm_region" data-parsley-validate class="form-horizontal form-label-left" onsubmit=" return validateForm()" method="post">

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="select_country">Select Country<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select name="select_country" id="select_country" class="form-control" required="required">
                                 <option value="">Select Country</option>
                                <?php foreach($select_country as $country_opt)
                                {   $country_id=$country_opt->id;
                                    $country_name=$country_opt->name;
                                ?>
                                
                                
                                <option value="<?php echo $country_id;?>"
                                    <?php echo set_select('select_country',$country_id,$country_id==$state_edit->country_id?true:false); ?>>
                                    <?php echo $country_name;?>
                                </option>
                                  <?php }?> 
                            </select>
                        </div>
                      </div>
                        
                         <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="state_name">State<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" required="required" id="state_name" name="state_name" value="<?php echo set_value('state_name',$state_edit->name);?>"   class="form-control col-md-7 col-xs-12" maxlength="300">
                            
                        </div>
                      </div>
                        
                           <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="short_name">State Short Name<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" required="required" id="state_name" name="short_name" value="<?php echo set_value('short_name',$state_edit->short_name);?>"   class="form-control col-md-7 col-xs-12" maxlength="300">
                            
                        </div>
                      </div>
<!--                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Status<span class="required">*</span>
                        </label>
                        <div class="col-md-2 col-sm-2 col-xs-2" id="master_data_status_<?php //echo $skill->id;?>" style="margin-top:5px;">
                            <?php //if($page_action=='edit'){ ?>
                             
                              <?php //echo  getStatusData($skill->id,$skill->status,'skill');?>
                              
                              <?php //}else{ ?>
                            <select name="status" id="status" class="form-control col-md-4 col-xs-4">
                                
                                <option value="1" <?php //echo  set_select('status', 1,$skill->status == '1'?true:true); ?>>Show</option>
                                 <option value="0" <?php //echo  set_select('status', 0,$skill->status == '0'?true:false); ?>>Hide</option>
                            </select>
                              <?php //} ?>
                         
                        </div>
                      </div>-->
                     
                       
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <input type="hidden" name="hidden_id" id="hidden_id" value="<?php echo $state_edit->id;?>">
                        <input type="hidden" name="page_action" value="<?php echo $page_action;?>">
			<button type="submit" class="btn btn-success"><?php echo $page_action=='edit'?'Update':'Submit';?></button>
                          <button type="button" class="btn btn-primary" onclick="history.go(-1)">Cancel</button>
                          
                        </div>
                      </div>

                    </form>
                  </div>
                </div>
              </div>
            </div>
			
			
			<!-- END of Add From-->
			<?php }else{?>
			<!-- Listing Page--->
			
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  
                  <div class="x_content">
                     
                    <table id="datatable" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>S.N.</th>
                          <th>Country</th>
                          <th>State</th>
                          <th>Short Name</th>
                          <th>Action</th>
                        </tr>
                      </thead>


                      <tbody>
                <?php
                if($states)
                {
                    $s=1;
                    foreach($states as $lhc_data)
                    {
                ?>
                    <tr>
                      <td><?php echo $s;?></td>
                      <td><?php echo $lhc_data->country_name;?></td>
                      <td><?php echo $lhc_data->name;?></td> 
                      <td><?php echo $lhc_data->short_name;?></td> 
                      <td>
                        <a href="<?php echo base_url('admin/master_data/manage_region/edit').'/'.$lhc_data->id;?>" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
                        <a href="<?php echo base_url('admin/master_data/manage_region/remove').'/'.$lhc_data->id;?>" title="Delete" onclick="return confirm('Do you really want to delete this data?')"><span class="glyphicon glyphicon-remove"></span></a>
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
        <!-- /page content -->
        
        
        <script>
 function validateForm() {
     
       var error_msg = ""; 
       //fetch required field value
      state_name = $('input[name=state_name]').val();
      short_name = $('input[name=short_name]').val();
       
      var invalid = [];
          if(state_name==""){
           error_msg += " -- State Name is required.</br>";
            invalid.push('state_name');
       }
       
        var invalid = [];
          if(short_name==""){
           error_msg += " -- Short Name is required.</br>";
            invalid.push('short_name');
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