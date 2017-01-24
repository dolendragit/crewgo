<?php 
	$this->load->view('templates/admin/header');
	$this->load->view('templates/admin/sidebar');
	$this->load->view('templates/admin/topnavigation');
        
	echo $template['body'];
	$this->load->view('templates/admin/footer');
?>