<?php
/**
 * Plugin Name: Nativity 
 * Description: Adds a nativity day
 * Version: 1.0
 * Author: Your Name
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Register Custom Post Type
function cpt_register_nativity() {
    $labels = array(
        'name'                  => _x('Nativities', 'Post Type General Name', 'text_domain'),
        'singular_name'         => _x('Nativity', 'Post Type Singular Name', 'text_domain'),
        'menu_name'             => __('Nativities', 'text_domain'),
        'name_admin_bar'        => __('Nativity', 'text_domain'),
        'add_new'               => __('Add New', 'text_domain'),
        'add_new_item'          => __('Add New Nativity', 'text_domain'),
        'new_item'              => __('New Nativity', 'text_domain'),
        'edit_item'             => __('Edit Nativity', 'text_domain'),
        'view_item'             => __('View Nativity', 'text_domain'),
        'all_items'             => __('All Nativities', 'text_domain'),
        'search_items'          => __('Search Nativities', 'text_domain'),
        'not_found'             => __('No nativities found', 'text_domain'),
        'not_found_in_trash'    => __('No nativities found in Trash', 'text_domain'),
    );

    $args = array(
        'label'                 => __('Nativity', 'text_domain'),
        'description'           => __('Custom post type for nativities', 'text_domain'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail'),
        'public'                => true,
        'has_archive'           => true,
        'rewrite'               => array('slug' => 'nativities'),
        'show_in_rest'          => true,
    );

    register_post_type('nativity', $args);
}

// Add custom fields
function cpt_add_custom_meta_boxes() {
    add_meta_box(
        'nativity_meta_box',
        __('Nativity Details', 'text_domain'),
        'cpt_render_meta_box',
        'nativity',
        'normal',
        'high'
    );
}

function cpt_render_meta_box($post) {
    wp_nonce_field('cpt_save_meta_box_data', 'cpt_meta_box_nonce');

    $day = get_post_meta($post->ID, '_cpt_day', true);
    $colour = get_post_meta($post->ID, '_cpt_colour', true);
    $body = get_post_meta($post->ID, '_cpt_body', true);
    $podcast = get_post_meta($post->ID, '_cpt_podcast', true);
    $video_start = get_post_meta($post->ID, '_cpt_video_start', true);
    $video_end = get_post_meta($post->ID, '_cpt_video_end', true);
    $thumbnail = get_post_meta($post->ID, '_cpt_thumbnail', true);
    $picture = get_post_meta($post->ID, '_cpt_picture', true);
    $nativity_figure = get_post_meta($post->ID, '_cpt_nativity_figure', true);

    ?>
    <label for="cpt_day"><?php _e('Day (integer)', 'text_domain'); ?></label>
    <input type="number" id="cpt_day" name="cpt_day" value="<?php echo esc_attr($day); ?>" /><br>

    <label for="cpt_body"><?php _e('Body (rich text)', 'text_domain'); ?></label>
    <?php
    wp_editor($body, 'cpt_body', array('textarea_name' => 'cpt_body'));
    ?><br>

    <label for="cpt_podcast"><?php _e('Podcast (URL)', 'text_domain'); ?></label>
    <input type="url" id="cpt_podcast" name="cpt_podcast" value="<?php echo esc_attr($podcast); ?>" /><br>


    <label for="cpt_colour"><?php _e('colour', 'text_domain'); ?></label>
    <input type="url" id="cpt_colour" name="cpt_colour" value="<?php echo esc_attr($podcast); ?>" /><br>

    <label for="cpt_video_start"><?php _e('Video Start (timecode)', 'text_domain'); ?></label>
    <input type="text" id="cpt_video_start" name="cpt_video_start" value="<?php echo esc_attr($video_start); ?>" /><br>

    <label for="cpt_video_end"><?php _e('Video End (timecode)', 'text_domain'); ?></label>
    <input type="text" id="cpt_video_end" name="cpt_video_end" value="<?php echo esc_attr($video_end); ?>" /><br>

    <label for="cpt_thumbnail"><?php _e('Thumbnail', 'text_domain'); ?></label>
    <input type="text" id="cpt_thumbnail" name="cpt_thumbnail" value="<?php echo esc_attr($thumbnail); ?>" /><br>
    <input type="button" id="cpt_thumbnail_button" class="button" value="<?php _e('Upload Image', 'text_domain'); ?>" /><br>

    <label for="cpt_picture"><?php _e('Picture', 'text_domain'); ?></label>
    <input type="text" id="cpt_picture" name="cpt_picture" value="<?php echo esc_attr($picture); ?>" /><br>
    <input type="button" id="cpt_picture_button" class="button" value="<?php _e('Upload Image', 'text_domain'); ?>" /><br>

    <label for="cpt_nativity_figure"><?php _e('Nativity Figure', 'text_domain'); ?></label>
    <input type="text" id="cpt_nativity_figure" name="cpt_nativity_figure" value="<?php echo esc_attr($nativity_figure); ?>" /><br>
    <input type="button" id="cpt_nativity_figure_button" class="button" value="<?php _e('Upload Image', 'text_domain'); ?>" /><br>
    
    <script>
    jQuery(document).ready(function($) {
        function uploadImage(buttonId, inputId) {
            var file_frame;
            $(document).on('click', buttonId, function(event) {
                event.preventDefault();

                // If the media frame already exists, reopen it.
                if (file_frame) {
                    file_frame.open();
                    return;
                }

                // Create the media frame.
                file_frame = wp.media({
                    title: '<?php _e('Select or Upload Image', 'text_domain'); ?>',
                    button: {
                        text: '<?php _e('Use this image', 'text_domain'); ?>'
                    },
                    multiple: false // Set to true to allow multiple files to be selected
                });

                // When an image is selected, run a callback.
                file_frame.on('select', function() {
                    // Get the selected size data from the media frame.
                    var attachment = file_frame.state().get('selection').first().toJSON();

                    // Update the input field with the attachment URL
                    $(inputId).val(attachment.url);
                });

                // Finally, open the modal on click.
                file_frame.open();
            });
        }

        uploadImage('#cpt_thumbnail_button', '#cpt_thumbnail');
        uploadImage('#cpt_picture_button', '#cpt_picture');
        uploadImage('#cpt_nativity_figure_button', '#cpt_nativity_figure');
    });
    </script>
    <?php
}

// Save meta box data
function cpt_save_meta_box_data($post_id) {
    if (!isset($_POST['cpt_meta_box_nonce']) || !wp_verify_nonce($_POST['cpt_meta_box_nonce'], 'cpt_save_meta_box_data')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (isset($_POST['cpt_day'])) {
        update_post_meta($post_id, '_cpt_day', intval($_POST['cpt_day']));
    }

    if (isset($_POST['cpt_body'])) {
        update_post_meta($post_id, '_cpt_body', sanitize_textarea_field($_POST['cpt_body']));
    }

    if (isset($_POST['cpt_podcast'])) {
        update_post_meta($post_id, '_cpt_podcast', esc_url($_POST['cpt_podcast']));
    }

    if (isset($_POST['cpt_video_start'])) {
        update_post_meta($post_id, '_cpt_video_start', sanitize_text_field($_POST['cpt_video_start']));
    }

    if (isset($_POST['cpt_video_end'])) {
        update_post_meta($post_id, '_cpt_video_end', sanitize_text_field($_POST['cpt_video_end']));
    }

    if (isset($_POST['cpt_thumbnail'])) {
        update_post_meta($post_id, '_cpt_thumbnail', esc_url($_POST['cpt_thumbnail']));
    }

    if (isset($_POST['cpt_picture'])) {
        update_post_meta($post_id, '_cpt_picture', esc_url($_POST['cpt_picture']));
    }

    if (isset($_POST['cpt_nativity_figure'])) {
        update_post_meta($post_id, '_cpt_nativity_figure', esc_url($_POST['cpt_nativity_figure']));
    }
}

function cpt_register_meta() {
    register_meta('post', '_cpt_day', array(
        'type' => 'integer',
        'single' => true,
        'show_in_rest' => true,
    ));
    register_meta('post', '_cpt_body', array(
        'type' => 'string',
        'single' => true,
        'show_in_rest' => true,
    ));
    register_meta('post', '_cpt_podcast', array(
        'type' => 'string',
        'single' => true,
        'show_in_rest' => true,
    ));
    register_meta('post', '_cpt_video_start', array(
        'type' => 'string',
        'single' => true,
        'show_in_rest' => true,
    ));
    register_meta('post', '_cpt_video_end', array(
        'type' => 'string',
        'single' => true,
        'show_in_rest' => true,
    ));
    register_meta('post', '_cpt_thumbnail', array(
        'type' => 'string',
        'single' => true,
        'show_in_rest' => true,
    ));
    register_meta('post', '_cpt_picture', array(
        'type' => 'string',
        'single' => true,
        'show_in_rest' => true,
    ));
    register_meta('post', '_cpt_nativity_figure', array(
        'type' => 'string',
        'single' => true,
        'show_in_rest' => true,
    ));
}

function cpt_get_day_meta($object, $meta_key, $meta_value) {
    return get_post_meta($object['id'], '_cpt_day', true);
}


function cpt_get_body_meta($object, $meta_key, $meta_value) {
    return get_post_meta($object['id'], '_cpt_body', true);
}

function cpt_get_colour_meta($object, $meta_key, $meta_value) {
    return get_post_meta($object['id'], 'cpt_colour', true);
}

function cpt_get_podcast_meta($object, $meta_key, $meta_value) {
    return get_post_meta($object['id'], '_cpt_podcast', true);
}

function cpt_get_video_start_meta($object, $meta_key, $meta_value) {
    return get_post_meta($object['id'], '_cpt_video_start', true);
}

function cpt_get_video_end_meta($object, $meta_key, $meta_value) {
    return get_post_meta($object['id'], '_cpt_video_end', true);
}

function cpt_get_thumbnail_meta($object, $meta_key, $meta_value) {
    return get_post_meta($object['id'], '_cpt_thumbnail', true);
}

function cpt_get_picture_meta($object, $meta_key, $meta_value) {
    return get_post_meta($object['id'], '_cpt_picture', true);
}

function cpt_get_nativity_figure_meta($object, $meta_key, $meta_value) {
    return get_post_meta($object['id'], '_cpt_nativity_figure', true);
}

function cpt_register_rest_fields() {

    register_rest_field('nativity', 'body', array(
        'get_callback' => 'cpt_get_body_meta',
        'schema' => array(
            'type' => 'string',
            'description' => 'The body of the nativity.',
        ),
    ));

    register_rest_field('nativity', 'colour', array(
        'get_callback' => 'cpt_get_colour_meta',
        'schema' => array(
            'type' => 'string',
            'description' => 'The body of the nativity.',
        ),
    ));

    register_rest_field('nativity', 'video_start', array(
        'get_callback' => 'cpt_get_video_start_meta',
        'schema' => array(
            'type' => 'string',
            'description' => 'The start time of the video associated with the nativity.',
        ),
    ));

    register_rest_field('nativity', 'podcast', array(
        'get_callback' => 'cpt_get_podcast_meta',
        'schema' => array(
            'type' => 'string',
            'description' => 'The podcast URL associated with the nativity.',
        ),
    ));



    register_rest_field('nativity', 'video_end', array(
        'get_callback' => 'cpt_get_video_end_meta',
        'schema' => array(
            'type' => 'string',
            'description' => 'The end time of the video associated with the nativity.',
        ),
    ));

    register_rest_field('nativity', 'thumbnail', array(
        'get_callback' => 'cpt_get_thumbnail_meta',
        'schema' => array(
            'type' => 'string',
            'description' => 'The URL of the thumbnail image for the nativity.',
        ),
    ));

    register_rest_field('nativity', 'picture', array(
        'get_callback' => 'cpt_get_picture_meta',
        'schema' => array(
            'type' => 'string',
            'description' => 'The URL of the main picture for the nativity.',
        ),
    ));

    register_rest_field('nativity', 'nativity_figure', array(
        'get_callback' => 'cpt_get_nativity_figure_meta',
        'schema' => array(
            'type' => 'string',
            'description' => 'The URL of the nativity figure image.',
        ),
    ));

    register_rest_field('nativity', 'day', array(
        'get_callback' => 'cpt_get_day_meta',
        'schema' => array(
            'type' => 'integer',
            'description' => 'The day of the nativity.',
        ),
    ));
}


add_action('init', 'cpt_register_nativity');
add_action('add_meta_boxes', 'cpt_add_custom_meta_boxes');
add_action('rest_api_init', 'cpt_register_meta');
add_action('rest_api_init', 'cpt_register_rest_fields');
add_action('save_post', 'cpt_save_meta_box_data');
