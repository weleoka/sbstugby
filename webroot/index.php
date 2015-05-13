<?php
/**
 * This is the Roo pagecontroller.
 *
 */
// Include the essential config-file which also creates the $roo variable with its defaults.
include(__DIR__.'/config.php');


// Do it and store it all in variables in the Roo container.
$roo['title'] = "SB semesterbyar";

$roo['header'] .= '<span class="siteslogan">Välkommen till SB semesterbyar!</span>';

$roo['main'] = <<<EOD
	<p style='text-align: center'>
		<a href='booking.php?category=1'>Stuga</a>
		<a href='booking.php?category=2'>Cykel</a>
		<a href='booking.php?category=3'>Skidor</a>
		<a href='view.php'>Visa alla bokningar.</a></p>
	</p>
EOD;

$roo['footer'];

// Finally, leave it all to the rendering phase of roo.
include(ROO_THEME_PATH);