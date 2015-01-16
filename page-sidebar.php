<?php /* Template Name: Sidebar */
defined('ABSPATH') or die("No script kiddies please!");
get_header(); 
?>
<div class="sections section-lsb">
	<div class="content-limiter">
		<div class="section-lsb_sidebar sections-block">
			<div class="section-lsb_sidebar-border"></div>
			<div class="menu sidebar-menu">
				<?php dynamic_sidebar( (int) get_post_meta( get_the_ID(), 'wpcr-admin-page-metabox-sidebar-number', true ) ); ?>
			</div>	
		</div>
		<div class="section-lsb_content sections-block">
					<?php if ( have_posts() ) {
						while ( have_posts() ) {
							the_post();
							the_content();
						}
					}
					?> 
		</div>
	</div>
</div>
<? echo do_shortcode( get_post_meta( get_the_ID(), 'wpcr_admin_page_metabox_bottom_wpeditor', true ) );
get_footer();
?>