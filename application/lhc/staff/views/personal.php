
                <!--personal-->
                <form method="post" action="<?php echo base_url('lhc/staff/personaldetailupdate'); ?>" id="staff" enctype="multipart/form-data">
                <input type="hidden" name="userid" value="<?php echo $user->id;?>">
                <div role="tabpanel" class="tab-pane active" id="home">
                   <div class="form-group  <?php echo form_error('name') ? 'has-error' : '' ?>">
                      <label for="exampleInputEmail1">Staff's Name</label>
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Name" name="name"  value="<?php echo $user->name;?>">
                      <?php echo form_error('name'); ?>
                   </div>
                   <div class="form-group  <?php echo form_error('email') ? 'has-error' : '' ?>">
                      <label for="exampleInputEmail1">Email</label>
                      <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email" name="email" value="<?php echo $user->email;?>">
                      <?php echo form_error('email'); ?>
                   </div>
                   <div class="form-group  <?php echo form_error('mobileno') ? 'has-error' : '' ?>">
                      <label for="exampleInputmobileno1">Mobile No.</label>
                      <input type="text" class="form-control" id="exampleInputmobileno1" placeholder="Mobile no." name="mobileno" value="<?php echo $user->phone_number;?>">
                      <?php echo form_error('mobileno'); ?>
                   </div>
                   <div class="form-group  <?php echo form_error('address') ? 'has-error' : '' ?>">
                      <label for="exampleInputaddress1">Address</label>
                      <input type="text" class="form-control"  placeholder="Address" name="address" value="<?php echo $user->full_address;?>">
                      <?php echo form_error('address'); ?>
                   </div>
                   <div class="form-group">
                      <label for="exampleInputaddress1">Sociable Hours</label>
                      <div class="row">
                         <div class="input-group col-sm-2 clockpicker <?php echo form_error('from') ? 'has-error' : '' ?>" data-align="top" data-autoclose="true">
                            <input type="text" class="form-control"  placeholder="From" name="from" value="<?php echo isset($info->sociable_hour_from) ?  $info->sociable_hour_from: '';?>">
                            <?php echo form_error('from'); ?>
                            <span class="input-group-addon">
                            <span class="glyphicon glyphicon-time"></span>
                            </span>
                         </div>
                         <div class="input-group col-sm-2 clockpicker <?php echo form_error('to') ? 'has-error' : '' ?>" data-align="top" data-autoclose="true">
                            <input type="text" class="form-control"  placeholder="To" name="to" value="<?php echo isset($info->sociable_hour_to) ?  $info->sociable_hour_to: '';?>">
                            <?php echo form_error('to'); ?>
                            <span class="input-group-addon">
                            <span class="glyphicon glyphicon-time"></span>
                            </span>
                         </div>
                      </div>
                   </div>
                   <div class="form-group  <?php echo form_error('workhours') ? 'has-error' : '' ?>">
                      <label for="exampleInputworkhours1">Work Hours</label>
                      <input type="text" class="form-control" id="exampleInputworkhours1" placeholder="Work hrs" name="workhours" value="<?php echo isset($info->work_hour) ?  $info->work_hour: '';?>">
                      <?php echo form_error('workhours'); ?>
                      <ul class="days">
                         <li><input type="checkbox" name="days[]" value="0" <?php echo (isset($available->day_0) && $available->day_0 == '1') ? 'checked' : '';?>>&nbsp;Mon</li>
                         <li><input type="checkbox" name="days[]" value="1" <?php echo (isset($available->day_1) && $available->day_1 == '1') ? 'checked' : '';?>>&nbsp;Tue</li>
                         <li><input type="checkbox" name="days[]" value="2" <?php echo (isset($available->day_2) && $available->day_2 == '1') ? 'checked' : '';?>>&nbsp;Wed</li>
                         <li><input type="checkbox" name="days[]" value="3" <?php echo (isset($available->day_3) && $available->day_3 == '1') ? 'checked' : '';?>>&nbsp;Thu</li>
                         <li><input type="checkbox" name="days[]" value="4" <?php echo (isset($available->day_4) && $available->day_4 == '1') ? 'checked' : '';?>>&nbsp;Fri</li>
                         <li><input type="checkbox" name="days[]" value="5" <?php echo (isset($available->day_5) && $available->day_5 == '1') ? 'checked' : '';?>>&nbsp;Sat</li>
                         <li><input type="checkbox" name="days[]" value="6" <?php echo (isset($available->day_6) && $available->day_6 == '1') ? 'checked' : '';?>>&nbsp;Sun</li>
                      </ul>
                      <?php echo form_error('days[]'); ?>
                   </div>
                   <div class="form-group  <?php echo form_error('contactablehours') ? 'has-error' : '' ?>">
                      <label for="exampleInputaddress1">Contactable Hours</label>
                      <div class="row">
                         <div class="input-group col-sm-2 clockpicker" data-align="top" data-autoclose="true">
                            <input type="text" class="form-control"  placeholder="From" name="contactable_from" value="<?php echo isset($info->conatctable_hour_from) ? $info->conatctable_hour_from: '';?>" >
                            <?php echo form_error('contactable_from'); ?>
                            <span class="input-group-addon">
                            <span class="glyphicon glyphicon-time"></span>
                            </span>
                         </div>
                         <div class="input-group col-sm-2 clockpicker" data-align="top" data-autoclose="true">
                            <input type="text" class="form-control"  placeholder="To" name="contactable_to" value="<?php echo isset($info->conatctable_hour_to) ? $info->conatctable_hour_to: '';?>">
                            <?php echo form_error('contactable_to'); ?>
                            <span class="input-group-addon">
                            <span class="glyphicon glyphicon-time"></span>
                            </span>
                         </div>
                      </div>
                   </div>
                   <div class="row form-group col-sm-4  <?php echo form_error('transportation') ? 'has-error' : '' ?>">
                      <label for="exampleInputtransportation1">Transportation.</label>
                      <select class="form-control" name="transportation">
                         <option value="1" <?php echo (isset($info->transport) && $info->transport == 1) ? 'selected' : '';?>>Has got Transportation</option>
                         <option value="0" <?php echo (isset($info->transport) && $info->transport == 0) ? 'selected' : '';?>>Use public Transport</option>
                      </select>
                      <?php echo form_error('transportation'); ?>
                   </div>
                   <div class="clearfix"></div>
                   <div class="row">
                
                   

                      <div class="col-sm-4">
                         <div class="form-group  <?php echo form_error('areas[]') ? 'has-error' : '' ?>">
                            <label for="exampleInputareas1">Areas Preferred To Work.</label>
                            <select class="form-control suburb" name="areas[]" multiple="multiple">
                               <option value="">Select Location</option>
                               <?php foreach($suburb as $area):?>
                               <option value="<?php echo $area->id;?>" <?php  echo (in_array($area->id, $selectedArray)) ?  "selected='selected'" : '';?>><?php echo $area->suburb;?></option>
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
                            <label for="exampleInputareas1">Document Type</label>
                            <select class="form-control" name="document_type">
                               <option value="L" <?php echo (isset($info->driving_passport) && $info->driving_passport == 'L') ? 'selected' : '';?>>Passport</option>
                               <option value="D" <?php echo (isset($info->driving_passport) && $info->driving_passport == 'D') ? 'selected' : '';?>>Driving Licence</option>
                            </select>
                            <?php echo form_error('document_type'); ?>
                         </div>
                      </div>
                      <div class="col-sm-3">
                         <div class="form-group  <?php echo form_error('driving_expiry') ? 'has-error' : '' ?>">
                            <label for="exampleInputareas1">Driving License.</label>
                            <input type="text" autocomplete="off" class="form-control mydatepicker"  placeholder="Expiry Date" name="driving_expiry" value="<?php echo (isset($info->expiry_date)) ? $info->expiry_date : '' ;?>">
                            <?php echo form_error('driving_expiry'); ?>
                         </div>
                      </div>
                      <div class="col-sm-3">
                         <label for="exampleInputareas1">&nbsp;</label>
                         <input type="file" class="form-control"  name="driving">
                      </div>
                   </div>
                   <div class="row form-group col-sm-4  <?php echo form_error('induction') ? 'has-error' : '' ?>">
                      <label for="exampleInputaddress1">Induction</label>
                      <select class="form-control" name="induction">
                         <?php foreach($induction as $key => $value): ?>
                         <option value="<?php echo $value->id;?>" <?php echo  ($value->id == $selectedInduction->induction_detail_id) ? 'selected' : '';?>><?php echo $value->name;?></option>
                         <?php endforeach;?>
                      </select>
                      <?php echo form_error('induction'); ?>
                   </div>
                   <!--asd-->
                 <div class="clearfix"></div>
                 <button type="submit" class="btn btn-primary btn-sm">Update Personal Details</button>
                </div>
                </form>
                <!--personal-->