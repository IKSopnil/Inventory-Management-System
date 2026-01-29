<?php
// -----------------------------------------------------------------------
// DEFINE SEPERATOR ALIASES
// -----------------------------------------------------------------------
define("URL_SEPARATOR", '/');
define("DS", DIRECTORY_SEPARATOR);

// -----------------------------------------------------------------------
// DEFINE ROOT PATHS
// -----------------------------------------------------------------------
defined('SITE_ROOT') ? null : define('SITE_ROOT', realpath(dirname(__FILE__)));
define("LIB_PATH_INC", SITE_ROOT . DS);

// -----------------------------------------------------------------------
// CLASS AUTOLOADER
// -----------------------------------------------------------------------
spl_autoload_register(function ($class_name) {
    $file = LIB_PATH_INC . strtolower($class_name) . '.php';
    if (file_exists($file)) {
        require_once($file);
    }
});

// -----------------------------------------------------------------------
// CORE INCLUDES (Procedural / Config / Globals)
// -----------------------------------------------------------------------
require_once(LIB_PATH_INC . 'config.php');
require_once(LIB_PATH_INC . 'functions.php');
require_once(LIB_PATH_INC . 'session.php');
require_once(LIB_PATH_INC . 'database.php');
require_once(LIB_PATH_INC . 'upload.php');
require_once(LIB_PATH_INC . 'sql.php');
require_once(LIB_PATH_INC . 'Invoice.php');

?>