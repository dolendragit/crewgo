
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
                        <input type="hidden" name="hidden_id" id="hidden_id" value="<?php echo @$priority->id;?>">
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
                          <th>Score</th>
                          <th>Is mandatory</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>


                      <tbody>
                <?php
                if($priorities)
                {
                    $s=1;
                    foreach($priorities as $priority)
                    {
                ?>
                    <tr>
                      <td style="cursor:move" ><?php echo $s;?></td>
                      <td><?php echo $priority->name;?>
                       <input type="hidden" name="data_id" value="<?php echo $priority->id;?>" class="data_id">
                      </td>
                      <td><?php echo $priority->score;?></td>
                      <td><?php echo $priority->is_mandatory == 0?'No':'Yes';?></td>
                       
                      <td id="master_data_status_<?php echo $priority->id;?>">
                          <?php
                          echo  getStatusData($priority->id,$priority->status,'priority');
                         ?>
                      </td>
                      <td>
                        <a href="<?php echo base_url('admin/master_data/priority/edit').'/'.$priority->id;?>" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
                        <a href="<?php echo base_url('admin/master_data/priority/remove').'/'.$priority->id;?>" onclick="return confirm('Do you really want to delete this data?')" title="Delete"><span class="glyphicon glyphicon-remove"></span></a>
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
			  
			var table = $('#datatable').DataTable( {
                        rowReorder: true
                        } );
                        
                       table.on( 'row-reorder', function ( e, diff, edit ) {
                           var data = []; //initialize array
                            
                       // var result = 'Reorder started on row: '+edit.triggerRow.data()[1]+'<br>';
                        
                        for ( var i=0, ien=diff.length ; i<ien ; i++ ) {
                            var rowData = table.row( diff[i].node ).data();
                            var table_data_id = {};
                            tr_html = diff[i];
                        
                            tr_index = tr_html.newPosition;
                            table_data_id.data_id = $('.data_id:eq('+tr_index+')').val();
                            table_data_id.index = tr_index;
                             
                             data.push(table_data_id);
                            //alert(tr_html);

                           // result += rowData[1]+' updated to be in position '+
                               // diff[i].newData+' (was '+diff[i].oldData+')<br>';
                        }
                        data = JSON.stringify(data);//json
                        changeMasterDataOrder('<?php echo base_url('admin/master_data/changeTableOrder');?>','priority',data);

                       // $('#result').html( 'Event result:<br>'+result );
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
      score = $('input[name=score]').val();
      status = $('input[name=status]').val();
       
      var invalid = [];
          if(name==""){
           error_msg += " -- Title is required.</br>";
            invalid.push('name');
       }
       if(score==""){
           error_msg += " -- Score is required.</br>";
            invalid.push('score');
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