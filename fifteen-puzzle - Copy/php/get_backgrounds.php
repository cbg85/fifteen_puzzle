<?php
$dir = '../images/backgrounds/';
$files = glob($dir . "*.{jpg,jpeg,png}", GLOB_BRACE);

$backgrounds = array_map('basename', $files);
echo json_encode($backgrounds);
?>
