<form id="overtime_rate" method="post" class="form-horizontal calender" role="form" onsubmit="return validateOvertimeForm()">
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
                               <label class="control-label col-sm-4" for="Interval">Interval <span class="required">*</span>
                                </label>
                                 <div class="col-sm-8">
                                     <select  name="p_interval" id="p_interval"   class="form-control" onchange="changeCalendarDay(this.value)">
                                         <option value="">Select Interval</option>
                                        <?php
                                        if($intervals){
                                            foreach($intervals as $interval){
                                         
                                                ?>
                                       <option value="<?php echo $interval->id;?>" <?php echo set_select('p_interval',$interval->id,@$overtime_wage_rate_rule->interval_id == $interval->id?true:false);?>><?php echo $interval->name;?></option>
                                              
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
                                       <option value="<?php echo $skill->skill_id;?>" <?php echo set_select('sp_skill_id',$skill->skill_id,@$skill_id==$skill->skill_id?true:false); ?>>
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
                               <label class="control-label col-sm-4" for="day">
                                   Day<span class="required">*</span>
                                </label>
                                 <div class="col-sm-8">
                                   <select  name="p_day" id="p_day"   class="form-control">
                                       
                                       <?php
                                      
                                       if($days){
                                           foreach($days as $day){
                                       ?>
                                        <option value="<?php echo $day->day_id;?>" <?php echo set_select('p_day',$day->day_id,@$overtime_wage_rate_rule->day_id == $day->day_id?true:false); ?>>
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
                               <label class="control-label col-sm-4" for="Calendar">
                                   LP charge(%)<span class="required">*</span>
                                </label>
                                 <div class="col-sm-8">
                                     <input type="text" name="sp_lp_charge" id="sp_lp_charge" class="form-control"  onkeypress="return isNumber(event)" maxlength="4" value="<?php echo @$overtime_wage_rate_rule->lhc_charge;?>">
                                   
                                </div> 
                               
                               
                           </div>
                            <div class="col-sm-4">
                               <label class="control-label col-sm-6" for="From">Threshold hours <span class="required">*</span>
                                </label>
                                 <div class="col-sm-6">
                                     <input type="text" name="threshold_hour" id="threshold_hour" maxlength="3" class="form-control" onkeypress="return isNumber(event)" value="<?php echo @$overtime_wage_rate_rule->threshold_hour;?>">
                                 
                                </div> 
                               
                               
                           </div>
                             <div class="col-sm-4">
                               <label class="control-label col-sm-7" for="applicable_for_all">Apply to all hours <span class="required"></span>
                                </label>
                                 <div class="col-sm-5">
                                    
                                     <input type="checkbox" name="applicable_for_all" id="applicable_for_all" class="form-control" value="1" <?php echo @$overtime_wage_rate_rule->applicable_for_all==1?'checked':'';?>>
                                   
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
                                     <input type="text" name="customer_rule_name" id="customer_rule_name" class="form-control" value="<?php echo @$overtime_wage_rate_rule->customer_rule_name;?>">
                                    
                                </div> 
                               
                               
                           </div>
                       </div>
                      <div class="row">
                      
                       <div class="form-group">
                            <div class="col-sm-12">
                               <label class="control-label col-sm-3" for="Calendar">Rule name for LP <span class="required"></span>
                                </label>
                                 <div class="col-sm-9">
                                    <input type="text" name="lhc_rule_name" id="lhc_rule_name" class="form-control" value="<?php echo @$overtime_wage_rate_rule->lhc_rule_name;?>">
                                </div> 
                               
                               
                           </div>
                           
                            
                       </div>
                  </div>
                   
                  <div class="row">
                               <h2> Pay Rate</h2>  
                  </div>
                  <div class="row">
                      
                       <div class="form-group">
                        
                                <div class="col-sm-3">
                                  <label class="control-label col-sm-5" for="Calendar">Manual  <span class="required"> </span>
                                     </label>  
                                     <div class="col-sm-7">
                                         <input type="text" name="sp_manual_rate" id="sp_manual_rate" class="form-control" onkeypress="return isNumber(event)" onchange="checkTimesOfShift()" maxlength="5" value="<?php echo @$overtime_wage_rate_rule->pay_rate=='0.00'?'':$overtime_wage_rate_rule->pay_rate;?>">

                                     </div> 
                                </div>
                              <label class="control-label col-sm-1  " for="Or"><span class="input-group-addon">or</span></label>
                                 <div class="col-sm-3">

                                     <label class="control-label col-sm-5" for="Time">Times <span class="required"> </span>
                                     </label>  
                                     <div class="col-sm-7">
                                         <input type="text" name="sp_times" id="sp_times" class="form-control" onkeypress="return isNumber(event)" onchange="checkManualRate()" maxlength="5" value="<?php echo @$overtime_wage_rate_rule->pay_times=='0.00'?'':$overtime_wage_rate_rule->pay_times;?>">

                                     </div>


                                </div>
                              <label class="control-label col-sm-1  " for="Or"><span class="input-group-addon">of</span></label>
                             <div class="col-sm-4">

                                     <label class="control-label col-sm-5" for="Shift Type">Shift Type <span class="required"></span>
                                     </label>  
                                     <div class="col-sm-7">
                                         <select name="sp_shift_type_id" id="sp_shift_type_id" class="form-control">
                                             <option value="">Select Shift Type</option>
                                             <?php
                                             if($shift_type){
                                                 foreach($shift_type as $stype){
                                             ?>
                                             <option value="<?php echo $stype->id;?>" <?php echo set_select('sp_shift_type_id',$stype->id,@$overtime_wage_rate_rule->shift_type_id==$stype->id?true:false);?>><?php echo $stype->name;?></option>
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
          </div>
              <div id="sp_form_errors" class="alert alert-danger fade in" style="display:none;">
	<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>		  
            <div id="sp_form_errors_msg"></div> 
        </div>
          <div class="modal-footer">
              <input type="hidden" name="hidden_id" id="hidden_id" value="<?php echo $this->input->post('load_wr')=='overtime_rate'?@$overtime_wage_rate_rule->id:"";?>"> 
            <button type="button" class="btn btn-default antoclose" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary antosubmit"><?php echo @$overtime_wage_rate_rule->id!="" && $this->input->post('load_wr')=='overtime_rate'?'Save Changes':'Save';?></button>
          </div>
        </div>
      </form> 

 

<script type="text/javascript">
      
   function validateOvertimeForm() {
     
     var error_msg = ""; 
       //fetch required field value
      sp_country_id = $('input[name=sp_country_id]').val();
      sp_region_id = $('#sp_region_id').val();
      p_interval = $('#p_interval').val();
      threshold_hour = $('#threshold_hour').val();
      sp_skill_id = $('#sp_skill_id').val();
      sp_level_id = $('#sp_level_id').val();
      sp_calendar_id = $('#sp_calendar_id').val();
      sp_time_from = $('input[name=sp_time_from]').val();
      sp_time_to = $('input[name=sp_time_to]').val();
      sp_lp_charge = $('input[name=sp_lp_charge]').val();
      sp_times = $('#sp_times').val();
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
       if(p_interval==""){
           error_msg += " -- Interval is required.</br>";
            invalid.push('p_interval');
       }
      if(threshold_hour==""){
           error_msg += " -- Threshold is required.</br>";
            invalid.push('threshold_hour');
       }
       
       
     /* if(sp_skill_id==""){
           error_msg += " -- Skill is required.</br>";
            invalid.push('sp_skill_id');
       }
       if(sp_level_id==""){
           error_msg += " -- Level is required.</br>";
            invalid.push('sp_level_id');
       }*/
       
      
      if(sp_times == "" && sp_manual_rate==""){
            error_msg += " -- Pay Rate is required.</br>";
            invalid.push('sp_time_to');
        }
      //Manual and times of validity
      
      if(sp_times != "" && sp_shift_type==""){
            error_msg += " -- Shift Type is required.</br>";
            invalid.push('sp_time_to');
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
        url:'<?php echo base_url('admin/wage_rate/saveOvertimeRate');?>',
        type:'post',
        dataType: 'json',
        data: $('#overtime_rate').serialize(),
            success:function(data){
                 
                if(data.status == '1'){
                    $("#special_day_rule_add_container .close").click();
                    document.getElementById('overtime_rate').reset();
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
    
    
    
 </script>