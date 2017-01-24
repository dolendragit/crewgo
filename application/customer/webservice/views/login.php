<h3><?php echo $title ?></h3>
<form method="POST" action="<?php echo customer_interface_url(uri_string()); ?>"  role="form"  class="form-horizontal" >	
    <div class="form-group">
        <label class="col-sm-3 control-label">Email:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="email" value="" /></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Password:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="password" value="" /></div>
    </div>
    <div class="form-group">
    <label class="col-sm-3 control-label">Device Id:</label>
	<div class="col-sm-9"><input class="form-control" type="text" name="device_id" value="" /></div>
	</div>
   <div class="form-group">
	<label class="col-sm-3 control-label">Device Type:</label>
	<div class="col-sm-9"><input class="form-control" type="text" name="device_type" value="" />1: iphone, 2: android</div>
   </div>
    <div class="form-group"><div class="col-sm-offset-3 col-sm-9"><input type="submit" class="btn btn-default" value="Submit" /></div></div>
</form>