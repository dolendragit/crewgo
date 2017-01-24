<h3><?php echo $title ?></h3>
<form method="POST" action="<?php echo base_url('staff').'/'.uri_string(); ?>"  role="form"  class="form-horizontal">
    <div class="form-group">
        <label class="col-sm-3 control-label">* Job Detail ID:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="job_detail_id" value="" /></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">* Staff Current Latitude/Longitude:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="job_site_lat_lng" value="" />In Format(lat,lng)</div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">* Is Staff away?:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="is_staff_away" value="" />0: Not away, 1: away</div>
    </div>
    <div class="form-group"><div class="col-sm-offset-3 col-sm-9"><input type="submit" class="btn btn-default" value="Submit" /></div></div>
</form>