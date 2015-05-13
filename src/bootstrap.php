<?php
/**
 * Bootstrapping functions, essential and needed for Roo to work together with some common helpers.
 *
 */

/**
 * Default exception handler.
 *
 */
function myExceptionHandler($exception) {
  echo "Roo: Uncaught exception: <p>" . $exception->getMessage() . "</p><pre>" . $exception->getTraceAsString(), "</pre>";
}
set_exception_handler('myExceptionHandler');

/**
 * Another autoloader for classes.
 *
 */
include(ROO_INSTALL_PATH . '/src/CForm/autoloader.php');

/**
 * Autoloader for classes.
 *
 */
function myAutoloader($class) {
  $path = ROO_INSTALL_PATH . "/src/{$class}/{$class}.php";
  if(is_file($path)) {
    include($path);
  }
  else {
    throw new Exception("Classfile '{$class}' does not exists.");
    echo ($path);
  }
}
spl_autoload_register('myAutoloader');






/**
 * Dump-function.
 *
 */
function dump($array) {
  echo "<pre>" . htmlentities(print_r($array, 1)) . "</pre>";
}
// anropa funktionen så här: dump($_SERVER);
