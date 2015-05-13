<?php
/**
 * This is a Roo pagecontroller.
 *
 */

// Include the config-file which also creates the $roo variable with its defaults.
include(__DIR__.'/config.php'); 

// Save variables in Roo container
$roo['title'] = "Uppdatera innehåll";
 
 
isset($_SESSION['user']) or die('Check: You must login to edit content.');
 
$content = new CContent($roo['database'], 'Content');



// Get all parameters 
$id     = isset($_POST['id'])    ? strip_tags($_POST['id']) : (isset($_GET['id']) ? strip_tags($_GET['id']) : null);
$title  = isset($_POST['title']) ? $_POST['title'] : null;
$data   = isset($_POST['data'])  ? $_POST['data'] : array();
$type   = isset($_POST['type'])  ? strip_tags($_POST['type']) : null;
$filter = isset($_POST['filter']) ? $_POST['filter'] : array();
$published = !empty($_POST['published'])  ? strip_tags($_POST['published']) : null;
$acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : 1;
$output = '';


// Check that incoming parameters are valid
isset($acronym) or die('Check: You must login to edit.');
is_numeric($id) or die('Check: Id must be numeric.');



if(isset($_POST['save'])) {
    
    $slug = $content->slugify($title);
    
    if(strtolower($type) == 'page') {
        $url = $slug;
    } else {
        $url = NULL;
    }
    
    $params = array($title, $slug, $url, $data, $type, $filter, $published, $id);
    $output = $content->updateContent($params);

} else if(isset($_POST['delete'])) {
    
    $output = $content->deleteContent($id);
    
}


// Get content with id
$res = $content->getContent($id);

if(isset($res[0])) {
  $c = $res[0];
} else {
  die('Misslyckades: det finns inget innehåll med sådant id.');
}

$title  = htmlentities($c->title);
$url    = htmlentities($c->url);
$data   = htmlentities($c->data);
$type   = htmlentities($c->type);
$filter = htmlentities($c->filter);
$published = htmlentities($c->published);

$roo['header'] .= '<span class="siteslogan">Edit Database Article!</span>';
 
$roo['main'] = <<<EOD
<article>
    <h1>Uppdatera innehåll</h1>
    
    <form method=post>
      <fieldset>
            <legend>Uppdatera innehåll</legend>
                <input type='hidden' name='id' value='{$id}'/>
                
                <p><label>Titel:<br/><input type='text' name='title' value='{$title}'/></label></p>
                <p><label>Text:<br/><textarea name='data'>{$data}</textarea></label></p>
                <p><label>Type:<br/><input type='text' name='type' value='{$type}'/></label></p>
                <p><label>Filter:<br/><input type='text' name='filter' value='{$filter}'/></label></p>
                <p><label>Publiseringsdatum:<br/><input type='text' name='published' value='{$published}'/></label></p>
                <p class=buttons>
                	<input type='submit' name='save' value='Spara'/>
                	<input type='submit' name='delete' value='Radera'/>
                </p>
                
                <a href='view.php'>Visa alla</a></p>
                <output>{$output}</output>
  </fieldset>
</form>   
</article>
EOD;

$roo['footer'];

 
// Finally, leave it all to the rendering phase of Roo.
include(ROO_THEME_PATH);
