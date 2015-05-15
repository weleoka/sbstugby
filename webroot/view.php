<?php

// Include the config-file which also creates the $roo variable with its defaults.
include(__DIR__.'/config.php');

// Save variablers in Roo container
$roo['title'] = "Visa bokningar";

$db = new \Mos\Database\CDatabaseBasic();
// $options = require "config_mysql.php";
$db->setOptions($roo['database']);
$db->connect();

$tn = $roo['tableNames'];

$bookings = new CBooking($db, $tn);

// 1 = stugbokning, 2 = cykelbokning, 3 = skidbokning
$stugbokningar = $bookings->getAllBookings(1);
$cykelbokningar = $bookings->getAllBookings(2);
$skidbokningar = $bookings->getAllBookings(3);

$roo['header'] .= '<span class="siteslogan">See Content!</span>';

$roo['main'] = <<<EOD
<article>
    <h1>Alla bokningar</h1>
    <ul>
        {$stugbokningar}
    </ul>
    <ul>
        {$cykelbokningar}
    </ul>
    <ul>
        {$skidbokningar}
    </ul>
    <p><a href='booking.php?category=1'>Stuga</a></p>
    <p><a href='booking.php?category=2'>Cykel</a></p>
    <p><a href='booking.php?category=3'>Skidor</a></p>
</article>
EOD;

$roo['footer'];

// Finally, leave it all to the rendering phase of Roo.
include(ROO_THEME_PATH);