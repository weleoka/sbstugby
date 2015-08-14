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
// category: 1 = stugbokning, 2 = cykelbokning, 3 = skidbokning

$criteria = [
    'id'            =>  isset($_GET['id']) ? $_GET['id'] : null,
    'category'  => isset($_GET['category']) ? $_GET['category'] : null,
    'paid'         =>  isset($_GET['paid']) ? $_GET['paid'] : null,
    'customer' => isset($_GET['customer']) ? $_GET['customer'] : null,
];

$html = "<h1>Bokningshanteraren</h1>";

// Make the header name of category.
if (is_numeric($criteria['category'])){
    // Make relevant header.
    $html .= "<h1>" . $bookingCategory->getCategoryStr($criteria['category']) . "ar</h1>"; // Append "ar" for plural.
    // query db for bookings.
    $searchedResult = $bookings->findBookings($criteria);
    // Make html from the resutset.
    $html .= $bookings->listSearchedBookings($searchedResult);

} else {
    for ($i = 1; $i <= 3; $i++) {
        $criteria['category'] = $i;
        // Make relevant header.
        $html .= "<h1>" . $bookingCategory->getCategoryStr($criteria['category']) . "ar</h1>"; // Append "ar" for plural.
        // query db for bookings.
        $searchedResult = $bookings->findBookings($criteria);
        // List resultset.
        $html .= $bookings->listSearchedBookings($searchedResult);
    }
}

/*if (is_null($criteria['category'])) {
    $html = $bookings->getAllBookings();
    $categoryStr = "Alla bokningar";

} */



$roo['header'] .= '<span class="siteslogan">Aktuella bokningar</span>';

$roo['main'] = <<<EOD
<article>
    <a href='view_bookings.php?category=1'>Stuga</a>
    <a href='view_bookings.php?category=2'>Cykel</a>
    <a href='view_bookings.php?category=3'>Skidor</a>
    <a href='view_bookings.php'>Alla</a>
    <ul>
        {$html}
    </ul>

</article>
EOD;

$roo['footer'];

// Finally, leave it all to the rendering phase of Roo.
include(ROO_THEME_PATH);