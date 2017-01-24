<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script> 
 
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
        <?php if($page_action == "add" || $page_action == "edit") {
            
       
            ?>
        <div id="form_errors" class="alert alert-danger fade in" style="display:none;">
	<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>		  
            <div id="form_errors_msg"></div> 
        </div>
            <div class="row">
              <div class="col-sm-12">
                <div class="x_panel">
                   
                  <div class="x_content">
                    <br />
   
                    <form id="frm_ppe" name="frm_ppe" data-parsley-validate class="form-horizontal form-label-left" onsubmit=" return validateForm()" method="post">
                   <div class="form-group">
                        <label class="control-label col-sm-2" for="Country">Country <span class="required">*</span>
                        </label>
                        <div class="col-sm-3">
                            
                        <select name="country_id" id="country_id" class="form-control">
                             <?php if($countries) {
                                 foreach($countries as $country) {
                                 
                             ?>
                            <option value="<?php echo $country->id;?>" <?php echo  set_select('country_id',$country->id,@$sociable_hour->country_id == $country->id?true:false); ?>><?php echo $country->name;?></option>
                                 <?php  } } ?>
                            
                        </select>
                         
                         
                        </div>
                          
                       <label class="control-label  col-sm-2 " for="Region">Region:<span class="required">*</span>
                        </label>
                          <div class="col-sm-2   " >
                              <select name="region_id" id="region_id" class="form-control" onchange="getSocaibleHoursView()"  >
                                 <option value="">--Select Region -- </option>
                             <?php if($regions) {
                                 foreach($regions as $region) {
                                 
                             ?>
                            <option value="<?php echo $region->id;?>" <?php echo  set_select('region_id',$region->id,@$cancellation->country_region_id == $region->id?true:false); ?>><?php echo $region->name;?></option>
                                 <?php  } } ?>
                            
                                </select>
                        </div>
                           
                         <label class="control-label col-sm-1  " for="Status"> Status</label>
                          <div class="col-sm-2" id="master_data_status_<?php echo $cancellation->id;?>" style="margin-top:5px;" >
                              <?php if($page_action=='edit'){ ?>
                             
                              <?php echo  getStatusData($cancellation->id,$cancellation->status,'cancellation');?>
                              
                              <?php }else{ ?>
                              
                        <select name="status" id="status" class="form-control ">
                                
                                <option value="1" <?php echo  set_select('status', 1,@$cancellation->status == '1'?true:true); ?>>Show</option>
                                 <option value="0" <?php echo  set_select('status', 0,@$cancellation->status == '0'?true:false); ?>>Hide</option>
                            </select>  
                              <?php } ?>
                          </div>
                      </div>
                      <div class="form-group">
                         
                          
                       <label class="control-label  col-sm-2 " for="Cancel Prior">Cancel Prior (Hour): <span class="required">*</span>
                        </label>
                          <div class="col-sm-3" >
                              <input type="text"  id="cancel_prior" name="cancel_prior" value="<?php echo set_value('cancel_prior',@$cancellation->cancel_prior);?>"   class="form-control" maxlength="10"  >
                        
                        </div>
                          <label class="control-label col-sm-2  " for="To"> Cancellation fee (%) <span class="required">*</span> </label>
                          <div class="col-sm-2   " >
                         <input type="text" id="cancel_fee" onkeypress="return isNumber(event)" name="cancel_fee" value="<?php echo set_value('cancel_fee',@$cancellation->cancel_fee);?>"   class="form-control" maxlength="10"  >
                           
                            
                        </div>
                         
                          
                          
                      </div>
                        
                        
                         <div class="form-group">
                        <label class="control-label col-sm-2" for="first-name">Remarks <span class="required"> </span>
                        </label>
                        <div class="col-sm-10">
                           <input type="text"   id="remakrs" name="remarks" value="<?php echo set_value('remarks',@$cancellation->remarks);?>"   class="form-control" maxlength="300">
                        </div>
                          
                     
                      </div>
                        
                
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class=" col-sm-6  col-md-offset-3">
                        <input type="hidden" name="hidden_id" id="hidden_id" value="<?php echo @$cancellation->id;?>">
                        <input type="hidden" name="page_action" value="<?php echo $page_action;?>">
			<button type="submit" class="btn btn-success"><?php echo $page_action=='edit'?'Update':'Submit';?></button>
                          <button type="button" class="btn btn-primary" onclick="history.go(-1)">Cancel</button>
                          
                        </div>
                      </div>

                    </form>
                  </div>
                    <!-- List related data -->
                    
   
                    
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
                            <th >S.N.</th>
                             <th>Country</th>
                            <th>Region</th>
                            <th>Cancel Prior</th>  
                            <th>Cancellation fee</th>
                            <th>Remarks</th>
                            <th>Status</th>
                            
                            <th  style="vertical-align: middle">Action</th>
                        </tr>
                        
                      </thead>


                      <tbody>
                <?php
                if($cancellations)
                {
                    $s=1;
                    foreach($cancellations as $cancellation)
                    {
                        
                      
                ?>
                    <tr>
                      <td ><?php echo $s;?></td>
                      <td ><?php echo $cancellation->country_name;?></td>
                      <td ><?php echo $cancellation->region_name;?></td>
                      <td ><?php echo $cancellation->cancel_prior;?></td>
                    
                      <td><?php echo $cancellation->cancel_fee;?></td>
                      <td><?php echo character_limiter(strip_tags($cancellation->remarks),50);?></td>
                     <td id="master_data_status_<?php echo $cancellation->id;?>">
                          <?php
                          echo getStatusData($cancellation->id,$cancellation->status,'cancellation');
                         ?>
                           
                         
                          
                      </td>
                      <td>
                        <a href="<?php echo base_url('admin/master_data/cancellation/edit').'/'.$cancellation->id;?>" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
                        <a href="<?php echo base_url('admin/master_data/cancellation/remove').'/'.$cancellation->id;?>" onclick="return confirm('Do you really want to delete this data?')" title="Delete"><span class="glyphicon glyphicon-remove"></span></a>
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
      
      country_region_id = $('#region_id').val();
       
      cancel_prior = $('input[name=cancel_prior]').val();
      cancel_fee = $('input[name=cancel_fee]').val();
       
      
      var invalid = [];
      if(country_region_id==""){
           error_msg += " -- Region is required.</br>";
            invalid.push('country_region_id');
       }
        if(cancel_prior==""){
           error_msg += " -- Cancel prior is required.</br>";
            invalid.push('cancel_prior');
       }
       if(cancel_fee==""){
           error_msg += " -- Cancellation fee is required.</br>";
            invalid.push('cancel_fee');
       }
        if(cancel_fee != ""){
           if(parseInt(cancel_fee)>100){
                error_msg += " -- Cancel fee can not be more than 100% is required.</br>";
                invalid.push('cancel_fee');
               
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
     $('#cancel_prior').datetimepicker({
         
       format: 'HH'
        
     }).on('dp.change',function(e){
         
    });
   
</script>
  
    
