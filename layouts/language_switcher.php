<?php
// Language switcher component
global $available_langs, $current_lang;
?>
<div class="language-switcher">
    <select id="language-select" onchange="changeLanguage(this.value)" class="form-control">
        <?php foreach ($available_langs as $code => $name): ?>
            <option value="<?php echo $code; ?>" <?php echo ($current_lang === $code) ? 'selected' : ''; ?>>
                <?php echo $name; ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<script>
    function changeLanguage(lang) {
        // Get current URL
        var url = new URL(window.location.href);

        // Set or update the lang parameter
        url.searchParams.set('lang', lang);

        // Redirect to the new URL
        window.location.href = url.toString();
    }
</script>

<style>
    .language-switcher {
        display: inline-block;
    }

    .language-switcher select {
        min-width: 180px;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background-color: #fff;
        font-size: 14px;
        cursor: pointer;
        transition: border-color 0.3s;
    }

    .language-switcher select:hover {
        border-color: #999;
    }

    .language-switcher select:focus {
        outline: none;
        border-color: #5cb85c;
        box-shadow: 0 0 5px rgba(92, 184, 92, 0.3);
    }

    /* For login page positioning */
    .login-lang-switcher {
        position: absolute;
        top: 20px;
        right: 20px;
        z-index: 1000;
    }

    /* For header positioning */
    .header-lang-switcher {
        margin-left: 15px;
    }

    .header-lang-switcher select {
        background-color: rgba(255, 255, 255, 0.9);
    }
</style>