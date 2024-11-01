<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div id="wi-registration-used" style="margin: 10px auto; width:50%;">
		<div class="meter">
			<span style="width:0%; max-width: 100% !important; background-color: <?php echo esc_attr( $bg_color ); ?>;"></span>
		</div>
		<div class="progress-information">
			<p class="text-colour--faded-60"><?php esc_html( $user_count ); ?></p>
			<p style="text-align: center; width:70%;">
			<?php if ( $progress < 100 ) : ?>
				<?php if ( ( $limit_users < $total_number_of_days && $progress < 80 ) || ( $limit_users > $total_number_of_days && $current_count_ratio < $total_count_ratio ) ) : ?>
						<?php 
							/* translators: %s: Number of registrations left */
							printf( esc_html__( '%s Registrations left until user can not register!', 'webinarignition' ), esc_html( absint( $user_left ) ) ); 
							?>
						<br>
						<?php echo wp_kses_post( $reset_date_message ); ?>
						<?php echo wp_kses_post( $act_now_link ); ?>
					</p>
					<?php
				else :

					if ( get_user_meta( get_current_user_id(), 'notice-webinarignition-free', true ) ) {
						delete_user_meta( get_current_user_id(), 'notice-webinarignition-free' );
					}

					$read_more = sprintf( '<a class="wi-dashboard-link" href="https://webinarignition.tawk.help/article/webinar-is-full-please-contact-the-webinar-host" target="_blank">%s</a>', __( 'Details', 'webinarignition' ) );
					?>
						<?php 
						/* translators: %s: Link to details page */
						printf( esc_html__( 'Visitors could possibly not register at the end of the period. %s', 'webinarignition' ), wp_kses_post( $read_more ) );
						?>
						<br>
						<?php echo wp_kses_post( $reset_date_message ); ?>
						<?php echo wp_kses_post( $act_now_link ); ?>
				<?php endif; ?>
			<?php else : ?>
					<?php
					$read_more = sprintf( '<a class="wi-dashboard-link" href="https://webinarignition.tawk.help/article/webinar-is-full-please-contact-the-webinar-host" target="_blank">%s</a>', __( 'Details', 'webinarignition' ) );
					/* translators: %1$s: Link to act now, %2$s: Link to read more details */
					printf( esc_html__( 'Registrations Are Closed! %1$s (%2$s)', 'webinarignition' ), wp_kses_post( $act_now_link ), wp_kses_post( $read_more ) );
					?>
					<br>

			<?php endif; ?>
			</p>
			<p class="text-colour--primary-red--80"><?php echo intval( $limit_users ); ?></p>
		</div>
	</div>
	<div id="webinarignition-reg-progress-counter" data-progress="<?php echo intval( $progress ); ?>"></div>