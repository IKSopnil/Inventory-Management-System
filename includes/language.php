<?php

// Default Language
$default_lang = 'en';

// Available Languages
$available_langs = [
    'en' => 'English',
    'zh-CN' => 'Mandarin Chinese',
    'hi' => 'Hindi',
    'es' => 'Spanish',
    'fr' => 'French',
    'ar' => 'Standard Arabic',
    'bn' => 'Bengali',
    'pt' => 'Portuguese',
    'ru' => 'Russian',
    'ur' => 'Urdu'
];

// Determine Language
if (isset($_GET['lang']) && array_key_exists($_GET['lang'], $available_langs)) {
    $_SESSION['lang'] = $_GET['lang'];
}

$current_lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : $default_lang;

// Load Language File
$lang_file = "languages/{$current_lang}.php";
if (file_exists($lang_file)) {
    $lang = include($lang_file);
} else {
    // Fallback to English if file missing
    $lang = include("languages/en.php");
}

// Translation Helper Function
function __($key)
{
    global $lang;
    return isset($lang[$key]) ? $lang[$key] : $key;
}
?>