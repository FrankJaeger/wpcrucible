<?php 
require_once( 'includes/helper-classes.php' );
	$s = new fpwpcr_settings();
		$logo_uri = esc_url( $s->get_value( 'wpcr-header-logo' ) );
		$menu_args = array(
			'theme_location'  => 'wpcr-main-main-menu',
			'container' 	  => 'div',
			'container_class' => 'menu footer-menu',
			'menu_class'	  => 'menu-items'
			);
?>
</div>
		<div class="footer">
			<div class="sections section-tt">
				<div class="content-limiter">
					<div class="section-tt_left sections-block sections-block_vcentered sections-block_vpadded section-tt_custom-footer">
						<?php wp_nav_menu( $menu_args ); ?>	
						<p class="content-text">&copy; <?php echo date('Y'); ?> Crucible Technology Limited</p>
					</div>
					<div class="section-tt_right sections-block sections-block_vcentered sections-block_hpadded sections-block_vpadded section-tt_custom-footer">
						<div class="single-image single-image_custom-footer">
							<img class="single-image_picture" src="<?php echo $s->get_value( 'wpcr-footer-logo' ); ?>" alt="<?php bloginfo( 'name' ); ?>" />
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php wp_footer(); ?>
	</body>
</html>