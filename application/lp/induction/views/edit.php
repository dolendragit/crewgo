<!-- page content -->
<div class="right_col" role="main">
   <!-- top tiles -->
   <div class="page-title">
    <h4>Edit Induction</h4>
   </div>


   <div class="row" style="padding-top: 20px;">
     <?php $this->load->view('home/alert');?>
      <div class="panel panel-default">
         <div class="panel-heading">Edit Induction</div>
         <div class="panel-body">
            <!--change password form-->
            <form method="post" action="" id="editinduction" enctype="multipart/form-data">

            <input type="hidden" name="inductionid" value="<?php echo $induction->id;?>">

              <div class="form-group  <?php echo form_error('name') ? 'has-error' : '' ?>">
                <label for="exampleInputEmail1">Inductee Name <span class="required">*</span></label>
                <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Name" name="name"  value="<?php echo $induction->name;?>">
                <?php echo form_error('name'); ?>
              </div>

               <div class="form-group  <?php echo form_error('email') ? 'has-error' : '' ?>">
                <label for="exampleInputEmail1">URL Link <span class="required">*</span></label>
                <input type="text" class="form-control"  placeholder="e.g http://test.com" name="url" value="<?php echo $induction->document_url;?>">
                <?php echo form_error('url'); ?>
              </div>

               <div class="form-group  <?php echo form_error('expiry') ? 'has-error' : '' ?>">
                <label for="exampleInputexpiry1">Expiry <span class="required">*</span></label>
                <input type="text" class="form-control mydatepicker" id="exampleInputexpiry1" placeholder="Expiry Date" name="expiry" value="<?php echo $induction->expiry_date;?>">
                <?php echo form_error('expiry'); ?>
              </div>


              <div class="form-group  <?php echo form_error('document') ? 'has-error' : '' ?>">
                <label for="exampleInputaddress1">Document(PDF, DOC, DOCX)</label>
                <input type="file" class="form-control"  placeholder="Document" name="document" value="">
                <?php echo form_error('document'); ?>
              </div>

               <div class="form-group  <?php echo form_error('provider') ? 'has-error' : '' ?>">
                <label for="exampleInputprovider1">Provider <span class="required">*</span></label>
                <input type="text" class="form-control" id="exampleInputprovider1" placeholder="Provider" name="provider" value="<?php echo $induction->provider_name;?>">
                <?php echo form_error('provider'); ?>
              </div>


              <!--asd-->

    

        </div>




              <button type="submit" class="btn btn-default btn-sm">Update Induction</button>
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


  $('#editinduction').validate({
  rules: {
    name: "required",   
    url: {
      required: true,
      url: true
    },
    expiry: "required",
    document: {
      extension: "pdf|doc|docx"
    },
    provider: "required"
  }, 

  messages: {
    extension : "Invalid file type",
  }

});

</script>