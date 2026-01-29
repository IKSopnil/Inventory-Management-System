<?php
/**
 * Theme Management System
 * Handles theme detection and persistence
 */

// Define available themes
define('THEME_LIGHT', 'light');
define('THEME_DARK', 'dark');
define('DEFAULT_THEME', THEME_LIGHT);

// Get current theme
function get_current_theme()
{
    // Check if theme is set in URL parameter
    if (isset($_GET['theme']) && in_array($_GET['theme'], [THEME_LIGHT, THEME_DARK])) {
        $_SESSION['theme'] = $_GET['theme'];
        return $_GET['theme'];
    }

    // Check if theme is set in session
    if (isset($_SESSION['theme']) && in_array($_SESSION['theme'], [THEME_LIGHT, THEME_DARK])) {
        return $_SESSION['theme'];
    }

    // Return default theme
    return DEFAULT_THEME;
}

// Check if dark mode is active
function is_dark_mode()
{
    return get_current_theme() === THEME_DARK;
}

// Get theme class for body element
function get_theme_class()
{
    return is_dark_mode() ? 'dark-mode' : 'light-mode';
}

// Get opposite theme (for toggle)
function get_opposite_theme()
{
    return is_dark_mode() ? THEME_LIGHT : THEME_DARK;
}
?>