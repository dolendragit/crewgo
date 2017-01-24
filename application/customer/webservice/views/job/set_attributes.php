<h3><?php echo $title ?></h3>
<form method="POST" action="<?php echo customer_interface_url(uri_string()); ?>"  role="form"  class="form-horizontal" >	
	<div class="form-group">
        <label class="col-sm-3 control-label">Job Id :</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="job_id" value="" placeholder="" /></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Attributes:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="attributes" value="" placeholder="11,24,3,54,7,6" /></div>
    </div>

    <div class="form-group"><div class="col-sm-offset-3 col-sm-9"><input type="submit" class="btn btn-default" value="Submit" /></div></div>
</form>