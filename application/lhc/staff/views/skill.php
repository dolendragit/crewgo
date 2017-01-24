
                  <div class="row" style="margin-top: 10px;">
                     <div class="col-sm-6">
                       <table class="table table-bordered">
                          <tr>
                            <th>Sn.</th>
                            <th>Skill</th>
                            <th>Level</th>
                            <th>Action</th> 
                          </tr>


                          <?php foreach($skill as $key => $value):?>
                            <tr>
                              <td><?php echo $key+1;?></td>
                              <td><?php echo $value->skillname;?></td>
                              <td><?php echo $value->levelname;?></td>
                              <td><a href="<?php echo base_url('lhc/staff/editskills/'.$value->id);?>"><i class="fa fa-pencil"></i></a> &nbsp; <a href="<?php echo base_url('lhc/staff/deleteskill/'.$value->id . '/'.$value->staff_user_id);?>" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i></a> </td>
                            </tr>
                          <?php endforeach;?>
                       </table>
                     </div>

                     <div class="col-sm-6">
                       <div class="panel panel-default">
                         <div class="panel panel-heading">
                           Add New Skill
                         </div>

                         <div class="panel panel-body">
                          <div class="row-fluid">
                            <form action="<?php echo base_url('lhc/staff/addskill');?>" method="post">
                              <input type="hidden" name="myuserid" value="<?php echo $user->id;?>">
                              <div class="form-group">
                                <label>Skill</label>
                                <select class="form-control myskill" name="skill" required>
                                  <option value="">Choose Skill</option>
                                 <?php foreach($industry as $val): ?>
                                  <option value="<?php echo $val->id;?>"><?php echo $val->name;?></option>
                                 <?php endforeach;?>
                                </select>
                              </div>

                               <div class="form-group">
                                <label>Level</label>
                                <select class="form-control level" name="level" required>
                                </select>
                              </div>

                              <button type="submit" class="btn btn-sm btn-primary">Add Skill</button>
                            </form>
                          </div>
                         </div>
                       </div>
                     </div>
                  </div>