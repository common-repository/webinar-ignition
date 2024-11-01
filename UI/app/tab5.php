<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="tabber" id="tab5" style="display: none;">

<div class="titleBar">

	<div class="titleBarText">
		<h2><?php esc_html_e( 'Webinar Replay Settings:', 'webinarignition' ); ?></h2>

		<p><?php esc_html_e( 'Here you can manage the settings for the webinar...', 'webinarignition' ); ?></p>
	</div>

	<?php
	$replay_preview_url = add_query_arg( array(
		'replay' => '',
		'lid' => '[lead_id]',
		'preview' => 'true',
	), get_the_permalink( $data->postID ) );
	?>

	<div class="launchConsole" style="margin-right: -20px;">
		<a
				href="<?php echo esc_url( $replay_preview_url ); ?>"
				target="_blank"
				data-default-href="<?php echo esc_url( $replay_preview_url ); ?>"
				class="custom_replay_page-webinarPreviewLinkDefaultHolder"
		>
			<i class="icon-external-link-sign"></i>
			<?php esc_html_e( 'Preview Webinar Replay', 'webinarignition' ); ?>
		</a>
	</div>

	<br clear="all"/>
	<?php 
	$input_get = array(
		'id' => filter_input(INPUT_GET, 'id', FILTER_UNSAFE_RAW)
	); 
	?>
</div>


<?php

if ( $webinar_data->webinar_date != 'AUTO' ) {

	webinarignition_display_edit_toggle(
		'film',
		esc_html__( 'Replay Video', 'webinarignition' ),
		'we_edit_replay_video',
		esc_html__( 'Setup for the video that is played on the webinar replay page...', 'webinarignition' )
	);

}

?>

<div id="we_edit_replay_video" class="we_edit_area">
	<?php
	webinarignition_display_textarea(
		$input_get['id'],
		$webinar_data->replay_video,
		esc_html__( 'Replay Video', 'webinarignition' ),
		'replay_video',
		esc_html__( 'This is the embed code for the video for the webinar replay...', 'webinarignition' ),
		esc_html__( 'Ex. Video embed code / iframe code', 'webinarignition' )
	);
	webinarignition_display_info(
		esc_html__( 'Note: Video Embed Code', 'webinarignition' ),
		esc_html__( 'If you are using Google Hangouts embed code, the same code they provide for the live boardcast will be the same code you enter here...  920px by 518px...', 'webinarignition' )
	);
	?>
</div>

<?php

webinarignition_display_edit_toggle(
	'time',
	esc_html__( 'Countdown - Expiring Replay', 'webinarignition' ),
	'we_edit_replay_cd',
	esc_html__( 'Settings for when the replay expires...', 'webinarignition' )
);

?>

<div id="we_edit_replay_cd" class="we_edit_area">
	<?php

	if ( $webinar_data->webinar_date == 'AUTO' ) {

		webinarignition_display_field(
			$input_get['id'],
			$webinar_data->auto_replay,
			esc_html__( 'Replay Availability', 'webinarignition' ),
			'auto_replay',
			__( 'The amount of time the auto replay is available for. Default its open for 3 days after the event. To disable replay, make it specify 00.<br/><strong>Disabling the replay will prevent Instant Webinar Access. Only disable the replay, if you are not using the instant access feature.</strong>', 'webinarignition' ),
			esc_html__( 'Eg. 3', 'webinarignition' ),
			'number'
		);

	} else {

		webinarignition_display_option(
			$input_get['id'],
			$webinar_data->replay_optional,
			esc_html__( 'Optional:: Countdown', 'webinarignition' ),
			'replay_optional',
			esc_html__( 'You can choose to show the countdown timer or hide it on your replay page...', 'webinarignition' ),
			esc_html__( 'Show Countdown Timer', 'webinarignition' ) . ' [show], ' . esc_html__( 'Hide Countdown Timer', 'webinarignition' ) . ' [hide]'
		);
		?>
		<div class="replay_optional" id="replay_optional_show">
			<?php
			webinarignition_display_date_picker(
				$input_get['id'],
				$webinar_data->replay_cd_date,
				'm-d-Y',
				esc_html__( 'Countdown Close Date', 'webinarignition' ),
				'replay_cd_date',
				esc_html__( 'This is the date the webinar goes down by, after this date, the replay page will be replaced with the closed page...', 'webinarignition' ),
				'MM-DD-YYYY',
				$webinar_date_format
			);
			webinarignition_display_field(
				$input_get['id'],
				$webinar_data->replay_cd_time,
				esc_html__( 'Countdown Close Time', 'webinarignition' ),
				'replay_cd_time',
				__( 'This is the time when the replay ends, <b>MUST BE IN 24 TIME, ie:  12:00 or 17:30</b>', 'webinarignition' ),
				'12:00'
			);

			?>
		</div>
		<div class="clear"></div>
		<?php
	}//end if
	webinarignition_display_field(
		$input_get['id'],
		$webinar_data->replay_cd_headline,
		esc_html__( 'Countdown Headline', 'webinarignition' ),
		'replay_cd_headline',
		esc_html__( 'This is the headline above the countdown area for how long the replay is live for...', 'webinarignition' ),
		esc_html__( 'Ex. This Replay Is Being Taken Down On Tuesday May 23rd', 'webinarignition' )
	);
	?>

</div>

<?php

webinarignition_display_edit_toggle(
	'money',
	esc_html__( 'Timed Action - Order Button', 'webinarignition' ),
	'we_edit_replay_timed',
	esc_html__( 'Setup the timed action - order button / html...', 'webinarignition' )
);

?>

<div id="we_edit_replay_timed" class="we_edit_area">
	<?php

	if ( $webinar_data->webinar_date == 'AUTO' ) { ?>
		<h3><?php esc_html_e( "Timed Actions From The 'Live' Webinar are used for the replay...", 'webinarignition' ); ?></h3>
		<?php
	} else {

		webinarignition_display_option(
			$input_get['id'],
			$webinar_data->replay_timed_style,
			esc_html__( 'Timed Action Style', 'webinarignition' ),
			'replay_timed_style',
			esc_html__( 'You can choose between a simple order button or custom HTML...', 'webinarignition' ),
			esc_html__( 'Order Button', 'webinarignition' ) . ' [button], ' . esc_html__( 'Custom HTML Copy', 'webinarignition' ) . ' [custom]'
		);
		?>
		<div class="replay_timed_style" id="replay_timed_style_button">
			<?php
			webinarignition_display_field(
				$input_get['id'],
				$webinar_data->replay_order_copy,
				esc_html__( 'Order Button Copy', 'webinarignition' ),
				'replay_order_copy',
				esc_html__( 'This is what the order button says...', 'webinarignition' ),
				esc_html__( 'Ex. Order Your Copy Now', 'webinarignition' )
			);
			webinarignition_display_field(
				$input_get['id'],
				$webinar_data->replay_order_url,
				esc_html__( 'Order URL', 'webinarignition' ),
				'replay_order_url',
				esc_html__( 'This is the URL where the order button will go...', 'webinarignition' ),
				esc_html__( 'Ex. http://yoursite.com/order-now', 'webinarignition' )
			);
			?>
		</div>
		<div class="replay_timed_style" id="replay_timed_style_custom">
			<?php
			webinarignition_display_wpeditor(
				$input_get['id'],
				$webinar_data->replay_order_html,
				esc_html__( 'Custom HTML Copy', 'webinarignition' ),
				'replay_order_html',
				esc_html__( 'This is custom html you can have for the timed area which will show under the replay...', 'webinarignition' )
			);
			?>
		</div>
		<?php
		webinarignition_display_field(
			$input_get['id'],
			$webinar_data->replay_order_time,
			esc_html__( 'Time For Button To Appear', 'webinarignition' ),
			'replay_order_time',
			esc_html__( 'This is the time in seconds you want the button to appear...', 'webinarignition' ),
			esc_html__( 'Ex. 60', 'webinarignition' )
		);

		webinarignition_display_info(
			esc_html__( 'Note: Timed Action', 'webinarignition' ),
			esc_html__( 'The timed action is in seconds, one second is 1, one minute would be 60, 15 minutes would be 900...', 'webinarignition' )
		);

	}//end if
	?>
</div>

<?php

webinarignition_display_edit_toggle(
	'remove-sign',
	esc_html__( 'Webinar Closed Copy', 'webinarignition' ),
	'we_edit_replay_closed',
	esc_html__( 'Copy / Settings for the closed page - when the replay has expired...', 'webinarignition' )
);

?>

<div id="we_edit_replay_closed" class="we_edit_area">
	<?php
	webinarignition_display_wpeditor(
		$input_get['id'],
		$webinar_data->replay_closed,
		esc_html__( 'Webinar Closed Copy', 'webinarignition' ),
		'replay_closed',
		esc_html__( 'This is the copy that is displayed when the countdown reaches zero, or when you select webinar closed in the main webinar control...', 'webinarignition' )
	);
	?>
</div>

<div class="bottomSaveArea">
	<a href="#" class="blue-btn-44 btn saveIt" style="color:#FFF;"><i class="icon-save"></i> <?php esc_html_e( 'Save & Update', 'webinarignition' ); ?></a>
</div>

</div>
