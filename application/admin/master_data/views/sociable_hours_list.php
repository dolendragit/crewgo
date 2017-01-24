<table id="datatable" class="table table-striped table-bordered">
        <thead>
            <tr>
              <th >S.N.</th>
              <th>Country</th>
              <th>Region</th>
              <th>Days</th>  
              <th>Time</th>
              <th>Sociable factor </th>
              <th>Non-sociable Hours</th>
              <th>NS peak</th>
              <!--<th>Status</th>-->

              <th  style="vertical-align: middle">Action</th>
          </tr>

        </thead>


         <tbody>
                <?php
               
                if($sociable_hours)
                {
                    $s=1;
                    foreach($sociable_hours as $sociable_hour)
                    {
                        
                      
                ?>
                    <tr>
                      <td ><?php echo $s;?></td>
                      <td ><?php echo $sociable_hour->country_name;?></td>
                      <td ><?php echo $sociable_hour->region_name;?></td>
                      <td><?php echo $days[$sociable_hour->days];?></td>
                      <td><?php echo $this->model_master_data->formatTime($sociable_hour->sociable_from_hour).' - ',$this->model_master_data->formatTime($sociable_hour->sociable_to_hour);?></td>
                      <td><?php echo $sociable_hour->sociable_factor;?></td>
                      <td><?php echo $sociable_hour->non_sociable_hour;?></td>
                      <td><?php echo $this->model_master_data->formatTime($sociable_hour->ns_peak);?></td>
                      <!--<td>
                           <?php
                          if($sociable_hour->status == '1')
                          {
                            echo '<span class="label label-success">Show</span>'; 
                          }
                          else
                          {
                              echo '<span class="label label-default">Hide</span>';
                              
                          }
                         ?>
                          
                      </td>-->
                      <td>
                        <a href="<?php echo base_url('admin/master_data/sociable_hours/edit').'/'.$sociable_hour->id;?>" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
                        <a href="<?php echo base_url('admin/master_data/sociable_hours/remove').'/'.$sociable_hour->id;?>" onclick="return confirm('Do you really want to delete this data?')" title="Delete"><span class="glyphicon glyphicon-remove"></span></a>
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