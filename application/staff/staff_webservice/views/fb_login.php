<h3><?php echo $title ?></h3>
<form method="POST" action="<?php echo base_url('staff').'/'.uri_string(); ?>"  role="form"  class="form-horizontal" >
    <div class="form-group">
        <label class="col-sm-3 control-label">Access Token:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="access_token" value="" /></div>
    </div>
     <div class="form-group">
    <label class="col-sm-3 control-label">Device Id:</label>
	<div class="col-sm-9"><input class="form-control" type="text" name="device_id" value="" /></div>
	</div>
   <div class="form-group">
	<label class="col-sm-3 control-label">Device Type:</label>
	<div class="col-sm-9"><input class="form-control" type="text" name="device_type" value="" />1: iphone, 2: android</div>
   </div>
    <div class="form-group"><div class="col-sm-offset-3 col-sm-9"><input type="submit" class="btn btn-default" value="Login " /></div></div>
</form>