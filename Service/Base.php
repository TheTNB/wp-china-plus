<?php

namespace HaoZiTeam\ChinaPlus\Service;

defined( 'ABSPATH' ) || exit;

/**
 * Class Base
 * 插件主服务
 * @package HaoZiTeam\ChinaPlus\Service
 */
class Base {

	public function __construct() {
		/**
		 * 插件列表页中所有插件增加「翻译校准」链接
		 */
		add_filter( sprintf( '%splugin_action_links', is_multisite() ? 'network_admin_' : '' ), function ( $links, $plugin = '' ) {
			$links[] = '<a target="_blank" href="https://wepublish.cn/translate/projects/plugin/' . substr( $plugin, 0, strpos( $plugin, '/' ) ) . '/">参与翻译</a>';

			return $links;
		}, 10, 2 );

		// 加速服务
		new Super();
		// 更新服务
		new Update();
	}
}
