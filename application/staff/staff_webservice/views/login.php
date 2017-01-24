<h3 xmlns="http://www.w3.org/1999/html"><?php echo $title ?></h3>
<form method="POST" action="<?php echo base_url('staff') . '/' . uri_string(); ?>" role="form" class="form-horizontal">
    <div class="form-group">
        <label class="col-sm-3 control-label">Mandatory Field for All Login Type</label>

        <div class="col-sm-9"></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">* Login Type:</label>

        <div class="col-sm-9"><input class="form-control" type="text" name="login_type" value=""/>1: Normal, 2: Google,
            3:Facebook
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">* Device Id:</label>

        <div class="col-sm-9"><input class="form-control" type="text" name="device_id" value=""/></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">* Device Type:</label>

        <div class="col-sm-9"><input class="form-control" type="text" name="device_type" value=""/>1: iphone, 2: android
        </div>
    </div>
    </br>
    </br>


    <div class="form-group">
        <label class="col-sm-3 control-label">Mandatory Login Type 1 Fields</label>

        <div class="col-sm-9"></div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label">Password:</label>

        <div class="col-sm-9"><input class="form-control" type="password" name="password" value=""/></div>
    </div>
    </br>
    </br>

    <div class="form-group">
        <label class="col-sm-3 control-label">Mandatory Login Type 2 Fields</label>

        <div class="col-sm-9"></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Google Id:</label>

        <div class="col-sm-9"><input class="form-control" type="text" name="google_id" value=""/>Google Id if login type
            == 2
        </div>
    </div>
    </br>
    </br>

    <div class="form-group">
        <label class="col-sm-3 control-label">Mandatory Login Type 1 & 2 Fields</label>

        <div class="col-sm-9"></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Email:</label>

        <div class="col-sm-9"><input class="form-control" type="text" name="email" value=""/></div>
    </div>
    </br>
    </br>

    <div class="form-group">
        <label class="col-sm-3 control-label">Mandatory Login Type 3 Fields</label>

        <div class="col-sm-9"></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Facebook access Token:</label>

        <div class="col-sm-9"><input class="form-control" type="text" name="access_token" value=""/>Facebook token if
            login type == 3
        </div>
    </div>
    </br>
    </br>


    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9"><input type="submit" class="btn btn-default" value="Submit"/></div>
    </div>
</form>

