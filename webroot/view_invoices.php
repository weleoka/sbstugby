<?php

// Include the config-file which also creates the $roo variable with its defaults.
include(__DIR__.'/config.php');

// Save variablers in Roo container
$roo['title'] = "Visa fakturor";

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
    'paid'         =>  isset($_GET['paid']) ? $_GET['paid'] : null,
    'customer' => isset($_GET['customer']) ? $_GET['customer'] : null,
];

$html = "<h1>Fakturahanteraren</h1>";

$searchedResult = $invoices->find($criteria);
// List resultset.
$html .= $invoices->listInvoices($searchedResult);


$roo['header'] .= '<span class="siteslogan">Aktuella bokningar</span>';

$roo['main'] = <<<EOD
<article>
    <a href='view_invoices.php?paid=1'>Paid Invoices</a>
    <ul>
        {$html}
    </ul>

</article>
EOD;

$roo['footer'];

// Finally, leave it all to the rendering phase of Roo.
include(ROO_THEME_PATH);