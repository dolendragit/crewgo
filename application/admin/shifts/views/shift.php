<style type="text/css">
     .form-control{
    height: 23px;
    padding: 2px 12px;
    font-size: 11px;
}

   .content-wrapper{
        margin: 10px;
    }

</style>

<!-- page content -->
<div class="right_col" role="main">
    <!-- top tiles -->
    <div class="page-title">
        <div class="title_left">
            <h3>
                Shifts - Search by
            </h3>
        </div>
    </div>
    <div class="clearfix">
    </div>


    <!--main content-->
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-content">
                <div class="row content-wrapper">
                    <div class="col-sm-4">
                        <form class="form-horizontal" method="get" action="<?php echo base_url('admin/shifts');?>">
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Job Id</label>
                            <div class="col-sm-6">
                              <input type="text" name="jo_id" class="form-control" value="<?php echo ($this->input->get('jo_id') != "") ? $this->input->get('jo_id'): '';?>">
                            </div>
                          </div>

                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Date</label>
                            <div class="col-sm-9">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <input type="text" name="jo_from" placeholder="from" class="form-control datepicker" value="<?php echo ($this->input->get('jo_from') != "") ? $this->input->get('jo_from'): '';?>">
                                    </div>

                                    <div class="col-sm-4">
                                        <input type="text" name="jo_to" placeholder="to" class="form-control datepicker" value="<?php echo ($this->input->get('jo_to') != "") ? $this->input->get('jo_to'): '';?>">
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                          </div>


                           <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Job Region</label>
                            <div class="col-sm-6">
                              <select class="form-control" name="jo_region">
                                <option value="">Select Job Region</option>
                                <?php foreach($region as $val) :?>
                                    <option value="<?php echo $val->id;?>"><?php echo $val->name;?></option>
                                <?php endforeach;?>
                              </select>
                            </div>
                          </div>


                           <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Staff Name</label>
                            <div class="col-sm-6">
                              <input type="text" name="st_na" class="form-control" value="<?php echo ($this->input->get('st_na') != "") ? $this->input->get('st_na'): '';?>">
                            </div>
                          </div>

                           <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Job Skill</label>
                            <div class="col-sm-6">
                              <select class="form-control" name="jo_sk">
                                <option value="">Select Job skill</option>
                                <?php foreach($skill as $val) :?>
                                    <option value="<?php echo $val->id;?>" <?php echo ($this->input->get('jo_sk') == $val->id) ? 'selected' : '';?> ><?php echo $val->name;?></option>
                                <?php endforeach;?>
                              </select>
                            </div>
                          </div>

                           <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Job status</label>
                            <div class="col-sm-6">
                              <select class="form-control" name="jo_st">
                                <option value="">Select Job status</option>
                                <option value="0" <?php echo ($this->input->get('jo_st') == 3 || $this->input->get('jo_st') == 4 ) ? 'selected' : '';?>>Scheduled</option>
                                <option value="1" <?php echo ($this->input->get('jo_st') == 1) ? 'selected' : '';?>>Completed</option>
                                <option value="2" <?php echo ($this->input->get('jo_st') == 2) ? 'selected' : '';?>>Running</option>
                              </select>
                            </div>
                          </div>

                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Venue</label>
                            <div class="col-sm-6">
                              <input type="text" name="ve" class="form-control" value="<?php echo ($this->input->get('ve') != "") ? $this->input->get('ve'): '';?>">
                            </div>
                          </div>

                    </div>


                </div>
            </div>

            <div class="panel-footer" style="margin-top: 10px;">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i>&nbsp;Filter</button>
            </div>
        </form>





        </div>

        

        <!--start of table-->
         <div class="row shift-table" style="margin-top: 20px;">

            <div class="col-sm-12">
              <table class="table  table-bordered table-condensed" id="mytable">
              <thead>
                <tr>
                  <th>Job No.</th>
                  <th>Time On-Off</th>
                  <th>Break</th>
                  <th>Hrs</th>
                  <th>Charge</th>
                  <th>Staff Name</th>
                  <th>Venue</th>
                  <th>Status</th>
                </tr>
              </thead>

             
        
                <tbody class="shift-data">
                  <?php foreach($allshift as $key => $val): ?>
                    <tr>
                        <td><?php echo $val['job_number'];?></td>
                        <td>
                        <?php foreach ($val['shift'] as $myvalue) :?>
                        <?php echo $myvalue['start_time'] . "&nbsp; &nbsp; &nbsp;" . $myvalue['end_time'] . "<br/>";?>
                        <?php endforeach;?> 
                        </td>
                        <td><?php echo number_format((float)$val['break'], 4, '.', '');?></td>
                        <td><?php echo (array_sum($val['totalhr']) > 500 ? 'N/A' :  array_sum($val['totalhr']));?></td>
                        <td><?php echo $val['total_cost'];?></td>
                        <td><?php echo $val['name'];?></td>
                        <td><?php echo $val['job_full_address'];?></td>
                        <td><?php if($val['job_status'] == 3 || $val['job_status'] == 4) { echo 'Scheduled';} elseif($val['job_status'] == 2) { echo 'Running';} elseif($val['job_status'] == 1){echo 'Complete';};?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>

              </table>
            </div>
          </div>

        <!--end of table-->
 
    </div>
    <!--end of main content-->
</div>



<script type="text/javascript">
    $('.datepicker').datepicker({
        dateFormat: 'yy-mm-dd'
    });


    $('#mytable').dataTable();
</script>


