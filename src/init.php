<?php
/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package BS
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

$block = 'block-bs-list-vertical';

// Hook server side rendering into render callback
register_block_type('bonseo/' . $block,
	array(
		'attributes' => array(
			'title' => array(
				'type' => 'string',
			),
			'max_entries' => array(
				'type' => 'string',
			),
			'type' => array(
				'type' => 'string',
			),
			'className' => array(
				'type' => 'string',
			)

		),
		'render_callback' => 'render_bs_list_vertical',
	)
);

/**
 * Enqueue Gutenberg block assets for backend editor.
 *
 * @uses {wp-blocks} for block type registration & related functions.
 * @uses {wp-element} for WP Element abstraction — structure of blocks.
 * @uses {wp-i18n} to internationalize the block's text.
 * @uses {wp-editor} for WP editor styles.
 * @since 1.0.0
 */
function bs_list_vertical_editor_assets()
{ // phpcs:ignore
	// Scripts.
	wp_enqueue_script(
		'bs_list_vertical-block-js', // Handle.
		plugins_url('/dist/blocks.build.js', dirname(__FILE__)), // Block.build.js: We register the block here. Built with Webpack.
		array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor'), // Dependencies, defined above.
		// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: File modification time.
		true // Enqueue the script in the footer.
	);
}

function render_bs_list_vertical_entries($posts)
{
	$html = '';
	while ($posts->have_posts()) : $posts->the_post();
		$title = get_the_title();
		$image = esc_url(get_the_post_thumbnail_url(get_the_ID()));
		$link = esc_url(get_the_permalink());
		$excerpt = strip_tags(wp_trim_words(get_the_content(), 20, '...'));
		$html .= '<div class="og-list-title-vertical__container__wrapper">
			<a href="' . $link . '" class="ml-article-rectangle
					a-text
					l-flex l-flex--align-center
					a-pad
					">
					<picture class=" a-pad-0">
					   <img class="a-image a-image--m a-image--rounded a-image--cover u-shadow--bottom lazy" data-src="' . $image . '">
					</picture>
					<div class="ml-article-rectangle__container
					   l-flex l-flex--direction-column a-pad">
					   <h3 class="a-text  a-text--brand a-text--bold">' . $title . '</h3>
					   <p class="a-text a-text--light a-text--s">' . $excerpt . '</p>
					</div>
				 </a><hr class="a-separator--classic a-bg" />
		  </div>';
		unset($post);
	endwhile;
	return $html;
}

function render_bs_list_vertical($attributes)
{
	$class = isset($attributes['className']) ? ' ' . $attributes['className'] : '';
	$title = isset($attributes['title']) ? $attributes['title'] : '';
	$entries = isset($attributes['max_entries']) ? $attributes['max_entries'] : 3;
	$type = isset($attributes['type']) ? $attributes['type'] : 'posts';
	$args = array(
		'post_type' => $type,
		'post_status' => 'publish',
		'posts_per_page' => $entries
	);
	$posts = new WP_Query($args);
	if (empty($posts)) {
		return "";
	}
	return '
		<section class="og-list-title-vertical
			   l-flex
			   l-position
			   a-bg ' . $class . ' ">
		   <h1 class="a-text  a-text--xl  og-list-title-vertical__title a-text--secondary">
			  ' . $title . '
		   </h1>
		   <nav class="og-list-title-vertical__container 
		   			   l-flex l-flex--direction-column l-column--1-1 
		   			   a-bg--mono-0 a-mar bs_viewport a-mi a-mi--temporal--left">'
		.render_bs_list_vertical_entries($posts).'</nav>
		</section>';
}

add_action('enqueue_block_editor_assets', 'bs_list_vertical_editor_assets');
