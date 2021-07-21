<?php
/**
 * Basic security, prevents file from being loaded directly.
 */
defined('ABSPATH') or die('Oops');

/**
 * Disable users route
 */
if (!function_exists('spyrit_essentials_disable_user_enpoints')) {
    function spyrit_essentials_disable_user_enpoints($endpoints)
    {
        if (isset($endpoints['/wp/v2/users'])) {
            unset($endpoints['/wp/v2/users']);
        }
        if (isset($endpoints['/wp/v2/users/(?P<id>[\d]+)'])) {
            unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);
        }
        return $endpoints;
    }
    add_filter('rest_endpoints', 'spyrit_essentials_disable_user_enpoints');
}
