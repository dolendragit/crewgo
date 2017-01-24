<!-- page content -->
<div class="right_col" role="main">
   <!-- top tiles -->
   <div class="page-title">
    <h4>Edit Qualification</h4>
   </div>


   <div class="row" style="padding-top: 20px;">
     <?php $this->load->view('home/alert');?>
      <div class="panel panel-default">
         <div class="panel-heading">Edit Qualification</div>
         <div class="panel-body">
            <!--change password form-->
            <form method="post" action="<?php echo base_url('lp/staff/editqualification'); ?>" id="staff" enctype="multipart/form-data">

            <input type="hidden" name="rowid" value="<?php echo $qualification->id;?>">
            <input type="hidden" name="userid" value="<?php echo $qualification->staff_user_id;?>">

              <div class="form-group  <?php echo form_error('title') ? 'has-error' : '' ?>">
                <label for="exampleInputEmail1">Title</label>
                <input type="text" class="form-control" name="title" value="<?php echo $qualification->name;?>">
                <?php echo form_error('title'); ?>
              </div>

               <div class="form-group  <?php echo form_error('expiry_date') ? 'has-error' : '' ?>">
                <label for="exampleInputEmail1">Expiry Date</label>
                  <input type="text" class="form-control mydatepicker" name="expiry_date" value="<?php echo $qualification->expiry_date;?>">
                <?php echo form_error('expiry_date'); ?>
              </div>

                <div class="form-group  <?php echo form_error('document') ? 'has-error' : '' ?>">
                <label for="exampleInputEmail1">Document</label>
                <input type="file" class="form-control" name="document">
                <?php echo form_error('document'); ?>
              </div>

              <!--asd-->

    

        </div>




              <button type="submit" class="btn btn-default btn-sm">Update Qualification</button>
            </form>
            <!--change password form-->
         </div>
      </div>
   </div>
</div>
<!-- /page content -->


<script type="text/javascript">
  $('body').on('focus',".mydatepicker", function(){

    if( $(this).hasClass('hasDatepicker') === false )  {
        $(this).datepicker({
          changeYear: true,
          changeMonth: true,
          dateFormat: 'yy-mm-dd'
        });
    }

});

  
</script>
