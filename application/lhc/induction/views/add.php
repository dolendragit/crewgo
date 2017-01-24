<!-- page content -->
<div class="right_col" role="main">
   <!-- top tiles -->
   <div class="page-title">
    <h4>Add New Induction</h4>
   </div>


   <div class="row" style="padding-top: 20px;">
     <?php $this->load->view('home/alert');?>
      <div class="panel panel-default">
         <div class="panel-heading">Add Induction</div>
         <div class="panel-body">
            <!--change password form-->
            <form method="post" action="<?php echo base_url('lhc/induction/add'); ?>" id="myinduction" enctype="multipart/form-data">

              <div class="form-group  <?php echo form_error('name') ? 'has-error' : '' ?>">
                <label for="exampleInputEmail1">Inductee Name</label>
                <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Name" name="name"  value="<?php echo set_value('name');?>">
                <?php echo form_error('name'); ?>
              </div>

               <div class="form-group  <?php echo form_error('email') ? 'has-error' : '' ?>">
                <label for="exampleInputEmail1">URL Link</label>
                <input type="text" class="form-control" id="exampleInputEmail1" placeholder="e.g http://test.com" name="url" value="<?php echo set_value('url');?>">
                <?php echo form_error('url'); ?>
              </div>

               <div class="form-group  <?php echo form_error('expiry') ? 'has-error' : '' ?>">
                <label for="exampleInputexpiry1">Expiry</label>
                <input type="text" class="form-control mydatepicker" id="exampleInputexpiry1" placeholder="Expiry Date" name="expiry" value="<?php echo set_value('expiry');?>">
                <?php echo form_error('expiry'); ?>
              </div>


              <div class="form-group  <?php echo form_error('document') ? 'has-error' : '' ?>">
                <label for="exampleInputaddress1">Document</label>
                <input type="file" class="form-control"  placeholder="Document" name="document" value="<?php echo set_value('document');?>">
                <?php echo form_error('document'); ?>
              </div>

               <div class="form-group  <?php echo form_error('provider') ? 'has-error' : '' ?>">
                <label for="exampleInputprovider1">Provider</label>
                <input type="text" class="form-control" id="exampleInputprovider1" placeholder="Provider" name="provider" value="<?php echo set_value('provider');?>">
                <?php echo form_error('provider'); ?>
              </div>


              <!--asd-->

    

        </div>




              <button type="submit" class="btn btn-default btn-sm">Add Induction</button>
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



  $('#myinduction').validate({
  rules: {
    name: "required",   
    url: "required",
    expiry: "required",
    document: "required",
    provider: "required"
  }
/*
  messages: {
    name: "Please enter name",
    email: "Please enter your email",
    address: "Please enter your address",
    from: "Please enter sociable hours from",
    to: "Please enter sociable hours to",
    workhours: "Please enter your work hour",
    "days[]": "Please choose days",
    contactable_from: "Please add contactable hour",
    contactable_to: "Please add contactable hour",
    "maincategory[]": "Please choose skills",
    transportation: "Please choose transportation option",
    "areas[]": "Please choose an area",
    driving_expiry: "Please choose an expiry date",
    induction: "Please choose induction"

  },
  errorPlacement: function(error, element) {
  if (element.attr("name") == "days[]")
      {
          error.insertAfter("ul.days");
      }
      else
      {
          error.insertAfter(element);
      }
  }*/
});

</script>