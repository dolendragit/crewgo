
                  <div class="row" style="margin-top: 10px;">
                     <div class="col-sm-6">
                       <table class="table table-bordered">
                          <tr>
                            <th>Sn.</th>
                            <th>Title</th>
                            <th>Expiry</th>
                            <th>Preview</th>
                            <th>Action</th> 
                          </tr>


                          <?php foreach($qualification as $key => $value):?>
                            <tr>
                              <td><?php echo $key+1;?></td>
                              <td><?php echo $value->name;?></td>
                              <td><?php echo $value->expiry_date;?></td>
                              <td><img src="<?php echo base_url('assets/uploads/qualifications/'.$value->document_name);?>" class="img-thumbnail" style="max-width: 60px;"></td>
                              <td><a href="<?php echo base_url('lp/staff/editqualification/'.$value->id);?>"><i class="fa fa-pencil"></i></a> &nbsp; <a href="<?php echo base_url('lp/staff/deletequalification/'.$value->id . '/'.$value->staff_user_id);?>" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i></a> </td>
                            </tr>
                          <?php endforeach;?>
                       </table>
                     </div>

                     <div class="col-sm-6">
                       <div class="panel panel-default">
                         <div class="panel panel-heading">
                           Add New Qualification
                         </div>

                         <div class="panel panel-body">
                          <div class="row-fluid">
                            <form action="<?php echo base_url('lp/staff/addqualification');?>" method="post" enctype="multipart/form-data">
                              <input type="hidden" name="myuserid" value="<?php echo $user->id;?>">
                              <div class="form-group">
                                <label>Title</label>
                                <input type="text" name="title" class="form-control" required> 
                              </div>

                               <div class="form-group">
                                <label>Expiry Date</label>
                                <input type="text" name="expiry_date" class="form-control mydatepicker" autocomplete="off" required>
                              </div>

                                <div class="form-group">
                                <label>Document</label>
                                <input type="file" name="document" class="form-control">
                              </div>

                              <button type="submit" class="btn btn-sm btn-primary">Add Qualification</button>
                            </form>
                          </div>
                         </div>
                       </div>
                     </div>
                  </div>