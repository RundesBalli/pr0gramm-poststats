<?php
/**
 * includes/functions/output.php
 * 
 * Output sanitizer function
 * 
 * @param  string
 * @return string
 */
function output($string) {
  return nl2br(htmlentities($string, ENT_QUOTES));
}
?>
