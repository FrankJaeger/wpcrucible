<?php defined('ABSPATH') or die("No script kiddies please!");
get_header();
if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
?>
<div class="content-limiter">
	<div class="content-title">
		<a href="<?php the_permalink(); ?>"><p class="content-title_text">// <?php the_title(); ?></p></a>
	</div>
	<?php the_content(); ?>
</div><p></p>
<?php
		}
	}
?>
<div class="gapper"></div>
<?php
get_footer();
?>