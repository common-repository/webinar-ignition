<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @var $webinar_data
 * @var $assets
 * @var $paid_check
 * @var $loginUrl
 * @var $user - Who is user
 */
?>

<?php

/**
 * The Below code is fully copied from auto-register.php
 * no changes made in functionality only improve the UI of the box that took verification code from the user
 *
 * If the login attribute contains false or something else
 * I will prefer the functionality of previous developers
 */
$email = '';
$user_full_name = '';
$user_first_name = '';
$user_last_name = '';
$webinar_user_email = '';

// Only get the required values from INPUT_GET
if ( isset( $_GET['login'] ) && wp_validate_boolean( $_GET['login'] ) ) { // phpcs:ignore
    $email = trim( sanitize_text_field( $_GET['e'] ) );
}

$webinar_user_email = ( isset( $_GET['email'] ) && ! empty( $_GET['email'] ) ) ? trim( sanitize_text_field( $_GET['email'] ) ) : '';
$user_full_name = ( isset( $_GET['first'] ) && ! empty( $_GET['first'] ) ) ? trim( sanitize_text_field( $_GET['first'] ) ) : '';
$user_full_name = is_email( $webinar_user_email ) ? $user_full_name : base64_decode( $user_full_name );
$webinar_user_email = is_email( $webinar_user_email ) ? $webinar_user_email : base64_decode( $webinar_user_email );

$order_id = WebinarignitionManager::webinarignition_is_paid_webinar( $webinar_data ) && 
            WebinarignitionManager::webinarignition_get_paid_webinar_type( $webinar_data ) === 'woocommerce' && 
            WebinarignitionManager::webinarignition_url_has_valid_wc_order_id();
$disable_reg_fields = false;
if ( $order_id ) {
    $disable_reg_fields = true;
    $user = WebinarignitionManager::webinarignition_get_user_from_wc_order_id();
} elseif ( is_user_logged_in() ) {
    $user = wp_get_current_user();
}

if ( ! empty( $user ) ) {
    $user_full_name  = $user->display_name;
    $user_first_name = $user->first_name;
    $user_last_name  = $user->last_name;
    if ( empty( $webinar_user_email ) ) {
        $webinar_user_email = $user->user_email;
    }
}

$WPreadOnlyMethod = 'wp_readonly';
if ( ! function_exists( $WPreadOnlyMethod ) ) {
    $WPreadOnlyMethod = 'readonly';
}
$user_full_name = ( isset( $_GET['n'] ) && ! empty( $_GET['n'] ) ) ? trim( sanitize_text_field( $_GET['n'] ) ) : $user_full_name;
$webinar_user_email = ( isset( $_GET['e'] ) && ! empty( $_GET['e'] ) ) ? trim( sanitize_text_field( $_GET['e'] ) ) : $webinar_user_email;

if ( ! empty( $webinar_data->ar_fields_order ) && is_array( $webinar_data->ar_fields_order ) ) {
    $alreadyAddedFields = array();
    $wi_showingGDPRHeading = false;

    foreach ( $webinar_data->ar_fields_order as $_field ) {
        if ( in_array( $_field, $alreadyAddedFields ) ) {
            continue;
        }
        $alreadyAddedFields[] = $_field;

        switch ( $_field ) {
            case 'ar_name':
                $required = ( isset( $webinar_data->ar_required_fields ) && is_array( $webinar_data->ar_required_fields ) && in_array( 'ar_name', $webinar_data->ar_required_fields ) ) ? true : false;

                if ( ! in_array( 'ar_lname', $webinar_data->ar_fields_order ) ) {
                    ?>
                    <div class="wiFormGroup wiFormGroup-lg">
                        <input type="text" 
                            class="radius fieldRadius wiRegForm wiFormControl <?php echo $required ? ' required' : ''; ?>" 
                            id="optName"
                            placeholder="<?php
                            webinarignition_display( $webinar_data->lp_optin_name, __( 'Enter Your Full Name...', 'webinarignition' ) );
                            echo $required ? '*' : '';
                            ?>"
                            value="<?php echo esc_html( $user_full_name ); ?>" 
                            autocomplete="off"
                        >
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="wiFormGroup wiFormGroup-lg">
                        <input type="text" 
                            class="radius fieldRadius wiRegForm optNamer wiFormControl <?php echo $required ? 'required' : ''; ?>" 
                            id="optFName" 
                            placeholder="<?php
                            webinarignition_display( $webinar_data->lp_optin_name, __( 'Enter Your First Name...', 'webinarignition' ) );
                            echo $required ? '*' : '';
                            ?>"
                            value="<?php echo esc_html( $user_first_name ); ?>" 
                            autocomplete="off"
                        >
                    </div>
                    <?php
                }
                break;
            case 'ar_lname':
                $required = ( isset( $webinar_data->ar_required_fields ) && is_array( $webinar_data->ar_required_fields ) && in_array( 'ar_lname', $webinar_data->ar_required_fields, true ) ) ? true : false;
                ?>
                <div class="wiFormGroup wiFormGroup-lg">
                    <input type="text" <?php $WPreadOnlyMethod( $disable_reg_fields, true, true ); ?> class="radius fieldRadius wiRegForm optNamer wiFormControl <?php echo $required ? 'required' : ''; ?>" id="optLName"
                            placeholder="<?php
                            webinarignition_display( $webinar_data->lp_optin_lname, __( 'Enter Your Last Name...', 'webinarignition' ) );
                            echo $required ? '*' : '';
                            ?>"
                            value="<?php echo esc_html( $user_last_name ); ?>" autocomplete="off" >
                </div>
                <input type="hidden" id="optName" value="#firstlast#">
                <?php
                break;
            case 'ar_email':
                // Checking if the current shortcode is autofill registration block.
                // Checking if the email is readonly.
                global $webinarignition_shortcode_params;

                /**
                 * The email readonly should check from url instead of from webinar data.
                 */
                $readonly_email = ( isset( $_GET['readonly'] ) && ! empty( $_GET['readonly'] ) ) ? filter_var( trim( sanitize_text_field( $_GET['readonly'] ) ), FILTER_VALIDATE_BOOLEAN ) : false;

                if ( ! empty( $webinarignition_shortcode_params[ $webinar_data->id ] ) && ! empty( $webinarignition_shortcode_params[ $webinar_data->id ]['block'] ) ) {
                    $readonly_email = wp_validate_boolean( $webinarignition_shortcode_params[ $webinar_data->id ]['readonly'] );
                }
                ?>
                <div class="wiFormGroup wiFormGroup-lg">
                    <input type="email" 
                        class="radius fieldRadius wiRegForm wiFormControl" 
                        id="optEmail" 
                        placeholder="<?php
                        webinarignition_display(
                            $webinar_data->lp_optin_email,
                            __( 'Enter Your Best Email...', 'webinarignition' )
                        );
                        ?>*"
                        value="<?php echo esc_html( $webinar_user_email ); ?>" 
                        autocomplete="off"
                        required
                    >
                </div>
                <?php
                break;
            case 'ar_phone':
                ?>
                <div class="wiFormGroup wiFormGroup-lg">
                    <input type="tel" class="radius fieldRadius wiRegForm wi_phone_number wiFormControl <?php echo ( isset( $webinar_data->ar_required_fields ) && is_array( $webinar_data->ar_required_fields ) && in_array( 'ar_phone', $webinar_data->ar_required_fields, true ) ) ? ' required' : ''; ?>" id="optPhone"
                            placeholder="<?php
                            webinarignition_display( $webinar_data->lp_optin_phone, __( 'Enter Your Phone Number...', 'webinarignition' ) );
                            echo ( isset( $webinar_data->ar_required_fields ) && is_array( $webinar_data->ar_required_fields ) && in_array( 'ar_phone', $webinar_data->ar_required_fields, true ) ) ? '*' : '';
                            ?>"
                    >
                </div>
                <?php
                break;

            // Additional cases for custom fields and GDPR checkboxes would follow here...

            default:
                break;
        }
    }

    webinarignition_closeGDPRSection();
}

if ( empty( $webinar_data->lp_optin_button ) || 'color' === trim( $webinar_data->lp_optin_button ) ) {
    ?>
    <button href="#" id="optinBTN" class="large button wiButton wiButton-block wiButton-lg addedArrow">
        <span id="optinBTNText">
            <?php webinarignition_display( $webinar_data->lp_optin_btn, __( 'Register For The Webinar', 'webinarignition' ) ); ?>
        </span>

        <span id="optinBTNLoading" style="display: none;" >
            <img src="<?php echo esc_url( WEBINARIGNITION_URL . 'inc/lp/images/loading_dots_cropped_small.gif' ); ?>" style="width: auto; height: 20px;"/>
        </span>
    </button>
    <?php
} else {
    ?>
    <a href="#" id="optinBTN" class="optinBTN optinBTNimg">
        <img src="<?php echo esc_url( $webinar_data->lp_optin_btn_image ); ?>" width="327" border="0"/>
    </a>
    <?php
}
?>
<div class="spam wiSpamMessage">
    <?php webinarignition_display( $webinar_data->lp_optin_spam, __( '* Your data is safe with us *', 'webinarignition' ) ); ?>
</div>
<?php if ( get_option( 'webinarignition_show_footer_branding' ) ) { ?>
    <div class="powered_by_text_wrap" style="margin-top: 15px;"><a href="<?php echo esc_url( get_option( 'webinarignition_affiliate_link' ) ); ?>"  target="_blank"><b><?php echo esc_html( get_option( 'webinarignition_branding_copy' ) ); ?></b></a> </div>
<?php } ?>
</div>
