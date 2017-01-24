<h3><?php echo $title ?></h3>
<form method="POST" action="<?php echo customer_interface_url(uri_string()); ?>" id="register_form"  role="form"  class="form-horizontal" enctype="multipart/form-data" >	
    <div class="form-group">
        <label class="col-sm-3 control-label"> * Name:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="name" value="" /></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label"> * Address:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="address" value="" /></div>
    </div>
<!--     <div class="form-group">
        <label class="col-sm-3 control-label"> * Email:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="email" value="" /></div>
    </div> -->
    <div class="form-group">
        <label class="col-sm-3 control-label"> * Phone:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="phone" value="" /></div>
    </div>
      <div class="form-group">
        <label class="col-sm-3 control-label"> * Availability:</label>
        
        <div class="col-sm-9">
            <span> csv where 1st element is represents sunday and last is saturday. 1 if selected else 0. csv must contain 7 items </span>
            <input class="form-control" type="text" name="availability" placeholder="0,0,0,0,0,0,0"/>
        </div>
    </div>
 
    <div class="form-group">
        <label class="col-sm-3 control-label"> * Profile Image:</label>
        <div class="col-sm-9"><input class="form-control" type="file" name="profile_image"/></div>
    </div>
    <div class="form-group"><div class="col-sm-offset-3 col-sm-9"><input type="submit" class="btn btn-default" value="Submit" /></div></div>
</form>