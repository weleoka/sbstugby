<?php
/**
 * This is a Roo pagecontroller.
 *
 */

// Include the config-file which also creates the $roo variable with its defaults.
include(__DIR__.'/config.php');

$db = new \Mos\Database\CDatabaseBasic();
$db->setOptions($roo['database']);
$db->connect();

$tn = $roo['tableNames'];

$bookings = new CBooking($db, $tn);

// GET parameters from URL.
$category = isset($_GET['category']) ? $_GET['category'] : null;

// User.
$acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : 1;

/*$formOptions = [
    'start'          => false,  // Only return the start of the form element
    'columns'        => 3,      // Layout all elements in one column
    'use_buttonbar'  => true,   // Layout consequtive buttons as one element wrapped in <p>
    'use_fieldset'   => true,   // Wrap form fields within <fieldset>
    'legend'         => 'booking',   // Use legend for fieldset
];*/

if (is_numeric($category) && $category <= 3 && $category > 0) {
    if ($category == 1) {
        $roo['title'] = "Boka stuga";
        $form = new CCottageBooking($db, $tn);
    } else if ($category == 2) {
        $roo['title'] = "Boka cykel";
        $form = new CBikeBooking($db, $tn);
    } else if ($category == 3) {
        $roo['title'] = "Boka skidor";
        $form = new CSkiiBooking($db, $tn);
    }
    $categoryStr = $bookings->getCategoryStr($category);
    $form->makeForm($category);
    $formStr = $form->getHTML(); // ($formOptions);
} else {
    $roo['title'] = "Kategorifel";
    $categoryStr = "Fel i bokningskategorin.";
    $formStr = "Ingen kategori hittades under det id:et..";
}

$roo['header'] .= '<span class="siteslogan">' . $categoryStr . '!</span>';

$roo['main'] = <<<EOD
<article>
    <h1>{$categoryStr}</h1>
    {$formStr}
</article>
EOD;

$roo['footer'];

// Finally, leave it all to the rendering phase of Roo.
include(ROO_THEME_PATH);