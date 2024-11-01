<?php
/**
 * @var $webinar_data
 * @var $uid
 * @var $is_compact
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
WebinarignitionManager::webinarignition_set_locale( $webinar_data );
?>
<div class="eventDate <?php echo esc_attr( $uid ); ?>" style="border:none; margin:0px; padding: 0 0 10px 0;">

	<div class="wiFormGroup wiFormGroup-lg">
		<?php
		if ( ! $is_compact ) {
			?>
			<label for="webinar_start_date">
				<h4 class="autoTitle">
					<?php
					webinarignition_display(
						$webinar_data->auto_translate_headline1,
						__( 'Choose a Date To Attend... ', 'webinarignition' )
					);
					?>
				</h4>
				<h5 class="autoSubTitle">
					<?php
					webinarignition_display(
						$webinar_data->auto_translate_subheadline1,
						__( 'Select a date that best suits your schedule...', 'webinarignition' )
					);
					?>
				</h5>
			</label>
			<?php
		}//end if
		?>
		<select id="webinar_start_date" class="wiFormControl">
			<option value="none"><?php esc_html_e( 'Loading Times...', 'webinarignition' ); ?></option>
		</select>
	</div>

	<div class="autoSep" <?php echo 'yes' === $webinar_data->auto_today ? 'style="display: none;"' : ''; ?> ></div>
	<div id="webinarTime" <?php echo 'yes' === $webinar_data->auto_today ? 'style="display: none;"' : ''; ?> >
		<div class="wiFormGroup wiFormGroup-lg">
			<?php
			if ( ! $is_compact ) {
				?>
				<label for="webinar_start_time">
					<h4 class="autoTitle"><?php webinarignition_display( $webinar_data->auto_translate_headline2, __( 'What Time Is Best For You?', 'webinarignition' ) ); ?></h4>
				</label>
				<?php
			}
			?>

			<select id="webinar_start_time" class="wiFormControl">
				<?php

				$webinar_times = array();

				if ( isset( $webinar_data->auto_time_1 ) && 'no' !== $webinar_data->auto_time_1 ) {
					$webinar_times[] = $webinar_data->auto_time_1;
				}

				if ( isset( $webinar_data->auto_time_2 ) && 'no' !== $webinar_data->auto_time_2 ) {
					$webinar_times[] = $webinar_data->auto_time_2;
				}

				if ( isset( $webinar_data->auto_time_3 ) && 'no' !== $webinar_data->auto_time_3 ) {
					$webinar_times[] = $webinar_data->auto_time_3;
				}

				$is_multiple_auto_time_enabled = WebinarignitionPowerups::webinarignition_is_multiple_auto_time_enabled( $webinar_data );

				if ( $is_multiple_auto_time_enabled && ! empty( $webinar_data->multiple__auto_time ) ) {
					foreach ( $webinar_data->multiple__auto_time as $index => $item ) {
						if ( 'no' !== $item ) {
							$webinar_times[] = $item;
						}
					}
				}

				$webinar_times = array_unique( $webinar_times );

				usort(
					$webinar_times,
					function ( $time1, $time2 ) {
						return ( strtotime( $time1 ) < strtotime( $time2 ) ) ? -1 : 1;
					}
				);

				foreach ( $webinar_times as $index => $item ) {
					printf(
						'<option value="%s">%s</option>',
						esc_html( $item ),
						esc_html( webinarignition_auto_custom_time( $webinar_data, $item ) )
					);
				}
				?>
			</select>
		</div>
	</div>
	<input
		type="hidden"
		id="timezone_user"
		value="<?php echo 'fixed' === $webinar_data->auto_timezone_type ? esc_html( $webinar_data->auto_timezone_custom ) : ''; ?>"
	>
	<input type="hidden" id="today_date" value="<?php echo esc_html( gmdate( 'Y-m-d' ) ); ?>">
</div>

<?php
WebinarignitionManager::webinarignition_restore_locale( $webinar_data );

$order_id = WebinarignitionManager::webinarignition_is_paid_webinar( $webinar_data ) && WebinarignitionManager::webinarignition_get_paid_webinar_type( $webinar_data ) === 'woocommerce' && WebinarignitionManager::webinarignition_url_has_valid_wc_order_id();
global $wpdb;

if ( $order_id ) {
	$user = WebinarignitionManager::webinarignition_get_user_from_wc_order_id();
} else {
	$user = wp_get_current_user();
}

$selected_date     = null;
$selected_time     = null;
$selected_datetime = null;
$user_id           = 0;
if ( ! empty( $user ) && isset( $user->user_email ) && ! empty( $user->user_email ) ) {
	$user_id = $user->ID;
}