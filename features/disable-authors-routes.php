<?php
/**
 * Basic security, prevents file from being loaded directly.
 */
defined('ABSPATH') or die('Oops');

/**
 * Hide the author pages like /?author=1
 */
if (!function_exists('spyrit_essentials_hide_author_listing')) {
    function spyrit_essentials_hide_author_listing()
    {
        $is_author_set = get_query_var('author', '');
        if ($is_author_set != '' && !is_admin()) {
            wp_redirect(home_url(), 301);
            exit;
        }
    }
    add_action('template_redirect', 'spyrit_essentials_hide_author_listing');
}

/**
 * Hide the author pages slug like /author/name
 */
if (!function_exists('spyrit_essentials_hide_author_page')) {
    function spyrit_essentials_hide_author_page()
    {
        global $wp_query;
        if (is_author()) {
            $wp_query->set_404();
            status_header(404);
        }
    }
    add_action('template_redirect', 'spyrit_essentials_hide_author_page');
}
