
<!-- begin sidebar -->
<div id="right">
	<!--
		<div id="author">
			Here is a section you can use to briefly talk about yourself or your site. Uncomment and delete this line to use.
			<h3><?php _e('The Author'); ?></h3>
			<p>Your description here.</p>
		</div>
		
		<div class="line"></div>
	-->
		<div id="links">
		
		<div id="pages">
			<h3><?php _e('The Pages'); ?></h3>
				<ul>
					<?php wp_list_pages('title_li='); ?>
				</ul>
		</div>
			
		<div class="line"></div>
		
		<h3>The Search</h3>
			<p class="searchinfo">search site archives</p>
			<div id="search">
				<div id="search_area">
					<form id="searchform" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
						<input class="searchfield" type="text" name="s" id="s" value="" title="Enter keyword to search" />
						<input class="submit" type="submit" name="submit" value="" title="Click to search archives" />
					</form>
				</div>
			</div>
			
			
			
		<div class="line"></div>
		
		<h3><?php _e('The Associates'); ?></h3>
			<ul>
				<?php get_links('-1', '<li>', '</li>', '', 0, 'name', 0, 0, -1, 0); ?>
			</ul>
				
		<div class="line"></div>
		
		<h3><?php _e('The Storage'); ?></h3>
			<ul>
		 		<?php wp_get_archives('type=monthly'); ?>
 			</ul>
 					
		<div class="line"></div>
		
			<h3><?php _e('The Categories'); ?></h3>
				<ul>
					<?php wp_list_cats(); ?>
				</ul>	
		<div class="line"></div>
		
			<h3><?php _e('The Meta'); ?></h3>
				<ul>
					<!-- <li><?php // wp_register(); ?></li> -->
					<li><?php wp_loginout(); ?></li>
					<li><a href="http://wordpress.org/" title="<?php _e('Powered by WordPress, state-of-the-art semantic personal publishing platform.'); ?>"><abbr title="WordPress">WP</abbr></a></li>
					<li><a href="http://gmpg.org/xfn/"><abbr title="XHTML Friends Network">XFN</abbr></a></li>
					<li><a href="feed:<?php bloginfo('rss2_url'); ?>" title="<?php _e('Syndicate this site using RSS'); ?>"><?php _e('<abbr title="Really Simple Syndication">RSS</abbr>'); ?></a></li>
					<li><a href="feed:<?php bloginfo('comments_rss2_url'); ?>" title="<?php _e('The latest comments to all posts in RSS'); ?>"><?php _e('Comments <abbr title="Really Simple Syndication">RSS</abbr>'); ?></a></li>
					<li><a href="#content" title="back to top">Back to top</a></li>
					<?php wp_meta(); ?>
				</ul>
				
		<div class="line"></div>

				</div>
</div>

<!-- end sidebar -->
