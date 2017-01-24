<link href="<?php echo base_url('assets/admin/vendors/select2/dist/css/select2.min.css');?>" rel="stylesheet">
<!-- page content -->
<div class="right_col" role="main">
  <!-- top tiles -->
  <div class="">
    <div class="page-title">
      <div class="title_left">
       <h3>Message Logs</h3>
   </div>
</div>
<div class="clearfix"></div>
<?php displayMessages();?>  
<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_content">
          <div class="panel panel-default">
            <div class="panel-body">
              <form class="form-horizontal">
                <div class="box-body">
                  <div class="form-group col-sm-5 text-center">
                      <label class="col-sm-4 control-label">Mobile Number</label>
                      <div class="col-sm-7">
                          <input type="text" data-column="0" class="search-input-text form-control">
                      </div>
                  </div>
                  <div class="form-group col-sm-5 text-center">
                    <label class="col-sm-4 control-label">Job ID</label>
                    <div class="col-sm-7">
                      <input type="text" data-column="1"  class="search-input-text form-control">
                  </div>
              </div>
              <div class="col-sm-2"></div>
              <div class="clearfix"></div>
              <div class="form-group col-sm-3">
                <label class="col-sm-5 control-label">Client</label>
                <div class="col-sm-7">
                  <select data-column="2"  class="search-input-select select-client">
                    <?php 
                    if($customers) {
                      ?>
                      <option value="">Select Client</option> 
                      <?php
                      foreach ($customers as $key => $customer) {
                        ?>
                        <option value="<?php echo $customer->customer_id ?>"><?php echo $customer->customer_name; ?></option> 
                        <?php 
                    }
                } else { 
                  ?>
                  <option value="">No clients found. </option>
                  <?php }
                  ?>
              </select>
          </div>
      </div>
       <div class="form-group col-sm-3">
            <label class="col-sm-3 control-label">Staff</label>
            <div class="col-sm-8">
              <input type="text" data-column="3"  class="search-input-text form-control">
          </div>
      </div>
      <div class="form-group col-sm-6">
          <label class="col-sm-2 control-label">Date</label>
          <div class="col-sm-4  input-group date">
            <div class="input-group-addon">
              <i class="fa fa-calendar"></i>
          </div>
          <input type="text" data-column="4" class="search-input-text form-control datepicker">
        </div>
      <label class="col-sm-1 control-label">to</label>
      <div class="col-sm-4  input-group date">
        <div class="input-group-addon">
          <i class="fa fa-calendar"></i>
      </div>
      <input type="text" data-column="5" class="search-input-text form-control datepicker">
  </div>
</div>
<div class="clearfix"></div>
</div>
</form>
</div>
</div>
<table id="datatable" class="table">
    <thead>
      <tr>
        <th>S.N.</th>
        <th width="10%">LHC</th>
        <th width="15%">Date</th>
        <th width="5%">Job ID</th>
        <th width="15%">Staff</th>
        <th width="2%"></th>
        <th width="10%">Customer/Supervisor</th>
        <th width="5%">Shift Status</th>
        <th>Message</th>
    </tr>
</thead>
<tbody>
</tbody>
</table>
</div>
</div>
</div>
</div>
<script src="<?php echo base_url('assets/admin/vendors/select2/dist/js/select2.min.js');?>"></script>
<script src="<?php echo base_url('assets/js/jquery-ui.js');?>"></script>
<script type="text/javascript">
  var base_url = '<?php echo base_url() ?>';
  $('.select-client').select2();

  var table;
  $(document).ready(function() {

        //datatables
        table = $('#datatable').DataTable({ 

            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
              "url": base_url+"admin/messages/ajax_message_log",
              "type": "POST"
          },

            //Set column definition initialisation properties.
            "columnDefs": [
            { 
                "targets": [ 0 ], //first column / numbering column
                "orderable": false, //set not orderable
            },
            ],

        });
        $("#datatable_filter").css("display","none");
          $('.search-input-text').on( 'keyup change, paste', function () {   // for text boxes
            var i =$(this).attr('data-column');  // getting column index
            var v =$(this).val();  // getting search input value
            table.columns(i).search(v).draw();
        } );

          $('.search-input-select').on( 'change', function () { // for select box
            var i =$(this).attr('data-column');  
            var v =$(this).val();  
            table.columns(i).search(v).draw();
        } );

          $( ".datepicker" ).datepicker({
            dateFormat: "yy-mm-dd",
            onSelect: function(dateText, inst) {
                var i =$(this).attr('data-column');  
                var v =$(this).val();  
                table.columns(i).search(v).draw();
            }
        });

      });

  </script>
</div>
</div>