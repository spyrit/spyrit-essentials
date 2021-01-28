<?php
/**
 * Basic security, prevents file from being loaded directly.
 */
defined('ABSPATH') or die('Oops');

/**
 * Disables XML-RPC API in WordPress 3.5+, which is enabled by default.
 */
add_filter('xmlrpc_enabled', '__return_false');

/**
 * Disable users route
 */
if (!function_exists('spy_disable_user_enpoints')) {
    function spy_disable_user_enpoints($endpoints)
    {
        if (isset($endpoints['/wp/v2/users'])) {
            unset($endpoints['/wp/v2/users']);
        }
        if (isset($endpoints['/wp/v2/users/(?P<id>[\d]+)'])) {
            unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);
        }
        return $endpoints;
    }
    add_filter('rest_endpoints', 'spy_disable_user_enpoints');
}

/**
 * Hide the author pages like /?author=1
 */
if (!function_exists('spy_hide_author_listing')) {
    function spy_hide_author_listing()
    {
        $is_author_set = get_query_var('author', '');
        if ($is_author_set != '' && !is_admin()) {
            wp_redirect(home_url(), 301);
            exit;
        }
    }
    add_action('template_redirect', 'spy_hide_author_listing');
}

/**
 * Hide the author pages slug like /author/name
 */
if (!function_exists('spy_hide_author_page')) {
    function spy_hide_author_page()
    {
        global $wp_query;
        if (is_author()) {
            $wp_query->set_404();
            status_header(404);
        }
    }
    add_action('template_redirect', 'spy_hide_author_page');
}
