<?php
/**
 * Plugin Name:       Branda Pro Email Enhancer  BPEH
 * Description:       A simple plugin to manage settings for the Branda Pro email template.
 * Version:           1.3.2
 * Author:            James @ Heypressgo.com
 * Author URL:        https://heyressgo.com
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// 1. Create the Admin Menu Item
function fwbe_add_admin_menu() {
    add_options_page('Branda Email Enhancer', 'Branda Email Enhancer', 'manage_options', 'branda_email_enhancer', 'fwbe_options_page_html');
}
add_action('admin_menu', 'fwbe_add_admin_menu');

// 2. Register Settings, Sections, and Fields
function fwbe_settings_init() {
    register_setting('fwbe_options_group', 'fwbe_options');

    // Section for Header/Logo
    add_settings_section('fwbe_header_section', 'Header & Logo', null, 'branda_email_enhancer');
    add_settings_field('fwbe_logo_url', 'Email Logo', 'fwbe_field_logo_url_cb', 'branda_email_enhancer', 'fwbe_header_section');

    // Section for Sender Details
    add_settings_section('fwbe_sender_section', 'Sender Details', null, 'branda_email_enhancer');
    add_settings_field('fwbe_from_name', 'From Name', 'fwbe_field_from_name_cb', 'branda_email_enhancer', 'fwbe_sender_section');
    add_settings_field('fwbe_from_email', 'From Email', 'fwbe_field_from_email_cb', 'branda_email_enhancer', 'fwbe_sender_section');
    
    // Section for Footer Content
    add_settings_section('fwbe_footer_section', 'Footer Content', null, 'branda_email_enhancer');
    add_settings_field('fwbe_footer_line_1', 'Footer Line 1', 'fwbe_field_footer_line_1_cb', 'branda_email_enhancer', 'fwbe_footer_section');
    add_settings_field('fwbe_footer_line_2', 'Footer Line 2 (Address)', 'fwbe_field_footer_line_2_cb', 'branda_email_enhancer', 'fwbe_footer_section');
    add_settings_field('fwbe_footer_line_3', 'Footer Line 3 (Phone)', 'fwbe_field_footer_line_3_cb', 'branda_email_enhancer', 'fwbe_footer_section');

    // Section for Styling
    add_settings_section('fwbe_styling_section', 'Styling Options', null, 'branda_email_enhancer');
    add_settings_field('fwbe_background_color', 'Email Background Color', 'fwbe_field_background_color_cb', 'branda_email_enhancer', 'fwbe_styling_section');
}
add_action('admin_init', 'fwbe_settings_init');

// 3. Field Callback Functions (How the fields are displayed)
function fwbe_field_logo_url_cb() {
    $options = get_option('fwbe_options');
    $logo_url = isset($options['logo_url']) ? $options['logo_url'] : '';
    ?>
    <div style="margin-bottom: 10px;">
        <img id="fwbe-logo-preview" src="<?php echo esc_url($logo_url); ?>" style="max-width: 200px; max-height: 80px; display: <?php echo $logo_url ? 'block' : 'none'; ?>; border: 1px solid #ddd; padding: 5px; background: #fff;">
    </div>
    <input type="hidden" id="fwbe_logo_url" name="fwbe_options[logo_url]" value="<?php echo esc_url($logo_url); ?>">
    <button type="button" class="button fwbe-upload-logo-button">Select/Upload Logo</button>
    <button type="button" class="button button-secondary fwbe-remove-logo-button" style="display: <?php echo $logo_url ? 'inline-block' : 'none'; ?>;">Remove Logo</button>
    <p class="description">Select the logo to display at the top of your emails.</p>
    <?php
}

function fwbe_field_from_name_cb() {
    $options = get_option('fwbe_options');
    $value = isset($options['from_name']) ? $options['from_name'] : get_bloginfo('name');
    echo '<input type="text" id="fwbe_from_name" name="fwbe_options[from_name]" value="' . esc_attr($value) . '" class="regular-text">';
}

function fwbe_field_from_email_cb() {
    $options = get_option('fwbe_options');
    $value = isset($options['from_email']) ? $options['from_email'] : get_bloginfo('admin_email');
    echo '<input type="email" name="fwbe_options[from_email]" value="' . esc_attr($value) . '" class="regular-text">';
}

function fwbe_field_footer_line_1_cb() {
    $options = get_option('fwbe_options');
    $value = isset($options['footer_line_1']) ? $options['footer_line_1'] : "This email is a transactional email only from the Warehouse website.";
    echo '<input type="text" id="fwbe_footer_line_1" name="fwbe_options[footer_line_1]" value="' . esc_attr($value) . '" class="regular-text">';
}

function fwbe_field_footer_line_2_cb() {
    $options = get_option('fwbe_options');
    $value = isset($options['footer_line_2']) ? $options['footer_line_2'] : "1/2103 Darley St, Mona Vale NSW 2103";
    echo '<input type="text" id="fwbe_footer_line_2" name="fwbe_options[footer_line_2]" value="' . esc_attr($value) . '" class="regular-text">';
}

function fwbe_field_footer_line_3_cb() {
    $options = get_option('fwbe_options');
    $value = isset($options['footer_line_3']) ? $options['footer_line_3'] : "(02) 9999 9999";
    echo '<input type="text" id="fwbe_footer_line_3" name="fwbe_options[footer_line_3]" value="' . esc_attr($value) . '" class="regular-text">';
}

function fwbe_field_background_color_cb() {
    $options = get_option('fwbe_options');
    $value = isset($options['background_color']) ? $options['background_color'] : '#f6f9fc';
    echo '<input type="text" id="fwbe_background_color" name="fwbe_options[background_color]" value="' . esc_attr($value) . '" class="color-picker">';
}

// Enqueue WordPress admin scripts (Color Picker & Media Uploader) and add styles
function fwbe_enqueue_admin_scripts($hook_suffix) {
    if ('settings_page_branda_email_enhancer' != $hook_suffix) return;
    
    // Enqueue required scripts
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_media();
    wp_enqueue_script('fwbe-media-uploader', plugin_dir_url(__FILE__) . 'media-uploader.js', ['jquery', 'wp-color-picker'], '1.2', true);

    // Add inline styles for the settings page layout and preview pane
    $custom_css = "
        .fwbe-settings-wrap { display: flex; flex-wrap: wrap; gap: 30px; }
        .fwbe-settings-form { flex: 2; min-width: 450px; }
        .fwbe-preview-pane { flex: 1; min-width: 320px; background-color: #f0f0f1; padding: 20px; border-radius: 4px; position: sticky; top: 50px; }
        .fwbe-preview-pane h2 { margin-top: 0; padding-bottom: 10px; border-bottom: 1px solid #ddd; }
        .email-preview-wrap { padding: 20px; text-align: center; font-family: Helvetica, Arial, sans-serif; transition: background-color 0.3s ease; border: 1px solid #ddd; }
        .email-preview-logo { max-width: 150px; margin: 0 auto 20px auto; display: block; padding-top: 20px; }
        .email-preview-content { background-color: #ffffff; padding: 20px; text-align: left; font-size: 14px; line-height: 1.5; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 20px; margin-left: 20px; margin-right: 20px; /* Added margin */ }
        .email-preview-footer { font-size: 10px; color: #8898aa; padding: 0; }
    ";
    wp_add_inline_style('wp-admin', $custom_css);
}
add_action('admin_enqueue_scripts', 'fwbe_enqueue_admin_scripts');


// 4. The Settings Page HTML
function fwbe_options_page_html() {
    $options = get_option('fwbe_options');
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <div class="fwbe-settings-wrap">

            <div class="fwbe-settings-form">
                <form action="options.php" method="post">
                    <?php
                    settings_fields('fwbe_options_group');
                    do_settings_sections('branda_email_enhancer');
                    submit_button('Save Settings');
                    ?>
                </form>
            </div>

            <div class="fwbe-preview-pane">
                <h2>Live Preview</h2>
                <div id="email-preview-wrap" style="background-color: <?php echo esc_attr($options['background_color'] ?? '#f6f9fc'); ?>">
                    <img id="preview-logo" class="email-preview-logo" src="<?php echo esc_url($options['logo_url'] ?? ''); ?>" alt="Logo Preview" style="display: <?php echo empty($options['logo_url']) ? 'none' : 'block'; ?>;">
                    <div class="email-preview-content">
                        <p>Hi Mr Trump,</p>
                        <p>This is where the main content of your email will go. The design is controlled by your settings.</p>
                        <p>Thanks,<br>The <span id="preview-from-name"><?php echo esc_html($options['from_name'] ?? get_bloginfo('name')); ?></span> team</p>
                    </div>
                    <div class="email-preview-footer">
                        <p id="preview-footer-1" style="margin: 5px 0;"><?php echo esc_html($options['footer_line_1'] ?? ''); ?></p>
                        <p id="preview-footer-2" style="margin: 5px 0;"><?php echo esc_html($options['footer_line_2'] ?? ''); ?></p>
                        <p id="preview-footer-3" style="margin: 5px 0;"><?php echo esc_html($options['footer_line_3'] ?? ''); ?></p>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php
}

// 5. Filter the Email Content and Headers
function fwbe_filter_email_content($args) {
    $options = get_option('fwbe_options');
    $message = $args['message'];

    // Default values
    $logo_url = !empty($options['logo_url']) ? $options['logo_url'] : '';
    $bg_color = !empty($options['background_color']) ? $options['background_color'] : '#f6f9fc';
    $footer_1 = !empty($options['footer_line_1']) ? $options['footer_line_1'] : '';
    $footer_2 = !empty($options['footer_line_2']) ? $options['footer_line_2'] : '';
    $footer_3 = !empty($options['footer_line_3']) ? $options['footer_line_3'] : '';

    $find = [
        '{{fw_logo_url}}',
        '{{fw_background_color}}',
        '{{fw_footer_line_1}}',
        '{{fw_footer_line_2}}',
        '{{fw_footer_line_3}}',
    ];
    $replace = [
        esc_url($logo_url),
        esc_attr($bg_color),
        esc_html($footer_1),
        esc_html($footer_2),
        esc_html($footer_3),
    ];
    
    $args['message'] = str_replace($find, $replace, $message);
    return $args;
}
add_filter('wp_mail', 'fwbe_filter_email_content', 20);

function fwbe_filter_from_name($original_name) {
    $options = get_option('fwbe_options');
    return !empty($options['from_name']) ? $options['from_name'] : $original_name;
}
add_filter('wp_mail_from_name', 'fwbe_filter_from_name', 20);

function fwbe_filter_from_email($original_email) {
    $options = get_option('fwbe_options');
    return !empty($options['from_email']) ? $options['from_email'] : $original_email;
}
add_filter('wp_mail_from', 'fwbe_filter_from_email', 20);

// --- 6. Plugin Update Checker (from GitHub) ---
// This code enables automatic updates from a GitHub repository.

// Make sure you have downloaded the 'puc' library and placed it in this plugin's folder.
require_once( plugin_dir_path( __FILE__ ) . 'puc/plugin-update-checker.php' );
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
    'https://github.com/heypressgo/fw-branda-enhancer', // <-- IMPORTANT: Change this URL
    __FILE__, // Full path to this plugin's main file
    'fw-branda-enhancer' // The plugin's slug (folder name)
);

// Optional: For PRIVATE GitHub repos, uncomment the line below and add your token.
// $myUpdateChecker->setAuthentication('YOUR_PERSONAL_ACCESS_TOKEN_HERE');
