<h3><?php echo $title ?></h3>
<form method="POST" action="<?php echo customer_interface_url(uri_string()); ?>"  role="form"  class="form-horizontal" >	
    <div class="form-group">
        <label class="col-sm-3 control-label">Job ID:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="job_id" value="" /></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">PPE:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="ppe" value="" /></div>
    </div>
     <div class="form-group">
        <label class="col-sm-3 control-label">Full Adderss:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="full_address" value="" /></div>
    </div>
     <div class="form-group">
        <label class="col-sm-3 control-label">Street:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="street" value="" /></div>
    </div>
     <div class="form-group">
        <label class="col-sm-3 control-label">PostCode:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="postcode_id" value="" /></div>
    </div>
     <div class="form-group">
        <label class="col-sm-3 control-label">Jobsite:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="job_site" value="" placeholder="0,1"/></div>
    </div>
     <div class="form-group">
        <label class="col-sm-3 control-label">more than one shift:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="more_than_one_shift" value="" placeholder="0,1" /></div>
    </div>
     <div class="form-group">
        <label class="col-sm-3 control-label">Presentable:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="presentable" value="" placeholder="0,1" /></div>
    </div>
     <div class="form-group">
        <label class="col-sm-3 control-label">Transport:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="transport" value="" placeholder="0,1" /></div>
    </div>
    <div class="form-group"><div class="col-sm-offset-3 col-sm-9"><input type="submit" class="btn btn-default" value="Submit" /></div></div>
</form>

