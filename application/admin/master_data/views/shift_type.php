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
   
                    <form id="frm_shift_type" name="frm_shift_type" data-parsley-validate class="form-horizontal form-label-left" onsubmit=" return validateForm()" method="post">
                   <div class="form-group">
                        <label class="col-sm-1" for="name">Name <span class="required">*</span>
                        </label>
                        <div class="col-sm-3">
                            <input type="text" id="name" name="name" required="required" value="<?php echo set_value('name',@$shift_edit->name);?>" class="form-control">                        
                         
                         
                        </div>
                          
                               
                      </div>
                      <div class="form-group">
                          
                       <label class="col-sm-2 " for="time_from">Shift time:  from <span class="required">*</span>
                        </label>
                          <div class="col-sm-2   " >
                              <input type="text"  id="time_from" required="required" name="time_from" value="<?php echo set_value('time_from',@$shift_edit->time_from);?>"   class="form-control" maxlength="10"  >
                          </div>
                          <label class="col-sm-1  " for="To"><span class="input-group-addon">To</span></label>
                          <div class="col-sm-2   " >
                         <input type="text" id="time_to" name="time_to" required="required" value="<?php echo set_value('time_to',@$shift_edit->time_to);?>"   class="form-control" maxlength="10">
                           
                            </div>     
                          
                      </div>
                        
                        
             
                        
                
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class=" col-sm-6  col-md-offset-3">
                        <input type="hidden" name="hidden_id" id="hidden_id" value="<?php echo @$shift_edit->id;?>">
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
                            <th >S.N.</th>
                             <th>Name</th>
                            <th>Time From</th>
                            <th>Time To</th>  
                                                   
                            <th  style="vertical-align: middle">Action</th>
                        </tr>
                        
                      </thead>


                      <tbody>
                <?php
                if($shift)
                {
                    $s=1;
                    foreach($shift as $record)
                    {
                         
                        
                      
                ?>
                    <tr>
                      <td ><?php echo $s;?></td>
                      <td><?php echo $record->name;?></td>
                      <td><?php echo $this->model_master_data->formatTime($record->time_from);?></td>
                      <td><?php echo $this->model_master_data->formatTime($record->time_to);?></td>
                      
                       
                      <td>
                        <a href="<?php echo base_url('admin/master_data/shift_type/edit').'/'.$record->id;?>" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
                        <a href="<?php echo base_url('admin/master_data/shift_type/remove').'/'.$record->id;?>" onclick="return confirm('Do you really want to delete this data?')" title="Delete"><span class="glyphicon glyphicon-remove"></span></a>
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
      
        
      var invalid = [];
      if(name==""){
           error_msg += " -- Name is required.</br>";
            invalid.push('name');
       }
        if(!jQuery('#time_from').val()){
           error_msg += " -- Start time is required.</br>";
            invalid.push('time_from');
       }
       
       if(!jQuery('#time_to').val()){
           error_msg += " -- End time is required.</br>";
            invalid.push('time_to');
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
     $('#time_to').datetimepicker({
         
       format: 'HH:mm A'
        
     }).on('dp.change',function(e){
        //getSociableHourNSpeak();
    });
      $('#time_from').datetimepicker({
         
       format: 'HH:mm A'
        
     }).on('dp.change',function(e){
        //getSociableHourNSpeak();
    });
      
     
     

</script>
  <script type="text/javascript">
 // $('#region_id').select2();
</script>
    
