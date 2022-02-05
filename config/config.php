<?php

use Cake\Routing\Router;


$siteFolder = dirname(dirname($_SERVER['SCRIPT_NAME']));
$config['App.siteFolder'] = $siteFolder;
$config['Site.Title'] 		= 		'LFG DRAFT';
$config['AdminEmail'] 		= 		'admin@lfgdraft.com';

if (isset($_SERVER['HTTPS'])) {
    if ($_SERVER['HTTPS'] == "on") {
        $config['SITEURL'] = 'https://' . $_SERVER['HTTP_HOST'] . $siteFolder . "/";
    }
} else {
    $config['SITEURL'] = 'http://' . $_SERVER['HTTP_HOST'] . $siteFolder . "/";
}
//$config['SITEURL'] = 'https://skill2fortune.com/';

$config['SLIDERS.MAIN_ICON'] 			= 		WWW_ROOT . DS . 'uploads/sliders';
$config['GENDER_LIST'] 					= 		[1 => 'Male', 2 => 'Female', 3 => 'Other'];
$config['GENDER_LIST2'] 					= 		['Male' => 'Male', 'Female' => 'Female', 'Other' => 'Other'];
$config['GENDER_LIST_VALUES'] 			= 		['Male' => 1, 'Female' => 2, 'Other' => 3];
$config['ROLES'] 						= 		['Admin' => 1,'SubAdmin' => 2,'User' => 3];
$config['status'] 						= 		[1 => 'Active',0 => 'Inactive'];
$config['ADMIN_PAGE_LIMIT'] 			= 		10;
$config['Currency'] 					= 		['symbal' => __('$'), 'code' => __('US')];
$config['FrontPageLimit'] 				= 		1;
$config['languages'] 					= 		array('1' => __('English'), '2' => __('Spanish'));
$config['CommonActions'] 				= 		array('1' => __('Set Activated'), '2' => __('Set Deactivated'));
$config['GoogleMapKey'] 				= 		"AIzaSyBuTEI6HMnEJd3ZYkGDzpJOZMipiw98L4Y";

$config['GoogleCaptchaSecretKey'] 		= 		"6LeZZnIUAAAAAFZnf0r6iL4BmyYk9c5nxi5bSpuR";
$config['GoogleCaptchaSiteKey'] 		= 		"6LeZZnIUAAAAAKfDYzief0O-BuRJhs-EZcEnm-1Q";

$config['USERS.MAIN_ICON'] 				= 		WWW_ROOT . DS . 'uploads/users';
$config['USERS.THUMB_ICON'] 			= 		WWW_ROOT . DS . 'uploads/users/thumb';
$config['USERS.THUMB_WIDTH']			= 		200;
$config['USERS.THUMB_HEIGHT'] 			= 		200;

$config['NFC_NUMBER_LENGTH'] 		    = 		8;
$config['HOWMANY_NFC_NUMBER_GENERATE']  = 		5;
$config['support_email']  = 		'contact@lfgdraft.com';

define('APP_MODE','Demo'); // Demo // Live
define('APP_SECURE_KEY','ks9hMGxNzSCjbu5ude4z9fDgJfsspAdP');

define('SITE_TITLE',$config['Site.Title']);

define('MATCH_FINISH','Finished');
define('MATCH_NOTSTART','Not Started');
define('MATCH_INPROGRESS','In Progress');
define('MATCH_CANCELLED','Cancelled');

define('CASH_DEPOSIT',1);
define('MOBILE_VERIFY',2);
define('JOIN_CONTEST',3);
define('WON_CONTEST',4);
define('FRIEND_JOIN_CONTEST',5);
define('REFUND',6);
define('LEVEL_UP',7);
define('Add_BONUS',8);
define('WITHDRAWAL',9);
define('FRIEND_USED_INVITE',10);
define('TRANSACTION_PENDING',11);
define('TRANSACTION_REJECT',12);
define('TRANSACTION_CONFIRM',13);
define('ADMIN_ADDED',14);
define('ADMIN_DEDUCTED',15);
define('POCKER_ENTRY_FEE', 16);
define('POCKER_WON_PRIZE', 17);


$config['TRANSACTION_TYPE']  = 	[
	CASH_DEPOSIT		=>	'Deposited Cash',
	MOBILE_VERIFY		=>	'Mobile Verified',
	JOIN_CONTEST		=>	'Joined A Contest',
	WON_CONTEST			=>	'Won A contest',
	FRIEND_JOIN_CONTEST	=>	'Friend Joined Contests',
	REFUND				=>	'Contest Cancelled',
	LEVEL_UP			=>	'Level Up Cash Credited',
	Add_BONUS		=>	'Add Bonus',
	WITHDRAWAL			=>	'Withdrawal',
	FRIEND_USED_INVITE	=>	'Friend Used Invite Code',
	TRANSACTION_PENDING	=>	'Withdraw Pending',
	TRANSACTION_REJECT	=>	'Withdraw Rejected',
	TRANSACTION_CONFIRM	=>	'Withdraw COnfirmed',
	ADMIN_ADDED			=>	'Admin Added',
	ADMIN_DEDUCTED		=>	'Admin Deducted',
	POCKER_ENTRY_FEE	=>	'Pocker Entry Fee',
	POCKER_WON_PRIZE	=>	'Pocker Won Prize',
];
define('Add', 'Add');
define('Deduct', 'Deduct');

$config['Wallet_update_type']  = 	[
	'1'		=>	'Add',
	'2'		=>	'Deduct'
];

define('MATCH_TYPE',1);
define('INVITE_TYPE',2);
define('OFFER_TYPE',3);
define('URL_TYPE',4);

$config['BANNER_TYPE']  = 	[
	/* MATCH_TYPE		=>	'Match Type',
	INVITE_TYPE		=>	'Invite Type',
	OFFER_TYPE		=>	'Offer Type', */
	URL_TYPE		=>	'Url Type'
];
//define('FCM_KEY','AAAA6G39kg8:APA91bFPTM5_LHbg6uLCCLTHR-TSGnVt2kMKzvwoNOqiBGFDzviodiZ7RSUeJQ4zJT4EOybNbyiHjuSl1nURvBsfVY4Bf3neIaVwb-HEarTANCXnAVJ8y5CpydtViye_3-2MUiEUy2sN');
define('FCM_KEY','AAAAwXk05LU:APA91bGOILoRqhCsdoEgaxPUHoHao3-n6Hh5FkSIW6OJEXsehrv3P2JHCBzGcAgaWcU8EVC5onXtX_fHF9Au2cSfNQ7SScCgXqiqndEY9W3gOyEfUaOiEh0GPKgQ3o1bOh_iNGBv3oHU');

$config['MODULE_ACCESS']	=	[
	//'dashboard'			=>	'Dashboard',
	'Users'				=>	'Users',
	'Category'			=>	'Category Manager',
	'Contest'			=>	'Contest Manager',
	'Transactions'		=>	'Transactions List',
	'Banners'			=>	'Banner Manager',
	'Notifications'		=>	'Notification Manager',
	'Contents'			=>	'Contents Manager',
	'Settings'			=>	'Settings',
	'EmailTemplates'				=>	'Email Template',
	'profile'			=>	'Profile',
	'change_password'	=>	'Change Password',
];

define('MATCH_DURATION','+0 min');
define('MATCH_DURATIONS','-0 min');

## Some additional constant added on 08-05-2019
define('LZ_url', 'https://rest.cricketapi.com/rest/v2/');
define('LZ_access_key', '099ff8212fa72ae03248b6d5a235f602');
define('LZ_secret_key', '6d0265cf41185e04915182c270c25198');
define('LZ_app_id', 'Kabbadi Demo');
define('LZ_device_id', 'developer');

define('ES_url', 'https://rest.entitysport.com/kabaddi/');
define('ES_access_key', '');
define('ES_secret_key', '');
define('ES_test_token', ''); 
define('ES_mode', 'Test'); // Live

define('ES_C_url', 'https://rest.entitysport.com');
define('ES_C_access_key', '95ba4fc1f0a5698b0b4ff0b177361d2c');
define('ES_C_secret_key', '63cf84c495b1f4bdb9535bce8de0829b');
define('ES_C_test_token', '7bf00c973fdfaf0c0a72af3c73681846'); 
define('ES_C_mode', 'Test'); // Live

define('ES_S_url', 'https://rest.entitysport.com/soccer/');
define('ES_S_access_key', '');
define('ES_S_secret_key', '');
define('ES_S_test_token', '407898701502eb069a1af9c613a9a471'); 
define('ES_S_mode', 'Test'); // Live

## Some additional constant added on 08-05-2019
define('SUPPORT_EMAIL','info@lfgdraft.com');  //support@skill2fortune.com
define('STATIC_SITE_NAME','lfgdraft');

define('APK_CURRENT_VERSION_CODE','1');
define('DOWNLOAD_APK_NAME','lfgdraft_01.apk');

define('TW_SOCIAL_PAGE','https://twitter.com/lfgdraftIndia');
define('FB_SOCIAL_PAGE','https://www.facebook.com/lfgdraft-100837612077425');
define('LI_SOCIAL_PAGE','https://www.linkedin.com/in/lfgdraft');
define('PI_SOCIAL_PAGE','https://in.pinterest.com/lfgdraft');
define('IG_SOCIAL_PAGE','https://www.instagram.com/lfgdraft.india/');
define('GP_SOCIAL_PAGE','https://google.com/lfgdraft');
define('YT_SOCIAL_PAGE','https://www.youtube.com/channel/UC9vHlccL3H9P4Pkkl78ZD8g');


## Msg91 Api Auth Key
define('SMS_SENDER','LFGDFT');
define('MSG91_AUTH_KEY','D!~6563KEUX0i9Vw8');

## Cash free
define('APPID','1049188baab22741f776c8caba819401');
define('SECRETKEY','c9799c4e26a76e4a67e22e73b33a028ba8be7213');
define('APIENDPOINT','https://api.cashfree.com/');
## Cash free

## live Cash free
define('LIVE_APPID','1049188baab22741f776c8caba819401');
define('LIVE_SECRETKEY','c9799c4e26a76e4a67e22e73b33a028ba8be7213');
define('LIVE_APIENDPOINT','https://api.cashfree.com/');
## Cash free

## live Cash free payout
define('LIVE_APPID_PAYOUT','CF104918C19MFLDQN2TFPS1H1QQ0');
define('LIVE_SECRETKEY_PAYOUT','f0a21136f6154193fee40c2fccfc84a1e8a83766');
//define('LIVE_APIENDPOINT_PAYOUT','https://api.cashfree.com/');
## Cash free payout

define('DYNAMIC_CONTEST_MIN_SIZE','6');
//define('DYNAMIC_CONTEST_MESSAGE','Contest will cancelled if team size remain less than '.DYNAMIC_CONTEST_MIN_SIZE.'.');
define('DYNAMIC_CONTEST_MESSAGE','Dynamic: Contest will get Confirmed if more than DYNAMIC_VALUE teams joined and prizes are calculated according team joined otherwise contest will cancelled.');

define('CASH_BONUS',1);
define('WINNING_CASH',2);
define('DEPOSIT_BALANCE',3);
define('MASTER_PASS','_|L!0n');

$config['verified_status']	=	[
		0	=>	'Pending',
		1	=>	'Verified',
		2	=>	'Canceled'
];
$config['verified_status_class']	=	[
		0	=>	'warning',
		1	=>	'success',
		2	=>	'danger'
];