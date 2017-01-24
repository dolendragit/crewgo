<h3><?php echo $title ?></h3>
<form method="POST" action="<?php echo base_url('staff').'/'.uri_string(); ?>" id="register_form"  role="form"  class="form-horizontal" enctype="multipart/form-data" >
    <div class="form-group">
        <label class="col-sm-3 control-label"> * Message ID:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="message_id" value="" /></div>
    </div>
     <div class="form-group">
        <label class="col-sm-3 control-label"> * Subject:</label>
        <div class="col-sm-9"><label>Lorem ipsom is a dymmy text. </label></div>
    </div>
     <div class="form-group">
        <label class="col-sm-3 control-label"> * Message Text:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="message" value="" /></div>
    </div>
    <div class="form-group"><div class="col-sm-offset-3 col-sm-9"><input type="submit" class="btn btn-default" value="Send Message" /></div></div>
</form>