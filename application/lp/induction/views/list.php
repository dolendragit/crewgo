
        <!-- page content -->
        <div class="right_col" role="main">
          <!-- top tiles -->
         <div class="">
            <div class="page-title">
              <div class="title_left">
              	 <h3> Induction List </h3>
              </div>
				 
			
              
            </div>
            <div class="clearfix"></div>
			
            <div class="clearfix"></div>
			
		
	
			
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                   <?php $this->load->view('home/alert');?>
                  <div class="x_content">
                     
                    <table id="datatable" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Sn</th>
                          <th>Provider</th>
                          <th>Document Id</th>
                          <th>Url</th>
                          <th>Expiry</th>
                          <th>Action</th>
                        </tr>
                      </thead>

                      <tbody>
                        <?php foreach ($induction as $key => $val): ?>
                          <tr>
                            <td><?php echo $key+1;?></td>
                            <td><?php echo $val->provider_name;?></td>
                            <td><?php echo $val->document_id;?></td>
                            <td><?php echo $val->document_url;?></td>
                            <td><?php echo $val->expiry_date;?></td>
                            <td><a href="<?php echo base_url('lp/induction/edit/'.$val->id);?>"><i class="fa fa-pencil"></i></a></td>
                          </tr>
                        <?php endforeach;?>
                      </tbody>


                       <tfoot>
                        <tr>
                          <th>Sn</th>
                          <th>Provider</th>
                          <th>Document Id</th>
                          <th>Url</th>
                          <th>Expiry</th>
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