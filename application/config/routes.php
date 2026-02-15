<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['forgotpassword'] = 'login/forgotpassword';
$route['enterotp'] = 'login/enterotp';
$route['resetpassword'] = 'login/resetpassword';
$route['logout'] = 'login/logout';

$route['contacts'] = 'home/contacts';

/*----------------------------------Website---------------------------------*/

//$route['signup'] = 'website/signup';
//$route['registered'] = 'website/registered';

//$route['about-us'] = 'website/aboutus';
//$route['contact-us'] = 'website/contactus';
//$route['privacy-policy'] = 'website/privacypolicy';
//$route['terms-conditions'] = 'website/termsconditions';
//$route['registered'] = 'website/registered';
//$route['service'] = 'website/service';
//$route['gallery'] = 'website/gallery';
//$route['register-now'] = 'website/registernow';

//$route['signin'] = 'login/index';

//$route['savecontact'] = 'website/savecontact';
//$route['savecontact'] = 'website/savecontact';

$route['helpdeskmessages'] = 'settings/helpdeskmessages';
$route['helpdesk'] = 'settings/helpdesk';

$route['activateaccount'] = 'epins/activateaccount';
$route['activationrequests'] = 'epins/requestlist';

$route['notice'] = 'settings/news';

/*----------------------------------APIs---------------------------------*/

$route['api/login'] = 'api/account/login';
$route['api/forgotpassword'] = 'api/account/forgotpassword';
$route['api/verifyotp'] = 'api/account/verifyotp';
$route['api/resetpassword'] = 'api/account/resetpassword';

$route['api/getstates'] = 'api/common/getstates';
$route['api/getdistricts'] = 'api/common/getdistricts';
$route['api/getareas'] = 'api/common/getareas';
$route['api/getrelations'] = 'api/common/getrelations';
$route['api/getbanks'] = 'api/common/getbanks';
$route['api/getpackages'] = 'api/common/getpackages';
$route['api/getadminaccountdetails'] = 'api/common/getadminaccountdetails';
$route['api/getnotice'] = 'api/common/getnotice';

$route['api/checkrenewal'] = 'api/members/checkrenewal';

$route['api/generateusername'] = 'api/members/generateusername';
$route['api/checksponsor'] = 'api/members/checksponsor';
$route['api/register'] = 'api/members/register';
$route['api/savepackagepayment'] = 'api/members/savepackagepayment';

$route['api/gethomedata'] = 'api/members/gethomedata';

$route['api/getmemberlist'] = 'api/members/getmemberlist';
$route['api/getdirectmembers'] = 'api/members/getdirectmembers';

$route['api/availableads'] = 'api/members/availableads';
$route['api/savead'] = 'api/members/savead';

$route['api/availableyoutubeads'] = 'api/members/availableyoutubeads';
$route['api/saveyoutubead'] = 'api/members/saveyoutubead';
$route['api/getyoutubelinks'] = 'api/members/getyoutubelinks';

$route['api/availablespins'] = 'api/members/availablespins';
$route['api/savespin'] = 'api/members/savespin';

$route['api/getprofile'] = 'api/profile/getprofile';
$route['api/saveprofile'] = 'api/profile/saveprofile';
$route['api/getmemberstatus'] = 'api/profile/getmemberstatus';
$route['api/getmemberdetails'] = 'api/profile/getmemberdetails';
$route['api/saveprofile'] = 'api/profile/saveprofile';
$route['api/getbankdetails'] = 'api/profile/getbankdetails';
$route['api/updatebankdetails'] = 'api/profile/updatebankdetails';
$route['api/getcontactdetails'] = 'api/profile/getcontactdetails';
$route['api/updatecontactdetails'] = 'api/profile/updatecontactdetails';
$route['api/getnomineedetails'] = 'api/profile/getnomineedetails';
$route['api/updatenomineedetails'] = 'api/profile/updatenomineedetails';
$route['api/uploadkyc'] = 'api/profile/uploadkyc';
$route['api/getkycdetails'] = 'api/profile/getkycdetails';
$route['api/updatepassword'] = 'api/profile/updatepassword';
$route['api/updatetxnpassword'] = 'api/profile/updatetxnpassword';

$route['api/checkactivation'] = 'api/members/checkactivation';
$route['api/activateaccount'] = 'api/wallet/activateaccount';

$route['api/adddeposit'] = 'api/wallet/adddeposit';
$route['api/getdepositlist'] = 'api/wallet/getdepositlist';

$route['api/getwallet'] = 'api/wallet/getwallet';
$route['api/getwallethistory'] = 'api/wallet/getwallethistory';
$route['api/requestwithdrawal'] = 'api/wallet/requestwithdrawal';
$route['api/withdrawalhistory'] = 'api/wallet/withdrawalhistory';

$route['api/wallettransfer'] = 'api/wallet/wallettransfer';
$route['api/wallettransferhistory'] = 'api/wallet/wallettransferhistory';


/*----------------------------------APIs---------------------------------*/
