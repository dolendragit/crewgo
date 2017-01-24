<h3><?php echo $title ?></h3>
<form method="POST" action="<?php echo base_url(uri_string()); ?>" id="register_form"  role="form"  class="form-horizontal" enctype="multipart/form-data" >	
    <div class="form-group">
        <label class="col-sm-3 control-label"> * Name:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="name" value="" /></div>
    </div>
    
    <div class="form-group">
        <label class="col-sm-3 control-label"> * Email:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="email" value="" /></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label"> Contact Number:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="phone" value="" /></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label"> *Message:</label>
        <div class="col-sm-9"><textarea class="form-control" name="message"></textarea></div>
    </div>
    
    <div class="form-group"><div class="col-sm-offset-3 col-sm-9"><input type="submit" class="btn btn-default" value="Submit" /></div></div>
</form>