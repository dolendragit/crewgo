<!-- sidebar menu -->
<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
   <div class="menu_section">
      <h3>&nbsp;</h3>
      <ul class="nav side-menu">

      <li>
      	<a href="#"><i class="fa fa-tachometer"></i>Dashboard</a>
      </li>

      <li>
         <a><i class="fa fa-cog"></i> Staff <span class="fa fa-chevron-down"></span></a>
         <ul class="nav child_menu">
            <li><a href="<?php echo base_url('lp/staff');?>">List Staff</a></li>
            <li><a href="<?php echo base_url('lp/staff/add');?>">Add Staff</a></li>
         </ul>
      </li>


        <li>
         <a><i class="fa fa-file"></i>Induction<span class="fa fa-chevron-down"></span></a>
         <ul class="nav child_menu">
            <li><a href="<?php echo base_url('lp/induction');?>">List Induction</a></li>
            <li><a href="<?php echo base_url('lp/induction/add');?>">Add Induction</a></li>
         </ul>
      </li>

        <li>
         <a><i class="fa fa-users"></i> Customers (CP) <span class="fa fa-chevron-down"></span></a>
         <ul class="nav child_menu">
            <li><a href="<?php echo base_url('lp/customer');?>">List Customer</a></li>
            <li><a href="<?php echo base_url('lp/customer/add');?>">Add Customer</a></li>
         </ul>
      </li>

       <li>
         <a href="<?php echo base_url('lp/orderstaff');?>"><i class="fa fa-user"></i> Order Staff (CP)</a>
      </li>


        <li>
         <a href="<?php echo base_url('lp/shift/getallshifts');?>"><i class="fa fa-clock-o"></i>Shifts</a>
      </li>


       <li>
         <a href="<?php echo base_url('lp/shift');?>"><i class="fa fa-calendar"></i>Shift Calendar</a>
      </li>


       <li>
         <a><i class="fa fa-cogs"></i>Settings<span class="fa fa-chevron-down"></span></a>
         <ul class="nav child_menu">
            <li><a href="<?php echo base_url('lp/wagerule');?>">Crewpay Wage Rules</a></li>
         </ul>
      </li>



      
   </div>
</div>
<!-- /sidebar menu -->