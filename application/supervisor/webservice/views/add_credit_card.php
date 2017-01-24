<h3><?php echo $title ?></h3>
<form method="POST" action="<?php echo customer_interface_url(uri_string()); ?>"  role="form"  class="form-horizontal">	
    <div class="form-group">
        <label class="col-sm-3 control-label">Name</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="name" value="" /></div>
    </div>
    
    <div class="form-group">
        <label class="col-sm-3 control-label">Card Number:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="number" value="" /></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Card Type:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="card_type" value="" placeholder="1 for master, 2 for visa"/></div>
    </div>
      <div class="form-group">
        <label class="col-sm-3 control-label">Expiry Month:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="month" value="" placeholder="mm" /></div>
    </div>
     <div class="form-group">
        <label class="col-sm-3 control-label">Expiry Year:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="year" value="" placeholder="yyyy" /></div>
    </div>
       <div class="form-group">
        <label class="col-sm-3 control-label">CVC:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="cvc" value="" placeholder="xxx" /></div>
    </div>
   
    <div class="form-group"><div class="col-sm-offset-3 col-sm-9"><input type="submit" class="btn btn-default" value="Submit" /></div></div>
</form>