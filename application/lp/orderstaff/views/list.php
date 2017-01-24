
        <!-- page content -->
        <div class="right_col" role="main">
          <!-- top tiles -->
         <div class="">
            <div class="page-title">
              <div class="title_left">
              	<h3>Re-order staff</h3>
              </div>
				 
			
              
            </div>
            <div class="clearfix"></div>
			
            <div class="clearfix"></div>
			
		
	
			
		<div class="row">
    <?php $this->load->view('home/alert');?>
          <div class="panel panel-default">
              <div class="panel-body">
                 <div class="col-sm-6">
                    <form role="form" method="get">
                      <div class="form-group col-sm-3">
                        <select class="form-control skill" name="sk_id" required>
                          <option value="">Select skill</option>
                             <?php foreach ($industry as $val): ?>
                              <option value="<?php echo $val->id; ?>" <?php echo ($this->input->get('sk_id') == $val->id) ? 'selected' : '';?>><?php echo $val->name; ?></option>
                             <?php endforeach;?>
                        </select>
                      </div>

                       <div class="form-group col-sm-3">
                        <select class="form-control level" name="l_id">
                          <option value="">Select Level</option>
                        </select>
                      </div>

                       <div class="form-group col-sm-2">
                        <button type="submit" class="btn btn-primary">Search</button>
                      </div>
                    </form>
                 </div>


                 <div class="col-sm-6">
                  <div class="row">
                    <div class="col-sm-6">
                       <div class="checkbox-wrapper">
                         <input type="checkbox" name="globalorder" class="orders">&nbsp; <span>Set Global Order</span>
                        </div>
                    </div>

          <form method="post" action="<?php echo base_url('lp/orderstaff');?>">
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-default">Reorder Staff</button>
                                </div>
                              </div>
                             </div>
                         </div>
                      </div>
                </div>



      <div class="clearfix"></div>


      <div class="row">
        <div class="col-md-3 col-sm-3 col-xs-3">
          <div class="panel panel-default">
            <div class="panel-heading">
              Select Shifts
            </div>

            <div class="panel-body" style="max-height: 480px; overflow-y: scroll;">
              <ul class="shifts">
                <?php foreach($shifts as $value):?>
                <li class="shift-holder">
                   <input type="checkbox" name="jobs[]" value="<?php echo $value->id;?>" class="jobs" <?php echo(checkJobStatus($value->id) > 0) ? 'checked disabled' : '';?>> &nbsp; 
                   <span class="job-listing" attr="<?php echo $value->id;?>" style="cursor:pointer;">
                     <?php echo $value->job_postcode_id;?>, <?php echo $value->start_date;?>, 
                     <?php echo $value->job_full_address ;?>, <?php echo $value->job_street;?> <br/> 
                     <?php echo $value->start_time;?> - <?php echo $value->end_time;?>
                     <?php if(checkJobStatus($value->id) > 0):?><span class="badge btn btn-success" style="font-size:8px;">Assigned</span><?php endif;?>
                  </span>
                </li>
               <?php endforeach;?>
               <?php echo form_error('jobs[]'); ?>
              </ul>
            </div>
          </div>
        </div>


       

  			<div class="col-md-9 col-sm-9 col-xs-9">

               <img src="<?php echo base_url('assets/img/loading.gif');?>" style="display:none;max-width:150px;margin-left: 40%;" class="loading">
                  <div class="x_panel">
                    
                    <div class="x_content">
                       
                      <table id="mytable" class="table table-striped table-bordered">
                        <thead>
                          <tr>
                            <th>&nbsp;</th>
                            <th>Name</th>
                            <th>Phone No.</th>
                            <th>Email</th>
                            <th>Join Date</th>
                          </tr>
                        </thead>


                        <?php
                          $userIds = array();                        
                          foreach ($staff as $key => $value) {
                            $userIds[] = $value->id;
                          }
                          $formatted = implode(",", $userIds);
                        ?>

                        <tbody class="staff-holder">
                          <input type="hidden" name="userid" id="myuserid" value="<?php echo $formatted;?>"> 
                          <input type="hidden" name="jobs[]" class="myjobid">
                          <?php foreach($staff as $key => $val): ?>
                            <tr myattr="<?php echo $val->id;?>">
                              <td style="text-align: center;"><i style="cursor: pointer;" class="fa fa-list fa-1x"></i></td>
                              <td><?php echo ($val->name != "") ? $val->name : 'N/A' ;?></td>
                              <td><?php echo ($val->phone_number != "") ? $val->phone_number : 'N/A' ;?></td>
                              <td><?php echo $val->email;?></td>
                              <td><?php echo ($val->created_on != "") ? $val->created_on : 'N/A' ;?></td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>


                          <tfoot>
                          <tr>
                            <th>&nbsp;</th>
                            <th>Name</th>
                            <th>Phone No.</th>
                            <th>Email</th>
                            <th>Join Date</th>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>
        </div>
			</div>
      </form>
    </div>
</div>

<script type="text/javascript">

$(function() {
  $('.skill').trigger('change');
});


  $('.skill').on('change', function(){
    var skill = $('.skill option:selected').val();
     $.ajax({
        type: "POST",
        url:  "<?php echo base_url();?>" + "lp/staff/industrySkill",
        data: {myvalue: skill},
        dataType: 'json',
        cache:false,
        success: function(result){
         $('.level').html('');
        // var subskill = [];
         var fromurl = <?php echo $this->input->get('l_id');?>

          $.each(result, function(key, val){

            var textselect
            if (fromurl == val.id){
              textselect = 'selected';
            } else {
              textselect = '';
            }

           subskill = `<option value="`+val.id+`"`+textselect+`>`+val.name+`</option>`;
            //subskill = `<option value="`+val.id+`">`+val.name+`</option>`;
           $('.level').append(subskill);
          });
        }
      });
  });


  $('.orders').change(function(){
    if($(".orders").prop('checked') == true){
      $('.jobs:not(:disabled)').prop('checked', true);
    } else {
        $('.jobs:not(:disabled)').prop('checked', false);
    }
  });




 /* $(document).on('change', 'input.jobs', function(){

    if($(this).closest('input.jobs').prop('checked') == true){
      var selectedJob = $(this).closest('input.jobs').val();

      $.ajax({
        type: "POST",
        url: "<?php echo base_url();?>" + "lhc/orderstaff/staffdata",
        data: {myvalue: selectedJob},
        dataType: 'json',
        cache:false,
        beforeSend: function(){
          $('.loading').css('display', 'block');
        },
        success: function(result){
          $('.staff-holder').find('tr').remove();
          //$('#myuserid').val('');
          //$('#myuserid').val(result.users);
          $('.staff-holder').append(result.cols);
          $('.loading').css('display', 'none');
          //$('.staff-holder').append(`<input type='hidden' name='userid' id='myuserid' value=`+result.users+`>`);
        }
      });
    } else {
      $('.staff-holder').html('');
    }
    
  });
*/




$(document).on('click', '.job-listing', function(){
   
    var doc = $(this);
    var selectedJob = doc.closest('span').attr('attr');

    $.ajax({
        type: "POST",
        url: "<?php echo base_url();?>" + "lp/orderstaff/staffdata"+ "?sk_id=<?php echo $this->input->get('sk_id');?>&l_id=<?php echo $this->input->get('l_id');?>",
        data: {myvalue: selectedJob},
        dataType: 'json',
        cache:false,
        beforeSend: function(){
          $('.loading').css('display', 'block');
        },
        success: function(result){
          $('.staff-holder').find('tr').remove();
          $('.staff-holder').find('.myjobid').val('');
          $('.staff-holder').append(result.cols);
          $('.staff-holder .myjobid').val(selectedJob);
          $('.loading').css('display', 'none');
          $('.jobs:not(:disabled)').prop('checked', false);
          doc.parent().find('.jobs').prop('checked', true);
        }
      });
});







  $(document).ready(function(){
          $("#mytable tbody").sortable({
        //items: "> tr:not(:first)",
          appendTo: "parent",
          helper: "clone",
          stop: function(event, ui){
          var data = $(this).sortable('toArray', { attribute: 'myattr' });
           $('#myuserid').val(data.filter(v=>v!=''));
           
          }
      }).disableSelection();
  });

</script>