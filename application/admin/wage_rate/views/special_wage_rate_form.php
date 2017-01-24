 <form id="special_rate" method="post" class="form-horizontal calender" role="form" onsubmit="return validateSPForm()">
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
                                   <select name="sp_region_id" id="sp_region_id" class="form-control" onchange="changeCalendarDay(this.value)"  >
                                 <option value="">--Select Region -- </option>
                             <?php if($regions) {
                                 foreach($regions as $region) {
                                 
                             ?>
                            <option value="<?php echo $region->id;?>" <?php echo  set_select('region_id',$region->id,@$selected_region_id== $region->id?true:false); ?>><?php echo $region->name;?></option>
                                 <?php  } } ?>
                            
                                </select>
                                </div> 
                               
                               
                           </div>
                             <div class="col-sm-4">
                               <label class="control-label col-sm-4" for="Calendar">Year <span class="required">*</span>
                                </label>
                                 <div class="col-sm-8">
                                     <select  name="sp_calendar_year" id="sp_calendar_year"   class="form-control" onchange="changeCalendarDay(this.value)">
                                         <option value="">Select Year</option>
                                        <?php
                                        if($years){
                                            foreach($years as $year){
                                         
                                                ?>
                                       <option value="<?php echo $year->year;?>" <?php echo set_select('sp_calendar_year',$year->year,@$special_wage_rate_rule->year == $year->year?true:false);?>><?php echo $year->year;?></option>
                                              
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
                               <label class="control-label col-sm-4" for="day">
                                   Day<span class="required">*</span>
                                </label>
                                 <div class="col-sm-8">
                                   <select  name="sp_calendar_id" id="sp_calendar_id"   class="form-control">
                                       <option value="">Select day</option>  
                                       <?php
                                       
                                       if($calendar_days){
                                           foreach($calendar_days as $calendar_day){
                                       ?>
                                        <option value="<?php echo $calendar_day->id;?>" <?php echo set_select('sp_calendar_id',$calendar_day->id,@$special_wage_rate_rule->calendar_id == $calendar_day->id?true:false); ?>>
                                           <?php echo $calendar_day->name;?> </option>
                                       
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
                                     <input type="text" name="sp_lp_charge" id="sp_lp_charge" class="form-control"  onkeypress="return isNumber(event)" maxlength="4" value="<?php echo @$special_wage_rate_rule->lhc_charge;?>">
                                   
                                </div> 
                               
                               
                           </div>
                            <div class="col-sm-4">
                               <label class="control-label col-sm-4" for="From">From <span class="required">*</span>
                                </label>
                                 <div class="col-sm-8">
                                      <input type="text" name="sp_time_from" id="sp_time_from" class="form-control" value="<?php echo @$special_wage_rate_rule->time_from;?>">
                                 
                                </div> 
                               
                               
                           </div>
                             <div class="col-sm-4">
                               <label class="control-label col-sm-4" for="Calendar">To <span class="required">*</span>
                                </label>
                                 <div class="col-sm-8">
                                     <input type="text" name="sp_time_to" id="sp_time_to" class="form-control" value="<?php echo @$special_wage_rate_rule->time_to;?>">
                                   
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
                                     <input type="text" name="customer_rule_name" id="customer_rule_name" class="form-control" value="<?php echo @$special_wage_rate_rule->customer_rule_name;?>">
                                    
                                </div> 
                               
                               
                           </div>
                       </div>
                      <div class="row">
                      
                       <div class="form-group">
                            <div class="col-sm-12">
                               <label class="control-label col-sm-3" for="Calendar">Rule name for LP <span class="required"></span>
                                </label>
                                 <div class="col-sm-9">
                                    <input type="text" name="lhc_rule_name" id="lhc_rule_name" class="form-control" value="<?php echo @$special_wage_rate_rule->lhc_rule_name;?>">
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
                                         <input type="text" name="sp_manual_rate" id="sp_manual_rate" class="form-control" onkeypress="return isNumber(event)" onchange="checkTimesOfShift()" maxlength="5" value="<?php echo @$special_wage_rate_rule->pay_rate=='0.00'?'':$special_wage_rate_rule->pay_rate;?>">

                                     </div> 
                                </div>
                              <label class="control-label col-sm-1  " for="Or"><span class="input-group-addon">or</span></label>
                                 <div class="col-sm-3">

                                     <label class="control-label col-sm-5" for="Time">Times <span class="required"> </span>
                                     </label>  
                                     <div class="col-sm-7">
                                         <input type="text" name="sp_times" id="sp_times" class="form-control" onkeypress="return isNumber(event)" onchange="checkManualRate()" maxlength="5" value="<?php echo @$special_wage_rate_rule->pay_times=='0.00'?'':$special_wage_rate_rule->pay_times;?>">

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
                                             <option value="<?php echo $stype->id;?>" <?php echo set_select('sp_shift_type_id',$stype->id,@$special_wage_rate_rule->shift_type_id==$stype->id?true:false);?>><?php echo $stype->name;?></option>
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
              <input type="hidden" name="hidden_id" id="hidden_id" value="<?php echo $this->input->post('load_wr')=='special_rate'? @$special_wage_rate_rule->id:"";?>"> 
            <button type="button" class="btn btn-default antoclose" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary antosubmit"><?php echo @$special_wage_rate_rule->id!="" && $this->input->post('load_wr')=='special_rate'?'Save Changes':'Save';?></button>
          </div>
        </div>
      </form>
