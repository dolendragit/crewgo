<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script> 

        <!-- page content -->
        <div class="right_col" role="main">
          <!-- top tiles -->
         <div class="">
            <div class="page-title">
              <div class="title_left">
                  <h3><?php echo  $page_title;//echo getPageActionTitle($page_url,$page_title,$page_action);?></h3>
                 
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
                    <form id="frm_ppe" name="frm_ppe" data-parsley-validate class="form-horizontal form-label-left" onsubmit=" return validateForm()" method="post">

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Title <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="name" name="name" value="<?php echo set_value('name',@$priority->name);?>"   class="form-control col-md-7 col-xs-12" maxlength="300">
                        </div>
                      </div>
                       
                        <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Score <span class="required">*</span>
                        </label>
                        <div class="col-md-2 col-sm-2 col-xs-2">
                            <input type="text" id="score" name="score" onkeypress="return isNumber(event)" value="<?php echo set_value('score',@$priority->score);?>"
                                   class="form-control col-md-7 col-xs-12" maxlength="2">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Status <span class="required">*</span>
                        </label>
                        <div class="col-md-2 col-sm-2 col-xs-2" id="master_data_status_<?php echo $priority->id;?>" style="margin-top:5px;">
                            <?php if($page_action=='edit'){ ?>
                             
                              <?php echo  getStatusData($priority->id,$priority->status,'priority');?>
                              
                              <?php }else{ ?>
                            <select name="status" id="status" class="form-control col-md-4 col-xs-4">
                                
                                <option value="1" <?php echo  set_select('status', 1,@$priority->status == '1'?true:true); ?>>Show</option>
                                 <option value="0" <?php echo  set_select('status', 0,@$priority->status == '0'?true:false); ?>>Hide</option>
                            </select>
                              <?php } ?>
                        </div>
                      </div>
                        
                        <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Is mandatory <span class="required"></span>
                        </label>
                        <div class="checkbox">
                         &nbsp;  <label> <input type="checkbox" id="is_mandatory" name="is_mandatory"   value="1"  <?php echo  set_checkbox('is_mandatory', 1,@$priority->is_mandatory == '1'?true:false); ?>>
                               &nbsp; Yes</span> </label>
                        </div>
                      </div>
                     
                       
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                         
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
                <div class="panel-group">
                    <form id="wage_rate_search" method="post" class="form-horizontal calender" role="form">
                   <div class="panel panel-default">
                       <div class="panel-heading">
                           <span class="tools pull-right">
                        <a href="javascript:;" class="fa fa-chevron-down"></a>
                    </span>
                           <h2><b>Search By</b></h2></div>
                       <div class="panel-body"> 
                    
                   <!--  SEARCH SECTION -->        
                      <div class="form-group">
                        
                     <div class="col-sm-4">
                        <div class="row">
                         <label class="control-label col-sm-3" for="Country">Country <span class="required">*</span>
                        </label> 
                            <div class="col-sm-9">
                                <select name="sp_country_id" id="sp_country_id" class="form-control">
                                    <?php if($countries) {
                                        foreach($countries as $country) {

                                    ?>
                                   <option value="<?php echo $country->id;?>" <?php echo  set_select('country_id',$country->id,@$sociable_hour->country_id == $country->id?true:false); ?>><?php echo $country->name;?></option>
                                        <?php  } } ?>

                               </select>
                            </div>
                      </div>
                        <br/>    
                       <div class="row">
                         <label class="control-label col-sm-3" for="Region">State <span class="required"> </span>
                        </label> 
                            <div class="col-sm-9">
                                <select name="region_id" id="region_id" class="form-control" onchange="changeSearchCalendarDay(this.value)"   >
                                 <option value="">--Select Region -- </option>
                             <?php if($regions) {
                                 foreach($regions as $region) {
                                 
                             ?>
                            <option value="<?php echo $region->id;?>" <?php echo  set_select('region_id',$region->id,@$this->input->post('region_id') == $region->id?true:false); ?>><?php echo $region->name;?></option>
                                 <?php  } } ?>
                            
                                </select>
                            </div>
                      </div>  
                        <br/>    
                       <div class="row">
                         <label class="control-label col-sm-3" for="Country">Skills <span class="required"> </span>
                        </label> 
                            <div class="col-sm-9">
                                <select  name="skill_id" id="skill_id"   class="form-control" onchange="changeSubSkill(this.value,'level_id')">
                                       <option value="">All</option>
                                      <?php
                                     if($skills){
                                      foreach($skills as $skill){
                                     ?>
                                       <option value="<?php echo $skill->skill_id;?>" <?php echo set_select('skill_id',$skill->skill_id,$skill->skill_id==$this->input->post('skill_id')?true:false);?>>
                                           <?php echo $skill->skill_name;?> </option>
                                     <?php
                                      }
                                     }
                                     ?>
                                   </select>
                            </div>
                      </div>    
                        <br/>    
                       <div class="row">
                         <label class="control-label col-sm-3" for="Country">Subskills <span class="required"> </span>
                        </label> 
                            <div class="col-sm-9">
                                <select  name="level_id" id="level_id"   class="form-control">
                                       <option value="">All</option>
                                   </select>
                            </div>
                      </div>    
                      
                   </div>
                        
                        <div class="col-sm-4">
                             <div class="row">
                             <label class="control-label col-sm-3" for="Country">Shift Type  
                             </div>
                             <div class="row">
                                 <div class="col-sm-9 pre-scrollable" style="min-height:155px;" >
                                     
                                         <?php if($shift_details){ 
                                           foreach($shift_details as $shifts){  
                                           ?>
                                     <div><?php echo $shifts->name.' '.$this->model_master_data->formatTime($shifts->time_from).' - '.$this->model_master_data->formatTime($shifts->time_to);?></div>
                                           <?php } } ?>
                                
                             </div>
                             </div>         
                        
                        </div>
                            <div class="col-sm-4">
                                 <div class="row">
                            
                             
                                <label class="control-label col-sm-3" for="Calendar">Year <span class="required">&nbsp;</span>
                                </label>
                                 <div class="col-sm-9">
                                   <select  name="calendar_year" id="calendar_year"   class="form-control" onchange="changeSearchCalendarDay(this.value)" >
                                       <?php if($years){
                                           foreach($years as $year){
                                      ?>
                                       <option value="<?php echo $year->year;?>" <?php echo  set_select('calendar_year',$year->year,@$selected_year == $year->year?true:false); ?> ><?php echo $year->year;?></option>
                                       <?php } } ?>
                                   </select>
                                     <br/>
                                     <div class="pre-scrollable" id="calendar_days_dates" style="min-height:150px;">
                                     <?php 
                                     $calendar_days_date = array();
                                     if($calendar_days){ 
                                         foreach($calendar_days as $cal_day){
                                             $calendar_days_date[] = date('d M',strtotime($cal_day->date));
                                      
                                     } }
                                     echo implode(', ',$calendar_days_date);
                                     ?>
                                     </div>
                                </div>
                                 </div>
                                <br/>
                                <div class="row">
                                     
                                    <!-- <div class="col-sm-3">
                                        <button type="submit" id="wage_rate_search_button" class="btn btn-primary antosubmit">Search</button>
                                    </div>
                                
                                    <div class="col-sm-9">  
                                        <button type="button" onclick="loadWRForm('copy_rule')"  class="btn btn-primary antosubmit" id="copy_data_button" >
                                            Copy Rule to Subskill or Skill</button>
                                    </div>-->
                                </div>
                         
                         
                        </div>
                            
                      </div>
                   <div class="col-sm-12">
                       
                       <div class="row">
                           <div class="col-sm-7">&nbsp;</div>
                                    <div class="col-sm-2">
                                        <button type="submit" id="wage_rate_search_button" class="btn btn-primary antosubmit"><span class="glyphicon glyphicon-search"></span> Search</button>
                                    </div>
                                
                                    <div class="col-sm-3">  
                                        <button type="button" onclick="loadWRForm('copy_rule')"  class="btn btn-primary antosubmit" id="copy_data_button" >
                                            Copy Rule to Subskill or Skill</button>
                                    </div>
                                </div>
                       
                   </div>
                 
                       <!-- END of SEARCH Section -->
                       </div>  
                   </div>
                    </form>
                 </div>
                    
                    
<div class="col-md-12 col-sm-12 col-xs-12">
        <div class="panel-group">
       <div class="panel panel-default">
           <div class="panel-heading">
               <span class="tools pull-right">
                        <a href="javascript:;" class="fa fa-chevron-down"></a>
                    </span>
               <h2><b>Special Day Rule&nbsp;||&nbsp;<a href="javascript:void(0)" onclick="loadWRForm('special_rate')"> Add <span class="fa fa-plus" style="font-size:16px;vertical-align:middle;"></span></a></b></h2></div>
           <div class="panel-body"> 
             
                <div class="x_panel">
                  
                  <div class="x_content">
                   <?php $has_data=0; ?>
                    <table id="special_datatable" class="table table-striped table-bordered">
                      <thead>
                        <tr  >
                         <th>S.N.</th>
                          <th>S.N.</th>
                          <th>Day</th>  
                          <th>From-To</th>
                          <th>Rule Name for Customer</th>
                          <th>Rule Name for LP</th>
                           <th>LP Charge(%)</th>
                           <th colspan="3">Pay Rate</th>
                           <th>Action</th>
                        </tr>
                         <tr>
                          <th>&nbsp;</th>
                          <th>&nbsp;</th>
                          <th>&nbsp;</th>  
                          <th>&nbsp;</th>
                          <th>&nbsp;</th>
                          <th>&nbsp;</th>
                          <th>&nbsp;</th>
                           <th>Manual (ph)</th>
                           <th>Times</th>
                           <th>Shift</th>
                           <th>
                               &nbsp;
                           </th>
                          
                        </tr>
                      </thead>


                      <tbody id="data_special_rule">
                <?php
                if($special_wage_rate_rule)
                {
                    $s=1;
                    $has_data = 1;
                    foreach($special_wage_rate_rule as $special_wr)
                    {
                ?>
                    <tr>
                        <td><?php echo $s;?></td>
                      <td style="cursor:move" >
                          <img src="<?php echo base_url('assets/admin/images/move.png');?>">
                      </td>
                      <td><?php echo date('d M Y',  strtotime($special_wr->day_name));?>
                       <input type="hidden" name="special_data_id" value="<?php echo $special_wr->id;?>" class="special_data_id">
                      </td>
                      <td><?php echo $this->model_master_data->formatTime($special_wr->time_from).' - '.$this->model_master_data->formatTime($special_wr->time_to);?></td>
                      <td><?php echo $special_wr->customer_rule_name;?></td>
                       <td><?php echo $special_wr->lhc_rule_name;?></td>
                      <td><?php echo number_format($special_wr->lhc_charge);?></td>
                      <td><?php echo number_format($special_wr->pay_rate,2);?></td>
                      <td><?php echo number_format($special_wr->pay_times,2);?></td>
                      <td><?php echo $special_wr->shift_name;?> (ph)</td>
                      <td>
                        <a href="javascript:void(0)"  onclick="loadWRForm('special_rate','<?php echo $special_wr->id;?>')" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
                        <a href="javascript:void(0)" onclick="removeWRSpecialRule('special_rate','<?php echo $special_wr->id;?>')"   title="Delete"><span class="glyphicon glyphicon-remove"></span></a>&nbsp;&nbsp;
                     <a href="javascript:void(0)" onclick="loadWRForm('special_rate_copy','<?php echo $special_wr->id;?>')"   title="Copy"><span class="glyphicon glyphicon-pawn"></span></a>
                          
                      </td>
                     
                    </tr>
                <?php 
                        $s++;
                    }
                }else{
                ?>
                    <tr><td>0</td><td colspan="8">No Records</td></tr>
                <?php
                }
                ?>
                        
                         
                      </tbody>
                    </table>
                  </div>
                </div>
              
           </div>
         </div>
       </div>
      
               <script type="text/javascript">
			  
			 var table_special = $('#special_datatable').DataTable( {
                         rowReorder: {
                                    selector: 'td:first-child'
                                },
                            columnDefs: [
                                { targets: 0, visible: false }
                            ]
                        } );
                         //table.row.add( [ 0, 2, 3, 4,5,6,7,8,9,10,11 ] ).draw().order();
                         //table.row.add( [ -1, 'zero bala task', 3, 4,5,6,7,8,9,10,11 ] ).draw().order();
                        
                            table_special.on( 'row-reorder', function ( e, diff, edit ) {
                           var data = []; //initialize array
                            
                       // var result = 'Reorder started on row: '+edit.triggerRow.data()[1]+'<br>';
                        
                        for ( var i=0, ien=diff.length ; i<ien ; i++ ) {
                            var rowData = table_special.row( diff[i].node ).data();
                            var table_data_id = {};
                            tr_html = diff[i];
                        
                            tr_index = tr_html.newPosition;
                            table_data_id.data_id = $('.special_data_id:eq('+tr_index+')').val();
                            table_data_id.index = tr_index;
                             
                             data.push(table_data_id);
                            //alert(tr_html);

                           // result += rowData[1]+' updated to be in position '+
                               // diff[i].newData+' (was '+diff[i].oldData+')<br>';
                        }
                         data = JSON.stringify(data);//json
                         changeWRDataOrder('<?php echo base_url('admin/wage_rate/changeWRTableOrder');?>','special_rate',data);

                       // $('#result').html( 'Event result:<br>'+result );
                    } );   
			 
			</script>
               
               
                </div>
          
          <!-- Overtime (penalty rate)-->
          <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="panel-group">
       <div class="panel panel-default">
           <div class="panel-heading">
               <span class="tools pull-right">
                        <a href="javascript:;" class="fa fa-chevron-down"></a>
                    </span>
               <h2><b>Overtime(penalty rates)&nbsp;||&nbsp;<a href="javascript:void(0)" onclick="loadWRForm('overtime_rate')"> Add <span class="fa fa-plus" style="font-size:16px;vertical-align:middle;"></span></a></b></h2></div>
           <div class="panel-body"> 
             
                <div class="x_panel">
                  
                  <div class="x_content">
                   
                    <table id="overtime_datatable" class="table table-striped table-bordered">
                      <thead>
                        <tr  >
                         <th>S.N.</th>
                          <th>S.N.</th>
                           <th>Interval</th>
                          <th>Day</th>  
                          <th>Threshold Hours</th>
                          <th>Rule Name for Customer</th>
                          <th>Rule Name for LP</th>
                           <th>LP Charge (%)</th>
                            <th>Apply to all hours</th>
                           <th colspan="3">Pay Rate</th>
                           <th>Action</th>
                        </tr>
                         <tr>
                           
                          <th>&nbsp;</th>
                          <th>&nbsp;</th>
                          <th>&nbsp;</th>
                          <th>&nbsp;</th>  
                          <th>&nbsp;</th>
                          <th>&nbsp;</th>
                          <th>&nbsp;</th>
                          <th>&nbsp;</th>
                            <th>&nbsp;</th>
                           <th>Manual (ph)</th>
                           <th>Times</th>
                           <th>Shift</th>
                           <th>
                               &nbsp;
                           </th>
                          
                        </tr>
                      </thead>


               <tbody id="data_overtime_rule">
                <?php
                if($overtime_wage_rate_rule)
                {
                    $s=1;$has_data=1;
                    
                    foreach($overtime_wage_rate_rule as $overtime_rate)
                    {
                        
                ?>
                    <tr>
                        <td><?php echo $s;?></td>
                      <td style="cursor:move" >
                          <img src="<?php echo base_url('assets/admin/images/move.png');?>">
                      </td>
                      <td><?php echo $overtime_rate->interval_name;?>
                       <input type="hidden" name="overtime_data_id" value="<?php echo $overtime_rate->id;?>" class="overtime_data_id">
                      </td>
                       
                      <td><?php echo $overtime_rate->day_name;?></td>
                      <td><?php echo $overtime_rate->threshold_hour;?></td>
                      <td><?php echo $overtime_rate->customer_rule_name;?></td>
                       <td><?php echo $overtime_rate->lhc_rule_name;?></td>
                      <td><?php echo number_format($overtime_rate->lhc_charge);?></td>
                      <td><?php echo $overtime_rate->applicable_for_all==1?'Yes':'No';?></td>
                      <td><?php echo number_format($overtime_rate->pay_rate,2);?></td>
                      <td><?php echo number_format($overtime_rate->pay_times,2);?></td>
                      <td><?php echo $overtime_rate->shift_name;?> (ph)</td>
                      <td>
                          <a href="javascript:void(0)"  onclick="loadWRForm('overtime_rate','<?php echo $overtime_rate->id;?>')" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
                        <a href="javascript:void(0)" onclick="removeWRSpecialRule('overtime_rate','<?php echo $overtime_rate->id;?>')"   title="Delete"><span class="glyphicon glyphicon-remove"></span></a>&nbsp;&nbsp;
                     <a href="javascript:void(0)" onclick="loadWRForm('overtime_rate_copy','<?php echo $overtime_rate->id;?>')"   title="Copy"><span class="glyphicon glyphicon-pawn"></span></a>
                          
                      </td>
                     
                    </tr>
                <?php 
                        $s++;
                    }
                }else{
                ?>
                    <tr><td>0</td><td colspan="10">No Records</td></tr>
                <?php
                }
                ?>
                        
                         
                      </tbody>
                    </table>
                  </div>
                </div>
              
           </div>
         </div>
       </div>
      
               <script type="text/javascript">
			  
			 var table_overtime = $('#overtime_datatable').DataTable( {
                         rowReorder: {
                                    selector: 'td:first-child'
                                },
                            columnDefs: [
                                { targets: 0, visible: false }
                            ]
                        } );
                        // table.row.add( [ 0, 2, 3, 4,5,6,7,8,9,10,11 ] ).draw().order();
                         //table.row.add( [ -1, 'zero bala task', 3, 4,5,6,7,8,9,10,11 ] ).draw().order();
                        
                        table_overtime.on( 'row-reorder', function ( e, diff, edit ) {
                           var data = []; //initialize array
                            
                       // var result = 'Reorder started on row: '+edit.triggerRow.data()[1]+'<br>';
                        
                        for ( var i=0, ien=diff.length ; i<ien ; i++ ) {
                            var rowData = table_overtime.row( diff[i].node ).data();
                            var table_data_id = {};
                            tr_html = diff[i];
                        
                            tr_index = tr_html.newPosition;
                            table_data_id.data_id = $('.overtime_data_id:eq('+tr_index+')').val();
                            table_data_id.index = tr_index;
                             
                             data.push(table_data_id);
                            //alert(tr_html);

                           // result += rowData[1]+' updated to be in position '+
                               // diff[i].newData+' (was '+diff[i].oldData+')<br>';
                        }
                         data = JSON.stringify(data);//json
                         changeWRDataOrder('<?php echo base_url('admin/wage_rate/changeWRTableOrder');?>','overtime',data);

                       // $('#result').html( 'Event result:<br>'+result );
                    } );  
			 
			</script>
               
               
                </div>
          <!-- Overtime (penalty rate)-->    
          
          
          
            <!-- Breaks not taken -->
          <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="panel-group">
       <div class="panel panel-default">
           <div class="panel-heading">
               <span class="tools pull-right">
                        <a href="javascript:;" class="fa fa-chevron-down"></a>
                    </span>
               <h2><b>Breaks not taken ||&nbsp;<a href="javascript:void(0)" onclick="loadWRForm('breaks_not_taken')"> Add <span class="fa fa-plus" style="font-size:16px;vertical-align:middle;"></span></a></b></h2></div>
           <div class="panel-body"> 
             
                <div class="x_panel">
                  
                  <div class="x_content">
                   
                    <table id="breaks_not_taken" class="table table-striped table-bordered">
                      <thead>
                        <tr  >
                         <th>S.N.</th>
                          <th>S.N.</th>
                          <th>Day</th>  
                          <th>Rule Name for Customer</th>
                          <th>Rule Name for LP</th>
                           <th>LP Charge(%)</th>
                           <th>Shift</th>
                           <th>From - To</th>
                           <th>Pay Rate (ph)</th>
                           <th>Action</th>
                        </tr>
                         
                      </thead>


               <tbody id="data_overtime_rule">
                <?php
                if($breaks_not_taken_wage_rate_rule)
                {
                    $s=1;
                    $has_data =1;
                    foreach($breaks_not_taken_wage_rate_rule as $breaks_rate)
                    {
                        
                ?>
                    <tr>
                        <td><?php echo $s;?></td>
                      <td style="cursor:move" >
                          <img src="<?php echo base_url('assets/admin/images/move.png');?>">
                      </td>
                      <td><?php echo $breaks_rate->day_name;?>
                       <input type="hidden" name="breaks_not_taken_data_id" value="<?php echo $breaks_rate->id;?>" class="breaks_not_taken_data_id">
                      </td>
                      <td><?php echo $breaks_rate->customer_rule_name;?></td>
                       <td><?php echo $breaks_rate->lhc_rule_name;?></td>
                      <td><?php echo number_format($breaks_rate->lhc_charge);?></td>
                      <td><?php echo  $breaks_rate->shift_name;?></td> 
                    <td><?php echo  $this->model_master_data->formatTime($breaks_rate->time_from).'-'.$this->model_master_data->formatTime($breaks_rate->time_to);?></td>
                      
                       
                       <td><?php echo number_format($breaks_rate->pay_rate,2);?></td>
                      <td>
                          <a href="javascript:void(0)"  onclick="loadWRForm('breaks_not_taken','<?php echo $breaks_rate->id;?>')" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
                        <a href="javascript:void(0)" onclick="removeWRSpecialRule('breaks_not_taken','<?php echo $breaks_rate->id;?>')"   title="Delete"><span class="glyphicon glyphicon-remove"></span></a>&nbsp;&nbsp;
                      <a href="javascript:void(0)" onclick="loadWRForm('breaks_not_taken_copy','<?php echo $breaks_rate->id;?>')"   title="Copy"><span class="glyphicon glyphicon-pawn"></span></a>
                          
                      </td>
                     
                    </tr>
                <?php 
                        $s++;
                    }
                }else{
                ?>
                    <tr><td>0</td><td colspan="10">No Records</td></tr>
                <?php
                }
                ?>
                        
                         
                      </tbody>
                    </table>
                  </div>
                </div>
              
           </div>
         </div>
       </div>
      
               <script type="text/javascript">
			  
			 var table_break_not_taken = $('#breaks_not_taken').DataTable( {
                         rowReorder: {
                                    selector: 'td:first-child'
                                },
                            columnDefs: [
                                { targets: 0, visible: false }
                            ]
                        } );
                         
                        table_break_not_taken.on( 'row-reorder', function ( e, diff, edit ) {
                           var data = []; //initialize array
                            
                       // var result = 'Reorder started on row: '+edit.triggerRow.data()[1]+'<br>';
                        
                        for ( var i=0, ien=diff.length ; i<ien ; i++ ) {
                            var rowData = table_break_not_taken.row( diff[i].node ).data();
                            var table_data_id = {};
                            tr_html = diff[i];
                        
                            tr_index = tr_html.newPosition;
                            table_data_id.data_id = $('.breaks_not_taken_data_id:eq('+tr_index+')').val();
                            table_data_id.index = tr_index;
                             
                             data.push(table_data_id);
                            //alert(tr_html);

                           // result += rowData[1]+' updated to be in position '+
                               // diff[i].newData+' (was '+diff[i].oldData+')<br>';
                        }
                         data = JSON.stringify(data);//json
                         changeWRDataOrder('<?php echo base_url('admin/wage_rate/changeWRTableOrder');?>','breaks_not_taken',data);

                       // $('#result').html( 'Event result:<br>'+result );
                    } );  
			 
			</script>
               
               
                </div>
          <!-- Overtime (Breaks Not taken)-->  
          
          
              <!-- Work Hours taken -->
          <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="panel-group">
       <div class="panel panel-default">
           <div class="panel-heading">
               <span class="tools pull-right">
                        <a href="javascript:;" class="fa fa-chevron-down"></a>
                    </span>
               <h2><b>Work Hours ||&nbsp;<a href="javascript:void(0)" onclick="loadWRForm('work_hours')"> Add <span class="fa fa-plus" style="font-size:16px;vertical-align:middle;"></span></a></b></h2></div>
           <div class="panel-body"> 
             
                <div class="x_panel">
                  
                  <div class="x_content">
                   
                    <table id="work_hours" class="table table-striped table-bordered">
                      <thead>
                        <tr  >
                         <th>S.N.</th>
                          <th>S.N.</th>
                          <th>Day</th>  
                          <th>Rule Name for Customer</th>
                          <th>Rule Name for LP</th>
                           <th>LP Charge(%)</th>
                           <th>Shift</th>
                           <th>From - To</th>
                           <th>Pay Rate (ph)</th>
                           <th>Action</th>
                        </tr>
                         
                      </thead>


               <tbody id="work_hours_rule">
                <?php
                 
                if($work_hour_rate_rule)
                {
                  
                    $s=1;
                    $has_data =1;
                    foreach($work_hour_rate_rule as $wh_rate)
                    {
                        
                ?>
                    <tr>
                        <td><?php echo $s;?></td>
                      <td style="cursor:move" >
                          <img src="<?php echo base_url('assets/admin/images/move.png');?>">
                      </td>
                      <td><?php echo $wh_rate->day_name;?>
                       <input type="hidden" name="work_hours_data_id" value="<?php echo $wh_rate->id;?>" class="work_hours_data_id">
                      </td>
                      <td><?php echo $wh_rate->customer_rule_name;?></td>
                       <td><?php echo $wh_rate->lhc_rule_name;?></td>
                      <td><?php echo number_format($wh_rate->lhc_charge);?></td>
                      <td><?php echo  $wh_rate->shift_name;?></td> 
                    <td><?php echo  $this->model_master_data->formatTime($wh_rate->time_from).'-'.$this->model_master_data->formatTime($wh_rate->time_to);?></td>
                      
                       
                       <td><?php echo number_format($wh_rate->pay_rate,2);?></td>
                      <td>
                          <a href="javascript:void(0)"  onclick="loadWRForm('work_hours','<?php echo $wh_rate->id;?>')" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
                        <a href="javascript:void(0)" onclick="removeWRSpecialRule('work_hours','<?php echo $wh_rate->id;?>')"   title="Delete"><span class="glyphicon glyphicon-remove"></span></a>&nbsp;&nbsp;
                        <a href="javascript:void(0)" onclick="loadWRForm('work_hours_copy','<?php echo $wh_rate->id;?>')"   title="Copy"><span class="glyphicon glyphicon-pawn"></span></a>
                     
                          
                      </td>
                     
                    </tr>
                <?php 
                        $s++;
                    }
                }else{
                      
                ?>
                    <tr><td>0</td><td colspan="11">No Records</td></tr>
                <?php
                }
                ?>
                        
                         
                      </tbody>
                    </table>
                  </div>
                </div>
              
           </div>
         </div>
       </div>
      
               <script type="text/javascript">
			  
			 var table = $('#work_hours').DataTable( {
                         rowReorder: {
                                    selector: 'td:first-child'
                                },
                            columnDefs: [
                                { targets: 0, visible: false }
                            ]
                        } );
                         
                        table.on( 'row-reorder', function ( e, diff, edit ) {
                           var data = []; //initialize array
                            
                       // var result = 'Reorder started on row: '+edit.triggerRow.data()[1]+'<br>';
                        
                        for ( var i=0, ien=diff.length ; i<ien ; i++ ) {
                            var rowData = table.row( diff[i].node ).data();
                            var table_data_id = {};
                            tr_html = diff[i];
                        
                            tr_index = tr_html.newPosition;
                            table_data_id.data_id = $('.work_hours_data_id:eq('+tr_index+')').val();
                            table_data_id.index = tr_index;
                             
                             data.push(table_data_id);
                            //alert(tr_html);

                           // result += rowData[1]+' updated to be in position '+
                               // diff[i].newData+' (was '+diff[i].oldData+')<br>';
                        }
                         data = JSON.stringify(data);//json
                         changeWRDataOrder('<?php echo base_url('admin/wage_rate/changeWRTableOrder');?>','work_hours',data);

                       // $('#result').html( 'Event result:<br>'+result );
                    } );  
			 
			</script>
               
               
                </div>
          <!-- Overtime (Work Hour)--> 
	</div>
			
			
			<?php } ?>
			<!--END of Listing Page-->

             
          </div>
        </div>
        <!-- /page content -->
        
        
 
 
<!-- Modal box content-->
<div id="special_day_rule_add_container" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
      <div class="modal-dialog  ">
          <div class="modal-content"  >

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title" id="myModalLabel">New Special day rule</h4>
          </div>
          <div id="special_wr_form">
           
          </div>
      </div>
    </div>
<!-- END of Modal Box Content-->
</div>
<script type="text/javascript">
    
    $('.modal-content').css({
  'width': function () { 
    return ($(document).width() * .6) + 'px';  
  }
  
   
});
 
function changeSubSkill(skill_id,target_id){
    $.ajax({
        url:'<?php echo base_url('admin/wage_rate/get_subskill');?>',
        type:'post',
        data:'skill_id='+skill_id+'&level_id=<?php echo $this->input->post('level_id');?>',
            success:function(data){
                $('#'+target_id).html(data);
            } 
        
    }); 
    
}

function changeCalendarDay(year){
 
 if($('#sp_calendar_year').val()!=""){
    $.ajax({
        url:'<?php echo base_url('admin/wage_rate/get_caledardays');?>',
        type:'post',
        data:'target=&country_id='+$('#sp_country_id').val()+'&region_id='+$('#sp_region_id').val()+'&year='+$('#sp_calendar_year').val(),
            success:function(data){
                $('#sp_calendar_id').html(data);
            } 
        
    });
     }
}

function changeSearchCalendarDay(year){
 
 if($('#calendar_year').val()!=""){
    $.ajax({
        url:'<?php echo base_url('admin/wage_rate/get_caledardays');?>',
        type:'post',
        data:'target=calendar_day_dates&country_id='+$('#sp_country_id').val()+'&region_id='+$('#region_id').val()+'&year='+$('#calendar_year').val(),
            success:function(data){
                $('#calendar_days_dates').html(data);
            } 
        
    });
     }
}
 


//get_caledardays
    </script>
    <script type="text/javascript">
 function validateSPForm() {
     
     var error_msg = ""; 
       //fetch required field value
      sp_country_id = $('input[name=sp_country_id]').val();
      sp_region_id = $('#sp_region_id').val();
      sp_calendar_year = $('#sp_calendar_year').val();
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
       if(sp_calendar_year==""){
           error_msg += " -- Year is required.</br>";
            invalid.push('sp_calendar_year');
       }
       
      /*  if(sp_skill_id==""){
           error_msg += " -- Skill is required.</br>";
            invalid.push('sp_skill_id');
       }
       if(sp_level_id==""){
           error_msg += " -- Level is required.</br>";
            invalid.push('sp_level_id');
       }*/
       if(sp_calendar_id==""){
           error_msg += " -- Day is required.</br>";
            invalid.push('sp_calendar_id');
       }
       
       if(sp_time_from==""){
           error_msg += " -- From is required.</br>";
            invalid.push('sp_time_from');
       }
       if(sp_time_to==""){
           error_msg += " -- To is required.</br>";
            invalid.push('sp_time_to');
       }
       
      if(sp_time_to < sp_time_from){
          error_msg += " -- To time can not be less than is required.</br>";
            invalid.push('sp_time_to');
      }
      
      
      
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
        url:'<?php echo base_url('admin/wage_rate/saveSpecialRate');?>',
        type:'post',
        dataType: 'json',
        data: $('#special_rate').serialize(),
            success:function(data){
                 
                if(data.status == '1'){
                    $("#special_day_rule_add_container .close").click();
                    document.getElementById('special_rate').reset();
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
    
    function checkTimesOfShift(){
           $('#sp_times').val('');
           $('#sp_shift_type_id').val('');
    }
    function checkManualRate(){
         $('#sp_manual_rate').val('');
    }
   
   
   function loadWRForm(load_wr,data_id){
       
       $("#special_day_rule_add_container").modal('show'); 
       $model_label = "";
       
       if(load_wr == 'special_rate'){
            model_label = 'Special Day Rule';
       }else if(load_wr == 'overtime_rate'){
            model_label = 'Overtime(penalty rates)';
       }else if(load_wr == 'breaks_not_taken'){
            model_label = 'Breaks not taken';
       }else if(load_wr == 'work_hours'){
            model_label = 'Work Hours';
       }else if(load_wr == 'copy_rule'){
            model_label = 'Copy Rule';
       }else if(load_wr == 'work_hours_copy'){
           model_label = 'Copy Work Hours';
       }else if(load_wr == 'breaks_not_taken_copy'){
            model_label = ' Copy Breaks not taken';
       }else if(load_wr == 'overtime_rate_copy'){
            model_label = ' Copy Overtime(penalty rates)';
       }else if(load_wr == 'special_rate_copy'){
            model_label = ' Copy Special Day Rule';
       }
       country_id = $('#sp_country_id').val();
       region_id = $('#region_id').val();
       skill_id = $('#skill_id').val();
       level_id = $('#level_id').val();
       $('#myModalLabel').html(model_label);
        $.ajax({
        url:'<?php echo base_url('admin/wage_rate/loadWRForm');?>',
        type:'post',
        data: 'load_wr='+load_wr+'&data_id='+data_id+'&country_id='+country_id+'&region_id='+region_id+'&skill_id='+skill_id+'&level_id='+level_id,
            success:function(data){
                
              $('#special_wr_form').html(data);
              $('#sp_time_from').datetimepicker({
         
                format: 'HH:mm'

              });
                $('#sp_time_to').datetimepicker({

                  format: 'HH:mm'

                });
                
            } 
        });
       //$("#special_day_rule_add_container").modal('show'); 
   }
   
   function removeWRSpecialRule(load_wr,data_id){
    if(confirm('Do you really want to delete this data?') == true){
        $.ajax({
        url:'<?php echo base_url('admin/wage_rate/removeWRRule');?>',
        type:'post',
        dataType:'json',
        data: 'load_wr='+load_wr+'&data_id='+data_id,
            success:function(data){
              if(data.status == 1){
                  
              callWRContainer();
            } else{
                //
            }
            alert(data.msg);
        }
        });
    
    }
       //$("#sp
   }
   
   function callWRContainer(){
       //load wr view
      // alert('hi');
      $('#wage_rate_search_button').click();
      
   }
   jQuery('.panel .tools .fa-chevron-down').click(function () {
        var el = jQuery(this).parents(".panel").children(".panel-body");
        if (jQuery(this).hasClass("fa-chevron-down")) {
            jQuery(this).removeClass("fa-chevron-down").addClass("fa-chevron-up");
            el.slideUp(200);
        } else {
            jQuery(this).removeClass("fa-chevron-up").addClass("fa-chevron-down");
            el.slideDown(200);
        }
    });
   
</script>
  <?php 
  if($this->input->post('skill_id')){
?> 
<script type="text/javascript">
   changeSubSkill('<?php echo $this->input->post('skill_id');?>','level_id');
</script>
<?php
  }
?>

<?php
if($has_data == 0){
?>
<script type="text/javascript">
    $('#copy_data_button').prop('disabled','true');
    </script>
<?php } ?>
