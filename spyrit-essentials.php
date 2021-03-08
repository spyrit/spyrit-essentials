<?php
/*
Plugin Name: SPYRIT Essentials
Description: Une extension qui permet d'améliorer la sécurité de votre site Wordpress.
Author: SPYRIT
Author URI: http://www.spyrit.net
Version: 0.4.2
*/

const SPYRIT_ESSENTIALS_VERSION = "0.4.2";
const SPYRIT_ESSENTIALS_REMOTE_INFO_URL = "https://raw.githubusercontent.com/spyrit/spyrit-essentials/master/info.json";
$plugin_path = plugin_dir_path(__FILE__);

function spyrit_essentials_settings_link($links)
{
    $settings_link = '<a href="options-general.php?page=spyrit-essentials-options">Réglages</a>';
    array_unshift($links, $settings_link);
    return $links;
}
add_filter("plugin_action_links_" . plugin_basename(__FILE__), 'spyrit_essentials_settings_link');

/**
 * Page d'options
 */
include_once 'SpyritEssentialsSettingPage.php';

/**
 * Mise à jour
 */
include_once $plugin_path . 'info_update.php';

/**
 * Lien réglages et style
 */
include_once $plugin_path . 'features/miscellaneous.php';

$options = get_option('spyrit_essentials_config');
/**
 * Commentaires
 */
if (!isset($options['comments'])) {
    include_once $plugin_path . 'features/disable-comments.php';
}

/**
 * Emojis
 */
if (!isset($options['emojis'])) {
    include_once $plugin_path . 'features/disable-emojis.php';
}

/**
 * XML-RPC
 */
if (!isset($options['xmlrpc'])) {
    include_once $plugin_path . 'features/disable-xmlrpc.php';
}

/**
 * Auteurs
 */
if (!isset($options['authors'])) {
    include_once $plugin_path . 'features/disable-authors-routes.php';
}

/**
 * Sécurité
 */
include_once $plugin_path . 'features/security.php';

/**
 * Routes
 */
include_once $plugin_path . 'features/routes.php';
