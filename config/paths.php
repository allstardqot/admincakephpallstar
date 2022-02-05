<?php
use Cake\Core\Configure;
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         3.0.0
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 */

/**
 * Use the DS to separate the directories in other defines
 */
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

/**
 * These defines should only be edited if you have cake installed in
 * a directory layout other than the way it is distributed.
 * When using custom settings be sure to use the DS and do not add a trailing DS.
 */

/**
 * The full path to the directory which holds "src", WITHOUT a trailing DS.
 */
define('ROOT', dirname(__DIR__));

/**
 * The actual directory name for the application directory. Normally
 * named 'src'.
 */
define('APP_DIR', 'src');

/**
 * Path to the application's directory.
 */
define('APP', ROOT . DS . APP_DIR . DS);

/**
 * Path to the config directory.
 */
define('CONFIG', ROOT . DS . 'config' . DS);

/**
 * File path to the webroot directory.
 */
define('WWW_ROOT', ROOT . DS . 'webroot' . DS);

/**
 * Path to the tests directory.
 */
define('TESTS', ROOT . DS . 'tests' . DS);

/**
 * Path to the temporary files directory.
 */
define('TMP', ROOT . DS . 'tmp' . DS);

/**
 * Path to the logs directory.
 */
define('LOGS', ROOT . DS . 'logs' . DS);

/**
 * Path to the cache files directory. It can be shared between hosts in a multi-server setup.
 */
define('CACHE', TMP . 'cache' . DS);

/**
 * The absolute path to the "cake" directory, WITHOUT a trailing DS.
 *
 * CakePHP should always be installed with composer, so look there.
 */
define('CAKE_CORE_INCLUDE_PATH', ROOT . DS . 'vendor' . DS . 'cakephp' . DS . 'cakephp');

/**
 * Path to the cake directory.
 */
define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
define('CAKE', CORE_PATH . 'src' . DS);

$pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";	 
$pageURL .= $_SERVER["SERVER_NAME"];
$siteFoler = dirname(dirname($_SERVER['SCRIPT_NAME']));
$siteUrl = $pageURL.$siteFoler;
$siteUrl = str_replace('\\','/',$siteUrl);

define('SITE_URL',$siteUrl);



$host = $_SERVER['HTTP_HOST'];
$root = $_SERVER['DOCUMENT_ROOT'];
//$folder = 'supertrusted/';
$folder = '';

define('WEBSITE_URL','https://' . $host . '/');
define('WEBSITE_ADMIN_URL',WEBSITE_URL.'admin/');

define('USER_IMG_DIR','img/workers/thumb/');

define('UPLOADS_DIR', 'uploads/');

//http path
define('UPLOAD_PATH',WEBSITE_URL . 'uploads/');
define('U_IMAGE_PATH',WEBSITE_URL . 'uploads/users/');
define('BANNER_PATH',WEBSITE_URL . 'uploads/banner_image/');
define('RECEIPT_PATH',WEBSITE_URL . 'uploads/receipts/');
define('INVOICE_PATH',WEBSITE_URL . 'uploads/invoices/');
define('R_IMAGE_PATH',WEBSITE_URL . 'uploads/reference/');
define('KYC_PATH',WEBSITE_URL . 'uploads/kyc/');

//root path
define('WEB_ROOT_PATH',  $root . '/'.$folder . 'webroot/');
define('UPLOAD_ROOT_PATH',  $root . '/'.$folder . 'webroot/uploads/');
define('U_IMAGE_ROOT_PATH', $root . '/'.$folder . 'webroot/uploads/users/');
define('BANNER_ROOT_PATH', $root . '/'.$folder . 'webroot/uploads/banner_image/');
define('RECEIPT_ROOT_PATH', $root . '/'.$folder . 'webroot/uploads/receipts/');
define('INVOICE_ROOT_PATH', $root . '/'.$folder . 'webroot/uploads/invoices/');
define('R_IMAGE_ROOT_PATH', $root . '/'.$folder . 'webroot/uploads/reference/');
define('KYC_ROOT_PATH', $root . '/'.$folder . 'webroot/uploads/kyc/');

define('CERT_ROOT_PATH', $root . '/'.$folder . 'nodeapi/certificates/');
define('KEY_ROOT_PATH', $root . '/'.$folder . 'nodeapi/certificates/keys');

define('DefalutListing',array("QB","RB","WR","TE","K","Flex"));
$global_config = [
    'encrypt_decrypt_key'=>'Kc6JLpKDZCKHueaAD6dBtNGvN7JULrXa',
    'secret_iv'=>'gCWVLngUNSSS4sRM',
];
Configure::write('global_config', $global_config);