
        <!-- page content -->
        <div class="right_col" role="main">
          <!-- top tiles -->
         <div class="">
            <div class="page-title">
              <div class="title_left">
              	
              </div>
				 
			
              
            </div>
            <div class="clearfix"></div>
			
            <div class="clearfix"></div>
			
		
	
			
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  
                  <div class="x_content">
                   <?php $this->load->view('home/alert');?>
                    <table id="datatable" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Sn</th>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Address</th>
                          <th>Contact</th>
                          <th>Action</th>
                        </tr>
                      </thead>


                      <tbody>
                      <?php foreach($customers as $key => $val):?>
                        <tr>
                          <td><?php echo $key+1;?></td>
                          <td><?php echo $val->name;?></td>
                          <td><?php echo $val->email;?></td>
                          <td><?php echo $val->full_address;?></td>
                          <td><?php echo $val->phone_number;?></td>
                          <td><a href="<?php echo base_url('lhc/customer/edit/'.$val->id);?>"><i class="fa fa-pencil"></i></a> &nbsp; <a href="<?php echo base_url('lhc/customer/destroy/'.$val->id);?>" onclick="return confirm('Are you sure?')"><i class="fa fa-trash-o"></i></a></td>
                        </tr>
                      <?php endforeach;?>
                      </tbody>


                      <tfoot>
                        <tr>
                          <th>Sn</th>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Address</th>
                          <th>Contact</th>
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