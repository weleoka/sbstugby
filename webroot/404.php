<?php 
/**
 * This is a Roo pagecontroller.
 *
 */
// Include the essential config-file which also creates the $roo variable with its defaults.
include(__DIR__.'/config.php'); 
 
 
// Do it and store it all in variables in the Roo container.
$roo['title'] = "404";
$roo['header'] = "";
$roo['main'] = "This is a 404 message from Roo. Document is not here.";
$roo['footer'];
 
// Send the 404 header 
header("HTTP/1.0 404 Not Found");
 
 
// Finally, leave it all to the rendering phase of Roo.
include(ROO_THEME_PATH);