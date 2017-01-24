<?php 
	$this->load->view('templates/customer/header');
	echo '<div class="container">';
	echo $template['body'];
    echo '</div>';
	$this->load->view('templates/customer/footer');
?>