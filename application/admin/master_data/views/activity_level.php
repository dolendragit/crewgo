
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
                    <form id="frm_ppe" name="frm_ppe" data-parsley-validate class="form-horizontal form-label-left" onsubmit=" return validateForm()" method="post">

                      <div class="form-group">
                        <label class="control-label col-sm-2" for="first-name">Activity Type <span class="required">*</span>
                        </label>
                        <div class="col-sm-2">
                           <select name="type" id="type" class="form-control">
                                <option value="Mild" <?php echo  set_select('type', 'Mild',@$activity->type == 'Mild'?true:true); ?>>Mild</option>
                                 <option value="Peak" <?php echo  set_select('type', 'Peak',@$activity->type == 'Peak'?true:false); ?>>Peak</option>
                            </select>
                         
                        </div>
                          
                          <label class="control-label  col-sm-3 " for="first-name">Activity <span class="required">*</span>
                        </label>
                        <div class="col-sm-5">
                            <input type="text" id="name" name="name" value="<?php echo set_value('name',@$activity->name);?>"   class="form-control" maxlength="300">
                        </div>
                      </div>
                        
                <div class="panel-group">
                   <div class="panel panel-success">
                    <div class="panel-heading">Potential Staff Result</div>
                       <div class="panel-body"> 
                           <div class='notes'>Left 'To' as blank to add potential staff result greater than 'From' value. </div>
                        <div class="form-group">
                        <label class="control-label  col-sm-3 " for="first-name">From <span class="required">*</span>
                        </label>
                        <div class="col-sm-2">
                            <input type="text" onkeypress="return isNumber(event)" id="p_staff_result_range_from" name="p_staff_result_range_from" value="<?php echo set_value('p_staff_result_range_from',@$activity->p_staff_result_range_from);?>"   class="form-control" maxlength="4">
                         
                        </div>
                          
                          <label class="control-label  col-sm-4 " for="first-name">To <span class="required">&nbsp;</span>
                        </label>
                        <div class="col-sm-2">
                          <input type="text" onkeypress="return isNumber(event)" id="p_staff_result_range_to" name="p_staff_result_range_to" value="<?php echo set_value('p_staff_result_range_to',@$activity->p_staff_result_range_to);?>"   class="form-control" maxlength="4">
                        </div>
                      </div>
                  </div>  
                   </div>
                 </div>
                 <div class="panel-group">
                   <div class="panel panel-success">
                    <div class="panel-heading">Request Message</div>
                       <div class="panel-body"> 

                        <div class="form-group">
                        <label class="control-label col-sm-2" for="first-name">Number of request <span class="required">*</span>
                        </label>
                        <div class=" col-sm-2 ">
                            <input type="text" onkeypress="return isNumber(event)" id="shift_request_number" name="shift_request_number" value="<?php echo set_value('shift_request_number',@$activity->shift_request_number);?>"   class="form-control" maxlength="4" placeholder="Request Message">
                         
                        </div>
                        <label class="control-label col-sm-2 " for="first-name">Time interval <span class="required">*</span>
                        </label>
                        <div class=" col-sm-2  ">
                           <input type="text" onkeypress="return isNumber(event)" id="shift_time_interval" name="shift_time_interval" value="<?php echo set_value('shift_time_interval',@$activity->shift_time_interval);?>"   class="form-control" maxlength="4" placeholder="Second">
                         
                        </div>
                            <label class="control-label col-sm-2 " for="first-name">Notice time <span class="required">*</span>
                        </label>
                        <div class="col-sm-2">
                            <input type="text" onkeypress="return isNumber(event)" id="shift_notice_time" name="shift_notice_time" value="<?php echo set_value('shift_notice_time',@$activity->shift_notice_time);?>"   class="form-control" maxlength="4" placeholder="Minutes">
                           
                        </div>
                          
                          
                         
                      </div>
                           
                  </div>  
                   </div>
                 </div>         
                           
                 <div class="panel-group">
                   <div class="panel panel-success">
                    <div class="panel-heading">Peak Pricing</div>
                       <div class="panel-body"> 
                       
                        <div class="form-group">
                        <label class="control-label  col-sm-2 " for="first-name">Peak Price <span class="required">*</span>
                        </label>
                        <div class=" col-sm-2 ">
                            <input type="text" onkeypress="return isNumber(event)" id="peak_price_factor" name="peak_price_factor" value="<?php echo set_value('peak_price_factor',@$activity->peak_price_factor);?>"   class="form-control" maxlength="4" placeholder="">
                         
                        </div>
                        <label class="control-label col-sm-2 " for="first-name">Filling Probability (%) <span class="required">*</span>
                        </label>
                        <div class=" col-sm-2  ">
                       <input type="text" onkeypress="return isNumber(event)" id="filling_probability" name="filling_probability" value="<?php echo set_value('filling_probability',@$activity->filling_probability);?>"   class="form-control" maxlength="4" placeholder="">
                         
                        </div>
                            <label class="control-label col-sm-2 " for="first-name">&nbsp;
                        </label>
                        <div class=" col-sm-2 ">
                            &nbsp;
                         
                        </div>
                         
                      </div>
                      </div>  
                   </div>
                 </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class=" col-sm-6  col-md-offset-3">
                        <input type="hidden" name="hidden_id" id="hidden_id" value="<?php echo @$activity->id;?>">
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
                            <th rowspan="2" style="vertical-align: middle">S.N.</th>
                            <th rowspan="2" style="vertical-align: middle;">Activity</th>  
                            <th colspan="2" style="text-align: center;" >Staff Potential Result</th>
                            <th colspan="3" style="text-align: center;">Request Message</th>
                            <th colspan="2" style="vertical-align: middle">Peak Pricing</th>
                             
                            <th rowspan="2" style="vertical-align: middle">Action</th>
                        </tr>
                        <tr>
                          
                          <th>From</th>
                          <th>To</th>
                          <th>No. of request</th>
                          <th>Time interval(sec)</th>
                          <th>Notice time(min)</th>
                           <th>Peak price</th>
                          <th>Filling probability</th>
                           
                        </tr>
                      </thead>


                      <tbody>
                <?php
                if($activities)
                {
                    $s=1;
                    foreach($activities as $activity)
                    {
                ?>
                    <tr>
                      <td ><?php echo $s;?></td>
                      <td><?php echo $activity->activity_level;?></td>
                      <td><?php echo $activity->p_staff_result_range_from;?></td>
                      <td><?php echo $activity->p_staff_result_range_to==0?"Over":$activity->p_staff_result_range_to;?></td>
                      <td><?php echo $activity->shift_request_number;?></td>
                      <td><?php echo $activity->shift_time_interval;?></td>
                      <td><?php echo $activity->shift_notice_time;?></td>
                      <td><?php echo $activity->peak_price_factor;?></td>
                       <td><?php echo $activity->filling_probability;?></td>
                        
                      <td>
                        <a href="<?php echo base_url('admin/master_data/activity_level/edit').'/'.$activity->id;?>" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
                        <a href="<?php echo base_url('admin/master_data/activity_level/remove').'/'.$activity->id;?>" onclick="return confirm('Do you really want to delete this data?')" title="Delete"><span class="glyphicon glyphicon-remove"></span></a>
                      </td>
                    </tr>
                <?php 
                        $s++;
                    }
                }else{
                ?>
                    <tr><td colspan="10">No Records</td></tr>
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
			  
			var table = $('#datatable').DataTable( {
                        rowReorder: false
                        } );
                        
                       /* table.on( 'row-reorder', function ( e, diff, edit ) {
                        var result = 'Reorder started on row: '+edit.triggerRow.data()[1]+'<br>';

                        for ( var i=0, ien=diff.length ; i<ien ; i++ ) {
                            var rowData = table.row( diff[i].node ).data();

                            result += rowData[1]+' updated to be in position '+
                                diff[i].newData+' (was '+diff[i].oldData+')<br>';
                        }

                        $('#result').html( 'Event result:<br>'+result );
                    } );*/
 
			 
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
      p_staff_result_range_from = $('input[name=p_staff_result_range_from]').val();
      shift_request_number = $('input[name=shift_request_number]').val();
      shift_time_interval = $('input[name=shift_time_interval]').val();
      shift_notice_time = $('input[name=shift_notice_time]').val();
      peak_price_factor = $('input[name=peak_price_factor]').val();
      filling_probability = $('input[name=filling_probability]').val();
       
      var invalid = [];
        if(name==""){
           error_msg += " -- Activity is required.</br>";
            invalid.push('name');
       }
       if(p_staff_result_range_from==""){
           error_msg += " -- Potential staff result 'From' is required.</br>";
            invalid.push('p_staff_result_range_from');
       }
       if(shift_request_number==""){
           error_msg += " -- Request message 'Number of request' is required.</br>";
            invalid.push('shift_request_number');
       }
       if(shift_notice_time==""){
           error_msg += " -- Request message 'Notice time' is required.</br>";
            invalid.push('shift_notice_time');
       }
       if(peak_price_factor==""){
           error_msg += " -- Peak pricing 'Peak price' is required.</br>";
            invalid.push('peak_price_factor');
       }
       if(filling_probability==""){
           error_msg += " -- Peak pricing 'Filling Probablity' is required.</br>";
            invalid.push('peak_price_factor');
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