<?php
/**
 * This is the Roo pagecontroller.
 *
 */
// Include the essential config-file which also creates the $roo variable with its defaults.
include(__DIR__.'/config.php');


$db = new CDatabase($roo['database']);

$user = new CUser($db);

$inloggad=true;
$success=false;

if(!$user->IsAuthenticated()){
    if(isset($_POST['acronym'], $_POST['password'])){
       $success = $user->login($_POST['acronym'], $_POST['password']);
    }
}
if(!$inloggad)
{
    $status = "Inte inloggad.";

}
else{
    $status = $user->statusIsAuthenticated();
}

// Do it and store it all in variables in the Roo container.
$roo['title'] = "LOGIN";

$roo['header'] .= '<span class="siteslogan">Log in!</span>';

$roo['main'] = <<<EOD
<form method=post>
  <fieldset>
  <legend>Login med:</legend>
  <p>usr: doe<br>pswd: doe<hr><br>usr: admin<br>pswd: admin.<hr></p>
  <p><label>usr:<br/><input autofocus = true type='text' name='acronym' value=''/></label></p>
  <p><label>pswd:<br/><input type='password' name='password' value=''/></label></p>
  <p><input type='submit' name='login' value='Login'/></p>
  <output><b>{$status}</b></output>
  </fieldset>
</form>
<p>.: :.</p>
EOD;

$roo['footer'];


// Finally, leave it all to the rendering phase of roo.
include(ROO_THEME_PATH);