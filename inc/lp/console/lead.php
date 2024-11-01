<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
?>
<!-- ON AIR AREA -->
<div id="leadTab" style="display:none;" class="consoleTabs">
	<div class="statsDashbord">
		<div class="statsTitle statsTitle-Lead">
			<div class="statsTitleIcon">
				<i class="icon-group icon-2x"></i>
			</div>

			<div class="statsTitleCopy">
				<h2><?php 
esc_html_e( 'Manage Registrants For Webinar', 'webinarignition' );
?></h2>

				<p><?php 
esc_html_e( 'All your Registrants / Leads for the event...', 'webinarignition' );
?></p>
			</div>

			<br clear="left"/>
		</div>
	</div>

	
	<?php 
?>
	<div class="container">
		<?php 
Webinar_Ignition_Notices_Manager::webinarignition_display_pro_notice();
?>
	</div>
	<?php 
?>
</div>

