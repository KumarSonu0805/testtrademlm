<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

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
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

///////////////////////////////////////////////
$startyear='2026';
$curyear = date('Y');
if($startyear<$curyear){
    $curyear=$startyear.'-'.$curyear;
}
defined('PROJECT_NAME')        OR define('PROJECT_NAME',"Green Trade"); 
defined('OUR_BRAND')       	   OR define('OUR_BRAND',"");
defined('SITE_SALT')           OR define('SITE_SALT',"BIT Score");
defined('TP')        		   OR define('TP',"bs_"); // Table Prefix
defined('NTYPE')               OR define('NTYPE',"bootstrap"); //Notification Type
defined('REQUEST_LOG')         OR define('REQUEST_LOG',FALSE); //API Log
defined('LOGO')                OR define('LOGO','assets/images/logo.png'); 
defined('LOGO_LIGHT')          OR define('LOGO_LIGHT','assets/images/logo.png'); 

defined('LOGO_HEADER_BG')      OR define('LOGO_HEADER_BG','dark2'); 
defined('HEADER_BG')           OR define('HEADER_BG','dark2'); 
defined('SIDEBAR_BG')          OR define('SIDEBAR_BG','dark2'); 
defined('CONTENT_BG')          OR define('CONTENT_BG','dark'); 

defined('DEDUCTION')           OR define('DEDUCTION',10); 

if(isset($_SERVER['HTTP_HOST']) && ($_SERVER['HTTP_HOST']=='localhost' || $_SERVER['HTTP_HOST']=='192.168.29.123')){
    defined('MIN_BAL')              OR define('MIN_BAL',0); //Minimum
    defined('MIN_DEPOSIT')          OR define('MIN_DEPOSIT',1); //Minimum
    defined('MIN_WITHDRAW')         OR define('MIN_WITHDRAW',10); //Minimum
    defined('ADMIN_ADDRESS')        OR define('ADMIN_ADDRESS','0x3599c27405c429bbe602649533ab9fc650fcd763'); //ADMIN Address
    defined('WORK_ENV')             OR define('WORK_ENV','development'); 
}
else{
    defined('MIN_BAL')              OR define('MIN_BAL',0); //Minimum
    defined('MIN_DEPOSIT')          OR define('MIN_DEPOSIT',1); //Minimum
    defined('MIN_WITHDRAW')         OR define('MIN_WITHDRAW',10); //Minimum
    defined('ADMIN_ADDRESS')        OR define('ADMIN_ADDRESS',''); //ADMIN Address
    defined('WORK_ENV')             OR define('WORK_ENV','production'); 
}

if(isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST']=='localhost'){
	defined('DB_HOST')		? null : define('DB_HOST','localhost');
	defined('DB_USER')		? null : define('DB_USER', 'root');
	defined('DB_PASS')	    ? null : define('DB_PASS','');
	defined('DB_NAME')		? null : define('DB_NAME','db_greentrade');
}
else{
	defined('DB_HOST')      ? null : define('DB_HOST', '127.0.0.1');
	defined('DB_USER')      ? null : define('DB_USER', 'u711511560_user_testmlm');
	defined('DB_PASS')     ? null : define('DB_PASS', 'TestTradeMlm@123#$');
	defined('DB_NAME')      ? null : define('DB_NAME', 'u711511560_db_testmlm');
}
