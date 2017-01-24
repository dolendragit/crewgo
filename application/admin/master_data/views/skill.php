
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
                    <form id="frm_skill" name="frm_skill" data-parsley-validate class="form-horizontal form-label-left" onsubmit=" return validateForm()" method="post">

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Title <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="name" name="name" value="<?php echo set_value('name',@$skill->name);?>"   class="form-control col-md-7 col-xs-12" maxlength="300">
                        </div>
                      </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Description <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <textarea style="height:100px;" name="description" id="description" class="form-control col-md-7 col-xs-12" maxlength="450"><?php echo $skill->description;?></textarea>
                            </div>
                        </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Status<span class="required">*</span>
                        </label>
                        <div class="col-md-2 col-sm-2 col-xs-2" id="master_data_status_<?php echo $skill->id;?>" style="margin-top:5px;">
                            <?php if($page_action=='edit'){ ?>
                             
                              <?php echo  getStatusData($skill->id,$skill->status,'skill');?>
                              
                              <?php }else{ ?>
                            <select name="status" id="status" class="form-control col-md-4 col-xs-4">
                                
                                <option value="1" <?php echo  set_select('status', 1,$skill->status == '1'?true:true); ?>>Show</option>
                                 <option value="0" <?php echo  set_select('status', 0,$skill->status == '0'?true:false); ?>>Hide</option>
                            </select>
                              <?php } ?>
                         
                        </div>
                      </div>
                     
                       
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <input type="hidden" name="hidden_id" id="hidden_id" value="<?php echo $skill->id;?>">
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
                          <th>Title</th>
                            <th>Description</th>
                            <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>


                      <tbody>
                <?php
                if($skills)
                {
                    $s=1;
                    foreach($skills as $skills_data)
                    {
                ?>
                    <tr>
                      <td><?php echo $s;?></td>
                      <td><?php echo $skills_data->name;?></td>
                        <td><?php echo $skills_data->description;?></td>
                      <td id="master_data_status_<?php echo $skills_data->id;?>">
                          
                        <?php
                          echo getStatusData($skills_data->id,$skills_data->status,'skill');
                         ?>
                         
                      </td>

                      <td>
                        <a href="<?php echo base_url('admin/master_data/skill/edit').'/'.$skills_data->id;?>" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
                        <a href="<?php echo base_url('admin/master_data/skill/remove').'/'.$skills_data->id;?>" title="Delete" onclick="return confirm('Do you really want to delete this data?')"><span class="glyphicon glyphicon-remove"></span></a>
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
      name = $('input[name=name]').val();
      status = $('input[name=status]').val();
       
      var invalid = [];
          if(name==""){
           error_msg += " -- Title is required.</br>";
            invalid.push('name');
       }
      
       if(status==""){
           error_msg += "-- Status is required.</br>";
            
           invalid.push('status');
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