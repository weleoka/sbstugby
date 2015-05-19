<?php

/**
 * Sample configuration file for Anax webroot.
 *
 */


/**
 * Define essential Anax paths, end with /
 *
 */
define('ROO_INSTALL_PATH', realpath(__DIR__ . '/../') . '/');
define('ROO_APP_PATH',     ANAX_INSTALL_PATH . 'app/');



/**
 * Include autoloader.
 * 
 */
include(ROO_APP_PATH . 'config/autoloader.php'); 



/**
 * Include global functions.
 *
 */
include(ROO_INSTALL_PATH . 'src/functions.php'); 

