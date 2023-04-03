<?php
/**
 * Plugin Name: WP-China-Plus
 * Description: 一个轻量级插件，用于改善WordPress在中国大陆的使用体验。
 * Author: 耗子开源
 * Author URI: https://github.com/HaoZi-Team/WP-China-Plus
 * Version: 2.0.1
 * License: GPLv3 or later
 * Text Domain: wp-china-plus
 * Domain Path: /languages
 * Network: True
 * Requires at least: 4.9
 * Tested up to: 9.9.9
 * Requires PHP: 5.6.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace HaoZiTeam\WP_CHINA_PLUS;

defined( 'ABSPATH' ) || exit;

use HaoZiTeam\WP_CHINA_PLUS\Inc\Plugin;
use HaoZiTeam\Autoload;

const VERSION     = '2.0.1';
const PLUGIN_FILE = __FILE__;

// 加载自动加载器
if ( ! class_exists( '\HaoZiTeam\Autoload\Autoloader', false ) ) {
	require_once( plugin_dir_path( PLUGIN_FILE ) . 'vendor/haozi-team/autoload/class-autoloader.php' );
}
require_once( plugin_dir_path( PLUGIN_FILE ) . 'vendor/plugin-update-checker/plugin-update-checker.php' );

Autoload\register_class_path( __NAMESPACE__ . '\Inc', plugin_dir_path( PLUGIN_FILE ) . 'inc' );

// 注册插件激活钩子
register_activation_hook( PLUGIN_FILE, [ Plugin::class, 'activate' ] );
// 注册插件删除钩子
register_uninstall_hook( PLUGIN_FILE, [ Plugin::class, 'uninstall' ] );

new Plugin();
