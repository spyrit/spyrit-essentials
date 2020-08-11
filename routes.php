<?php
/* Désactivation de la route "users" */
add_filter('rest_endpoints', function ($endpoints) {
    if (isset($endpoints['/wp/v2/users'])) {
        unset($endpoints['/wp/v2/users']);
    }
    if (isset($endpoints['/wp/v2/users/(?P<id>[\d]+)'])) {
        unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);
    }
    return $endpoints;
});

/* Route d'API pour vérifier la version WP installée */
add_action('rest_api_init', function () {
    register_rest_route('spyrit-essentials/v1', '/check-version', [
        'methods' => 'GET',
        'callback' => 'spyrit_essentials_check_version',
    ]);
});

function spyrit_essentials_check_version(WP_REST_Request $request)
{
    $auth = gethostbyname('spyrit.net') === $_SERVER['REMOTE_ADDR'];
    $response = [];

    if ($auth) {
        wp_version_check();
        $from_api = get_site_transient('update_core');
        if (! $from_api) {
            return [];
        }
        $currentVersion = get_bloginfo('version');
        $plugins = get_plugins();
        $versionManager = [
            'blogName' => get_bloginfo('name'),
            'current' => $currentVersion,
            'minor' => [],
            'plugins' => [],
        ];
        foreach ($from_api->updates as $offer) {
            if (substr($offer->version, 4) !== substr($currentVersion, 4)) {
                $versionManager['minor'][] = $offer->version;
            }
        }
        $versionManager['minor'] = array_values(array_unique($versionManager['minor']));

        $activePlugins = [];
        foreach(get_option('active_plugins') as $key => $value) {
            $name = explode('/',$value);
            $activePlugins[] = $name[0];
        }

        foreach ($plugins as $plugin) {
            $pluginArr = [
                'name' => $plugin['TextDomain'],
                'version' => $plugin['Version'],
                'active' => in_array($plugin['TextDomain'], $activePlugins),
            ];
            $versionManager['plugins'][] = $pluginArr;
        }

        $response = $versionManager;
    }
    return json_encode($response);
}