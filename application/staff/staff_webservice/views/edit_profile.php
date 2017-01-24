<h3><?php echo $title ?></h3>
<form method="POST" action="<?php echo base_url('staff/' . uri_string()); ?>" id="register_form" role="form"
      class="form-horizontal" enctype="multipart/form-data">
    <div class="form-group">
        <label class="col-sm-3 control-label">Profile Image:</label>

        <div class="col-sm-9"><input type="file" name="profile_image" multiple/></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label"> * Name:</label>

        <div class="col-sm-9"><input class="form-control" type="text" name="name" value=""/></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label"> * Address:</label>

        <div class="col-sm-9"><input class="form-control" type="text" name="address" value=""/></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label"> * Phone Number:</label>

        <div class="col-sm-9"><input class="form-control" type="text" name="phone_number" value=""/></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label"> * Auto Bookable:</label>

        <div class="col-sm-9"><input class="form-control" type="text" name="auto_bookable" value=""/>0: No, 1: Yes
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label"> * Brief Description:</label>

        <div class="col-sm-9">
            <textarea class="form-control" type="text" name="brief_description"></textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label"> * Prefered Suburbs (as Valid JSON):</label>

        <div class="col-sm-9"><input class="form-control" type="text" name="suburbs" value=""/></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Sample Suburbs Input:</label>

        <div class="col-sm-9">
            <?php echo "<pre>"; print_r($suburbs); ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label"> * LHC linked:</label>

        <div class="col-sm-9"><input class="form-control" type="text" name="lhc_linked" value=""/></div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9"><input type="submit" class="btn btn-default" value="Submit"/></div>
    </div>
</form>