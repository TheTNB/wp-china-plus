<?php

namespace HaoZiTeam\WP_CHINA_PLUS\Inc;

defined( 'ABSPATH' ) || exit;

use HaoZiTeam\WP_CHINA_PLUS\Inc\Service\Super as SuperService;
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

use const HaoZiTeam\WP_CHINA_PLUS\PLUGIN_FILE;

class Plugin {

	private $settings_api;

	/**
	 * 创建一个插件实例
	 */
	public function __construct() {
		$this->settings_api = new Setting();
		new SuperService();

		// 加载插件
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( is_multisite() ? 'network_admin_menu' : 'admin_menu', array( $this, 'admin_menu' ) );

		// 加载插件更新
		PucFactory::buildUpdateChecker(
			'https://dl.cdn.haozi.net/wp-china-plus/plugin.json',
			PLUGIN_FILE,
			'wp-china-plus'
		);
	}

	/**
	 * 加载插件
	 */
	public function plugins_loaded() {
		add_action( 'admin_notices', [ $this, 'admin_notices' ] );
		// 加载插件文本域
		load_plugin_textdomain( 'wp-china-plus', false, plugin_dir_path( PLUGIN_FILE ) . 'languages' );
	}

	/**
	 * 挂载设置项
	 */
	public function admin_init() {
		$sections = array(
			array(
				'id'    => 'wp_china_plus_setting',
				'title' => __( '设置', 'wp-china-plus' )
			),
			array(
				'id'          => 'wp_china_plus_about',
				'title'       => __( '关于', 'wp-china-plus' ),
				'show_submit' => false
			)
		);

		$fields = array(
			'wp_china_plus_setting' => array(
				array(
					'name'    => 'super_store',
					'label'   => __( '市场加速', 'wp-china-plus' ),
					'desc'    => __( '替换 WordPress 应用市场使用加速镜像，这将极大优化您的 WordPress 使用体验',
						'wp-china-plus' ),
					'type'    => 'radio',
					'default' => 'on',
					'options' => array(
						'on'  => '启用',
						'off' => '禁用'
					)
				),
				array(
					'name'    => 'weavatar',
					'label'   => __( 'WeAvatar头像', 'wp-china-plus' ),
					'desc'    => __( '替换Gravatar头像为<a href="https://weavatar.com" target="_blank">WeAvatar</a>头像，WeAvatar致力于打造多端多元化的统一头像服务',
						'wp-china-plus' ),
					'type'    => 'radio',
					'default' => 'on',
					'options' => array(
						'on'  => '启用',
						'off' => '禁用'
					)
				),
				array(
					'name'    => 'remove_news',
					'label'   => __( '移除 WordPress活动及新闻', 'wp-china-plus' ),
					'desc'    => __( '移除管理后台仪表盘上的 WordPress活动及新闻 组件，可加快管理后台仪表盘的访问速度',
						'wp-china-plus' ),
					'type'    => 'radio',
					'default' => 'on',
					'options' => array(
						'on'  => '启用',
						'off' => '禁用'
					)
				),
				array(
					'name'    => 'super_admin',
					'label'   => __( '后台静态加速', 'wp-china-plus' ),
					'desc'    => __( '替换 WordPress 所依赖的静态文件使用 WP-China-Plus 的公共节点，此选项可显著加快小带宽服务器的管理后台访问速度',
						'wp-china-plus' ),
					'type'    => 'radio',
					'default' => 'on',
					'options' => array(
						'on'  => '启用',
						'off' => '禁用'
					)
				),
				array(
					'name'    => 'super_gfont',
					'label'   => __( '谷歌字体加速', 'wp-china-plus' ),
					'desc'    => __( '替换谷歌字体文件使用 WP-China-Plus 的公共节点，建议只在包含谷歌字体的情况下才启用该选项',
						'wp-china-plus' ),
					'type'    => 'radio',
					'default' => 'off',
					'options' => array(
						'off'   => '禁用',
						'front' => '前台启用',
						'back'  => '后台启用',
						'all'   => '全局启用'
					)
				),
				array(
					'name'    => 'super_gajax',
					'label'   => __( '谷歌前端公共库加速', 'wp-china-plus' ),
					'desc'    => __( '替换谷歌前端公共库文件使用 WP-China-Plus 的公共节点，建议只在包含谷歌前端公共库的情况下才启用该选项',
						'wp-china-plus' ),
					'type'    => 'radio',
					'default' => 'off',
					'options' => array(
						'off'   => '禁用',
						'front' => '前台启用',
						'back'  => '后台启用',
						'all'   => '全局启用'
					)
				),
				array(
					'name'    => 'super_cdnjs',
					'label'   => __( 'CDNJS公共库加速', 'wp-china-plus' ),
					'desc'    => __( '替换 CDNJS 公共库文件使用 WP-China-Plus 的公共节点，建议只在包含 CDNJS 公共库的情况下才启用该选项',
						'wp-china-plus' ),
					'type'    => 'radio',
					'default' => 'off',
					'options' => array(
						'off'   => '禁用',
						'front' => '前台启用',
						'back'  => '后台启用',
						'all'   => '全局启用'
					)
				),
				array(
					'name'    => 'email',
					'label'   => __( '推广与指导', 'wp-china-plus' ),
					'desc'    => __( '在 WordPress 的邮件模版尾部自动插入指向 WeAvatar.com 的超链接，帮助推广 WeAvatar 并指导访客如何修改头像。WeAvatar 的发展壮大需你我共同参与，感谢参与推广的每一个人！',
						'wp-china-plus' ),
					'type'    => 'radio',
					'default' => 'on',
					'options' => array(
						'on'  => '启用',
						'off' => '禁用'
					)
				),
			),
			'wp_china_plus_about'   => array(
				array(
					'name'  => 'about',
					'label' => __( '关于', 'wp-china-plus' ),
					'type'  => 'html',
					'html'  => __( '<h4>GitHub：<a href="https://github.com/HaoZi-Team/WP-China-Plus" target="_blank">https://github.com/HaoZi-Team/WP-China-Plus</a></h4><p>WP-China-Plus 是 WordPress 本土化的一部分，其作用是对 WordPress 中的外部请求和静态资源进行加速，改善 WordPress 在国内的使用体验。</p><p>问题反馈请前往项目的 Issues 区｜交流QQ群：<a target="_blank" href="https://jq.qq.com/?_wv=1027&amp;k=I1oJKSTH">12370907</a>｜QQ频道：<a target="_blank" href="https://pd.qq.com/s/fyol46wfy">耗子</a></p><br><p>维护相关API服务器和节点需要一定的成本，如果可能，不妨赞助使插件发展得更好。</p>',
						'wp-china-plus' )
				),
				array(
					'name'  => 'sponsor',
					'label' => __( '赞助商', 'wp-china-plus' ),
					'type'  => 'html',
					'html'  => __( '<h4>感谢以下赞助商的支持：</h4><p><a href="http://cdn.ddunyun.com/" target="_blank">盾云CDN</a></p>',
						'wp-china-plus' )
				)
			)
		);

		// set the settings
		$this->settings_api->set_sections( $sections );
		$this->settings_api->set_fields( $fields );

		// initialize settings
		$this->settings_api->admin_init();
	}

	/**
	 * 挂载设置页面
	 */
	public function admin_menu() {
		// 后台设置
		add_submenu_page(
			is_multisite() ? 'settings.php' : 'options-general.php',
			esc_html__( 'WP-China-Plus', 'wp-china-plus' ),
			esc_html__( 'WP-China-Plus', 'wp-china-plus' ),
			is_multisite() ? 'manage_network_options' : 'manage_options',
			'wp-china-plus',
			array( $this, 'plugin_page' )
		);
		// 插件页设置
		add_filter( 'plugin_action_links', function ( $links, $file ) {
			if ( 'wp-china-plus/wp-china-plus.php' !== $file ) {
				return $links;
			}
			$settings_link = '<a href="' . add_query_arg( array( 'page' => 'wp-china-plus' ),
					is_multisite() ? 'settings.php' : 'options-general.php' ) . '">' . esc_html__( '设置',
					'wp-china-plus' ) . '</a>';
			array_unshift( $links, $settings_link );

			return $links;
		}, 10, 2 );
	}

	/**
	 * 设置页面模版
	 */
	public function plugin_page() {

		echo '<h1>WP-China-Plus</h1>';
		echo '<h2>一个轻量级插件，用于改善WordPress在中国大陆的使用体验。</h2><span style="float: right; padding-right: 20px;">By: 耗子开源</span>';
		echo '<div class="wrap">';

		$this->settings_api->show_navigation();
		$this->settings_api->show_forms();

		echo '</div>';
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
		delete_option( 'wp_china_plus_setting' );
		delete_option( 'wp_china_plus_about' );
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
