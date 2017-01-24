<?php
/**
 * Created by PhpStorm.
 * User: rabin
 * Date: 1/16/17
 * Time: 12:01 PM
 */
?>

<h3><?php echo $title ?></h3>
<form method="POST" action="<?php echo base_url('staff') . '/' . uri_string(); ?>" id="post_staff_unavailability_form"
      role="form"
      class="form-horizontal" enctype="multipart/form-data">
    <div class="form-group">
        <label class="col-sm-3 control-label"> * Unavailability Json:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="timestamps" value=""/><pre>{
"date":"2017-01-16",
"availability": [{"start": "1484544943","end": "1484544953"}]
}</pre></div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9"><input type="submit" class="btn btn-default" value="Submit"/></div>
    </div>
</form>
