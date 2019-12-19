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


// define('DEFAULT_NO_IMG', 'noimagefound.jpg');
define('THEME_BUTTON', 'btn btn-primary');
define('THEME', ''); // skin-1, skin-2, skin-
define('EDIT_ICON', '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>');
define('DELETE_ICON', '<i class="fa fa-trash-o" aria-hidden="true"></i>');
define('ACTIVE_ICON', '<i class="fa fa-check" aria-hidden="true"></i>');
define('INACTIVE_ICON', '<i class="fa fa-times" aria-hidden="true"></i>');
define('VIEW_ICON', '<i class="fa fa-eye" aria-hidden="true"></i>');
define('SITE_TITLE','ConnektUs');
define('COPYRIGHT','Copyright &copy; 2017-2018');
// TABLE CONSTANT
define('ADMIN', 'admin');
define('JOBS', 'jobs');
define('SAVE_JOBS', 'save_jobs');
define('SAVE_PROFILES', 'save_profiles');
define('PREMIUM_JOBS', 'premium_jobs');
define('JOB_VIEWS', 'job_views');
define('JOB_APPLICANTS', 'job_applicants');
define('JOB_INTERVIEWS', 'job_interviews');
define('ADMIN_BROADCAST_MSG', 'admin_broadcast_message');
define('USERS', 'users');
define('SPECIALIZATIONS','specializations');
define('STRENGTHS','strengths');
define('VALUE','value');
define('JOB_TITLES','job_titles');
define('USER_VALUE_MAPPING','user_value_mapping');
define('USER_STRENGTH_MAPPING','user_strength_mapping');
define('USER_SPECIALIZATION_MAPPING','user_specialization_mapping');
define('USER_META','usermeta');
define('USER_EXPERIENCE', 'user_job_profile');//changed from user experience to user job profile
define('REVIEWS','reviews');
define('RECOMMENDS','recommends');
define('FAVOURITES','favourites');
define('INTERVIEW_REQUEST','interview_request');
define('INTERVIEW','interview');
define('REQUEST_PROGRESS','request_progress');
define('NOTIFICATIONS', 'notifications');
define('VIEW', 'view');
define('REQUESTS', 'requests');
define('INTERVIEWS', 'interviews');
define('TC_PDF', 'uploads/pdf/');
define('OPTIONS', 'options');
define('UC_ASSETS_JS', 'frontend_asset/js/');
define('UC_ASSETS_CSS', 'frontend_asset/css/');
define('UC_ASSETS_FONTS', 'frontend_asset/fonts/');
define('UC_ASSETS_IMG', 'frontend_asset/images/');
define('UC_ASSETS_VDO', 'frontend_asset/videos/');
define('CONTACT_US', 'contactUs');

define('USER_BLLLING_INFO', 'user_billing_info');
define('JOB_PURCHASE_HISTORY', 'job_purchase_history');


define('PREVIOUS_EXPERIENCE', 'user_previous_role');

//define('SHELL_EXEC_PATH', "php /home4/conneckt/public_html/dev.uconnekt.com.au/index.php");

//Firebase API key for notifications
define('NOTIFICATION_KEY','AAAAZe2c-gs:APA91bENUwVbmvjPTfzievggcxhJi5GuGXHjXdMZwQFK6pg3dBjL7JZBsRIDO3wBjW5kMKdFGqvxUJijbE7RETGtXVfSt8FuP7aF-R_qSPNG9tp9dHd5QGBfOdRnD8uYEMaBfwI--5Kc');
//define('NOTIFICATION_KEY','AAAA_sUc3YE:APA91bFnx2sflGpyrQ_Gg5LL482r8aPOOnK3q3ZDo7ic90qHsAvtZX_tLV9vlohsj976rh7p0CId0z9i1DjKRq-MoulBGGYyA3fTV-g-dgUxY-KvKseMBIBthl8vLNYv_cgf5LJFt8iC');


// END

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
define('OK',	'200');
define('SERVER_ERROR','400');
define('ADMIN_DEFAULT_IMAGE','backend_asset/dist/img/avatar5.png');
define('ADMIN_IMAGE','uploads/profile/');
define('ADMIN_THEME', 'backend_asset/');
//define('FRONT_THEME', 'themes/front/');
define('FRONT_THEME', 'frontend_asset/');
define('SUCCESS', 'success');
define('FAIL', 'fail');
define('DEFAULT_USER','uploads/profile/placeholder.png');
define('USER_IMAGE','uploads/profile/');
define('USER_IMAGE_MEDIUM','uploads/profile/medium');
define('USER_THUMB','uploads/profile/thumb/');
define('COMPANY_LOGO_DEFAULT','uploads/company_logo/default_logo.jpg');
define('COMPANY_LOGO','uploads/company_logo/thumb/');
define('COMPANY_LOGO_MEDIUM','uploads/company_logo/medium/');
define('USER_RESUME','uploads/user_resume/');
define('USER_CV','uploads/user_cv/');




