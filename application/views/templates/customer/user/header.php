<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo ADMIN_SITE_TITLE;?></title>
 
    <!-- Bootstrap -->
    <link href="<?php echo base_url('assets/admin/vendors/bootstrap/dist/css/bootstrap.min.css');?>" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo base_url('assets/admin/vendors/font-awesome/css/font-awesome.min.css');?>" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?php echo base_url('assets/admin/vendors/nprogress/nprogress.css');?>" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?php echo base_url('assets/admin/vendors/nprogress/nprogress.css');?>" rel="stylesheet">
    <!-- iCheck -->
    <link href="<?php echo base_url('assets/admin/vendors/iCheck/skins/flat/green.css');?>" rel="stylesheet">
    <!-- bootstrap-progressbar -->
    <link href="<?php echo base_url('assets/admin/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css');?>" rel="stylesheet">
    <!-- JQVMap -->
    <link href="<?php echo base_url('assets/admin/vendors/jqvmap/dist/jqvmap.min.css');?>" rel="stylesheet"/>

    <!-- Custom Theme Style -->
    <link href="<?php echo base_url('assets/admin/build/css/custom.min.css');?>" rel="stylesheet">
	 <link href="<?php echo base_url('assets/admin/build/css/cruva_admin.css');?>" rel="stylesheet">
	 <!-- jQuery -->
    <script src="<?php echo base_url('assets/admin/vendors/jquery/dist/jquery.min.js');?>"></script>
	<script src="<?php echo base_url('assets/admin/vendors/datatables.net/js/jquery.dataTables.min.js');?>"></script>
	 <script src="<?php echo base_url('assets/admin/vendors/datatables.net-buttons/js/dataTables.buttons.min.js');?>"></script>
	 <!-- Bootstrap -->
    <script src="<?php echo base_url('assets/admin/vendors/bootstrap/dist/js/bootstrap.min.js');?>"></script>
    <script src="<?php echo base_url('assets/admin/js/custom.js');?>"></script>
    <script src="https://cdn.datatables.net/rowreorder/1.1.2/js/dataTables.rowReorder.min.js"></script>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

  </head>
<?php 
$user_details = $this->ion_auth->user()->row();
 
?>
  <div id="mydiv" style="display:none;">
  <img src="<?php echo base_url('assets/images')."/ajax-loader.gif";?>" class="ajax-loader">
  </div>
  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="<?php echo base_url('admin');?>" class="site_title"><img class="admin-logo" src="<?php echo base_url('assets/images').'/'.CRUVA_ADMIN_LOGO;?>" style="padding-top:8px;opacity:1"> <span><?php echo ADMIN_SITE_TITLE;?></span></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile">
              <div class="profile_pic">
                <img src="<?php echo getUserProfileImage($user_details->profile_image);?>" alt="Profile Image" class="img-circle profile_img">
              </div>
              <div class="profile_info">
                <span>Welcome,</span>
                <h2><?php echo $user_details->name;?></h2>
              </div>
            </div>
            <!-- /menu profile quick info -->

            <br />

             
       