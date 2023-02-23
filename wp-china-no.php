<?php
/**
 * Plugin Name: WP-China-No
 * Description: 一个轻量级插件，用于改善WordPress在中国大陆的使用体验。
 * Author: 耗子开发组@耗子
 * Author URI: https://hzbk.net/
 * Version: 1.0.1
 * License: GPLv3 or later
 * Text Domain: wp-china-no
 * Domain Path: /languages
 * Requires at least: 4.9
 * Tested up to: 6.2.0
 * Requires PHP: 5.6.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace HaoZiTeam\WP_CHINA_NO;

use HaoZiTeam\WP_CHINA_NO\Inc\Plugin;
use HaoZiTeam\Autoload;

const VERSION     = '1.0.1';
const PLUGIN_FILE = __FILE__;
const PLUGIN_DIR  = __DIR__;

define( 'PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'PLUGIN_URL', plugin_dir_url( __FILE__ ) );

if ( ! class_exists( '\HaoZiTeam\Autoload\Autoloader', false ) ) {
	include __DIR__ . '/vendor/haozi-team/autoload/class-autoloader.php';
}

Autoload\register_class_path( __NAMESPACE__ . '\Inc', PLUGIN_DIR . '/inc' );

include __DIR__ . '/inc/helper.php';

// 注册插件激活钩子
register_activation_hook( PLUGIN_FILE, [ Plugin::class, 'activate' ] );
// 注册插件停用钩子
register_deactivation_hook( PLUGIN_FILE, [ Plugin::class, 'deactivate' ] );

new Plugin();
