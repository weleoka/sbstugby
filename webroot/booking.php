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
$title  = isset($_POST['title']) ? $_POST['title'] : null;
$data   = isset($_POST['data'])  ? $_POST['data'] : null;
$type   = isset($_POST['type'])  ? strip_tags($_POST['type']) : "post";
$filter = isset($_POST['filter']) ? $_POST['filter'] : null;
$published = !empty($_POST['published']) ? strip_tags($_POST['published']) : null;
$acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : 1;
$output = '';

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
    $formStr = "no category found matching the criteria.";
}

if(isset($_POST['save'])) {

    if(!empty($title) && !empty($data)) {

        $params = array($title, $data, $type, $filter, $published);

        $output = $bookings->insertContent($params);
        $db1 = $bookings->db;
        $db1->Dump();
    } else {
        $output = 'Tomma fält! Informationen sparades inte!';
    }


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