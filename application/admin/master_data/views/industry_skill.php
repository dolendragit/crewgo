<link href="<?php echo base_url('assets/admin/select2/css/select2.css');?>" rel="stylesheet">
 <script src="<?php echo base_url('assets/admin/select2/js/select2.js');?>"></script>
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
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Industry <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           
                            <select name="industry_id" id="industry_id" class="form-control col-md-4 col-xs-4 select_industry" onchange="checkSelectedSkill(this.value)">
                                 <option value="">--Select Industry--</option>
                                 <?php                
                                 if($industries) {
                                     foreach($industries as $industry) {
                                 ?>
                                 <option value="<?php echo $industry->id;?>" <?php echo  set_select('industry_id',$industry->id,$industry->id == $sel_industry_id?true:false); ?>>
                                 <?php echo $industry->name;?>
                                 </option>
                                     <?php } }
                                 ?>
                               
                            </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-sm-3" for="last-name">Skill<span class="required">*</span>
                        </label>
                        <div class="col-sm-9">
                            
                            <select name="skill_id[]" id="skill_id" class="form-control col-md-4 col-xs-4" multiple="">
                                 <option value="">--Select Skill--</option>
                                 <?php
                                 if($skills) {
                                     foreach($skills as $skill) {
                                 ?>
                                 <option value="<?php echo $skill->id;?>" <?php echo  set_select('skill_id',$skill->id,$skill->id == in_array($skill->id,$industry_skill_id_array[$sel_industry_id])?true:false); ?>>
                                 <?php echo $skill->name;?>
                                 </option>
                                     <?php } }
                                 ?>
                               
                            </select>
                         
                        </div>
                      </div>
                     
                       
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <input type="hidden" name="hidden_id" id="hidden_id" value="<?php echo $industry->id;?>">
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
                          <th>Industry</th>  
                          <th>Skill</th>  
                          <th>Action</th>
                        </tr>
                      </thead>


                      <tbody>
                <?php
                if($industry_data_array)
                {
                    $s=1;
                    foreach($industry_data_array as $industry_id=>$industry_name)
                    {
                ?>
                    <tr>
                      <td><?php echo $s;?></td>
                      <td><?php echo $industry_name;?></td>
                      <td><?php echo implode(",",$industry_skill_name_array[$industry_id]);?></td>
                     <td>
                        <a href="<?php echo base_url('admin/master_data/mapping/industrySkill/edit').'/'.$industry_id;?>" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
                        <a href="<?php echo base_url('admin/master_data/mapping/industrySkill/remove').'/'.$industry_id;?>" title="Delete" onclick="return confirm('Do you really want to delete this data?')"><span class="glyphicon glyphicon-remove"></span></a>
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
       skill_id = $('#skill_id').val();
      industry_id = $('#industry_id').val();
      
      var invalid = [];
       if(industry_id==""){
           error_msg += "-- Industry is required.</br>";
            
           invalid.push('industry_id');
        } 
          if(!skill_id){
           error_msg += " -- Skill is required.</br>";
            invalid.push('skill_id');
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
    
    $('#skill_id').select2({
        
        placeholder: 'Select an option'
    });
 

$('#skill_id').on('select2:unselecting', function (evt) {
  remove_skill_id = evt.params.args.data.id;
  sel_industry_id = '<?php echo $sel_industry_id;?>';
  is_record_deleted =0;
  delete_msg = "";
  if(confirm('Do you really want to remove the data?') == true){
   $("#mydiv").show();
  $.ajax({
        async: false,
        url:'<?php echo base_url('admin/master_data/mapping/removeIndustrySkill');?>',
        type:'post',
        dataType: 'json',
        data:'industry_id='+sel_industry_id+'&skill_id='+remove_skill_id,
            success:function(data){
               if(data.status === 1){
                  is_record_deleted = 1;
                  delete_msg = data.msg;
                  
            }else{
                
                  is_record_deleted = 0; 
                  delete_msg = data.msg;
              }
        }
    });
    $("#mydiv").hide();
        
         if(is_record_deleted === 1){
             alert(delete_msg);
             return true;
             
         }else{
             alert(delete_msg);
             return false;
             
         }
  
  }else{
  return false;
      //nothing
  }
  
});

function checkSelectedSkill(industry_id){
   data_exist = 0;
    
       $.ajax({
        async: false,
        url:'<?php echo base_url('admin/master_data/mapping/checkIndustrySkill');?>',
        type:'post',
        dataType: 'json',
        data:'industry_id='+industry_id,
            success:function(data){
               if(data  === 1){
                 data_exist = 1;
            }else{
                data_exist = 0;
              }
        }
    }); 
      
   
    if(data_exist === 1){
      goto_url = "<?php echo base_url('admin/master_data/mapping/industrySkill/edit');?>/"+industry_id; 
      document.location = goto_url;
    }else{
        goto_url = "<?php echo base_url('admin/master_data/mapping/industrySkill/add');?>/"+industry_id; 
         document.location = goto_url;
    }
}
$('.select_industry').select2();
</script>