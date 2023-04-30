<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.7.0
 */

use \Directorist\Helper;

$columns = floor( 12 / $taxonomy->columns );
?>
<div id="directorist" class="atbd_wrapper directorist-w-100">
	<?php
	/**
	 * @since 5.6.6
	 */
	do_action( 'atbdp_before_all_locations_loop', $taxonomy );
	?>
	<div class="<?php Helper::directorist_container_fluid(); ?>">
	<div class="atbdp atbdp-categories atbdp-text-list">
		<div class="<?php Helper::directorist_row(); ?> atbdp-no-margin">
			<?php
			if( $locations ) {
				foreach ($locations as $location) {
					$toggle_class = $location['has_child'] ? 'directorist-category-list__toggle' : '';
					$toggle_icon = $location['has_child'] ? 'las la-angle-down' : '';
					$has_icon = $location['icon_class'] ? 'directorist-category-list__card--icon' : '';
					?>
					<div class="<?php Helper::directorist_column( $columns ); ?> directorist-category-list-one">
						<div class="directorist-category-list">
							<a class="directorist-category-list__card <?php echo wp_kses_post( $toggle_class ); ?> <?php echo wp_kses_post( $has_icon ); ?> " href="<?php echo esc_url($location['permalink']);?>">
								<?php if($location['icon_class']){ ?>
									<span class="directorist-category-list__icon">
										<?php directorist_icon( $location['icon_class'] ); ?>
									</span>
								<?php } ?>
								<span class="directorist-category-list__name">
									<?php echo esc_html($location['name']);?>
								</span>
								<span class="directorist-category-list__count">
									<?php echo wp_kses_post( $location['list_count_html'] );?>
								</span>
								<?php if($location['has_child']){ ?>
									<span class="directorist-category-list__toggler">
										<?php directorist_icon( $toggle_icon ); ?>
									</span>
								<?php } ?>
							</a>
							<?php echo wp_kses_post( $location['subterm_html'] );?>
						</div>
					</div>
					<?php
				}
			}
			else {
				?>
				<p><?php esc_html_e( 'No Results found!', 'directorist' ); ?></p>
				<?php
			}
			?>
		</div>
	</div>
	</div>
	<?php
	/**
     * @since 5.6.6
     */
    do_action( 'atbdp_after_all_locations_loop' );
    ?>
</div>