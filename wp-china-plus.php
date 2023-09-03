<?php
/**
 * Plugin Name: WP-China-Plus
 * Description: 一个轻量级插件，用于改善 WordPress 在中国大陆的使用体验。
 * Author: 耗子开源
 * Author URI: https://github.com/haozi-team/wp-china-plus
 * Version: 2.1.2
 * License: GPLv3 or later
 * Text Domain: wp-china-plus
 * Domain Path: /languages
 * Network: True
 * Requires at least: 4.9
 * Tested up to: 9.9.9
 * Requires PHP: 5.6.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace HaoZiTeam\ChinaPlus;

defined( 'ABSPATH' ) || exit;

const VERSION     = '2.1.2';
const PLUGIN_FILE = __FILE__;
const PLUGIN_DIR  = __DIR__;

require_once( plugin_dir_path( __FILE__ ) . 'vendor/autoload.php' );

// 注册插件激活钩子
register_activation_hook( PLUGIN_FILE, [ Plugin::class, 'activate' ] );
// 注册插件删除钩子
register_uninstall_hook( PLUGIN_FILE, [ Plugin::class, 'uninstall' ] );

new Plugin();
