<?php
/*
Plugin Name: Campos Quest
Description: Adds the Campos Quest game as a shortcode nd page template.
Version:     1.4.0
Author:      Tomas Mulder
Author URI:  https://www.thinkaquamarine.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: campos-quest
Domain Path: /languages
*/

/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Define constants
 */
define( 'CAMPOS_QUEST_VERSION', '1.4.0' );
define( 'CAMPOS_QUEST_BASENAME', plugin_basename( __FILE__ ) );
define( 'CAMPOS_QUEST_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CAMPOS_QUEST_PLUGIN_INC', CAMPOS_QUEST_PLUGIN_DIR . 'includes/' );
define( 'CAMPOS_QUEST_PLUGIN_URI', plugin_dir_url( __FILE__ ) );
define( 'CAMPOS_QUEST_LEVELS', 4 );
define( 'CAMPOS_QUEST_LEADERBOARD_COUNT', 10 );

/**
 * Include admin functionality
 */
require_once CAMPOS_QUEST_PLUGIN_INC . 'admin/utility.php';
require_once CAMPOS_QUEST_PLUGIN_INC . 'admin/templates.php';
require_once CAMPOS_QUEST_PLUGIN_INC . 'admin/enqueue.php';
require_once CAMPOS_QUEST_PLUGIN_INC . 'admin/api.php';
require_once CAMPOS_QUEST_PLUGIN_INC . 'admin/settings.php';
require_once CAMPOS_QUEST_PLUGIN_DIR . 'updates/update-checker.php';
