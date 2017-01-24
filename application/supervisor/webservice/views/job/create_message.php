<h3><?php echo $title ?></h3>
<form method="POST" action="<?php echo customer_interface_url(uri_string()); ?>" role="form"  class="form-horizontal" enctype="multipart/form-data" >	
    <div class="form-group">
        <label class="col-sm-3 control-label">  Job ID:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="job_id" value="" /></div>
    </div>
	 <div class="form-group">
        <label class="col-sm-3 control-label">  TO:</label>
        <div class="col-sm-9">
        <select class="form-control" name="receivers">
        	<option value="all">All</option>
        	<option value="team-1">Team: Audio</option>
        	<option value="all">Team: Hardware</option>
        	<option value="12">John Doe</option>
        	<option value="13">Michel J.</option>
        	<option value="14">Crystina</option>
        </select>
        </div>
    </div>
     <div class="form-group">
        <label class="col-sm-3 control-label">  Title:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="title" value="" /></div>
    </div>
     <div class="form-group">
        <label class="col-sm-3 control-label">  Message:</label>
        <div class="col-sm-9"><textarea name="message" class="form-control"></textarea></div>
    </div>
  <!--   <div class="form-group">
        <label class="col-sm-3 control-label">  Image:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="image" value="" /></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">  Video:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="video" value="" /></div>
    </div> -->
    <div class="form-group">
    	<label class="col-sm-3 control-label">  Delete Option:</label>
    	<div class="col-sm-9">
    		<div class="radio">
    		<label><input type="radio" name="delete_option" value="1" checked="checked" /> Staff can delete anytime</label>	
    		</div>
    		<div class="radio">
    		<label><input type="radio" name="delete_option" value="2" /> Staff cannot delete before shifts ends</label>	
    		</div>
    		<div class="radio">
    		<label><input type="radio" name="delete_option" value="3" /> Staff can not delete</label>	
    		</div>    		
    	</div>
    </div>
     <div class="form-group">
        <label class="col-sm-3 control-label">  Can Reply:</label>
        <div class="col-sm-9"><label><input type="checkbox" name="can_reply" value="1" checked="checked" /> staff cannot reply</label></div>
    </div>    
    <div class="form-group"><div class="col-sm-offset-3 col-sm-9"><input type="submit" class="btn btn-default" value="Submit" /></div></div>
</form>