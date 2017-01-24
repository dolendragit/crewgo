
        <!-- page content -->
        <div class="right_col" role="main">
          <!-- top tiles -->
         <div class="">
            <div class="page-title">
              <div class="title_left">
               <h3>User Group</h3>
              </div>
				 
			
              
            </div>
            <div class="clearfix"></div>
			<?php displayMessages();?>	
            <div class="clearfix"></div>
			 
			
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  
                  <div class="x_content">
                     
                    <table id="datatable" class="table table-striped table-bordered">
                      <thead>
                        <tr>
						 <th>S.N.</th>
                          <th>User group</th>
                           
                          <th>Action</th>
                        </tr>
                      </thead>


                      <tbody>
					  <?php
							  if($user_groups)
							  {
								  $u=1;
								  foreach($user_groups as $group_user)
								  {
									  if($group_user->id > 2)
										  continue;
					?>
                        <tr>
						  <td><?php echo $u;?></td>
                          <td><?php echo $group_user->description;?></td>
                          
                           <td>
						  <a href="<?php echo base_url('admin/master_setup/user_priv').'/'.$group_user->id;?>" title="Group Privillege"><span class="glyphicon glyphicon-lock"></span></a> 
						  
						  </td>
                        </tr>
							  <?php $u++;} } ?>
                         
                         
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
			</div>
			
			 
			<!--END of Listing Page-->

             
          </div>
        </div>
        <!-- /page content -->