<?php

namespace HaoZiTeam\ChinaPlus;

defined( 'ABSPATH' ) || exit;

use HaoZiTeam\ChinaPlus\Service\Base;
use HaoZiTeam\ChinaPlus\Service\Setting;

class Plugin {

	/**
	 * 创建一个插件实例
	 */
	public function __construct() {
		new Base();
		new Setting();
	}

	/**
	 * 插件激活时执行
	 */
	public static function activate() {
		// 兼容性检测
		self::check();
	}

	/**
	 * 插件删除时执行
	 */
	public static function uninstall() {
		// 清除设置
		is_multisite() ? delete_site_option( 'wp_china_plus_setting' ) : delete_option( 'wp_china_plus_setting' );
		is_multisite() ? delete_site_option( 'wp_china_plus_about' ) : delete_option( 'wp_china_plus_about' );
	}

	/**
	 * 插件兼容性检测函数
	 */
	public static function check() {
		$notices = [];
		if ( version_compare( PHP_VERSION, '5.2.0', '<' ) ) {
			$notices[] = '<div class="notice notice-error"><p>' . sprintf( __( 'WP-China-Plus 插件需要 PHP 5.2.0 或更高版本，当前版本为 %s，插件已自动禁用。',
					'wp-china-plus' ),
					PHP_VERSION ) . '</p></div>';
			deactivate_plugins( 'wp-china-plus/wp-china-plus.php' );
		}
		if ( is_plugin_active( 'wp-china-no/wp-china-no.php' ) ) {
			// 停用插件
			deactivate_plugins( 'wp-china-no/wp-china-no.php' );
			$notices[] = '<div class="notice notice-error is-dismissible">
					<p><strong>' . __( '检测到旧版插件 WP-China-No，已自动禁用！', 'wp-china-plus' ) . '</strong></p>
				</div>';
		}
		if ( is_plugin_active( 'wp-china-yes/wp-china-yes.php' ) ) {
			// 停用插件
			deactivate_plugins( 'wp-china-yes/wp-china-yes.php' );
			$notices[] = '<div class="notice notice-error is-dismissible">
					<p><strong>' . __( '检测到不兼容的插件 WP-China-Yes，已自动禁用！', 'wp-china-plus' ) . '</strong></p>
				</div>';
		}
		if ( is_plugin_active( 'kill-429/kill-429.php' ) ) {
			// 停用插件
			deactivate_plugins( 'kill-429/kill-429.php' );
			// 输出提示信息
			$notices[] = '<div class="notice notice-error is-dismissible">
					<p><strong>' . __( '检测到不兼容的插件 Kill 429，已自动禁用！', 'wp-china-plus' ) . '</strong></p>
				</div>';

		}
		// 代理服务器检测
		if ( defined( 'WP_PROXY_HOST' ) || defined( 'WP_PROXY_PORT' ) ) {
			// 输出提示信息
			$notices[] = '<div class="notice notice-warning is-dismissible">
					<p><strong>' . __( '检测到已在 WordPress 配置文件中设置代理服务器，这可能会导致插件无法正常工作！',
					'wp-china-plus' ) . '</strong></p>
				</div>';
		}

		set_transient( 'wp-china-plus-admin-notices', $notices, 10 );
	}

	/**
	 * 输出管理后台提示信息
	 */
	public function admin_notices() {
		$notices = get_transient( 'wp-china-plus-admin-notices' );
		if ( $notices ) {
			foreach ( $notices as $notice ) {
				echo $notice;
			}
			delete_transient( 'wp-china-plus-admin-notices' );
		}
	}
}
