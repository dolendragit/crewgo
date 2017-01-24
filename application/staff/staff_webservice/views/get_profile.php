<?php
/**
 * Created by PhpStorm.
 * User: rabin
 * Date: 11/27/16
 * Time: 1:51 PM
 */
?>
<h3><?php echo $title ?></h3>
<form method="POST" action="<?php echo base_url('staff').'/'.uri_string(); ?>"  role="form"  class="form-horizontal">
    <div class="form-group"><div class="col-sm-offset-3 col-sm-9"><input type="submit" class="btn btn-default" value="Submit" /></div></div>
</form>