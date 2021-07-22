<?php

/* Route d'API pour vérifier la version WP installée */
add_action('rest_api_init', function () {
    register_rest_route('spyrit-essentials/v1', '/check-version', [
        'methods' => 'GET',
        'callback' => 'spy_check_version',
        'permission_callback' => 'spy_check_version_permissions_check',
    ]);
});

function spy_check_version_permissions_check(WP_REST_Request $request)
{
    $options = get_option('spyrit_essentials_config');
    $wptoken = isset($options['token']) && $options['token'] ? $options['token'] : null;
    $requestToken = isset($_GET['token']) && $_GET['token'] ? $_GET['token'] : null;

    if ($wptoken && $requestToken && ($wptoken === $requestToken)) {
        return true;
    }

    return new WP_Error('rest_forbidden', esc_html__('Vous ne pouvez pas accéder à cette page.'));
}

function spy_check_version(WP_REST_Request $request)
{
    wp_version_check();
    $currentVersion = get_bloginfo('version');
    $versionManager = [
        'code' => 'success',
        'blogName' => get_bloginfo('name'),
        'current' => $currentVersion,
        'php' => phpversion(),
        'debug' => defined('WP_DEBUG') && WP_DEBUG ? 'enabled' : 'disabled',
        'plugins' => [],
        'themes' => [],
    ];

    if (!function_exists('get_plugins')) {
        require_once ABSPATH.'wp-admin/includes/plugin.php';
    }
    $plugins = get_plugins();
    foreach ($plugins as $key => $value) {
        $pluginArr = [
            'name' => $value['TextDomain'],
            'version' => $value['Version'],
            'active' => is_plugin_active($key),
        ];
        $versionManager['plugins'][] = $pluginArr;
    }

    $themes = wp_get_themes();
    $activeTheme = get_option('stylesheet');
    foreach ($themes as $name => $theme) {
        $themeArray = [
            'name' => $name,
            'version' => $theme['Version'],
            'active' => ($activeTheme === $name) ? 1 : 0,
        ];
        $versionManager['themes'][] = $themeArray;
    }

    return $versionManager;
}
