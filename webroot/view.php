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

// GET parameters from URL.
// 1 = stugbokning, 2 = cykelbokning, 3 = skidbokning
$category = isset($_GET['category']) ? $_GET['category'] : null;

if (is_numeric($category)){
    $result = $bookings->getBookings($category);
    $bokningar = $bookings->listBookings($result);
    $categoryStr = $bookings->getCategoryStr($category) . "ar"; // Append "ar" for plural.

} else if ($category = 'all') {
    $bokningar = $bookings->getAllBookings();
    $categoryStr = "Alla bokningar";

} else {
    $categoryStr = "No category booking.";
    $formStr = "No category found matching the criteria.";
}

$roo['header'] .= '<span class="siteslogan">Aktuella bokningar</span>';

$roo['main'] = <<<EOD
<article>
    <h1>{$categoryStr}</h1>
    <ul>
        {$bokningar}
    </ul>
    <p><a href='view.php?category=1'>Stuga</a></p>
    <p><a href='view.php?category=2'>Cykel</a></p>
    <p><a href='view.php?category=3'>Skidor</a></p>
    <p><a href='view.php?category=all'>Alla</a></p>
</article>
EOD;

$roo['footer'];

// Finally, leave it all to the rendering phase of Roo.
include(ROO_THEME_PATH);