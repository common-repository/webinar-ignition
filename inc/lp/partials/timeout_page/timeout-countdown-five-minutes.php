<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$time_limit = 300;
$is_preview = WebinarignitionManager::webinarignition_url_is_preview_page();

if (isset($_GET['lid'])) {
    $lead_id = sanitize_text_field($_GET['lid']);
    $watch_time = (int) get_option('wi_lead_watch_time_' . $lead_id, true);

    if ('ultimate_powerup_tier1a' === $statusCheck->name) {
        $watch_limit = MINUTE_IN_SECONDS * 45;
    } else {
        $watch_limit = MINUTE_IN_SECONDS * 45;
    }

    if ($watch_limit - $watch_time > 300) {
        $time_limit = 300;
    } else {
        $time_limit = $watch_limit - $watch_time;
    }
}


// Enqueue custom script
wp_enqueue_script('webinarignition-frontend-countdown');

// Localize script to pass PHP variables to JavaScript
wp_localize_script('webinarignition-frontend-countdown', 'wi_data', array(
    'time_limit' => intval($watch_limit),
    'is_preview' => $is_preview,
    'user_can_edit' => current_user_can('edit_posts'),
    'webinar_live_video' => isset($webinar_data->webinar_live_video),
    'status_name' => $statusCheck->name,
    'watch_limit' => isset($watch_limit) ? esc_attr($watch_limit) : 0,
    'active_plugins' => apply_filters('active_plugins', get_option('active_plugins')),
));

?>
<style type="text/css">
#wi_count_down_5_mint {
    position: absolute;
    left: 0;
    top: 0;
    z-index: 9999999;
}

.base-timer {
    position: relative;
    width: 100px;
    height: 100px;
    background: rgba(0, 0, 0, 0.5);
    border-radius: 100px;
}

.base-timer__svg {
    transform: scaleX(-1);
}

.base-timer__circle {
    fill: none;
    stroke: none;
}

.base-timer__path-elapsed {
    stroke-width: 7px;
    stroke: grey;
}

.base-timer__path-remaining {
    stroke-width: 7px;
    stroke-linecap: round;
    transform: rotate(90deg);
    transform-origin: center;
    transition: 1s linear all;
    fill-rule: nonzero;
    stroke: currentColor;
}

.base-timer__path-remaining.green {
    color: rgb(65, 184, 131);
}

.base-timer__path-remaining.orange {
    color: orange;
}

.base-timer__path-remaining.red {
    color: red;
}

.base-timer__label {
    position: absolute;
    width: 100px;
    height: 100px;
    top: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
}
</style>
<div id="wi_count_down_5_mint" style="display:none;"></div>
