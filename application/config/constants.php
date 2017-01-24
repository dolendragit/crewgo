<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| ADMIN CONSTANT SETTINGS
|--------------------------------------------------------------------------
|
|  
|
*/

define('ADMIN_SITE_TITLE', 'CRUVA :: Admin Panel');
define('DEFAULT_EMAIL', 'cruva@info.com');
define('EMAIL_SITENAME', 'CREW GO');
define('CRUVA_ADMIN_LOGO', 'cruva_admin_logo.png');


/*
|--------------------------------------------------------------------------
|USER TYPE CONSTANT SETTINGS
|--------------------------------------------------------------------------
|
|  
|
*/

define('ADMIN', '1');
define('LHC', '2');
define('CUSTOMER', '3');
define('STAFF', '4');
define('SUPERVISOR', '5');
define('JOB_STAFF_STATUS_DEFAULT', '0');
define('JOB_STAFF_STATUS_CHECKED_IN', '1');
define('JOB_STAFF_STATUS_COMPLETED', '2');
define('JOB_STAFF_STATUS_ON_BREAK', '3');

define('STAFF_PERIMETER_SETTING_SLUG', 'staff_leaves_site');

define('JOB_STAFF_BREAK_CONFLICT_RESOLVED', '1');
define('JOB_STAFF_BREAK_CONFLICT_NO', '2');
define('JOB_STAFF_BREAK_CONFLICT_YES', '3');

define('DEFAULT_TIMEZONE', 'Australia/Sydney');
define('DEFAULT_JOB_PERIMETER_SIZE_METERS', '100');

define('STAFF_TRACK_INTERVAL', 30); //IN MINUTES

/* End of file constants.php */
/* Location: ./application/config/constants.php */