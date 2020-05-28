<?php
/*
Plugin Name: SPYRIT Essentials
Description: Une extension qui permet d'améliorer la sécurité de votre site Wordpress.
Author: SPYRIT
Author URI: http://www.spyrit.net
Version: 0.2
*/

const SPYRIT_ESSENTIALS_VERSION = "0.2";
const SPYRIT_ESSENTIALS_REMOTE_INFO_URL = "https://raw.githubusercontent.com/spyrit/spyrit-essentials/master/info.json";

/* Mise à jour */
include_once plugin_dir_path(__FILE__) . 'info_update.php';

/* Routes */
include_once plugin_dir_path(__FILE__) . 'routes.php';
