<?php
/**
 * Theme Toggle Component
 * Displays a toggle switch for switching between light and dark modes
 */
$current_theme = get_current_theme();
$opposite_theme = get_opposite_theme();
$is_dark = is_dark_mode();
?>

<style>
    .theme-toggle-container {
  display: inline-flex;
  align-items: center;
  padding: 0;
  background: transparent;
}

.theme-toggle {
  position: relative;
  display: inline-block;
  width: 48px;
  height: 24px;
}

.theme-toggle input {
  opacity: 0;
  width: 0;
  height: 0;
}

.theme-slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: var(--text-muted);
  transition: 0.3s;
  border-radius: 24px;
}

.theme-slider:before {
  position: absolute;
  content: "";
  height: 18px;
  width: 18px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  transition: 0.3s;
  border-radius: 50%;
  box-shadow: var(--shadow-sm);
}

.theme-toggle input:checked + .theme-slider {
  background-color: var(--color-primary);
}

.theme-toggle input:checked + .theme-slider:before {
  transform: translateX(24px);
}

.theme-icon {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  font-size: 11px;
  z-index: 1;
  pointer-events: none;
}

.theme-icon.sun {
  left: 6px;
  color: #fbbf24;
}

.theme-icon.moon {
  right: 6px;
  color: #f1f5f9;
}

.theme-toggle-label {
  display: inline-block;
  margin-right: 8px;
  font-size: 13px;
  font-weight: 600;
  color: var(--text-muted);
}

    /* Login page specific styling */
    .login-page .theme-toggle-container {
        position: absolute;
        top: 20px;
        right: 20px;
        z-index: 1000;
    }

    /* Header specific styling */
    .header .theme-toggle-container {
        margin-left: 0;
        margin-right: 15px;
    }
</style>

<div class="theme-toggle-container">
    <span class="theme-toggle-label">
        <?php echo __('theme'); ?>
    </span>
    <label class="theme-toggle">
        <input type="checkbox" id="themeToggle" <?php echo $is_dark ? 'checked' : ''; ?>>
        <span class="theme-slider">
            <span class="theme-icon sun">☀</span>
            <span class="theme-icon moon">🌙</span>
        </span>
    </label>
</div>

<script>
    function changeTheme(theme) {
        // Get current URL
        const url = new URL(window.location.href);

        // Set theme parameter
        url.searchParams.set('theme', theme);

        // Reload page with new theme
        window.location.href = url.toString();
    }

    // Theme toggle functionality
    document.addEventListener('DOMContentLoaded', function () {
        const themeToggle = document.getElementById('themeToggle');

        if (themeToggle) {
            themeToggle.addEventListener('change', function () {
                const newTheme = this.checked ? 'dark' : 'light';
                changeTheme(newTheme);
            });
        }
    });
</script>