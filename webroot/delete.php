<?php
/**
 * This is an Roo pagecontroller.
 *
 */


// Include the essential config-file which also creates the $roo variable with its defaults.
include(__DIR__.'/config.php');

isset($_SESSION['user']) or die('Check: You must login to delete.');

$id = isset($_GET['id']) ? $_GET['id'] : null;

$pageId = "delete";
$output = null;

// $db = new CDatabase($roo['database']);
$content = new CContent($roo['database'], 'Content');

if(isset($_GET['y'])){
  $output = $content->deleteContent($id);
}
if(isset($_GET['y2'])){
  $output = $content->delete($id);
}


// Do it and store it all in variables in the roo container.
$roo['title'] = "Ta bort inlägg/post";

$roo['header'] .= '<span class="siteslogan">Delete article or blog!</span>';

$roo['main'] = <<<EOD
  <article id="restore">

    <h1>Ta bort inlägg/post</h1>
    <p>Vill du verkligen ta bort inlägget/posten?</p>
    <a href="delete.php?y2&id={$id}">Ja</a>
    <p>Ta bort inlägget/posten men spara i utkast?</p>
    <a href="delete.php?y&id={$id}">Ja</a> · <a href="view.php">Nej</a>

    <p>{$output}</p>
    <h1><a href='view.php'>back to admin page</a></h1>

  </article>
EOD;

$roo['footer'];

// Finally, leave it all to the rendering phase of Roo.
include(ROO_THEME_PATH);