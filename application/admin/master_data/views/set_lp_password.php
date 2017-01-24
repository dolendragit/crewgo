<div>
    <a class="hiddenanchor" id="signup"></a>
    <a class="hiddenanchor" id="signin"></a>
    <style>
        }
    </style>





    <?php if($this->session->flashdata('error') != ""):?>
        <div class="alert alert-danger">
            <span><?php echo $this->session->flashdata('error');?></span>
        </div>
    <?php endif;?>

    <div class="login_wrapper">
        <div class="animate form login_form">

            <div id="form_errors" class="alert alert-danger fade in" style="display:none;">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <div id="form_errors_msg"></div>
            </div>

            <section class="login_content">

                <?php if($this->session->flashdata('message') != ""):?>
                    <div class="alert alert-info">
                        <span><?php echo $this->session->flashdata('message');?></span>
                    </div>
                <?php endif;?>

                <?php if($this->session->flashdata('success') != ""):?>
                    <div class="alert alert-success">
                        <span><?php echo $this->session->flashdata('success');?></span>
                    </div>
                <?php endif;?>

                <form class="form-horizontal" method="post" action="<?php echo base_url("admin/master_data/activate_registration/activate");?>" onsubmit="return validateForm()">
                <div>
                    <img class="admin-logo" src="<?php echo base_url('assets/images').'/'.CRUVA_ADMIN_LOGO;?>"><span class="after-logo-text">  LP Password </span>
                </div>
                <div>
                    <input type="password" name="password" placeholder="Password" required="required" class="form-control">
                    <?php echo form_error('password'); ?>
                </div>
                <div>
                    <input type="password" name="confirm_password" placeholder="Re-type Password" required="required" class="form-control">
                    <?php echo form_error('confirm_password'); ?>
                </div>

                <div style="padding-top:5px;text-align:left;">
                    <input type="hidden" name="lp_id" value="<?php echo $lp_id;?>">
                    <input type="submit" name="submit" value="Set Password"  class="btn btn-primary  active" style="margin-left:0px;">
                </div>
                <div class="clearfix"></div>
                <div class="separator">
                    <div class="clearfix"></div>
                    <br />
                    <div>
                        <p>©<?php echo date('Y');?> All Rights Reserved.</p>
                    </div>
                </div>
                </form>
            </section>
        </div>
    </div>
</div>


<script>
    function validateForm() {
        var error_message = "";
        var error = "";

        password = $('input[name=password]').val();
        c_password = $('input[name=confirm_password]').val();

        if(!password){
            error_message += "Password cannot be empty <br>";
        }

        if(!c_password){
            error_message += "Confirm Password cannot be empty <br>";
        }

        if(password.length < 6){
            error_message += "Password must be at least 5 characters.<br>";
        }

        if(password != c_password){
            error_message += "Password and Confirm Password do not match.<br>";
        }


        if(error_message != ""){

            error = "<div stlye='font-weight:bold;text-align:left;'>Please correct the following errors and try again:</div>";
            error += error_message;
            //alert(error);
            $('#form_errors_msg').html(error);
            $('#form_errors').show();
            return false;
        }

    return true;
    }
</script>
