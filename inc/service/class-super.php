<?php

namespace HaoZiTeam\WP_CHINA_PLUS\Inc\Service;

defined( 'ABSPATH' ) || exit;

use HaoZiTeam\WP_CHINA_PLUS\Inc\Setting;

/**
 * Class Super
 * 该类用于实现插件的加速功能
 * 核心代码来自 WP-China-Yes 项目
 * @package HaoZiTeam\WP_CHINA_PLUS\Inc\Service
 */
class Super {

	public function __construct() {
		$setting = new Setting();

		/**
		 * WordPress.Org API替换
		 */
		if ( is_admin() || wp_doing_cron() ) {
			if ( $setting->get_option( 'super_store', 'wp_china_plus_setting', 'on' ) != 'off' ) {
				add_filter( 'pre_http_request', function ( $preempt, $r, $url ) {
					if ( ( ! strpos( $url, 'api.wordpress.org' ) && ! strpos( $url,
							'downloads.wordpress.org' ) ) ) {
						return $preempt;
					}

					$url = str_replace( 'api.wordpress.org', 'wpa.cdn.haozi.net', $url );
					$url = str_replace( 'downloads.wordpress.org', 'wpd.cdn.haozi.net', $url );

					// curl版本低于7.15.0不支持https
					$curl_version = '1.0.0';
					if ( function_exists( 'curl_version' ) ) {
						$curl_version_array = curl_version();
						if ( is_array( $curl_version_array ) && key_exists( 'version', $curl_version_array ) ) {
							$curl_version = $curl_version_array['version'];
						}
					}
					if ( version_compare( $curl_version, '7.15.0', '<' ) ) {
						$url = str_replace( 'https://', 'http://', $url );
					}

					return wp_remote_request( $url, $r );
				}, PHP_INT_MAX, 3 );
			}
		}

		/**
		 * 移除 WordPress活动及新闻 小组件
		 */
		if ( is_admin() && $setting->get_option( 'remove_news', 'wp_china_plus_setting', 'on' ) != 'off' ) {
			add_action( 'wp_dashboard_setup', function () {
				global $wp_meta_boxes;

				unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'] );
			} );
		}

		/**
		 * WordPress 核心静态文件链接替换
		 */
		if ( is_admin() && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
			if (
				$setting->get_option( 'super_admin', 'wp_china_plus_setting', 'on' ) != 'off' &&
				! stristr( $GLOBALS['wp_version'], 'alpha' ) &&
				! stristr( $GLOBALS['wp_version'], 'beta' ) &&
				! stristr( $GLOBALS['wp_version'], 'RC' )
			) {
				// 禁用合并加载，以便于使用公共资源节点
				global $concatenate_scripts;
				$concatenate_scripts = false;

				$this->page_str_replace( 'preg_replace', [
					'~' . home_url( '/' ) . '(wp-admin|wp-includes)/(css|js)/~',
					sprintf( 'https://wpstatic.cdn.haozi.net/%s/$1/$2/', $GLOBALS['wp_version'] )
				], 'back' );
			}
		}

		if ( ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
			/**
			 * 谷歌字体替换
			 */
			if ( $setting->get_option( 'super_gfont', 'wp_china_plus_setting', 'off' ) != 'off' ) {
				$this->page_str_replace( 'str_replace', [
					'fonts.googleapis.com',
					'gfont.cdn.haozi.net'
				], $setting->get_option( 'super_gfont', 'wp_china_plus_setting', 'off' ) );
			}

			/**
			 * 谷歌前端公共库替换
			 */
			if ( $setting->get_option( 'super_gajax', 'wp_china_plus_setting', 'off' ) != 'off' ) {
				$this->page_str_replace( 'str_replace', [
					'ajax.googleapis.com',
					'gajax.cdn.haozi.net'
				], $setting->get_option( 'super_gajax', 'wp_china_plus_setting', 'off' ) );
			}

			/**
			 * CDNJS前端公共库替换
			 */
			if ( $setting->get_option( 'super_cdnjs', 'wp_china_plus_setting', 'off' ) != 'off' ) {
				$this->page_str_replace( 'str_replace', [
					'cdnjs.cloudflare.com/ajax/libs',
					'cdnjs.cdn.haozi.net'
				], $setting->get_option( 'super_cdnjs', 'wp_china_plus_setting', 'off' ) );
			}
		}

		/**
		 * WeAvatar
		 */
		if ( $setting->get_option( 'weavatar', 'wp_china_plus_setting', 'on' ) != 'off' ) {
			add_filter( 'user_profile_picture_description', [ $this, 'set_user_profile_picture_for_weavatar' ], 1 );
			add_filter( 'avatar_defaults', [ $this, 'set_defaults_for_weavatar' ], 1 );
			add_filter( 'um_user_avatar_url_filter', [ $this, 'get_weavatar_url' ], 1 );
			add_filter( 'bp_gravatar_url', [ $this, 'get_weavatar_url' ], 1 );
			add_filter( 'get_avatar_url', [ $this, 'get_weavatar_url' ], 1 );
		}

		/**
		 * WeAvatar 推广与指导
		 */
		if ( $setting->get_option( 'email', 'wp_china_plus_setting', 'on' ) != 'off' ) {
			add_filter( 'wp_mail', function ( $args ) {
				// 将 mail_content_type 设置为 text/html，以便于在邮件中使用 HTML 标签
				add_filter( 'wp_mail_content_type', function () {
					return 'text/html';
				}, PHP_INT_MAX );
				$args['message'] .= PHP_EOL . '<p>本站头像由 © WeAvatar 提供，WeAvatar 致力于打造统一的互联网头像体系</p><p>想要修改您的头像？请前往 <a href="https://weavatar.com" target="_blank">https://weavatar.com</a></p>';

				return $args;
			}, PHP_INT_MAX );
		}
	}

	/**
	 * 头像替换函数
	 */
	function get_weavatar_url( $url ) {
		$sources = array(
			'www.gravatar.com',
			'0.gravatar.com',
			'1.gravatar.com',
			'2.gravatar.com',
			'secure.gravatar.com',
			'cn.gravatar.com',
			'gravatar.com',
			'sdn.geekzu.org',
			'gravatar.duoshuo.com',
			'gravatar.loli.net',
			'cravatar.cn',
		);

		if ( $setting->get_option( 'weavatar', 'wp_china_plus_setting', 'on' ) != 'cc' ) {
			return str_replace( $sources, 'weavatar.com', $url );
		} else {
			return str_replace( $sources, 'weavatar.cc', $url );
		}
	}

	/**
	 * WordPress讨论设置中的默认LOGO名称替换函数
	 */
	function set_defaults_for_weavatar( $avatar_defaults ) {
		$avatar_defaults['gravatar_default'] = 'WeAvatar 头像';

		return $avatar_defaults;
	}

	/**
	 * 个人资料卡中的头像上传地址替换函数
	 */
	function set_user_profile_picture_for_weavatar() {
		return '<a href="https://weavatar.com" target="_blank">您可以在 WeAvatar 修改您的资料图片</a>';
	}

	/**
	 * 页面替换
	 *
	 * @param $replace_func string 要调用的字符串关键字替换函数
	 * @param $param array 传递给字符串替换函数的参数
	 */
	private function page_str_replace( $replace_func, $param, $level ) {
		if ( $level == 'front' && is_admin() ) {
			return;
		} elseif ( $level == 'back' && ! is_admin() ) {
			return;
		}

		add_action( 'init', function () use ( $replace_func, $param ) {
			ob_start( function ( $buffer ) use ( $replace_func, $param ) {
				$param[] = $buffer;

				return call_user_func_array( $replace_func, $param );
			} );
		}, PHP_INT_MAX );
	}
}
