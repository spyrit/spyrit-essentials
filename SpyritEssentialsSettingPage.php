<?php
class SpyritEssentialsSettingPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action('admin_menu', [$this, 'spyrit_essentials_plugin_page']);
        add_action('admin_init', [$this, 'spyrit_essentials_page_init']);
    }

    /**
     * Add options page
     */
    public function spyrit_essentials_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'SPYRIT Essentials',
            'SPYRIT Essentials',
            'manage_options',
            'spyrit-essentials-options',
            [$this, 'spyrit_essentials_admin_page']
        );
    }

    /**
     * Options page callback
     */
    public function spyrit_essentials_admin_page()
    {
        $this->options = get_option('spyrit_essentials_config'); ?>
        <div class="wrap">
            <h1><img src="<?= plugin_dir_url(__FILE__) ?>/img/spyrit-favicon.png" alt="Logo de SPYRIT">SPYRIT Essentials <span>v<?= SPYRIT_ESSENTIALS_VERSION ?></span></h1>
            <div class="notices-wrap"><?php do_action('admin_notices') ?></div>
            <div class="container">
                <form method="post" action="options.php">
                    <?php
                    settings_fields('spyrit-essentials-group');
        do_settings_sections('spyrit-essentials-options'); ?>
                    <div class="warning-section">
                        <p><span class="icon dashicons-before dashicons-warning"></span> Activer les options marquées d'un point d'exclamation peut affecter la sécurité de votre site.</p>
                    </div>
                    <?php submit_button(); ?>
                </form>
                <div class="copy">
                    SPYRIT Essentials version <?= SPYRIT_ESSENTIALS_VERSION ?><br>
                    Développé par <a href="https://www.spyrit.net" title="Accéder au site de SPYRIT" target="_blank">Spyrit systèmes d'information</a>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function spyrit_essentials_page_init()
    {
        register_setting(
            'spyrit-essentials-group',
            'spyrit_essentials_config'
        );

        add_settings_section(
            'spyrit-essentials-section-api',
            'API',
            null,
            'spyrit-essentials-options'
        );

        add_settings_section(
            'spyrit-essentials-section-options',
            'Options',
            null,
            'spyrit-essentials-options'
        );

        add_settings_field(
            'spyrit-essentials-token',
            'Token',
            [$this, 'spyrit_essentials_token_callback'],
            'spyrit-essentials-options',
            'spyrit-essentials-section-api'
        );

        add_settings_field(
            'spyrit-essentials-emojis',
            'Activer les emojis',
            [$this, 'spyrit_essentials_emojis_callback'],
            'spyrit-essentials-options',
            'spyrit-essentials-section-options'
        );

        add_settings_field(
            'spyrit-essentials-comments',
            'Activer les commentaires',
            [$this, 'spyrit_essentials_comments_callback'],
            'spyrit-essentials-options',
            'spyrit-essentials-section-options'
        );

        add_settings_field(
            'spyrit-essentials-xmlrpc',
            'Activer le XML-RPC <span class="icon dashicons-before dashicons-warning"></span>',
            [$this, 'spyrit_essentials_xmlrpc_callback'],
            'spyrit-essentials-options',
            'spyrit-essentials-section-options'
        );

        add_settings_field(
            'spyrit-essentials-authors',
            'Activer les pages relatives aux auteurs <span class="icon dashicons-before dashicons-warning"></span>',
            [$this, 'spyrit_essentials_authors_callback'],
            'spyrit-essentials-options',
            'spyrit-essentials-section-options'
        );
    }

    public function spyrit_essentials_token_callback()
    {
        printf(
            '<input type="text" id="token" name="spyrit_essentials_config[token]" value="%s" placeholder="Clé token" />',
            isset($this->options['token']) ? esc_attr($this->options['token']) : ''
        );
    }

    public function spyrit_essentials_emojis_callback()
    {
        $checked = isset($this->options['emojis']) && 1 == $this->options['emojis'] ? ' checked="checked"' : '';
        echo '<input type="checkbox" id="emojis" name="spyrit_essentials_config[emojis]" value="1"'.$checked.'/>';
    }

    public function spyrit_essentials_comments_callback()
    {
        $checked = isset($this->options['comments']) && 1 == $this->options['comments'] ? ' checked="checked"' : '';
        echo '<input type="checkbox" id="comments" name="spyrit_essentials_config[comments]" value="1"'.$checked.'/>';
    }

    public function spyrit_essentials_xmlrpc_callback()
    {
        $checked = isset($this->options['xmlrpc']) && 1 == $this->options['xmlrpc'] ? ' checked="checked"' : '';
        echo '<input type="checkbox" id="xmlrpc" name="spyrit_essentials_config[xmlrpc]" value="1"'.$checked.'/>';
    }

    public function spyrit_essentials_authors_callback()
    {
        $checked = isset($this->options['authors']) && 1 == $this->options['authors'] ? ' checked="checked"' : '';
        echo '<input type="checkbox" id="authors" name="spyrit_essentials_config[authors]" value="1"'.$checked.'/>';
    }
}

if (is_admin()) {
    $settings_page = new SpyritEssentialsSettingPage();
}
