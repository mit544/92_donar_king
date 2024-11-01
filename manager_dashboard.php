<?php 
session_start();

// Language selection logic
$available_languages = ['en', 'fr', 'es'];
$default_language = 'en';

if (isset($_GET['lang']) && in_array($_GET['lang'], $available_languages)) {
    $lang = $_GET['lang'];
    $_SESSION['lang'] = $lang;
    setcookie('lang', $lang, time() + (3600 * 24 * 30), '/');
} elseif (isset($_SESSION['lang']) && in_array($_SESSION['lang'], $available_languages)) {
    $lang = $_SESSION['lang'];
} elseif (isset($_COOKIE['lang']) && in_array($_COOKIE['lang'], $available_languages)) {
    $lang = $_COOKIE['lang'];
} else {
    $lang = $default_language;
}

// language path
$lang_file = "./lang/lang.{$lang}.php";

if (!file_exists($lang_file)) {
    $lang = $default_language;
    $lang_file = "./lang/lang.{$lang}.php";
}


include_once $lang_file;

// error hangling if the file is missing in the directery
if (!isset($lang) || !is_array($lang)) {
    die('Language file is missing or incorrect.');
}

include './php_scripts/connection.php'; 