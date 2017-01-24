<!-- page content -->
<div class="right_col" role="main">
   <!-- top tiles -->
   <div class="page-title">
    <h4>Edit Skill</h4>
   </div>


   <div class="row" style="padding-top: 20px;">
     <?php $this->load->view('home/alert');?>
      <div class="panel panel-default">
         <div class="panel-heading">Edit Skill</div>
         <div class="panel-body">
            <!--change password form-->
            <form method="post" action="<?php echo base_url('lhc/staff/editskills'); ?>" id="staff" enctype="multipart/form-data">

            <input type="hidden" name="skillid" value="<?php echo $selectedIndustry->id;?>">
            <input type="hidden" name="userid" value="<?php echo $selectedIndustry->staff_user_id;?>">

              <div class="form-group  <?php echo form_error('skill') ? 'has-error' : '' ?>">
                <label for="exampleInputEmail1">Skill</label>
                <select class="form-control skill" name="skill" required>
                  <?php foreach($industry as $key => $val):?>
                    <option value="<?php echo $val->id;?>"><?php echo $val->name;?></option>
                  <?php endforeach; ?>
                </select>
                <?php echo form_error('skill'); ?>
              </div>

               <div class="form-group  <?php echo form_error('level') ? 'has-error' : '' ?>">
                <label for="exampleInputEmail1">Level</label>
                 <select class="form-control level" name="level" required>
                   
                 </select>
                <?php echo form_error('level'); ?>
              </div>

              <!--asd-->

    

        </div>




              <button type="submit" class="btn btn-default btn-sm">Update Skill</button>
            </form>
            <!--change password form-->
         </div>
      </div>
   </div>
</div>
<!-- /page content -->



<script type="text/javascript">


$(function(){
  $('.skill').trigger('change');
  var selectedLev = <?php echo $selectedIndustry->level_id;?>;
  localStorage.setItem('lev', selectedLev);
});

  $('.skill').on('change', function(){
    var myId = localStorage.getItem('lev');
    var selectedVal = $('.skill option:selected').val();
      $.ajax({
        type: "POST",
        url:  "<?php echo base_url();?>" + "lhc/staff/industrySkill",
        data: {myvalue: selectedVal},
        dataType: 'json',
        cache:false,
        success: function(result){
         // localStorage.removeItem('lev');
          $('.level').html('');
          $.each(result, function(key, val){
            var see;
            if (val.id == myId){
              see = 'selected';
            } else {
              see = '';
            }
            level = `<option value="`+val.id+`" `+see+`>`+val.name+`</option>`;
           $('.level').append(level);
          });

        }
      });
  });
</script>