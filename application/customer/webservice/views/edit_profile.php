<h3><?php echo $title ?></h3>
<form method="POST" action="<?php echo base_url(uri_string()); ?>" id="register_form"  role="form"  class="form-horizontal" enctype="multipart/form-data" >	
   <div class="form-group">
	<label class="col-sm-3 control-label"> Image:</label>
        <div class="col-sm-9"><input type="file" name="profile_image" multiple/></div>
	</div>
    <div class="form-group">
        <label class="col-sm-3 control-label"> * First Name:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="firstname" value="" /></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label"> * Last Name:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="lastname" value="" /></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label"> * Gender:</label>
        <div class="col-sm-9">
            <select name="gender">
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label"> *Postcode:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="postcode" value="" /></div>
    </div>
<!--    <div class="form-group">
        <label class="col-sm-3 control-label"> *Suburb:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="suburb" value="" /></div>
    </div>-->
    <div class="form-group">
        <label class="col-sm-3 control-label"> * Mobile Number:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="phone_number" value="" /></div>
    </div>
    <div class="form-group"><div class="col-sm-offset-3 col-sm-9"><input type="submit" class="btn btn-default" value="Submit" /></div></div>
</form>