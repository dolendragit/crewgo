<h3><?php echo $title ?></h3>
<form method="POST" action="<?php echo base_url('staff').'/'.uri_string(); ?>"  role="form"  class="form-horizontal">
    <div class="form-group">
        <label class="col-sm-3 control-label">* Job Alert Id:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="job_alert_id" value="" /></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">* Response Flag:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="response_flag" value="" /> 1 = accepted, 2 = ignored, 3 = make unavailable</div>
    </div>
    <div class="form-group"><div class="col-sm-offset-3 col-sm-9"><input type="submit" class="btn btn-default" value="Submit" /></div></div>
</form>