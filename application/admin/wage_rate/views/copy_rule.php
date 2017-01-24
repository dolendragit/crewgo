 <form id="copy_wage_rate_rule" method="post" class="form-horizontal calender" role="form" onsubmit="return validateCopyRuleForm()">
          <div class="modal-body">
            <div   style="padding: 5px 20px;">
           
                  <div class="row">
                      
                       <div class="form-group">
                        
                                 <div class="col-sm-5">
                               <label class="control-label col-sm-4" for="Calendar">Subskill <span class="required"></span>
                                </label>
                                 <div class="col-sm-8">
                                   <select  name="sp_level_id" id="sp_level_id"   class="form-control">
                                       <option value="">All</option>
                                       <?php
                                       
                                       if($sub_skills){
                                           foreach($sub_skills as $sub_skill){
                                       ?>
                                        <option value="<?php echo $sub_skill->level_id;?>"  >
                                           <?php echo $sub_skill->level_name;?> </option>
                                       
                                           <?php } } ?>
                                   </select>
                                </div> 
                               
                               
                           </div>
                           <label class="control-label  col-sm-1 " for="OR">OR
                        </label>
                            
                            <div class="col-sm-6">
                               <label class="control-label col-sm-4" for="Skill">
                                   Skill<span class="required"></span>
                                </label>
                                 <div class="col-sm-8">
                                     
                                     <select  name="sp_skill_id" id="sp_skill_id"   class="form-control" >
                                       <option value="">All</option>
                                      <?php
                                     if($skills){
                                      foreach($skills as $skill){
                                     ?>
                                       <option value="<?php echo $skill->skill_id;?>" <?php echo set_select('sp_skill_id',$skill->skill_id,@$work_hour_wage_rate_rule->skill_id==$skill->skill_id?true:false); ?>>
                                           <?php echo $skill->skill_name;?> </option>
                                     <?php
                                      }
                                     }
                                     ?>
                                   </select>
                                </div> 
                               
                               
                           </div>
                       
                           
                            
                       </div>
                  </div>
                  
                  
                 
          </div>
              <div id="sp_form_errors" class="alert alert-danger fade in" style="display:none;">
	<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>		  
            <div id="sp_form_errors_msg"></div> 
        </div>
          <div class="modal-footer">
              <input type="hidden" name="srch_country_id" id="country_id" value="<?php echo @$this->input->post('country_id');?>"> 
              <input type="hidden" name="srch_region_id" id="country_id" value="<?php echo @$this->input->post('region_id');?>"> 
              <input type="hidden" name="srch_skill_id" id="country_id" value="<?php echo @$this->input->post('skill_id');?>"> 
              <input type="hidden" name="srch_level_id" id="country_id" value="<?php echo @$this->input->post('level_id');?>"> 
              <input type="hidden" name="hidden_id" id="hidden_id" value="<?php echo @$work_hour_wage_rate_rule->id;?>"> 
            <button type="button" class="btn btn-default antoclose" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary antosubmit"><?php echo @$work_hour_wage_rate_rule->id!=""?'Save Changes':'Save';?></button>
          </div>
        </div>
      </form> 

 

<script type="text/javascript">
      
   function validateCopyRuleForm() {
     
     var error_msg = ""; 
 
      sp_skill_id = $('#sp_skill_id').val();
      sp_level_id = $('#sp_level_id').val();
      
      var invalid = [];
      if(sp_skill_id=="" && sp_level_id==""){
            error_msg += " -- Skill or subskill is required.</br>";
            invalid.push('sp_skill_id');
        }
      
      
       if(error_msg!=""){
      
         heading_error ="<div stlye='font-weight:bold;text-align:left;'>Please correct the following errors and try again:</div>"; 
         heading_error += error_msg;
         $('#sp_form_errors_msg').html(heading_error);
         $('#sp_form_errors').show();
         //alert(heading_error);
         first_invalid = invalid[0];
        // $('input[name='+first_invalid+']').focus();
        $(window).scrollTop(20);
         
         return false;
       }
       $('#sp_form_errors').hide();
       $('#sp_form_errors_msg').html('');
       
          
        $.ajax({
        url:'<?php echo base_url('admin/wage_rate/copyWRRule');?>',
        type:'post',
        dataType: 'json',
        data: $('#copy_wage_rate_rule').serialize(),
            success:function(data){
                 
                if(data.status == '1'){
                    $("#special_day_rule_add_container .close").click();
                    document.getElementById('copy_wage_rate_rule').reset();
                     $("#mydiv").hide();//close
                     callWRContainer();
                     
             } else{
                error_msg += data.msg+"</br>";
                heading_error ="<div stlye='font-weight:bold;text-align:left;'>Please correct the following errors and try again:</div>"; 
                heading_error += error_msg;
                $('#sp_form_errors_msg').html('');
                $('#sp_form_errors_msg').html(heading_error);
                $('#sp_form_errors').show();
             }
            }
        });
        
       
       return false;
    }
    
   
    
    $("#sp_skill_id").change(function(){
       $("#sp_level_id").val('');
    });
     $("#sp_level_id").change(function(){
       $("#sp_skill_id").val('');
    });
    
    $('#myModalLabel').html('Copy Rule from <?php echo $skill_name.' -> '.$level_name;?>');
    
 </script>