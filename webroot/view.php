<?php

// Include the config-file which also creates the $roo variable with its defaults.
include(__DIR__.'/config.php');

// Save variablers in Roo container
$roo['title'] = "Visa bokningar";

$db = new \Mos\Database\CDatabaseBasic();
$db->setOptions($roo['database']);
$db->connect();

$tn = $roo['tableNames'];

$bookings = new CBooking($db, $tn);
$bookingCategory = new CBookingCategory($db, $tn);

// GET parameters from URL.
// 1 = stugbokning, 2 = cykelbokning, 3 = skidbokning
$category = isset($_GET['category']) ? $_GET['category'] : null;

if (is_numeric($category)){
    // get name of category.
    $categoryStr = $bookingCategory->getCategoryStr($category) . "ar"; // Append "ar" for plural.

    // query db for bookings.
    $result = $bookings->getBookings($category);
    $joinedResult = $bookings->getJoinedBookings($category);

    // dump($result);
    // dump($joinedResult);

    $bookingsStr = $bookings->listBookings($result);
    $joinedBookingsStr = $bookings->listJoinedBookings($joinedResult);

} else if ($category == 'all') {
    $bookingsStr = $bookings->getAllBookings();
    $categoryStr = "Alla bokningar";

} else {
    $bookingsStr = "Välj kategori.";
    $categoryStr = "Bokningshanteraren";
}

$roo['header'] .= '<span class="siteslogan">Aktuella bokningar</span>';

$roo['main'] = <<<EOD
<article>
    <a href='view.php?category=1'>Stuga</a> 
    <a href='view.php?category=2'>Cykel</a> 
    <a href='view.php?category=3'>Skidor</a> 
    <a href='view.php?category=all'>Alla</a> 
    <h1>{$categoryStr}</h1>
    <ul>
        {$bookingsStr}
        {$joinedBookingsStr}
    </ul>

</article>
EOD;

$roo['footer'];

// Finally, leave it all to the rendering phase of Roo.
include(ROO_THEME_PATH);