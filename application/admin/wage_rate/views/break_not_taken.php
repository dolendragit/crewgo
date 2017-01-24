<form id="breaks_not_traken" method="post" class="form-horizontal calender" role="form" onsubmit="return validateBreaksNotTakenForm()">
          <div class="modal-body">
            <div   style="padding: 5px 20px;">
               
                  
                  <div class="row">
                      
                       <div class="form-group">
                        
                           <div class="col-sm-4">
                               <label class="control-label col-sm-4" for="Country">
                                   Country<span class="required">*</span>
                                </label>
                                 <div class="col-sm-8">
                                    <select name="sp_country_id" id="sp_country_id" class="form-control">
                                    <?php if($countries) {
                                        foreach($countries as $country) {

                                    ?>
                                   <option value="<?php echo $country->id;?>" <?php echo  set_select('country_id',$country->id,@$selected_counry_id == $country->id?true:false); ?>><?php echo $country->name;?></option>
                                        <?php  } } ?>

                               </select>
                                </div> 
                               
                               
                           </div>
                            <div class="col-sm-4">
                               <label class="control-label col-sm-4" for="State">State <span class="required">*</span>
                                </label>
                                 <div class="col-sm-8">
                                   <select name="sp_region_id" id="sp_region_id" class="form-control"  >
                                 <option value="">--Select Region -- </option>
                             <?php if($regions) {
                                 foreach($regions as $region) {
                                 
                             ?>
                            <option value="<?php echo $region->id;?>" <?php echo  set_select('region_id',$region->id,@$selected_region_id == $region->id?true:false); ?>><?php echo $region->name;?></option>
                                 <?php  } } ?>
                            
                                </select>
                                </div> 
                               
                               
                           </div>
                             <div class="col-sm-4">
                               <label class="control-label col-sm-4" for="Interval">Day <span class="required">*</span>
                                </label>
                                 
                               
                                 <div class="col-sm-8">
                                   <select  name="p_day" id="p_day"   class="form-control">
                                       
                                       <?php
                                       
                                       if($days){
                                           foreach($days as $day){
                                       ?>
                                        <option value="<?php echo $day->id;?>" <?php echo set_select('p_day',$day->id,@$breaks_not_taken_wage_rate_rule->day_id == $day->id?true:false); ?>>
                                           <?php echo $day->name;?> </option>
                                       
                                           <?php } } ?>
                                   </select>
                                </div> 
                               
                        
                               
                           </div>
                           
                            
                       </div>
                  </div>
                  
                  
                  
                  <div class="row">
                      
                       <div class="form-group">
                        
                           <div class="col-sm-4">
                               <label class="control-label col-sm-4" for="Skill">
                                   Skill<span class="required"></span>
                                </label>
                                 <div class="col-sm-8">
                                     
                                     <select  name="sp_skill_id" id="sp_skill_id"   class="form-control" onchange="changeSubSkill(this.value,'sp_level_id')">
                                       <option value="">All</option>
                                      <?php
                                     if($skills){
                                      foreach($skills as $skill){
                                     ?>
                                       <option value="<?php echo $skill->skill_id;?>" <?php echo set_select('sp_skill_id',$skill->skill_id,@$selected_skill_id==$skill->skill_id?true:false); ?>>
                                           <?php echo $skill->skill_name;?> </option>
                                     <?php
                                      }
                                     }
                                     ?>
                                   </select>
                                </div> 
                               
                               
                           </div>
                            <div class="col-sm-4">
                               <label class="control-label col-sm-4" for="Calendar">Subskill <span class="required"></span>
                                </label>
                                 <div class="col-sm-8">
                                   <select  name="sp_level_id" id="sp_level_id"   class="form-control">
                                       <option value="">All</option>
                                       <?php
                                       
                                       if($sub_skills){
                                           foreach($sub_skills as $sub_skill){
                                       ?>
                                        <option value="<?php echo $sub_skill->level_id;?>" <?php echo set_select('sp_level_id',$sub_skill->level_id,@$selected_level_id == $sub_skill->level_id?true:false); ?>>
                                           <?php echo $sub_skill->level_name;?> </option>
                                       
                                           <?php } } ?>
                                   </select>
                                </div> 
                               
                               
                           </div>
                                <div class="col-sm-4">

                                     <label class="control-label col-sm-5" for="Shift Type">Shift Type <span class="required">*</span>
                                     </label>  
                                     <div class="col-sm-7">
                                         <select name="sp_shift_type_id" id="sp_shift_type_id" class="form-control" onchange="changeShiftTime(this.value)">
                                             <option value="">Select Shift Type</option>
                                             <?php
                                             if($shift_type){
                                                 foreach($shift_type as $stype){
                                             ?>
                                             <option value="<?php echo $stype->id;?>" <?php echo set_select('sp_shift_type_id',$stype->id,@$breaks_not_taken_wage_rate_rule->shift_type_id==$stype->id?true:false);?>><?php echo $stype->name;?></option>
                                             <?php
                                                 }
                                             }
                                             ?>
                                         </select>

                                     </div>


                                </div>
                           
                            
                       </div>
                  </div>
                  
                  
                  <div class="row">
                      
                       <div class="form-group">
                        
                            <div class="col-sm-4">
                               <label class="control-label col-sm-4" for="Calendar">
                                   LP charge(%)<span class="required">*</span>
                                </label>
                                 <div class="col-sm-8">
                                     <input type="text" name="sp_lp_charge" id="sp_lp_charge" class="form-control"  onkeypress="return isNumber(event)" maxlength="4" value="<?php echo @$breaks_not_taken_wage_rate_rule->lhc_charge;?>">
                                   
                                </div> 
                               
                               
                           </div>
                            <div class="col-sm-4">
                               <label class="control-label col-sm-6" for="From"> From-To :    <span class="required"></span>
                                </label>
                                <div class="col-sm-6" id="shift_from_to" style="margin-top:10px;">
                                    <?php if($breaks_not_taken_wage_rate_rule) { 
                                      echo  $this->model_master_data->formatTime($breaks_not_taken_wage_rate_rule->time_from).'-'.$this->model_master_data->formatTime($breaks_not_taken_wage_rate_rule->time_to); 
                                        
                                     } ?>
                                </div> 
                               
                               
                           </div>
                             <div class="col-sm-4">
                               <label class="control-label col-sm-7" for="Pay Rate">Pay Rate <span class="required">*</span>
                                </label>
                                 <div class="col-sm-5">
                                    
                                      <input type="text" name="sp_manual_rate" id="sp_manual_rate" class="form-control" onkeypress="return isNumber(event)"   maxlength="5" value="<?php echo @$breaks_not_taken_wage_rate_rule->pay_rate=='0.00'?'':$breaks_not_taken_wage_rate_rule->pay_rate;?>">
                                   
                                </div> 
                               
                               
                           </div>
                           
                           
                            
                       </div>
                  </div>
                  
                  
                  <div class="row">
                      
                       <div class="form-group">
                        
                           <div class="col-sm-12">
                               <label class="control-label col-sm-3" for="Calendar">
                                   Rule name for customer<span class="required"></span>
                                </label>
                                 <div class="col-sm-9">
                                     <input type="text" name="customer_rule_name" id="customer_rule_name" class="form-control" value="<?php echo @$breaks_not_taken_wage_rate_rule->customer_rule_name;?>">
                                    
                                </div> 
                               
                               
                           </div>
                       </div>
                      <div class="row">
                      
                       <div class="form-group">
                            <div class="col-sm-12">
                               <label class="control-label col-sm-3" for="Calendar">Rule name for LP <span class="required"></span>
                                </label>
                                 <div class="col-sm-9">
                                    <input type="text" name="lhc_rule_name" id="lhc_rule_name" class="form-control" value="<?php echo @$breaks_not_taken_wage_rate_rule->lhc_rule_name;?>">
                                </div> 
                               
                               
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
              <input type="hidden" name="hidden_id" id="hidden_id" value="<?php  echo $this->input->post('load_wr')=='breaks_not_taken'?@$breaks_not_taken_wage_rate_rule->id:"";?>"> 
            <button type="button" class="btn btn-default antoclose" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary antosubmit"><?php echo @$breaks_not_taken_wage_rate_rule->id!="" && $this->input->post('load_wr')=='breaks_not_taken'?'Save Changes':'Save';?></button>
          </div>
        </div>
      </form> 

 

<script type="text/javascript">
      
   function validateBreaksNotTakenForm() {
     
     var error_msg = ""; 
       //fetch required field value
      sp_country_id = $('input[name=sp_country_id]').val();
      sp_region_id = $('#sp_region_id').val();
      sp_skill_id = $('#sp_skill_id').val();
      sp_level_id = $('#sp_level_id').val();
      sp_lp_charge = $('input[name=sp_lp_charge]').val();
      sp_shift_type = $('#sp_shift_type_id').val();
      sp_manual_rate = $('#sp_manual_rate').val();
       
      var invalid = [];
          if(sp_country_id==""){
           error_msg += " -- Country is required.</br>";
            invalid.push('sp_country_id');
       }
       if(sp_region_id==""){
           error_msg += " -- State is required.</br>";
            invalid.push('sp_region_id');
       }
      
       if(sp_shift_type==""){
           error_msg += " -- Shift Type is required.</br>";
            invalid.push('sp_shift_type');
       }
        if(sp_lp_charge==""){
           error_msg += " -- LP charge is required.</br>";
            invalid.push('sp_lp_charge');
       }
       
      
      if(sp_manual_rate==""){
            error_msg += " -- Pay Rate is required.</br>";
            invalid.push('sp_manual_rate');
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
        url:'<?php echo base_url('admin/wage_rate/saveBreakNotTaken');?>',
        type:'post',
        dataType: 'json',
        data: $('#breaks_not_traken').serialize(),
            success:function(data){
                 
                if(data.status == '1'){
                    $("#special_day_rule_add_container .close").click();
                    document.getElementById('breaks_not_traken').reset();
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
    
    function changeShiftTime(shift_type){
        $.ajax({
        url:'<?php echo base_url('admin/wage_rate/getShiftDetail');?>',
        type:'post',
        dataType: 'json',
        data: 'shift_id='+$('#sp_shift_type_id').val(),
            success:function(data){
                 
                if(data.status == '1'){
                     
                     $("#shift_from_to").html(data.msg);//close
                     
                     
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
    }
    
    
    
 </script>