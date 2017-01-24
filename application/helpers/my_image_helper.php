<?php

/* This function is used to get image for users*/

function getUserProfileImage($image_name,$is_thumb='thumb')
{
	if($image_name!="" && file_exists('assets/images/profile_image/'.$image_name)):
		 $profile_image = $image_name;
		else:
		$profile_image = "default_profle.png";
	 endif;
	 
	 if($is_thumb=="thumb"):
		$image_path = base_url('assets/images/profile_image/thumb');
		else:
		$image_path = base_url('assets/images/profile_image');
			
	 endif;
	 
	 return $image_path.'/'.$profile_image;
}
