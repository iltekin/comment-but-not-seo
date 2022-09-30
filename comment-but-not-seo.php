<?php
/**
 * Plugin Name: Comment But Not SEO
 * Plugin URI: https://iltekin.com/projects/comment-but-not-seo-wordpress-plugin
 * Description: Displays a notice to the commenter not to use the URL field of comment form for their Search Engine Optimization
 * Version: 1.0.0
 * Text Domain: comment-but-not-seo
 * Author: Sezer Iltekin
 * Author URI: https://iltekin.com
 */

function cbns_css_and_js(){
    wp_enqueue_script('cbns-scripts', plugins_url('js/scripts.js',__FILE__ ), array('jquery'));
    wp_enqueue_style( 'cbns-style', plugins_url('css/style.css', __FILE__));
}

add_action('wp_enqueue_scripts', "cbns_css_and_js");

$display_options = ["block", "inline"];
$cbns_default_message = "Comments made for the purpose of SEO work are not approved.";

$cbns_message = get_option('cbns_message_input', $cbns_default_message);
$cbns_custom_css = get_option('cbns_custom_css_input', '.cbns_notice { }');
$cbns_display_attribute = get_option('cbns_display_attribute', 0);
$cbns_margin = get_option('cbns_margin', '10px');
$cbns_font_size = get_option('cbns_font_size', '12px');
$cbns_text_color = get_option('cbns_text_color', '#000000');

function cbns_createNotice($message) {
    global $display_options;
    global $cbns_message;
    global $cbns_text_color;
    global $cbns_font_size;
    global $cbns_margin;
    global $cbns_display_attribute;
    global $cbns_custom_css;

    echo "<script>var cbns_message = '<small class=\"cbns_notice\">" . esc_html($cbns_message) . "</small>';</script>";
    echo '<style> .cbns_notice { ';
    echo 'color: ' . esc_attr($cbns_text_color) . '; ';
    echo 'font-size: ' . esc_attr($cbns_font_size) . '; ';
    echo 'margin: ' . esc_attr($cbns_margin) . '; ';
    echo 'display: ' . esc_attr($display_options[$cbns_display_attribute]) . '; } ';
    echo esc_attr($cbns_custom_css).'</style>';
}


add_action( 'wp_head', 'cbns_createNotice' );

add_action( 'admin_menu', 'cbns_options_page' );

function cbns_options_page() {

    add_options_page(
        'Comment But Not SEO Settings', // page title
        'Comment But Not SEO Settings', // menu title
        'manage_options', // capability to access the page
        'cbns-settings', // menu slug
        'cbns_settings_page_content', // callback function
        5 // position
    );

}

function cbns_settings_page_content() {
    ?>
    <div class="wrap">
        <h1>Comment But Not SEO Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields( 'cbns_group' ); // settings group name
            do_settings_sections( 'cbns-settings' ); // a page slug
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

add_action( 'admin_init',  'cbns_register_settings' );

function cbns_register_settings() {

    add_settings_section(
        'homepage_section', // section ID
        '', // title
        '', // callback function
        'cbns-settings' // page slug
    );

    // 1st field
    add_settings_field(
        'cbns_message_input',
        'Notice Message',
        'cbns_message_input_field_html', // function which prints the field
        'cbns-settings', // page slug
        'homepage_section', // section ID
        array(
            'label_for' => 'cbns_message_input',
            'class' => 'cbns_message_input',
        )
    );

    register_setting(
        'cbns_group', // settings group name
        'cbns_message_input', 	// field name
        'sanitize_text_field' // sanitization function
    );

    // cbns_display_attribute field
    add_settings_field(
        'cbns_display_attribute',
        'Display Attribute',
        'cbns_display_attribute_field_html', // function which prints the field
        'cbns-settings', // page slug
        'homepage_section', // section ID
        array(
            'label_for' => 'cbns_display_attribute',
            'class' => 'cbns_display_attribute',
        )
    );

    register_setting(
        'cbns_group', // settings group name
        'cbns_display_attribute', 	// field name
        'sanitize_text_field' // sanitization function
    );

    // cbns_margin field
    add_settings_field(
        'cbns_margin',
        'Margin',
        'cbns_margin_field_html', // function which prints the field
        'cbns-settings', // page slug
        'homepage_section', // section ID
        array(
            'label_for' => 'cbns_margin',
            'class' => 'cbns_margin',
        )
    );

    register_setting(
        'cbns_group', // settings group name
        'cbns_margin', 	// field name
        'sanitize_text_field' // sanitization function
    );

    // cbns_font_size field
    add_settings_field(
        'cbns_font_size',
        'Font Size',
        'cbns_font_size_field_html', // function which prints the field
        'cbns-settings', // page slug
        'homepage_section', // section ID
        array(
            'label_for' => 'cbns_font_size',
            'class' => 'cbns_font_size',
        )
    );

    register_setting(
        'cbns_group', // settings group name
        'cbns_font_size', 	// field name
        'sanitize_text_field' // sanitization function
    );

    // 2nd field
    add_settings_field(
        'cbns_text_color',
        'Text Color',
        'cbns_text_color_field_html', // function which prints the field
        'cbns-settings', // page slug
        'homepage_section', // section ID
        array(
            'label_for' => 'cbns_text_color',
            'class' => 'cbns_text_color',
        )
    );

    register_setting(
        'cbns_group', // settings group name
        'cbns_text_color', 	// field name
        'sanitize_text_field' // sanitization function
    );


    // 3rd field
    add_settings_field(
        'cbns_custom_css_input',
        'Custom CSS',
        'cbns_custom_css_input_field_html', // function which prints the field
        'cbns-settings', // page slug
        'homepage_section', // section ID
        array(
            'label_for' => 'cbns_custom_css_input',
            'class' => 'cbns_custom_css_input',
        )
    );

    register_setting(
        'cbns_group', // settings group name
        'cbns_custom_css_input', 	// field name
        'sanitize_text_field' // sanitization function
    );

}

function cbns_message_input_field_html() {
    global $cbns_message;
    echo '<input style="min-width:50%;" type="text" id="cbns_message_input" name="cbns_message_input" value="'. esc_html($cbns_message) .'">';
}

function cbns_display_attribute_field_html() {
    global $display_options;
    global $cbns_display_attribute;

    echo '<select style="min-width:50%;" id="cbns_display_attribute" name="cbns_display_attribute">';

    foreach($display_options as $key => $value){
        echo '<option value="' . esc_html($key) . '" ';
        if($cbns_display_attribute == $key){ echo ' selected'; }
        echo '>' . ucwords(esc_attr($value)) . '</option>';
    }

    echo '</select>';
}

function cbns_margin_field_html() {
    global $cbns_margin;
    echo '<input style="min-width:50%;" type="text" id="cbns_margin" name="cbns_margin" value="'. esc_html($cbns_margin) .'">';
}

function cbns_font_size_field_html() {
    global $cbns_font_size;
    echo '<input style="min-width:50%;" type="text" id="cbns_font_size" name="cbns_font_size" value="'. esc_html($cbns_font_size) .'">';
}

function cbns_text_color_field_html() {
    global $cbns_text_color;
    echo '<input type="color" id="cbns_text_color" name="cbns_text_color" value="'. esc_html($cbns_text_color) .'">';
}

function cbns_custom_css_input_field_html() {
    global $cbns_custom_css;
    echo '<textarea style="min-width:50%;" rows="10" id="cbns_custom_css_input" name="cbns_custom_css_input">'.esc_html($cbns_custom_css).'</textarea>';
}

add_filter( 'plugin_action_links_comment-but-not-seo/comment-but-not-seo.php', 'cbns_settings_link' );

function cbns_settings_link( $links ) {
    // Build and escape the URL.
    $url = esc_url( add_query_arg(
        'page',
        'cbns-settings',
        get_admin_url() . 'options-general.php'
    ) );
    // Create the link.
    $settings_link = "<a href='$url'>" . __( 'Settings' ) . '</a>';
    // Adds the link to the end of the array.
    array_push(
        $links,
        $settings_link
    );
    return $links;
}