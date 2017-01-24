<!-- page content -->
<div class="right_col" role="main">
   <!-- top tiles -->
   <div class="page-title">
    <h4>Edit Staff Details</h4>
   </div>



 <div class="row" style="padding-top: 20px;">
   <?php $this->load->view('home/alert');?>
   <div class="panel panel-default">
      <div class="panel-heading">Edit LHC Staff</div>
      <div class="panel-body">
         <ul class="nav nav-tabs" role="tablist" id="myTab">
            <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Personal Details</a></li>
            <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Skills</a></li>
            <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Qualifications</a></li>
            <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Trainings</a></li>
         </ul>
         <!--change password form-->
            <div class="tab-content">
              <div role="tabpane1" class="tab-pane active" id="home">
                  <?php include('personal.php');?>
              </div>




                <!--skills-->
                <div role="tabpane1" class="tab-pane" id="profile">
                  <?php include('skill.php');?>
                </div>
                <!--skills-->

                <!--qualification-->
                <div role="tabpane1" class="tab-pane" id="messages">
                  <?php include('qualification.php');?>
                </div>
                <!--qualification-->

                 <!--training-->
                <div role="tabpane1" class="tab-pane" id="settings">
                   <?php include('training.php');?>
                </div>
                <!--training-->
            </div>
      </div>
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

$('.clockpicker').clockpicker();

$(".suburb").select2({
   placeholder: "Select your preferred location",
   allowClear: true
});


 $('.myskill').on('change', function(){
    var selectedVal = $('.myskill option:selected').val();
      $.ajax({
        type: "POST",
        url:  "<?php echo base_url();?>" + "lhc/staff/industrySkill",
        data: {myvalue: selectedVal},
        dataType: 'json',
        cache:false,
        success: function(result){
          $('.level').html('');
          $.each(result, function(key, val){
            level = `<option value="`+val.id+`">`+val.name+`</option>`;
           $('.level').append(level);
          });

        }
      });
  });

</script>