<?php
if (!defined('BASEPATH'))
exit('No direct script access allowed');

class User_model extends MY_Model 
{
		protected $table = 'tbl_user';
    public function __construct() 
    {
        parent::__construct();

    }
}