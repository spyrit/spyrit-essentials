<?php
add_filter('plugins_api', 'spyrit_essentials_plugin_info', 20, 3);

function spyrit_essentials_plugin_info($res, $action, $args)
{
    if ($action !== 'plugin_information') {
        return false;
    }

    if ('spyrit-essentials' !== $args->slug) {
        return $res;
    }

    if (false == $remote = get_transient('spyrit_upgrade_spyrit-essentials')) {
        $remote = wp_remote_get(
            REMOTE_INFO_URL,
            [
                'timeout' => 10,
                'headers' => [
                    'Accept' => 'application/json'
                ]]
        );

        if (!is_wp_error($remote) && isset($remote['response']['code']) && $remote['response']['code'] == 200 && !empty($remote['body'])) {
            set_transient('spyrit_upgrade_spyrit-essentials', $remote, 43200);
        }
    }

    if ($remote) {
        $remote = json_decode($remote['body']);
        $res = new stdClass();
        $res->name = $remote->name;
        $res->slug = 'spyrit-essentials';
        $res->version = $remote->version;
        $res->tested = $remote->tested;
        $res->requires = $remote->requires;
        $res->author = '<a href="' . $remote->author_homepage . '">' . $remote->author . '</a>';
        $res->download_link = $remote->download_url;
        $res->trunk = $remote->download_url;
        $res->last_updated = $remote->last_updated;
        $res->sections = [
            'description' => $remote->sections->description,
            'installation' => $remote->sections->installation,
            'changelog' => $remote->sections->changelog,
        ];

        $res->banners = array(
            'low' => plugins_url('/img/spyrit-essentials-banner-772x250.jpg', __FILE__),
            'high' => plugins_url('/img/spyrit-essentials-banner-1544x500.jpg', __FILE__)
        );
        return $res;
    }

    return false;
}


add_filter('site_transient_update_plugins', 'spyrit_essentials_push_update');

function spyrit_essentials_push_update($transient)
{
    if (empty($transient->checked)) {
        return $transient;
    }

    if (false == $remote = get_transient('spyrit_upgrade_spyrit-essentials')) {
        $remote = wp_remote_get(
            REMOTE_INFO_URL,
            array(
                'timeout' => 10,
                'headers' => array(
                    'Accept' => 'application/json'
                ) )
        );

        if (!is_wp_error($remote) && isset($remote['response']['code']) && $remote['response']['code'] == 200 && !empty($remote['body'])) {
            set_transient('spyrit_upgrade_spyrit-essentials', $remote, 43200);
        }
    }

    if ($remote) {
        $remote = json_decode($remote['body']);

        if ($remote && version_compare(PLUGIN_VERSION, $remote->version, '<') && version_compare($remote->requires, get_bloginfo('version'), '<')) {
            $res = new stdClass();
            $res->slug = 'spyrit-essentials';
            $res->plugin = 'spyrit-essentials/spyrit-essentials.php';
            $res->new_version = $remote->version;
            $res->tested = $remote->tested;
            $res->package = $remote->download_url;
            $transient->response[$res->plugin] = $res;
        }
    }
    return $transient;
}

add_action('upgrader_process_complete', 'spyrit_essentials_after_update', 10, 2);

function spyrit_essentials_after_update($upgrader_object, $options)
{
    if ($options['action'] == 'update' && $options['type'] === 'plugin') {
        delete_transient('spyrit_upgrade_spyrit-essentials');
    }
}
