<?php 
	$this->load->view('templates/customer/user/header');
	$this->load->view('templates/customer/user/sidebar');
	$this->load->view('templates/customer/user/topnavigation');
        
	echo $template['body'];
	$this->load->view('templates/customer/user/footer');
?>