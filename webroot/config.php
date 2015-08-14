<?php
/**
 * Config-file for Roo. Change settings here to affect installation.
 *
 */

/**
 * Set the error reporting.
 *
 */
error_reporting(-1);                // Report all type of errors
ini_set('display_errors', 1);     // Display all errors
ini_set('output_buffering', 0);   // Do not buffer outputs, write directly

date_default_timezone_set('Europe/Stockholm');


/**
 * Define essential Roo paths, end with /
 *
 */
define('ROO_INSTALL_PATH', realpath(__DIR__ . '/../') . '/');
// define('ROO_INSTALL_PATH', __DIR__ . '/..');
define('ROO_THEME_PATH', ROO_INSTALL_PATH . '/theme/render.php');


/**
 * Include bootstrapping functions.
 *
 */
include(ROO_INSTALL_PATH . '/src/bootstrap.php');


/**
 * Start the session.
 *
 */
session_name(preg_replace('/[^a-z\d]/i', '', __DIR__));
session_start();


/**
 * Create the roo variable.
 *
 */
$roo = array();


/**
 * Site wide settings.
 *
 */
$roo['lang']         = 'sv';
$roo['title_append'] = ' | SB Semesterbyar';

$title = htmlentities(isset($_GET['title']) ? $_GET['title'] : null);

 /**
 * Theme related settings.
 *
 */
$roo['stylesheets'] = array('css/style.css', 'css/table.css', 'css/calendar.css');
$roo['favicon']    = 'favicon.png';

$roo['header'] = <<<EOD
	<a href='index.php'><img class='sitelogo' src='img/rm.png' alt='SB logo'/></a>
	<span class='sitetitle'>SB Semesterbyar</span>
EOD;

$sdf = date(DATE_RFC2822);
$roo['footer'] = <<<EOD
	<footer>
		<span class='sitefooter'>
			Copyright &copy; SB semesterbyar (info@ship.space) |
			<a href='https://github.com'>GitHub</a> |
			<a href='http://validator.w3.org/unicorn/check?ucn_uri=referer&amp;ucn_task=conformance'>Unicorn</a> |
			<a href="http://validator.w3.org/check/referer">HTML5</a>
			<br>{$sdf}
		</span>
	</footer>
EOD;


/**
 * Navigation
 *
 */

$roo['navbar'] = array(
	'class' => 'nb-plain',
	'items' => array(
		'home' => array('text'=>'SB Hem', 'url'=>'index.php', 'title'=>'SB semesterbyar'),
		'bookings' => array('text'=>'Bokningar', 'url'=>'view_bookings.php', 'title'=>'Bokningar'),
		'invoices' => array('text'=>'Fakturor', 'url'=>'view_invoices.php', 'title'=>'Fakturor'),
		'01' => array('text'=>'Stuga', 'url'=>'booking.php?category=1', 'title'=>'Boka stuga'),
		'02' => array('text'=>'Cykel', 'url'=>'booking.php?category=2', 'title'=>'Boka cykel'),
		'03' => array('text'=>'Skidor', 'url'=>'booking.php?category=3', 'title'=>'Boka skidor'),
	),

		'callback_selected' => function($url) {
				if(basename($_SERVER['SCRIPT_FILENAME']) == $url) {
			 			return true;
				}
		}
);



/**
 * Settings for the database.
 * Connection options and credentials in config_mysql.php file.
 */
$roo['database'] = require "config_mysql.php";

$roo['tableNames'] = array(
                                'booking'=>'bokning',
                                'customer'=>'bokning_betalperson',
                                'invoice'=>'bokning_faktura',
                                'bookingCategory'=>'bokning_typ',

                                'bikeBooking'=>'cykel_bokning',
                                'bikeHelmet'=>'cykel_hjälm',
                                'bike'=>'cykel_instans',
                                'bikeType'=>'cykel_typ',

                                'period'=>'kal_period',
                                'priceClass'=>'kal_prisklass',
                                'priceList'=>'kal_prislista',
                                'calendar'=>'kal_vecka',

                                'person'=>'person',

                                'skiiBooking'=>'skid_bokning',
                                'skiiHelmet'=>'skid_hjälm',
                                'skiis'=>'skid_par',
                                'skiiStick'=>'skid_stav',
                                'skiiType'=>'skid_typ',

                                'cottage'=>'stuga',
                                'cottageBooking'=>'stuga_bokning',
);


/**
 * Settings for JavaScript.
 *
 */
$roo['modernizr'] = 'js/modernizr.js';
// $roo['jquery'] = '//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js';
$roo['jquery'] = null; // To disable jQuery
$roo['javascript_include'] = array();
//$roo['javascript_include'] = array('js/main.js'); // To add extra javascript files


/**
 * Google analytics.
 *
 */
$roo['google_analytics'] = null; // 'UA-22093351-1'; // Set to null to disable google analytics