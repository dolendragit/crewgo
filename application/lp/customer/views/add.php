<!-- page content -->
<div class="right_col" role="main">
   <!-- top tiles -->
   <div class="page-title">
    <h4>Add New Customer</h4>
   </div>


   <div class="row" style="padding-top: 20px;">
     <?php $this->load->view('home/alert');?>
      <div class="panel panel-default">
         <div class="panel-heading">Add Customer</div>
         <div class="panel-body">
            <!--change password form-->
            <form method="post" action="<?php echo base_url('lp/customer/add'); ?>" id="addcustomer" enctype="multipart/form-data">

              <div class="form-group  <?php echo form_error('name') ? 'has-error' : '' ?>">
                <label for="exampleInputEmail1">Customer's Name <span class="required">*</span></label>
                <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Name" name="name"  value="<?php echo set_value('name');?>">
                <?php echo form_error('name'); ?>
              </div>

               <div class="form-group  <?php echo form_error('email') ? 'has-error' : '' ?>">
                <label for="exampleInputEmail1">Email <span class="required">*</span></label>
                <input type="text" class="form-control" placeholder="Email address" name="email" value="<?php echo set_value('email');?>">
                <?php echo form_error('email'); ?>
              </div>

               <div class="form-group  <?php echo form_error('phone_number') ? 'has-error' : '' ?>">
                <label for="exampleInputexpiry1">Phone <span class="required">*</span></label>
                <input type="text" class="form-control" id="exampleInputexpiry1" placeholder="Contact no." name="phone_number" value="<?php echo set_value('phone_number');?>">
                <?php echo form_error('phone_number'); ?>
              </div>



               <div class="form-group  <?php echo form_error('full_address') ? 'has-error' : '' ?>">
                <label for="exampleInputprovider1">Address <span class="required">*</span></label>
                <input type="text" class="form-control" id="exampleInputprovider1" placeholder="Full Address" name="full_address" value="<?php echo set_value('full_address');?>">
                <?php echo form_error('full_address'); ?>
              </div>


              <!--asd-->

    

        </div>




              <button type="submit" class="btn btn-default btn-sm">Invite Access</button>
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


   $('#addcustomer').validate({
  rules: {
    name: "required",   
    email: {
      required: true,
      email: true
    },
    phone_number: "required",
    full_address: "required"
  }, 

 

});

</script>