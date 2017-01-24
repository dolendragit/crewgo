<h3><?php echo $title ?></h3>
<form method="POST" action="<?php echo customer_interface_url(uri_string()); ?>"  role="form"  class="form-horizontal" >  
    <div class="form-group">
        <label class="col-sm-3 control-label">Device Name:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="device_name" value="" placeholder="" /></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Device OS:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="device_os" value="" placeholder="" /></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Device Token:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="device_token" value="" placeholder="" /></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Device Environment:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="device_env" value="" placeholder="0:none, 1:development, 2: distribution, 3: live app store" /></div>
    </div>

    <div class="form-group"><div class="col-sm-offset-3 col-sm-9"><input type="submit" class="btn btn-default" value="Submit" /></div></div>
</form>