<!-- page content -->
<div class="right_col" role="main">
   <!-- top tiles -->
   <div class="page-title">
    <h4>Edit Staff Details</h4>
   </div>



 <div class="row" style="padding-top: 20px;">
   <?php $this->load->view('home/alert');?>
   <div class="panel panel-default">
      <div class="panel-heading">Edit LP Staff</div>
      <div class="panel-body">
         <ul class="nav nav-tabs" role="tablist" id="myTab">
            <li role="presentation" class="<?php echo (!isset($_GET['tb']) ? 'active' : '');?>"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Personal Details</a></li>
            <li role="presentation" class="<?php echo  (isset($_GET['tb']) && $_GET['tb'] == 'skills') ? 'active' : '';?>"><a href="#skills" aria-controls="skills" role="tab" data-toggle="tab">Skills</a></li>
            <li role="presentation" class="<?php echo  (isset($_GET['tb']) && $_GET['tb'] == 'qualification') ? 'active' : '';?>"><a href="#qualification" aria-controls="qualification" role="tab" data-toggle="tab">Qualifications</a></li>
            <li role="presentation" class="<?php echo  (isset($_GET['tb']) && $_GET['tb'] == 'training') ? 'active' : '';?>"><a href="#training" aria-controls="training" role="tab" data-toggle="tab">Trainings</a></li>
         </ul>
         <!--change password form-->
            <div class="tab-content">
              <div role="tabpane1" class="tab-pane <?php echo (!isset($_GET['tb']) ? 'active' : '');?>" id="home">
                  <?php include('personal.php');?>
              </div>




                <!--skills-->
                <div role="tabpane1" class="tab-pane <?php echo (isset($_GET['tb']) && $_GET['tb'] == 'skills') ? 'active' : '';?>" id="skills">
                  <?php include('skill.php');?>
                </div>
                <!--skills-->

                <!--qualification-->
                <div role="tabpane1" class="tab-pane <?php echo (isset($_GET['tb']) && $_GET['tb'] == 'qualification') ? 'active' : '';?>" id="qualification">
                  <?php include('qualification.php');?>
                </div>
                <!--qualification-->

                 <!--training-->
                <div role="tabpane1" class="tab-pane <?php echo (isset($_GET['tb']) && $_GET['tb'] == 'training') ? 'active' : '';?>" id="training">
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

$(function(){
  $(".suburb").select2({
     placeholder: "Select your preferred location",
     allowClear: true
  });
});


 $('.myskill').on('change', function(){
    var selectedVal = $('.myskill option:selected').val();
      $.ajax({
        type: "POST",
        url:  "<?php echo base_url();?>" + "lp/staff/industrySkill",
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