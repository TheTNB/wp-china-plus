<?php

namespace HaoZiTeam\ChinaPlus\Service;

defined( 'ABSPATH' ) || exit;

use HaoZiTeam\Setting\API;

/**
 * Class Setting
 * 插件设置服务
 * @package HaoZiTeam\ChinaPlus\Service
 */
class Setting {
	private $setting_api;

	public function __construct() {
		$this->setting_api = new API();
		add_action( 'admin_init', [ $this, 'admin_init' ] );
		add_action( is_multisite() ? 'network_admin_menu' : 'admin_menu', [ $this, 'admin_menu' ] );
	}

	/**
	 * 挂载设置项
	 */
	public function admin_init() {

		$sections = [
			[
				'id'    => 'wp_china_plus_setting',
				'title' => __( '设置', 'wp-china-plus' )
			],
			[
				'id'          => 'wp_china_plus_about',
				'title'       => __( '关于', 'wp-china-plus' ),
				'show_submit' => false
			]
		];

		$fields = [
			'wp_china_plus_setting' => [
				[
					'name'    => 'super_store',
					'label'   => __( '市场加速', 'wp-china-plus' ),
					'desc'    => __( '替换 WordPress 应用市场使用加速镜像，这将极大优化您的 WordPress 使用体验',
						'wp-china-plus' ),
					'type'    => 'radio',
					'default' => 'on',
					'options' => [
						'on'  => '启用',
						'off' => '禁用'
					]
				],
				[
					'name'    => 'weavatar',
					'label'   => __( 'WeAvatar头像', 'wp-china-plus' ),
					'desc'    => __( '替换Gravatar头像为<a href="https://weavatar.com" target="_blank">WeAvatar</a>头像，WeAvatar致力于打造多端多元化的统一头像服务',
						'wp-china-plus' ),
					'type'    => 'radio',
					'default' => 'on',
					'options' => [
						'on'  => '启用',
						'off' => '禁用'
					]
				],
				[
					'name'    => 'remove_news',
					'label'   => __( '移除 WordPress活动及新闻', 'wp-china-plus' ),
					'desc'    => __( '移除管理后台仪表盘上的 WordPress活动及新闻 组件，可加快管理后台仪表盘的访问速度',
						'wp-china-plus' ),
					'type'    => 'radio',
					'default' => 'on',
					'options' => [
						'on'  => '启用',
						'off' => '禁用'
					]
				],
				[
					'name'    => 'block_unnecessary',
					'label'   => __( '屏蔽 WordPress 无用 API 请求', 'wp-china-plus' ),
					'desc'    => __( '屏蔽 WordPress 自带的一些无用 API 请求（浏览器检查、PHP 检查），可加快管理后台的访问速度',
						'wp-china-plus' ),
					'type'    => 'radio',
					'default' => 'off',
					'options' => [
						'on'  => '启用',
						'off' => '禁用'
					]
				],
				[
					'name'    => 'super_admin',
					'label'   => __( '后台静态加速', 'wp-china-plus' ),
					'desc'    => __( '替换 WordPress 所依赖的静态文件使用 WP-China-Plus 的公共节点，此选项可显著加快小带宽服务器的管理后台访问速度',
						'wp-china-plus' ),
					'type'    => 'radio',
					'default' => 'on',
					'options' => [
						'on'  => '启用',
						'off' => '禁用'
					]
				],
				[
					'name'    => 'super_gfont',
					'label'   => __( '谷歌字体加速', 'wp-china-plus' ),
					'desc'    => __( '替换谷歌字体文件使用 WP-China-Plus 的公共节点，建议只在包含谷歌字体的情况下才启用该选项',
						'wp-china-plus' ),
					'type'    => 'radio',
					'default' => 'off',
					'options' => [
						'off'   => '禁用',
						'front' => '前台启用',
						'back'  => '后台启用',
						'all'   => '全局启用'
					]
				],
				[
					'name'    => 'super_gajax',
					'label'   => __( '谷歌前端公共库加速', 'wp-china-plus' ),
					'desc'    => __( '替换谷歌前端公共库文件使用 WP-China-Plus 的公共节点，建议只在包含谷歌前端公共库的情况下才启用该选项',
						'wp-china-plus' ),
					'type'    => 'radio',
					'default' => 'off',
					'options' => [
						'off'   => '禁用',
						'front' => '前台启用',
						'back'  => '后台启用',
						'all'   => '全局启用'
					]
				],
				[
					'name'    => 'super_cdnjs',
					'label'   => __( 'CDNJS公共库加速', 'wp-china-plus' ),
					'desc'    => __( '替换 CDNJS 公共库文件使用 WP-China-Plus 的公共节点，建议只在包含 CDNJS 公共库的情况下才启用该选项',
						'wp-china-plus' ),
					'type'    => 'radio',
					'default' => 'off',
					'options' => [
						'off'   => '禁用',
						'front' => '前台启用',
						'back'  => '后台启用',
						'all'   => '全局启用'
					]
				],
				[
					'name'    => 'email',
					'label'   => __( '推广与指导', 'wp-china-plus' ),
					'desc'    => __( '在 WordPress 的邮件模版尾部自动插入指向 WeAvatar.com 的超链接，帮助推广 WeAvatar 并指导访客如何修改头像。WeAvatar 的发展壮大需你我共同参与，感谢参与推广的每一个人！',
						'wp-china-plus' ),
					'type'    => 'radio',
					'default' => 'on',
					'options' => [
						'on'  => '启用',
						'off' => '禁用'
					]
				],
			],
			'wp_china_plus_about'   => [
				[
					'name'  => 'about',
					'label' => __( '关于', 'wp-china-plus' ),
					'type'  => 'html',
					'html'  => __( '<h4>GitHub：<a href="https://github.com/HaoZi-Team/WP-China-Plus" target="_blank">https://github.com/haozi-team/wp-china-plus</a></h4><p>WP-China-Plus 是 WordPress 本土化的一部分，其作用是对 WordPress 中的外部请求和静态资源进行加速，改善 WordPress 在国内的使用体验。</p><p>问题反馈请前往项目的 Issues 区｜交流QQ群：<a target="_blank" href="https://jq.qq.com/?_wv=1027&amp;k=I1oJKSTH">12370907</a>｜QQ频道：<a target="_blank" href="https://pd.qq.com/s/fyol46wfy">耗子</a></p><br><p>维护相关API服务器和节点需要一定的成本，如果可能，不妨赞助使插件发展得更好。</p>',
						'wp-china-plus' )
				],
				[
					'name'  => 'sponsor',
					'label' => __( '赞助商', 'wp-china-plus' ),
					'type'  => 'html',
					'html'  => __( '<h4>感谢以下赞助商的支持：</h4><p><a href="https://www.ddunyun.com/aff/PNYAXMKI" target="_blank">盾云</a></p><p><a href="https://www.anycast.ai/" target="_blank">AnyCast.Ai</a></p><p><a href="https://su.sctes.com/register?code=8st689ujpmm2p" target="_blank">无畏云加速</a></p><p><a href="https://www.jihulab.com/" target="_blank">极狐</a></p>',
						'wp-china-plus' )
				]
			]
		];

		$this->setting_api->set_sections( $sections );
		$this->setting_api->set_fields( $fields );
		$this->setting_api->admin_init();
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
			[ $this, 'setting_page' ]
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
	public function setting_page() {
		echo '<h1>WP-China-Plus</h1>';
		echo '<h4>我们的终极目标是打造一个本土化的 WordPress，包括应用市场、翻译、文档等方面。这需要非常大量的开发工作。</h4><h4>如果你也对此感兴趣且熟悉 WordPress 开发 / Vue 开发 / Golang 开发，欢迎通过下方关于页面的联系方式加入我们。</h4><span style="float: right; padding-right: 20px;">By: 耗子开源</span>';
		echo '<div class="wrap">';

		$this->setting_api->show_navigation();
		$this->setting_api->show_forms();

		echo '</div>';
	}
}
