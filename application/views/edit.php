<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Cruva</title>
        <link rel="shortcut icon" href="http://localhost/cruva/assets/favicon.png">
        <link href="http://localhost/cruva/assets/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" media="screen" />
        <link href="http://localhost/cruva/assets/css/main.min.css" rel="stylesheet" type="text/css" media="screen" />
        <link href="http://localhost/cruva/assets/css/style.app.css" rel="stylesheet" type="text/css" media="screen" />
      
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"  crossorigin="anonymous"></script>
        <script> var base_url = 'http://localhost/cruva/'</script>      
        <script src="http://localhost/cruva/assets/js/jquery.js"></script>
        <cript src="http://localhost/cruva/assets/lib/jquery.form.js"></script>        
        <script src="http://localhost/cruva/assets/js/api_scripts.js"></script>     
    </head>
    <body class='bg-egg_shell'>
        <div id='sidebar' class='inner'>
            <h3>Functions</h3>
            <div class="mainNav">

               <div class="bs-example">
    <div class="panel-group" id="accordion">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Login</a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in">
                <div class="panel-body">
                    <ul  id="demo" class="collapse in nav list_unstyled">
                    <li> <a href="http://localhost/cruva/customer/webservice/login" class="load-form">Login</a></li>
                    <li> <a href="http://localhost/cruva/customer/webservice/register" class="load-form">Register</a></li>
                    <li> <a href="http://localhost/cruva/customer/webservice/change_password" class="load-form">Change Password</a></li>
                    <li> <a href="http://localhost/cruva/customer/webservice/fb_login" class="load-form">Facebook Login</a></li>
                    <li> <a href="http://localhost/cruva/customer/webservice/fb_register" class="load-form">Facebook Register</a></li>
                    <li> <a href="http://localhost/cruva/customer/webservice/google_login" class="load-form">Google Login</a></li>
                    <li> <a href="http://localhost/cruva/customer/webservice/google_register" class="load-form">Google Register</a></li>
                    <li> <a href="http://localhost/cruva/customer/webservice/forgot_password" class="load-form">Forgot Password Request</a></li>
                    <li> <a href="http://localhost/cruva/customer/webservice/update_device_info" class="load-form">Update Device Info</a></li>
                    <li> <a href="http://localhost/cruva/customer/webservice/get_lhc_options" class="load-form">Get LHC Users</a></li>
                    <li> <a href="http://localhost/cruva/customer/webservice/set_lhc_user" class="load-form">Set LHC User</a></li>
                    <li> <a href="http://localhost/cruva/customer/webservice/unset_lhc_user" class="load-form">Unset LHC User</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">Job</a>
                </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse">
                <div class="panel-body">
                    <ul  id="demo" class="collapse in nav list_unstyled">
                        <li> <a href="http://localhost/cruva/customer/webservice/job/get_industries" class="load-form">Get Industry</a></li>
                        <li> <a href="http://localhost/cruva/customer/webservice/job/get_skills" class="load-form">Get Skills</a></li>
                        <li> <a href="http://localhost/cruva/customer/webservice/job/get_levels" class="load-form">Get Subskill</a></li>
                        <li> <a href="http://localhost/cruva/customer/webservice/job/get_meeting_place" class="load-form">Get Meeting Place</a></li>
                        <li> <a href="http://localhost/cruva/index.php/developer_view/add_job" class="load-form">Add Job</a></li>
                        <li> <a href="http://localhost/cruva/customer/webservice/job/add_job_test" class="load-form">Add Dummy Job</a></li>
                        <li> <a href="http://localhost/cruva/customer/webservice/job/wage" class="load-form">Get Wage</a></li>
                        <li> <a href="http://localhost/cruva/customer/webservice/job/generate_quote" class="load-form">Generate Quote</a></li>
                        <li> <a href="http://localhost/cruva/customer/webservice/job/get_jobs" class="load-form">Get Jobs</a></li>
                        <li> <a href="http://localhost/cruva/customer/webservice/job/get_calendar_jobs" class="load-form">Get Calendar Jobs</a></li>
                        <li> <a href="http://localhost/cruva/customer/webservice/job/get_job_detail" class="load-form">Get Job Detail</a></li>
                        <li> <a href="http://localhost/cruva/customer/webservice/job/add_supervisor" class="load-form">Add Supervisor</a></li>
                        <li> <a href="http://localhost/cruva/customer/webservice/job/update_supervisor" class="load-form">Update Supervisor</a></li>
                        <li> <a href="http://localhost/cruva/customer/webservice/job/get_supervisors" class="load-form">Get Supervisors</a></li>
                        <li> <a href="http://localhost/cruva/customer/webservice/job/delete_supervisor" class="load-form">Delete Supervisor</a></li>
                        <li> <a href="http://localhost/cruva/customer/webservice/job/get_supervisor_detail" class="load-form">Get Supervisor Detail</a></li>
                        <li> <a href="http://localhost/cruva/customer/webservice/job/add_job_supervisor" class="load-form">Add Job Supervisor</a></li>
                        <li> <a href="http://localhost/cruva/customer/webservice/job/book_job" class="load-form">Book Job</a></li>
                        <li> <a href="http://localhost/cruva/customer/webservice/job/create_induction" class="load-form">Create Induction</a></li>
                        <li> <a href="http://localhost/cruva/customer/webservice/job/get_qualifications" class="load-form">Get Qualifications</a></li>
                        <li> <a href="http://localhost/cruva/customer/webservice/job/get_attributes" class="load-form">Get Job Attributes</a></li>
                        <li> <a href="http://localhost/cruva/customer/webservice/job/set_attributes" class="load-form">Set Job Attributes</a></li>
                        <li> <a href="http://localhost/cruva/customer/webservice/job/additional_information" class="load-form">Additional Information</a></li>
                        <li> <a href="http://localhost/cruva/customer/webservice/job/repeat_job" class="load-form">Repeat Job</a></li>
                        <li> <a href="http://localhost/cruva/customer/webservice/job/get_peak_price" class="load-form">Get Peak Price</a></li>
                        <li> <a href="http://localhost/cruva/customer/webservice/job/view_book_screen" class="load-form">View Book Screen</a></li>
                        <li> <a href="http://localhost/cruva/customer/webservice/job/delete_shift" class="load-form">Delete shift</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">Profile</a>
                </h4>
            </div>
            <div id="collapseThree" class="panel-collapse collapse">
                <div class="panel-body">
                    <ul  id="demo" class="collapse in nav list_unstyled">
                        <li> <a href="http://localhost/cruva/customer/webservice/profile/detail" class="load-form">Profile Detail</a></li>
                        <li> <a href="http://localhost/cruva/customer/webservice/profile/edit" class="load-form">Edit Profile Detail</a></li>
                        <li> <a href="http://localhost/cruva/customer/webservice/profile/get_default_break_times" class="load-form">Default Break times</a></li>
                        <li> <a href="http://localhost/cruva/customer/webservice/profile/customer_attributes" class="load-form">Customer Attributes</a></li>
                        <li> <a href="http://localhost/cruva/customer/webservice/profile/set_attributes" class="load-form">Set Customer Attributes</a></li>
                    </ul>
                </div>
            </div>
        </div>


        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">Payment</a>
                </h4>
            </div>
            <div id="collapseFour" class="panel-collapse collapse">
                <div class="panel-body">
                    <ul  id="demo" class="collapse in nav list_unstyled">
                        <li> <a href="http://localhost/cruva/customer/webservice/profile/add_credit_card" class="load-form">Add Credit Card</a></li>
                        <li> <a href="http://localhost/cruva/customer/webservice/profile/get_credit_card" class="load-form">Get Credit Card</a></li>
                    </ul>
                </div>
            </div>
        </div>


        
    </div>
    
</div> 
                
            </div>
        </div>
        <div id='content' >
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div>
                            
                            <div class="form-inline"  > 
                                <div class="form-group">
                                    <label class="sr-only" for="auth_user_id" >* Authentication Key:</label>
                                    <input class="form-control" type="text" id="auth_user_id" name="Authentication-Key" value=""  placeholder="Authentication Key"/>
                                </div>
<!--                                <div class="form-group">
                                    <label  class="sr-only" for="auth_hash_code" >* Hash Code:</label>
                                    <input class="form-control" type="text" id="auth_hash_code" name="hash_code" value="" placeholder="hash Code" />
                                </div>
                                <div class="form-group">
                                    <label  class="sr-only" for="auth_device_id" >* Device Id:</label>
                                    <input class="form-control" type="text" id="auth_device_id" name="device_id" value="" placeholder="Device Id" />
                                </div>
                                <div class="form-group">
                                    <label  class="sr-only" for="auth_device_type" >* Device Type:</label>
                                    <input class="form-control" type="text" id="auth_device_type" name="device_type" value="" placeholder="Device Type" />1: iphone, 2: android
                                </div>-->
                            </div>
                            
                            
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div id="form-view">
                            <h3>Forms</h3>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div>
                            <h3>Request</h3>
                            <pre id="request"></pre>
                        </div>
                        <div>
                            <h3>Response</h3>
                            <pre id="results"></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
