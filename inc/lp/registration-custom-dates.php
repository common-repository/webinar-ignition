<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="eventDate" style="border:none; margin:0px; padding: 0 0 10px 0;">
	<span class="autoTitle">
		<?php
		webinarignition_display(
			$webinar_data->auto_translate_headline1,
			__( 'Choose a Date To Attend... ', 'webinarignition' )
		);
		?>
	</span>
	<span class="autoSubTitle">
		<?php
		webinarignition_display(
			$webinar_data->auto_translate_subheadline1,
			__( 'Select a date that best suits your schedule...', 'webinarignition' )
		);
		?>
	</span>

	<select id="webinar_start_date">
		<option value="none"><?php esc_html_e( 'Loading Times...', 'webinarignition' ); ?></option>
	</select>

	<div class="autoSep" <?php echo 'yes' === $webinar_data->auto_today ? 'style="display: none;"' : ''; ?> ></div>
	<div id="webinarTime" <?php echo 'yes' === $webinar_data->auto_today ? 'style="display: none;"' : ''; ?> >
		<span class="autoTitle"><?php webinarignition_display( $webinar_data->auto_translate_headline2, __( 'What Time Is Best For You?', 'webinarignition' ) ); ?></span>
		<select id="webinar_start_time">
			<?php
			if ( 'no' !== $webinar_data->auto_time_1 ) {
				printf(
					'<option value="%s">%s</option>',
					esc_html( $webinar_data->auto_time_1 ),
					esc_html( webinarignition_auto_custom_time( $webinar_data, $webinar_data->auto_time_1 ) )
				);
			}

			if ( 'no' !== $webinar_data->auto_time_2 ) {
				printf(
					'<option value="%s">%s</option>',
					esc_html( $webinar_data->auto_time_2 ),
					esc_html( webinarignition_auto_custom_time( $webinar_data, $webinar_data->auto_time_2 ) )
				);
			}

			if ( 'no' !== $webinar_data->auto_time_3 ) {
				printf(
					'<option value="%s">%s</option>',
					esc_html( $webinar_data->auto_time_3 ),
					esc_html( webinarignition_auto_custom_time( $webinar_data, $webinar_data->auto_time_3 ) )
				);
			}
			?>
		</select>
	</div>
	<input type="hidden" id="timezone_user" value="<?php echo 'fixed' === $webinar_data->auto_timezone_type ? esc_html( $webinar_data->auto_timezone_custom ) : ''; ?>">
	<input type="hidden" id="today_date" value="<?php echo esc_html( gmdate( 'Y-m-d' ) ); ?>">
</div>
