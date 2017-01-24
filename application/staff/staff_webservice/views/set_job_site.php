<h3><?php echo $title ?></h3>
<form method="POST" action="<?php echo base_url('staff').'/'.uri_string(); ?>"  role="form"  class="form-horizontal">
    <div class="form-group">
        <label class="col-sm-3 control-label">* Job Staff ID:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="job_staff_id" value="" /></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">* Job Site Latitude/Longitude:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="job_site_lat_lng" value="" />In Format(lat,lng)</div>
    </div>
    <div class="form-group"><div class="col-sm-offset-3 col-sm-9"><input type="submit" class="btn btn-default" value="Submit" /></div></div>
</form>