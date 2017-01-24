<h3><?php echo $title ?></h3>
<form method="POST" action="<?php echo base_url('staff') . '/' . uri_string(); ?>" id="register_form" role="form"
      class="form-horizontal" enctype="multipart/form-data">
    <div class="form-group">
        <label class="col-sm-3 control-label">* Register Type:</label>

        <div class="col-sm-9"><input class="form-control" type="text" name="register_type" value=""/>1: Normal, 2: Google,
            3:Facebook
        </div>
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
        <label class="col-sm-3 control-label"> * Email:</label>

        <div class="col-sm-9"><input class="form-control" type="text" name="email" value=""/></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label"> * Password:</label>

        <div class="col-sm-9"><input class="form-control" type="password" name="password" value=""/></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label"> * Phone Number:</label>

        <div class="col-sm-9"><input class="form-control" type="text" name="phone_number" value=""/></div>
    </div>

    <div class="skill-div">
        </br>
        </br>
        <div class="form-group">
            <label class="col-sm-5 control-label"> * Skills(as Valid JSON):</label>

            <div class="col-sm-7">
                <input class="form-control" type="text" name="skill" value=""/>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label">Available Skills:</label>

        <div class="col-sm-9">
            <?php echo "<pre>"; print_r($skills); ?>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label">Mandatory Register Type 2 Fields</label>

        <div class="col-sm-9"></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Google Id:</label>

        <div class="col-sm-9"><input class="form-control" type="text" name="google_id" value=""/>Google Id if Register type
            == 2
        </div>
    </div>
    </br>
    </br>

    <div class="form-group">
        <label class="col-sm-3 control-label">Mandatory Register Type 3 Fields</label>

        <div class="col-sm-9"></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Facebook access Token:</label>

        <div class="col-sm-9"><input class="form-control" type="text" name="access_token" value=""/>Facebook token if
            Register type == 3
        </div>
    </div>
    </br>
    </br>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9"><input type="submit" class="btn btn-default" value="Submit"/></div>
    </div>
</form>