<h3><?php echo $title ?></h3>
<form method="POST" action="<?php echo customer_interface_url(uri_string()); ?>" id="register_form"  role="form"  class="form-horizontal" enctype="multipart/form-data" >	
    <div class="form-group">
        <label class="col-sm-3 control-label"> * Job Id:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="job_id" value="" /></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label"> * Name of Induction:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="name" value="" /></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label"> * Provider:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="provider" value="" /></div>
    </div>
       <div class="form-group">
        <label class="col-sm-3 control-label"> * Link:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="link" value="" /></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label"> * Email:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="email" value="" /></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label"> * Instruction:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="other_detail" value="" /></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label"> * Valid induction only:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="valid_induction" value="" placeholder="0,1" /></div>
    </div>
 
    <div class="form-group"><div class="col-sm-offset-3 col-sm-9"><input type="submit" class="btn btn-default" value="Submit" /></div></div>
</form>