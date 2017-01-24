<?php 
	$this->load->view('templates/lhcadmin/header');
	$this->load->view('templates/lhcadmin/sidebar');
	$this->load->view('templates/lhcadmin/topnavigation');        
	echo $template['body'];
	$this->load->view('templates/lhcadmin/footer');
?>