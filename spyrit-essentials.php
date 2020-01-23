<?php
/*
Plugin Name: SPYRIT Essentials
Description: Une extension qui permet de d'améliorer la sécurité sur votre site Wordpress
Author: SPYRIT
Author URI: http://www.spyrit.net
Version: 0.1
*/

const PLUGIN_VERSION = "0.1";
const REMOTE_INFO_URL = "https://raw.githubusercontent.com/spyrit/spyrit-essentials/master/info.json";

/* Mise à jour */
include_once plugin_dir_path(__FILE__) . 'info_update.php';