<?php
/**
 * Functions for this theme.
 *
 * See README.TXT for theme doc.
 *
 * @package davidsword-2018
 */

defined( 'ABSPATH' ) || exit;

global $wp_customize, $post, $swrdbs;

/**
 * The davidsword.ca plugin.
 *
 * Regardless of theme, this functionality is carried over.
 * Storing in theme since all my themes will be custom and centeralized codebase is eaiser.
 */
require 'plugin-dsca/index.php';



/* =====================================================================================================
   THEME CONFIGURE, visual and theme development side of things
/* ----------------------------------------------------------------------------------------------------- */

$swrdbs = [
	'theme_varient' => '2.0.14',
	'dev' => false,
	'dev_user_id' => 1,
	'hero_title' => true,
	'hero_w' => 1600,
	'hero_h' => 900,
];

// turn on errors ASAP, uses $swrdbs['dev'] boolean
swrdbs_errors();

// ADD WORDPRESS FEATURES
add_theme_support( 'custom-logo', [
	'height'      => 100,
	'width'       => 400,
	'flex-height' => true,
	'flex-width'  => true,
	'header-text' => ['site-title', 'site-description'],
]);

add_post_type_support( 'page', 'excerpt' );

add_action( 'after_setup_theme', function () {
	// gutenberg
	add_theme_support( 'wp-block-styles' );

	add_theme_support( 'post-thumbnails' , ['post' , 'page'] );
	add_theme_support( 'html5', ['comment-list','comment-form','search-form','gallery','caption'] );
	add_theme_support( 'align-wide' );
    add_theme_support( 'editor-color-palette',
        '#a156b4',
        '#d0a5db',
        '#eee',
        '#444'
    );

	// remove_theme_support( 'post-formats' );
});

// wordpress overwrite width default settings
$content_width = '';

// REGISTER WORDPRESS FEATURES
register_nav_menu( 'main-nav', 'Main Navigation' );
//register_nav_menu( 'sec-nav',  'Secondary Navigation' );
//register_sidebars(2, ['name'=>'Foobar %d']);

// Add browser name to body class
add_filter('body_class','thematic_browser_class_names'); //@TODO decied if nessisary

// add shortcodes for swrdbs
add_shortcode('space','swrdbs_shortcode_space');
add_shortcode('clear','swrdbs_shortcode_clear');
add_shortcode('box','swrdbs_shortcode_box');
add_shortcode('email','swrdbs_return_email');
add_shortcode('phone','swrdbs_phone');
add_shortcode('iframe','swrdbs_iframe');
add_shortcode('todo', 'swrdbs_todoshortcode' );
add_shortcode('social','swrdbs_make_social');

// admin: load customizer interface
add_action( 'customize_register', 'swrdbs_customize_register' );
add_action( 'customize_controls_print_styles','swrdbs_customizer_hide_some_stuff');

// admin: header and notice for plugin checks
add_action('admin_head','swrdbs_modify_admin_header');
add_action('admin_notices','swrdbs_plugin_check');
// add_filter( 'mce_css', 'swrdbs_tinymce_css' );

// TOOLKIT CONFIGURE, not `$swrdbs` options because these can be used on any theme
add_filter('sword_toolkit', function() {
	return [
		'remove_menu_pages'		=> ['widgets.php'],
		'remove_menu_sub_pages' => [
			'themes.php' => 'widgets.php',
		],
		'remove_jquery_migrate' => true,
		'remove_wp_head_junk' => true,
		'redirect_if_attachment' => true,
		'remove_post_tags' => true,
		'remove_emojis' => false,
	];
});

/* =====================================================================================================
   PLUGINS CONFIGURE
/* ----------------------------------------------------------------------------------------------------- */

// GRAVITY FORM EDITS
add_filter("gform_confirmation_anchor", '__return_true');
add_action('wp_print_styles', function(){ wp_dequeue_style('gforms_css'); });
add_filter('gform_init_scripts_footer', '__return_true'); // push js to footer

// MONSTER INSIGHTS
// add_action( 'wp_enqueue_scripts', 'swrdbs_move_monsterinsights', 99);
// function swrdbs_move_monsterinsights() {
// 	wp_dequeue_script( 'monsterinsights-lite-frontend-script'); // get outta the header
// 	wp_enqueue_script(
// 		'monsterinsights-lite-frontend-script', // "monsterinsights-lite" for non-upgraded
// 		plugins_url().'/google-analytics-for-wordpress/assests/js/frontend.min.js',
// 		[], // no depends
// 		get_bloginfo('version'), // refresh cache once in a while
// 		true // move to footer
// 	);
// }



/* =====================================================================================================
   MAIN HOOKS (most popular hooks)
   - wp_enqueue_scripts
   - body_class
   - the_content
   - get_footer
/* ----------------------------------------------------------------------------------------------------- */

add_action( 'enqueue_block_editor_assets', function () {
	wp_enqueue_style(
		'ds_guteneditor', // Handle.
		get_template_directory_uri().'/assests/css/gutenberg.css',
		null, // Dependencies, defined above.
		time()
	);
});

/**
 * Enqueue scripts into Wordpress
 *
 * Load in various styles and scripts into wordpress. Run conditionals to reduce unneeded scripts
 *
 * @since 1.0.0
 *
 * @see `add_action( 'wp_enqueue_scripts'...`
 */
add_action( 'wp_enqueue_scripts', function () {
	global $swrdbs;

	/* jQUERY ---------------------------------- */
	//wp_deregister_script( 'jquery');
    //wp_register_script( 'jquery', includes_url( '/js/jquery/jquery.js' ), false, NULL, true );
    //wp_enqueue_script( 'jquery' );


	/* MAIN JS ---------------------------------- */
	$mainjs = ($swrdbs['dev']) ? 'main.js' : 'main.min.js';
	$ver = ($swrdbs['dev']) ? time() : $swrdbs['theme_varient'];

	wp_enqueue_script(
		'swrdbs_js',
		get_template_directory_uri() . '/assests/js/'.$mainjs,
		['jquery','ds_gist','lightbox'],
		$ver,
		true
	);

	wp_enqueue_script(
		'darkmode',
		get_template_directory_uri() . '/assests/js/darkmode.js',
		[],
		$ver,
		true
	);

	/* GIST ---------------------------------- */
	wp_enqueue_script(
		'ds_gist',
		get_template_directory_uri() . '/assests/js/gist.js',
		['jquery'],
		$ver,
		true
	);

	/* LIGHTBOX ---------------------------------- */
	wp_enqueue_script(
		'lightbox',
		get_template_directory_uri() . '/assests/js/featherlight.js',
		[ 'jquery', 'lightbox_swipe' ],
		$ver,
		true
	);

	/* SWIPE ---------------------------------- */
	wp_enqueue_script(
		'lightbox_swipe',
		get_template_directory_uri() . '/assests/js/swipe.js',
		['jquery'],
		$ver,
		true
	);

	/* MAIN CSS ---------------------------------- */
	if (!$swrdbs['dev']) {
		wp_enqueue_style(
			'main',
			get_template_directory_uri() . '/style.css',
			[],
			false
		);
	}

	/* LIGHTBOX ---------------------------------- */
	add_action( 'wp_footer', function () {
		wp_enqueue_style(
			'lightbox',
			get_template_directory_uri() . '/assests/css/jquery.lightbox.css'
		);
	});

});


/**
 *
 *
 */
add_action('body_class', function ($classes) {
	global $swrdbs, $post;
    if (empty($post->post_content) || strpos($post->post_content.' ', 'et_pb_section') === false) {
        $classes[] = 'divi_no';
    } else {
        $classes[] = 'divi_yes';
    }
	if (!is_front_page())
		$classes[] = 'inner_page';
	if ($swrdbs['dev'])
		$classes[] = 'swrdbsdev';
	return $classes;
});


/**
 * Main modifier of the_content() output
 *
 * Default: add an inline featured images (reaches beyond @width)
 *
 * @since 1.0.0
 *
 * @see `functions.php` `add_action('the_content'...`
 *
 * @return string HTML $the_content with any conditional additions herin
 */
add_filter('the_content',function ($the_content) {
    global $swrdbs;

    return $the_content;
},999);

/**
 *
 *
 *
 *
 *
 *
 *
 *
 *
 */



/* =====================================================================================================
   MAIN CONTENT FUNCTIONS main functions of the theme
   - swrdbs_make_card (business schema card)
/* ----------------------------------------------------------------------------------------------------- */

/**
 * Make vcard
 *
 * Creates a vcard using schema.org, pulls vars from Customizer -> Contact Details
 *
 * @since 1.0.0
 *
 * @see _/customizer.php
 */
function swrdbs_make_card() {
	global $swrdbs;

	// if YOAST SEO is enabled, we don't need two schema entries
	if (defined( 'WPSEO_FILE' )) return;

	$return = "
	<div itemscope itemtype='https://schema.org/LocalBusiness' id='card'>

		<img itemprop='logo' alt=\"".get_bloginfo('name')."\" title=\"".get_bloginfo('name')."\" src='".get_site_icon_url()."' />
		<span itemprop='name'>".get_bloginfo('name')."</span>
		<div itemprop='address' itemscope itemtype='https://schema.org/PostalAddress'>";

			if (!empty($swrdbs['streetaddress']))
			$return .= "<span itemprop='streetAddress'>{$swrdbs['streetaddress']}</span>";

			if (isset($swrdbs['city']) && !empty($swrdbs['city']))
			$return .= "<span itemprop='addressLocality'>{$swrdbs['city']}</span>,
			<span itemprop='addressRegion'>{$swrdbs['province']}</span>";

			if (!empty($swrdbs['postalcode']))
				$return .= "<span itemprop='postalCode'>{$swrdbs['postalcode']}</span>";

		$return .= "
		</div>
		<span class='label'>Call:</span>
		<span itemprop='telephone'>";

		if (isset($swrdbs['phonenumber']))
			swrdbs_return_clean_phonenumber($swrdbs['phonenumber']);

		$return .= "</span><br />
		<span class='label'>Email:</span>
		<span class='email'>".swrdbs_return_email('','')."</span>";

		if (!empty($swrdbs['fax']))
			$return .= "<span itemprop='faxNumber'>{$swrdbs['fax']}</span>";

		$domaintext = str_replace( ['https://','https://','www.'],'',strtolower( rtrim(get_bloginfo('url'),"/") ) );
		$return .= "<span itemprop='url'><a href='".get_bloginfo('url')."'>".$domaintext."</a></span>";

		if (!empty($swrdbs['latitude']))
		$return .= "<div itemprop='geo' itemscope itemtype='https://schema.org/GeoCoordinates'>
			<meta itemprop='latitude' content='{$swrdbs['latitude']}' />
			<meta itemprop='longitude' content='{$swrdbs['longitude']}' />
		</div>";

		if (!empty($swrdbs['has_search']))
		$return .= '
		<form itemprop="potentialAction" itemscope itemtype="https://schema.org/SearchAction">
			<meta itemprop="target" content="'.get_bloginfo('url').'/?s={search_term_string}"/>
			<input itemprop="query-input" type="text" name="search_term_string" required/>
			<input type="submit"/>
		</form>';

	$return .= swrdbs_make_social()."
	</div><!--/#card-->";

	return $return;
}
