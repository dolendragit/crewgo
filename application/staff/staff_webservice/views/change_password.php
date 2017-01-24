<h3><?php echo $title ?></h3>
<form method="POST" action="<?php echo base_url('staff').'/'.uri_string(); ?>"  role="form"  class="form-horizontal">	
      <div class="form-group">
        <label class="col-sm-3 control-label">Old Password:</label>
        <div class="col-sm-9"><input class="form-control" type="password" name="old_password" value="" /></div>
    </div>
      <div class="form-group">
        <label class="col-sm-3 control-label">New Password:</label>
        <div class="col-sm-9"><input class="form-control" type="password" name="new_password" value="" /></div>
    </div>
     <div class="form-group">
        <label class="col-sm-3 control-label">Confirm Password:</label>
        <div class="col-sm-9"><input class="form-control" type="password" name="confirm_password" value="" /></div>
    </div>
   
    <div class="form-group"><div class="col-sm-offset-3 col-sm-9"><input type="submit" class="btn btn-default" value="Submit" /></div></div>
</form>