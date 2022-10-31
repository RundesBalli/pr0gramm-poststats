<?php
/**
 * includes/generation/generateOutput.php
 * 
 * Generates the output with previous generated contents.
 */
$output = preg_replace(
  array(
    "/{TITLE}/im",
    "/{NAV}/im",
    "/{CONTENT}/im",
    "/{FOOTER}/im"
  ),
  array(
    (!empty($title) ? $title." - " : NULL),
    $nav,
    $content,
    $footer
  ),
  $template
);
?>
