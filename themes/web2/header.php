<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><!-- Replace with your own languge code - more info here (http://www.w3.org/TR/REC-html40/struct/dirlang.html#h-8.1) -->

<head profile="http://gmpg.org/xfn/11">
	<title><?php bloginfo('name'); ?><?php wp_title(); ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo('charset'); ?>" />
	<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats please -->
	<style type="text/css" media="screen">
		@import url( <?php bloginfo('stylesheet_url'); ?> );
	</style>

	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
	<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
	<link rel="shortcut icon" type="image/ico" href="<?php bloginfo('template_url'); ?>/favicon.ico" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <?php wp_get_archives('type=monthly&format=link'); ?>
	<?php //comments_popup_script(); // off by default ?>
	<?php wp_head(); ?>
</head>

<body>
<div id="container">
<div id="skip">
	<p><a href="#content" title="Skip to site content">Skip to content</a></p>
	<p><a href="#search" title="Skip to search" accesskey="s">Skip to search - Accesskey = s</a></p>
</div>
<hr />
	<h1><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></h1>
	<!-- Tag line description is off by default. Please see readme.txt or CSS(h1,tagline) for more info
		<div class="tagline"><?php // remove bloginfo('description'); ?></div> 
	-->
	<div id="content_bg">
	<!-- Needed for dropshadows -->
	<div class="container_left">
	<div class="container_right">
	<div class="topline">
	<!-- Start float clearing -->
	<div class="clearfix">
<!-- end header -->