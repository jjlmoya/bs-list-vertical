<?php
/**
 * Plugin Name: List Vertical  [BonSeo Block]
 * Plugin URI: https://www.bonseo.es/bloques-gutenberg/list-vertical
 * Description: Un banner simple en forma de flecha
 * Author: jjlmoya
 * Author URI: https://www.bonseo.es/
 * Version: 1.0.0
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 * @package BS
 */


if (!defined('ABSPATH')) {
	exit;
}
if (!in_array('bs-core/plugin.php', apply_filters('active_plugins', get_option('active_plugins')))) {
	add_action('admin_notices', function () {
		global $pagenow;
		if ($pagenow == "plugins.php") {
			?>
			<div id="updated" class="error notice is-dismissible">
				<p> Puede que algunos plugins vean afectados su comportamiento y estilo debido a que no se ha instalado
					la dependencia con el Plugin "BS-CORE" disponible gratuitamente en https://bonseo.es/plugins</p>
			</div>
			<?php
		}
	});
	return;
}


if (!function_exists('bs_create_block_category')) {
	function bs_create_block_category($categories, $post)
	{
		return array_merge(
			$categories,
			array(
				array(
					'slug' => 'bonseo-blocks',
					'title' => __('BonSeo', 'bonseo-blocks'),
				),
			)
		);
	}

	add_filter('block_categories', 'bs_create_block_category', 10, 2);
}

require_once plugin_dir_path(__FILE__) . 'src/init.php';
