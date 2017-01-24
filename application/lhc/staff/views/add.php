<!-- page content -->
<div class="right_col" role="main">
   <!-- top tiles -->
   <div class="page-title">
    <h4>Add New Staff</h4>
   </div>


   <div class="row" style="padding-top: 20px;">
     <?php $this->load->view('home/alert');?>
      <div class="panel panel-default">
         <div class="panel-heading">Add LHC Staff</div>
         <div class="panel-body">
            <!--change password form-->
            <form method="post" id="staffadd" action="<?php echo base_url('lhc/staff/add'); ?>" id="staff" enctype="multipart/form-data">

              <div class="form-group  <?php echo form_error('name') ? 'has-error' : '' ?>">
                <label for="exampleInputEmail1">Staff's Name<span class="mandatory">&nbsp;*</span></label>
                <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Name" name="name"  value="<?php echo set_value('name');?>">
                <?php echo form_error('name'); ?>
              </div>

               <div class="form-group  <?php echo form_error('email') ? 'has-error' : '' ?>">
                <label for="exampleInputEmail1">Email<span class="mandatory">&nbsp;*</span></label>
                <input type="email" class="form-control"  placeholder="Email" name="email" value="<?php echo set_value('email');?>">
                <?php echo form_error('email'); ?>
              </div>

               <div class="form-group  <?php echo form_error('mobileno') ? 'has-error' : '' ?>">
                <label for="exampleInputmobileno1">Mobile No.<span class="mandatory">&nbsp;*</span></label>
                <input type="text" class="form-control" id="exampleInputmobileno1" placeholder="Mobile no." name="mobileno" value="<?php echo set_value('mobileno');?>">
                <?php echo form_error('mobileno'); ?>
              </div>


              <div class="form-group  <?php echo form_error('address') ? 'has-error' : '' ?>">
                <label for="exampleInputaddress1">Address<span class="mandatory">&nbsp;*</span></label>
                <input type="text" class="form-control"  placeholder="Address" name="address" value="<?php echo set_value('address');?>">
                <?php echo form_error('address'); ?>
              </div>

               

                <div class="form-group">
                <label for="exampleInputaddress1">Sociable Hours<span class="mandatory">&nbsp;*</span></label>
                <div class="row">
                  <div class="input-group col-sm-2 clockpicker <?php echo form_error('from') ? 'has-error' : '' ?>" data-align="top" data-autoclose="true">
                    <input type="text" class="form-control"  placeholder="From" name="from" value="<?php echo set_value('from');?>">
                     <?php echo form_error('from'); ?>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-time"></span>
                    </span>
                  </div>


                   <div class="input-group col-sm-2 clockpicker <?php echo form_error('to') ? 'has-error' : '' ?>" data-align="top" data-autoclose="true">
                    <input type="text" class="form-control"  placeholder="To" name="to" value="<?php echo set_value('to');?>">
                     <?php echo form_error('to'); ?>
                     <span class="input-group-addon">
                        <span class="glyphicon glyphicon-time"></span>
                    </span>
                  </div>
                </div>
                </div>


              <div class="form-group  <?php echo form_error('workhours') ? 'has-error' : '' ?>">
                <label for="exampleInputworkhours1">Work Hours<span class="mandatory">&nbsp;*</span></label>
                <input type="text" class="form-control" id="exampleInputworkhours1" placeholder="Work hrs" name="workhours" value="<?php echo set_value('workhours');?>">
                <?php echo form_error('workhours'); ?>
                <ul class="days">
                  <li><input type="checkbox" name="days[]" value="0">&nbsp;Mon</li>
                  <li><input type="checkbox" name="days[]" value="1">&nbsp;Tue</li>
                  <li><input type="checkbox" name="days[]" value="2">&nbsp;Wed</li>
                  <li><input type="checkbox" name="days[]" value="3">&nbsp;Thu</li>
                  <li><input type="checkbox" name="days[]" value="4">&nbsp;Fri</li>
                  <li><input type="checkbox" name="days[]" value="5">&nbsp;Sat</li>
                  <li><input type="checkbox" name="days[]" value="6">&nbsp;Sun</li>
                </ul>
                <label class="avday"></label>
                <?php echo form_error('days[]'); ?>
              </div>




               <div class="form-group  <?php echo form_error('contactablehours') ? 'has-error' : '' ?>">
                <label for="exampleInputaddress1">Contactable Hours<span class="mandatory">&nbsp;*</span></label>
                <div class="row">
                  <div class="input-group col-sm-2 clockpicker" data-align="top" data-autoclose="true">
                    <input type="text" class="form-control"  placeholder="From" name="contactable_from" value="<?php echo set_value('contactable_from');?>" >
                    <?php echo form_error('contactable_from'); ?>
                     <span class="input-group-addon">
                        <span class="glyphicon glyphicon-time"></span>
                    </span>
                  </div>

                   <div class="input-group col-sm-2 clockpicker" data-align="top" data-autoclose="true">
                    <input type="text" class="form-control"  placeholder="To" name="contactable_to" value="<?php echo set_value('contactable_to');?>">
                    <?php echo form_error('contactable_to'); ?>
                     <span class="input-group-addon">
                        <span class="glyphicon glyphicon-time"></span>
                    </span>
                  </div>
                </div>
                </div>




              <div class="form-group  <?php echo form_error('skills') ? 'has-error' : '' ?>">
                 <label for="exampleInputaddress1">Skills<span class="mandatory">&nbsp;*</span></label>
                    <div class="row parentone">
                      <div class="tobecloned">  
                        <div class="col-sm-2">
                          <select class="form-control category" name="maincategory[]">
                            <option value="">Choose Main Skill</option>
                             <?php foreach ($industry as $val): ?>
                              <option value="<?php echo $val->id; ?>"><?php echo $val->name; ?></option>
                             <?php endforeach;?>
                          </select>
                          <?php echo form_error('maincategory[]'); ?>
                        </div>

                         <div class="col-sm-2">
                           <select class="form-control subcategory" name="subskill[]">
                           
                          </select>
                           <?php echo form_error('subskill[]'); ?>
                        </div>
                      </div>



                      <div class="col-sm-1">
                        <button type="button" class="btn btn-primary btn-sm" id="addskillset">Add</button>
                      </div>
                  </div>

                   <div class="row added"></div>
                </div>



                <div class="form-group  <?php echo form_error('skills') ? 'has-error' : '' ?>">
                 <label for="exampleInputaddress1">Qualifications</label>
                    <div class="row parentqualification">
                     
                        <div class="col-sm-2">
                          <input type="text" class="form-control"  placeholder="Title" name="title[]">
                        </div>

                         <div class="col-sm-2">
                             <input type="text" autocomplete="off" class="form-control mydatepicker"  placeholder="Expiry Date" name="expiry[]">
                        </div>

                         <div class="col-sm-2">
                             <input type="file" class="form-control"  name="documents[]">
                        </div>
                      



                      <div class="col-sm-1">
                        <button type="button" class="btn btn-primary btn-sm" id="addqualification">Add</button>
                      </div>
                  </div>

                   <div class="row addedq"></div>
                </div>


                <div class="form-group  <?php echo form_error('skills') ? 'has-error' : '' ?>">
                 <label for="exampleInputaddress1">Trainings</label>
                    <div class="row parenttraining">
                     
                        <div class="col-sm-2">
                          <input type="text" class="form-control"  placeholder="Title" name="training_title[]">
                        </div>

                         <div class="col-sm-2">
                             <input type="text" autocomplete="off" class="form-control mydatepicker"  placeholder="Expiry Date" name="training_expiry[]">
                        </div>

                         <div class="col-sm-2">
                             <input type="file" class="form-control"  name="training_document[]">
                        </div>
                      



                      <div class="col-sm-1">
                        <button type="button" class="btn btn-primary btn-sm" id="addtraining">Add</button>
                      </div>
                  </div>

                   <div class="row addedt"></div>
                </div>



              <div class="row form-group col-sm-4  <?php echo form_error('transportation') ? 'has-error' : '' ?>">
                <label for="exampleInputtransportation1">Transportation.<span class="mandatory">&nbsp;*</span></label>
                <select class="form-control" name="transportation">
                    <option value="1">Has got Transportation</option>
                    <option value="0">Use public Transport</option>
                </select>
                <?php echo form_error('transportation'); ?>
              </div>
              <div class="clearfix"></div>



              <div class="row">
                <div class="col-sm-4">
                   <div class="form-group  <?php echo form_error('areas[]') ? 'has-error' : '' ?>">
                      <label for="exampleInputareas1">Areas Preferred To Work.<span class="mandatory">&nbsp;*</span></label>
                       <select class="form-control suburb" name="areas[]" multiple="multiple">
                          <option value="">Select Location</option>
                          <?php foreach($suburb as $area):?>
                            <option value="<?php echo $area->id;?>"><?php echo $area->suburb;?></option>
                         <?php endforeach;?>
                        </select>
                        <?php echo form_error('areas[]'); ?>
                    </div>
                </div>

                <div class="col-sm-4">
                  <label for="exampleInputareas1"><input type="checkbox" name="nearby" class="nearby" checked>&nbsp; Include Nearby Km </label>
                   <select class="form-control" name="boundary">
                          <option value="5">5</option>
                          <option value="10">10</option>
                        </select>
                </div>
              </div>

               <div class="row">

                 <div class="col-sm-3">
                   <div class="form-group  <?php echo form_error('document_type') ? 'has-error' : '' ?>">
                      <label for="exampleInputareas1">Document Type<span class="mandatory">&nbsp;*</span></label>
                      <select class="form-control" name="document_type">
                        <option value="L">Passport</option>
                        <option value="D">Driving Licence</option>
                      </select>
                      <?php echo form_error('document_type'); ?>
                    </div>
                </div>


                <div class="col-sm-3">
                   <div class="form-group  <?php echo form_error('driving_expiry') ? 'has-error' : '' ?>">
                      <label for="exampleInputareas1">Driving License.<span class="mandatory">&nbsp;*</span></label>
                        <input type="text" autocomplete="off" class="form-control mydatepicker"  placeholder="Expiry Date" name="driving_expiry" value="<?php echo set_value('driving_expiry');?>">
                      <?php echo form_error('driving_expiry'); ?>
                    </div>
                </div>

                <div class="col-sm-3">
                 <label for="exampleInputareas1">&nbsp;</label>
                  <input type="file" class="form-control"  name="driving">
                </div>
              </div>



               <div class="row form-group col-sm-4  <?php echo form_error('induction') ? 'has-error' : '' ?>">
                <label for="exampleInputaddress1">Induction<span class="mandatory">&nbsp;*</span></label>
                <select class="form-control" name="induction">
                  <?php foreach($induction as $key => $value): ?>
                    <option value="<?php echo $value->id;?>"><?php echo $value->name;?></option>
                  <?php endforeach;?>
                </select>
                <?php echo form_error('induction'); ?>
              </div>

              <!--asd-->

    

        </div>




              <button type="submit" class="btn btn-default btn-sm">Add Staff</button>
            </form>
            <!--change password form-->
         </div>
      </div>
   </div>
</div>
<!-- /page content -->



<script type="text/javascript">
  $(document).on('change', '.category', function(){
    var $this = $(this);
    var selectedVal = $(this).find('option:selected').val();
      $.ajax({
        type: "POST",
        url:  "<?php echo base_url();?>" + "lhc/staff/industrySkill",
        data: {myvalue: selectedVal},
        dataType: 'json',
        cache:false,
        success: function(result){
          $this.closest('.parentone').find('.subcategory').html('');
        // var subskill = [];
          $.each(result, function(key, val){
            subskill = `<option value="`+val.id+`">`+val.name+`</option>`;
           $this.closest('.parentone').find('.subcategory').append(subskill);
          });

          //$('select.subcategory').append(subskill);
        }
      });
  });




  $('#addskillset').on('click', function(){
    var cloneDiv = `
       <div class="parentone">
       <div class="col-sm-2" style="margin-bottom:10px;">
                          <select class="form-control category" name="maincategory[]" required>
                            <option value="">Choose Main Skill</option>
                             <?php foreach ($industry as $val): ?>
                              <option value="<?php echo $val->id; ?>"><?php echo $val->name; ?></option>
                             <?php endforeach;?>
                          </select>
                        </div>

                         <div class="col-sm-2" style="margin-bottom:10px;">
                           <select class="form-control subcategory" name="subskill[]">
                           
                          </select>
                        </div>

                         <div class="col-sm-1">
                        <button type="button" class="btn btn-primary btn-sm rmrow">Remove</button>
                      </div>

                      <div class="clearfix"></div>
        </div>
    `;
     $('.added').append(cloneDiv);
  });


  $('.added').off('click').on('click', '.rmrow', function(){
    $(this).parent().parent().remove();
  });



   $('#addqualification').on('click', function(){
    var cloneDiv1 = `
      <div class="parentqualification">
                    
                        <div class="col-sm-2">
                          <input type="text" class="form-control"  placeholder="Title" name="title[]" required>
                        </div>

                         <div class="col-sm-2">
                             <input type="text" class="form-control mydatepicker" autocomplete="off"  placeholder="Expiry Date" name="expiry[]" required>
                        </div>

                         <div class="col-sm-2">
                             <input type="file" class="form-control"  name="documents[]">
                        </div>
                     



                      <div class="col-sm-1">
                        <button type="button" class="btn btn-primary btn-sm rmqualification">Remove</button>
                      </div>

                      <div class="clearfix"></div>
                  </div>
    `;
     $('.addedq').append(cloneDiv1);
  });


  $('.addedq').off('click').on('click', '.rmqualification', function(){
    $(this).parent().parent().remove();
  });


  $('#addtraining').on('click', function(){
    var cloneDiv2 = `
      <div class="parenttraining">
                    
                        <div class="col-sm-2">
                          <input type="text" class="form-control"  placeholder="Title" name="training_title[]">
                        </div>

                         <div class="col-sm-2">
                             <input type="text" class="form-control mydatepicker" autocomplete="off"  placeholder="Expiry Date" name="training_expiry[]">
                        </div>

                         <div class="col-sm-2">
                             <input type="file" class="form-control"  name="training_document[]">
                        </div>
                     



                      <div class="col-sm-1">
                        <button type="button" class="btn btn-primary btn-sm rmtraining">Remove</button>
                      </div>

                      <div class="clearfix"></div>
                  </div>
    `;
     $('.addedt').append(cloneDiv2);
  });


  $('.addedt').off('click').on('click', '.rmtraining', function(){
    $(this).parent().parent().remove();
  });


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

/*$(".suburb").select2({
   placeholder: "Select your preferred location",
   allowClear: true
});*/


$(".suburb").select2({
  placeholder: "Select your preferred location",
});



$('#staffadd').validate({
  rules: {
    name: "required",   
    email: {
          required: true,
          email: true
        },
    mobileno: "required",
    address: "required",
    from: "required",
    to: "required",
    workhours: "required",
    "days[]": "required",
    contactable_from: "required",
    contactable_to: "required",
    "maincategory[]": "required",
    transportation: "required",
    "areas[]": "required",
    driving_expiry: "required",
    induction: "required",
  },

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
  }
});
</script>