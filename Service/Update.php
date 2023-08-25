<?php

namespace HaoZiTeam\ChinaPlus\Service;

defined( 'ABSPATH' ) || exit;

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;
use const HaoZiTeam\ChinaPlus\PLUGIN_FILE;

/**
 * Class Update
 * 插件更新服务
 * @package HaoZiTeam\ChinaPlus\Service
 */
class Update {

	public function __construct() {
		PucFactory::buildUpdateChecker(
			'https://dl.cdn.haozi.net/wp-china-plus/plugin.json',
			PLUGIN_FILE,
			'wp-china-plus'
		);
	}
}
