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
    <div class="form-group">
        <label class="col-sm-3 control-label"> * Email:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="email" value="" /></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label"> * Phone:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="phone" value="" /></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label"> *  About me:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="about_me" value="" /></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label"> * Default Break Time:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="default_break_time" value=""  placeholder="15,30,45"/></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label"> * Profile Image:</label>
        <div class="col-sm-9"><input class="form-control" type="file" name="profile_image"/></div>
    </div>
    <div class="form-group"><div class="col-sm-offset-3 col-sm-9"><input type="submit" class="btn btn-default" value="Submit" /></div></div>
</form>