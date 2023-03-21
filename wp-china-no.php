<?php
/**
 * Plugin Name: WP-China-No
 * Description: 一个轻量级插件，用于改善WordPress在中国大陆的使用体验。
 * Author: 耗子开发组
 * Author URI: https://hzbk.net/
 * Version: 1.4.0
 * License: GPLv3 or later
 * Text Domain: wp-china-no
 * Domain Path: /languages
 * Network: True
 * Requires at least: 4.9
 * Tested up to: 9.9.9
 * Requires PHP: 5.6.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace HaoZiTeam\WP_CHINA_NO;

defined( 'ABSPATH' ) || exit;

use HaoZiTeam\WP_CHINA_NO\Inc\Plugin;
use HaoZiTeam\Autoload;

const VERSION     = '1.4.0';
const PLUGIN_FILE = __FILE__;

// 加载自动加载器
if ( ! class_exists( '\HaoZiTeam\Autoload\Autoloader', false ) ) {
	require_once( plugin_dir_path( PLUGIN_FILE ) . 'vendor/haozi-team/autoload/class-autoloader.php' );
}
require_once( plugin_dir_path( PLUGIN_FILE ) . 'vendor/plugin-update-checker/plugin-update-checker.php' );

Autoload\register_class_path( __NAMESPACE__ . '\Inc', plugin_dir_path( PLUGIN_FILE ) . 'inc' );

// 注册插件激活钩子
register_activation_hook( PLUGIN_FILE, [ Plugin::class, 'activate' ] );
// 注册插件停用钩子
register_deactivation_hook( PLUGIN_FILE, [ Plugin::class, 'deactivate' ] );

new Plugin();
