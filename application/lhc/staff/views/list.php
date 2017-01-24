
        <!-- page content -->
        <div class="right_col" role="main">
          <!-- top tiles -->
         <div class="">
            <div class="page-title">
              <div class="title_left">
              	<h3> Staff List </h3>
              </div>
				 
			
              
            </div>
            <div class="clearfix"></div>
			
            <div class="clearfix"></div>
			
		
	
			
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  
                  <div class="x_content">
                     
                    <table id="datatable" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Sn</th>
                          <th>Name</th>
                          <th>Phone No.</th>
                          <th>Skills > SubSkill</th>
                          <th>Join Date</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>

                      <tbody>
                        <?php foreach($staff as $key => $val): ?>
                          <tr>
                            <td><?php echo $key+1;?></td>
                            <td><?php echo ($val->name != "") ? $val->name : 'N/A' ;?></td>
                            <td><?php echo ($val->phone_number != "") ? $val->phone_number : 'N/A' ;?></td>
                            <td><?php echo (getSkills($val->skill_id, $val->level_id )!= "") ? getSkills($val->skill_id, $val->level_id ) : ''?></td>
                            <td><?php echo ($val->created_on != "") ? $val->created_on : 'N/A' ;?></td>
                            <td><?php echo ($val->active == 1) ? 'Active' : 'Suspended' ;?></td>
                            <td><a href="<?php echo base_url('lhc/staff/edit/'.$val->id);?>"><i class="fa fa-pencil"></i></a></td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>


                        <tfoot>
                        <tr>
                          <th>Sn</th>
                          <th>Name</th>
                          <th>Phone No.</th>
                          <th>Skills > SubSkill</th>
                          <th>Join Date</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </tfoot>


                  
                    </table>
                  </div>
                </div>
              </div>
			</div>
			
			<script type="text/javascript">
			  
			$('#datatable').DataTable();
			 
			</script>
			
			<!--END of Listing Page-->

             
          </div>
        </div>
        <!-- /page content -->