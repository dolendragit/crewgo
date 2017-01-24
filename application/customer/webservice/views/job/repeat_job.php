<h3><?php echo $title ?></h3>
<form method="POST" action="<?php echo customer_interface_url(uri_string()); ?>"  role="form"  class="form-horizontal" >	
    <div class="form-group">
        <label class="col-sm-3 control-label">Job ID:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="job_id" value="" /></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Schedule Type :</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="schedule_type" value="" placeholder="W" /></div>
    </div>
     <div class="form-group">
        <label class="col-sm-3 control-label">Schedule Rate :</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="schedule_rate" value="" /></div>
    </div>
     <div class="form-group">
        <label class="col-sm-3 control-label">Start Date:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="start_date" value="" /></div>
    </div>
     <div class="form-group">
        <label class="col-sm-3 control-label">Required occurance:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="required_occurance" value="" /></div>
    </div>
<!--      <div class="form-group">
        <label class="col-sm-3 control-label">Created occurance:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="created_occurance" value="" placeholder=""/></div>
    </div>  -->
    <div class="form-group">
        <label class="col-sm-3 control-label">End Date:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="end_date" value="" /></div>
    </div>
    <div class="form-group"><div class="col-sm-offset-3 col-sm-9"><input type="submit" class="btn btn-default" value="Submit" /></div></div>
</form>

    

