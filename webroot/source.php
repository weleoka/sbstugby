<?php
/**
 * This is a Roo pagecontroller.
 *
 */
// Include the essential config-file which also creates the $apex variable with its defaults.
include(__DIR__.'/config.php');


// Do it and store it all in variables in the Apex container.
$roo['title'] = "Visa Källkod";

$roo['header'] .= '<span class="siteslogan">View Source!</span>';

// Add style for csource
$roo['stylesheets'][] = 'css/source.css';

// Create the object to display sourcecode
//$source = new CSource();
$source = new CSource(array('secure_dir' => '..', 'base_dir' => '..'));

// Do it and store it all in variables in the Roo container.
$roo['title'] = "Visa källkod";
$roo['main'] = "<h1>Visa källkod</h1>\n" . $source->View();

$roo['footer'];

// Finally, leave it all to the rendering phase of Roo.
include(ROO_THEME_PATH);