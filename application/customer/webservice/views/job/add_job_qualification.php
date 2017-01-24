<h3><?php echo $title ?></h3>
<form method="PUT" action="<?php echo $url; ?>"  role="form"  class="form-horizontal" >	
    <div class="form-group">
        <label class="col-sm-3 control-label"> Json:</label>
        <div class="col-sm-12"><pre><?php echo $json_schema; ?></pre></div>
    </div>
    <div class="form-group"><div class="col-sm-offset-3 col-sm-9"><input type="submit" url="http://192.168.0.32/cruva/customer/webservice/job/get_data" class="btn btn-default _curl" value="Submit" /></div></div>
</form>