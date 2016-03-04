<?php
/*
Plugin Name: DP-Customizer
Description: Creates Wrapper Functions for WordPress theme customizer
Version: 0.0.1
Plugin URI: http://dppad.com/
Author URI: http://khalidhoffman.info
Author: Khalid Hoffman
Text Domain: dp_customizer
*/

global $customizer_plugin_path;
$customizer_plugin_path = plugin_dir_path(__FILE__);
require_once($customizer_plugin_path . 'classes/customizer.php');

