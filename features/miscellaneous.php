<?php

/**
 * Style
 */
function spyrit_essentials_load_plugin_css()
{
    $plugin_url = plugin_dir_url(__FILE__);
    wp_enqueue_style('SE_backoffice', $plugin_url . '../css/spyrit-essentials.css');
}
add_action('admin_print_styles', 'spyrit_essentials_load_plugin_css');
