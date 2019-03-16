<?php
/**
 * Header
 *
 * @package davidsword-ca
 */

defined( 'ABSPATH' ) || exit;

?><!DOCTYPE html>
<html lang="en">
<head>

<!--

👋🏻 Why hello there!

v<?php echo wp_get_theme()->get( 'Version' ); ?> - synced with WPPusher
via https://github.com/davidsword/davidsword.ca-2018

🌷 Have a great rest of your day!

-->

<title><?php wp_title(); ?></title>

<!-- #### META #### -->
<meta charset="utf-8">
<meta name="author" content="<?php echo get_bloginfo( 'name' ); ?>" />
<meta name="robots" content="index, follow" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<!-- #### WordPress wp_head #### -->
<?php wp_head(); ?>

<!-- #### /WordPress wp_head #### -->

<!-- #### ENABLE RESPONSIVE #### -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="apple-mobile-web-app-capable" content="yes" />

<!-- #### FEED #### -->
<link rel="alternate" type="application/rss+xml" title="RSS 2.0 Feed for Posts" href="<?php bloginfo( 'rss2_url' ); ?>" />

</head>
<body <?php body_class(); ?>>

	<header id='head'>
		<div class='head__inner'>
			<h1>
				<a href='<?php bloginfo( 'url' ); ?>'><?php echo bloginfo( 'name' ); ?></a>
				<span class='description'><?php bloginfo( 'description' ); ?></span>
			</h1>
			<nav id='main'>
				<ul>
					<?php
					wp_nav_menu( [
						'container'      => '',
						'items_wrap'     => '%3$s',
						'title_li'       => '',
						'theme_location' => 'main-nav',
					] );
					?>
				</ul>
			</nav>
			<nav id='sec'>
				<ul>
					<?php
					wp_nav_menu( [
						'container'      => '',
						'items_wrap'     => '%3$s',
						'title_li'       => '',
						'theme_location' => 'sec-nav',
					] );
					?>
				</ul>
			</nav>
		</div>
	</header>
