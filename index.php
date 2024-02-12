<?php
require_once 'vendor/autoload.php';
require 'db/config.php';

$loader = new \Twig\Loader\FilesystemLoader('Templates');
$twig = new \Twig\Environment($loader);

// Handle AJAX requests here (if needed)

// Load and display the main template
$template = $twig->load('index.html');
echo $template->render([
    // Pass variables to the template
]);
?>
