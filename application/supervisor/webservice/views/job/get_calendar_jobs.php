<h3><?php echo $title ?></h3>
<form method="POST" action="<?php echo customer_interface_url(uri_string()); ?>" id="register_form"  role="form"  class="form-horizontal" enctype="multipart/form-data" >   
    <div class="form-group">
        <label class="col-sm-3 control-label"> * Date:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="date" value="" placeholder="2016-12" /></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label"> * Specific Date:</label>
        <span>set 1 to get specific date jobs</span>
        <div class="col-sm-9"><input class="form-control" type="text" name="specific_date" value="" placeholder="0,1" /></div>
    </div>
    <div class="form-group"><div class="col-sm-offset-3 col-sm-9"><input type="submit" class="btn btn-default" value="Submit" /></div></div>
</form>