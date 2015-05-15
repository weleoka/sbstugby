<?php
/**
 * This is a Roo pagecontroller.
 *
 */

// Include the config-file which also creates the $roo variable with its defaults.
include(__DIR__.'/config.php');

// Save variables in Roo container
$roo['title'] = "Boka";

$db = new \Mos\Database\CDatabaseBasic();
$db->setOptions($roo['database']);
$db->connect();

$tn = $roo['tableNames'];

$bookings = new CBooking($db, $tn);

// GET parameters from URL.
$category = isset($_GET['category']) ? $_GET['category'] : null;

// POST, from form below, parameters
$acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : 1;

if (is_numeric($category)){

    if ($category = 1) {
        $form = new CBooking_cottage($db, $tn);
    } else if ($category = 2) {
        $form = new CBooking_bike($db, $tn);
    } else if ($category = 3) {
        $form = new CBooking_skii($db, $tn);
    }
    $form->makeForm($category);

    $categoryStr = $bookings->getCategoryStr($category);
    $formStr = $form->getHTML();

} else {
    $categoryStr = "No category booking.";
    $formStr = "No category found matching the criteria.";
}

$roo['header'] .= '<span class="siteslogan">' . $categoryStr . '!</span>';

$roo['main'] = <<<EOD
<article>
    <h1>{$categoryStr}</h1>
    Skapa ny betalperson?
    {$formStr}
</article>
EOD;

$roo['footer'];

// Finally, leave it all to the rendering phase of Roo.
include(ROO_THEME_PATH);