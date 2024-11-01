<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div id="listapps" class="dashList wi-list-apps">

	<div id="appHeader" class="dashHeaderListing">
		<span><i class="icon-dashboard" style="margin-right: 5px;"></i><?php esc_html_e( 'Manage All Of Your Webinars', 'webinarignition' ); ?>:</span>
	</div>


	<div class="wi-webinar-wrap">
		<?php

		// Display Apps:
		global $wpdb;
		$table_db_name = $wpdb->prefix . 'webinarignition';
		$query         = "(SELECT * FROM $table_db_name )";
		$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM %i", $table_db_name ), OBJECT );
		foreach ( $results as $results ) {
			// Get Date // Date
			$ID           = $results->ID;
			$results2     = WebinarignitionManager::webinarignition_get_webinar_data( $ID );
			$webinar_icon = isset( $results2->webinar_date ) && 'AUTO' === $results2->webinar_date ? 'refresh' : 'microphone';
			$webinar_url  = add_query_arg(
				array(
					'page' => 'webinarignition-dashboard',
					'id'   => $ID,
				),
				admin_url( 'admin.php' )
			);
			?>
			<div class="wi-webinar editableSectionHeading editableSectionHeadingDASH" webinarID="<?php echo absint( $results->ID ); ?>" editsection="we_edit_webinar_settings" style="margin-right: 0px; margin-left: 0px;">

				<a href="<?php echo esc_url( $webinar_url ); ?>">
					<div class="webinar-icon-title-wrap">
						<span class="editableSectionIcon">
							<i class="icon-<?php echo esc_attr( $webinar_icon ); ?> icon-2x"></i>
						</span>

						<span class="editableSectionTitle editableSectionTitleDash ">
							<span>
								<span class="editableSectionWebinarTitle" title="<?php echo esc_attr( $results->appname ); ?>"><?php echo esc_attr( $results->appname ); ?></span>
								<span class="editableSectionTitleSmall"><strong><?php esc_html_e( 'Created', 'webinarignition' ); ?>:</strong> <?php echo esc_html( $results->created ); ?></span>
							</span>

							<span class="appedit">
								<?php
								// Get Total Leads
								if ( isset( $results2->webinar_date ) && 'AUTO' === $results2->webinar_date ) {
									$table_db_name = $wpdb->prefix . 'webinarignition_leads_evergreen';
									// Sanitize input values
									$ID = intval( $ID ); // Ensure $ID is an integer

									// Prepare and execute the query
									$leads = $wpdb->get_results(
										$wpdb->prepare(
											"SELECT * FROM `{$table_db_name}` WHERE `app_id` = %d",
											$ID
										),
										OBJECT
									);
								} else {
									$table_db_name = $wpdb->prefix . 'webinarignition_leads';
									// Sanitize input values
									$ID = intval( $ID ); // Ensure $ID is an integer

									// Prepare and execute the query
									$leads = $wpdb->get_results(
										$wpdb->prepare(
											"SELECT * FROM `{$table_db_name}` WHERE `app_id` = %d",
											$ID
										),
										OBJECT
									);
								}//end if

								$totalLeads = count( $leads );
								$totalLeads = number_format( $totalLeads );

								?>
								<?php
								if ( isset( $results2->webinar_date ) && 'AUTO' === $results2->webinar_date ) {
									// Auto Webinar
									?>
									<span class="ctrl" style="margin-right: 6px;">EVERGREEN</span>
									<?php
								} else {
									// Live Webinar
									?>
									<span class="ctrl" style="margin-right: 6px;"><span style="font-weight:normal;">Webinar Date:</span> <?php echo isset( $results2->webinar_date ) ? esc_html( $results2->webinar_date ) : ''; ?></span>
									<?php
								}
								?>
								<span class="ctrl" style="margin-right: 6px;"><span style="font-weight:normal;"><?php esc_html_e( 'Total Registrants', 'webinarignition' ); ?>:</span> <?php echo esc_html( $totalLeads ); ?></span>
							</span>
						</span>
					</div>

					<span class="editableSectionToggle">
						<?php if ( ! empty( $results2->webinar_status ) && ( 'draft' === $results2->webinar_status ) ) : ?>
							<i class="toggleIcon icon-edit-sign icon-2x" title="<?php esc_html_e( 'Draft', 'webinarignition' ); ?>"></i>
						<?php else : ?>
							<i class="toggleIcon icon-edit-sign icon-2x published" title="<?php esc_html_e( 'Published', 'webinarignition' ); ?>"></i>
						<?php endif; ?>
					</span>


				</a>
			</div>
			<?php
		}//end foreach
		?>
	</div>

	<div class="appnew">
		<a href="<?php echo esc_url( admin_url( '?page=webinarignition-dashboard&create' ) ); ?>">
			<div class="blue-btn-2 btn newWebinarBTN wi-btn-new-webinar">
					<i class="icon-plus-sign" style="margin-right: 5px;"></i>
					<?php esc_html_e( 'Create a New Webinar', 'webinarignition' ); ?>
			</div>
		</a>
		<br clear="right">
	</div>
</div>

<br clear="left">