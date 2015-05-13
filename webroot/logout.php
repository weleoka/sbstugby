<?php 
/**
 * This is the Roo pagecontroller.
 *
 */
// Include the essential config-file which also creates the $roo variable with its defaults.
include(__DIR__.'/config.php'); 
 
 
$db = new CDatabase($roo['database']);  
$user= new CUser($db);

// Logout the user
if(isset($_POST['logout'])) {
   $user->logout();
}
 

$status=$user->statusIsAuthenticated(); 


// Do it and store it all in variables in the Roo container.
$roo['title'] = "LOGOUT";
 
$roo['header'] .= '<span class="siteslogan">Log out!</span>'; 
 
$roo['main'] = <<<EOD
<form method=post>
  <fieldset>
  <legend>Login</legend>
  <p><input type='submit' name='logout' value='Logout'/></p>
  <output><b>{$status}</b></output>
  </fieldset>
</form>
<p>.: :.</p>
EOD;
 
$roo['footer'];
 
 
// Finally, leave it all to the rendering phase of roo.
include(ROO_THEME_PATH);