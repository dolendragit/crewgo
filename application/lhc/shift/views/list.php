
        <!-- page content -->
<div class="right_col" role="main">
          <!-- top tiles -->
            <div class="page-title">
              <div class="title_left">
              	<h3>Click the dates</h3>
              </div>
            </div>


            <div class="clearfix"></div>
		
			
      <div class="row">
        <div class="col-sm-8 col-sm-offset-4 calendar-holder">
            <div id="datepicker"></div>
        </div>
        <img src="<?php echo base_url('assets/img/loading.gif');?>" style="display:none;max-width:150px;margin-left: 40%;" class="loading">
      </div>


      <div class="row my-alert" style="margin-top: 10px; display: none;">
        <div class="col-sm-8 col-sm-offset-2">
          <div class="alert alert-danger">No Records Found..</div>
        </div>
      </div>

      <div class="row shift-table" style="margin-top: 20px; display: none;">
        <div class="col-sm-12">
          <table class="table table-condensed">
          <thead>
            <tr>
              <th>Job No.</th>
              <th>Date</th>
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
              
            </tbody>

          </table>
        </div>
      </div>


			
</div>

<script type="text/javascript">
  $(function(){
    $( "#datepicker" ).datepicker({
      onSelect: function(){
        var selectedDate = $(this).val();
        
        $.ajax({
          type: "POST",
          url: "<?php echo base_url();?>" + "lhc/shift/shiftsByDate",
          data: {mydate: selectedDate},
          dataType: 'json',
          cache:false,
          beforeSend: function(){
              $('.loading').css('display', 'block');
              $('.shift-table').css('display', 'none');
            },
          success: function(result){
              $('.shift-data').html('');
              $('.loading').css('display', 'none');
              if (result == ""){
                $('.my-alert').css('display', 'block');
              } else {
                $('.my-alert').css('display', 'none');
                $('.shift-table').css('display', 'block');
                $('.shift-data').append(result);
              }
             
          }

        });
      }
    });
  });
</script>