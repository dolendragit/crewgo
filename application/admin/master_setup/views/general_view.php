<link href="<?php echo base_url('assets/admin/select2/css/select2.css');?>" rel="stylesheet">
 <script src="<?php echo base_url('assets/admin/select2/js/select2.js');?>"></script>
 <script src="https://cdn.datatables.net/select/1.2.0/css/select.dataTables.min.css"></script>
<script src="https://cdn.datatables.net/rowreorder/1.1.2/css/rowReorder.dataTables.min.css"></script>
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
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                   
                  <div class="x_content">
                    <br />
                    <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">First Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="first-name" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Last Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="last-name" name="last-name" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Middle Name / Initial</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="middle-name" class="form-control col-md-7 col-xs-12" type="text" name="middle-name">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Gender</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <div id="gender" class="btn-group" data-toggle="buttons">
                            <label class="btn btn-default" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                              <input type="radio" name="gender" value="male"> &nbsp; Male &nbsp;
                            </label>
                            <label class="btn btn-primary" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                              <input type="radio" name="gender" value="female"> Female
                            </label>
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Date Of Birth <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="birthday" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text">
                        </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
						<button type="submit" class="btn btn-success">Submit</button>
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
                      <div id="result"></div>
                    <table id="datatable" class="table table-striped table-bordered">
                      <thead>
                         
                        <tr>
                          
                          <th>S.N.</th>
                          <th>Name</th>
                          <th>Position</th>
                          <th>Office</th>
                          <th>Age</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>


                      <tbody>
                        <tr data-id="1">
                        
                          <td >1</td>
                          <td>Tiger Nixon
                              <input type="hidden" name="data_id" value="1" class="data_id">
                          </td>
                          <td>System Architect</td>
                          <td>Edinburgh</td>
                          <td>61</td>
                          <td><span class="label label-default"> Passive</span></td>
                          <td>
						  <a href="#" title="edit"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
						  <a href="#" title="remove"><span class="glyphicon glyphicon-remove"></span></a>
						  </td>
                        </tr>
                        <tr data-id="2">   
                        <td style="cursor:move">2</td>
                            
                          <td>Garrett Winters
                           <input type="hidden" name="data_id" value="2" class="data_id">
                          </td>
                          <td>Accountant</td>
                          <td>Tokyo</td>
                          <td>63</td>
                          <td><span class="label label-success">Active</span></td>
                          <td>
						  <a href="<?php echo base_url('admin/master_setup/general_view/edit/1');?>" title="edit"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
						  <a href="<?php echo base_url('admin/master_setup/general_view/remove/1');?>" title="remove"><span class="glyphicon glyphicon-remove"></span></a>
						  </td>
                        </tr>
                         <tr data-id="3">   
                        <td style="cursor:move">3</td>
                            
                          <td>Garrett Winters
                              <input type="hidden" name="data_id" id="data_id" value="3" class="data_id">
                          </td>
                          <td>Accountant</td>
                          <td>Tokyo</td>
                          <td>63</td>
                          <td><span class="label label-success">Active</span></td>
                          <td>
						  <a href="<?php echo base_url('admin/master_setup/general_view/edit/1');?>" title="edit"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
						  <a href="<?php echo base_url('admin/master_setup/general_view/remove/1');?>" title="remove"><span class="glyphicon glyphicon-remove"></span></a>
						  </td>
                        </tr>
                         
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
                           var data = []; 
                            
                            //selected_row_data = edit.triggerRow.data()[1];
                            //alert(selected_row_data.find('input').val());
                          
                        var result = 'Reorder started on row: '+edit.triggerRow.data()[1]+'<br>';
                        
                        for ( var i=0, ien=diff.length ; i<ien ; i++ ) {
                            var rowData = table.row( diff[i].node ).data();
                            var table_data_id = {};
                            tr_html = diff[i];
                        
                            tr_index = tr_html.newPosition;
                            table_data_id.data_id = $('.data_id:eq('+tr_index+')').val();
                            table_data_id.index = tr_index;
                             
                             data.push(table_data_id);
                            //alert(tr_html);

                            result += rowData[1]+' updated to be in position '+
                                diff[i].newData+' (was '+diff[i].oldData+')<br>';
                        }
                        data = JSON.stringify(data);
                        changeMasterDataOrder('<?php echo base_url('admin/master_data/changeTableOrder');?>','test',data);

                        $('#result').html( 'Event result:<br>'+result );
                    } ); 
 
			 
			</script>
			<?php } ?>
			<!--END of Listing Page-->

             
          </div>
        </div>
        <!-- /page content -->