<?php

 /**
  * Plugin Name: Separate Media Library
  * Plugin URI:  https://github.com/kasparsj/separate-media-library
  * Description: Separate the media library by post type
  * Author:      Kaspars Jaudzems
  * Author URI:  http://kasparsj.wordpress.com/
  * Version:     1.0
  */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Exit if not admin
if( !defined( 'WP_ADMIN' ) ) return;

class SeparateMediaLibrary {
    
    protected $options;
    
    public function __construct() {
        add_action('admin_init', array($this, 'admin_init'));
        add_action('admin_menu', array( $this, 'admin_menu'));
        add_action('wp_ajax_query-attachments', array( $this, 'ajax_query_attachments'), 1);
    }
    
    public function admin_init() {
        register_setting('separate_media_library', 'separate_media_library', array($this, 'validate_options'));
    }
    
    public function admin_menu() {
        add_options_page('Separate Media Library', 'Separate Media Library', 'manage_options', 'separate_media_library', array($this, 'options_page'));
    }
    
    public function options_page() {
        $options = $this->get_options();
        include("options-page.php");
    }
    
    protected function get_options() {
        if ($this->options === null) {
            $this->options = get_option('separate_media_library');
            if (!is_array($this->options)) {
                $this->options = array(
                    "post_types" => array()
                );
                update_option('separate_media_library', $this->options);
            }
        }
        return $this->options;
    }
    
    public function validate_options($input) {
        return $input;
    }
    
    public function ajax_query_attachments() {
        $options = $this->get_options();
        if (!empty($options['post_types'])) {
            add_filter('posts_join', array($this, 'posts_join'));
            add_filter('posts_where', array($this, 'posts_where'));
        }
    }
    
    public function posts_join($join) {
        if (strpos($join, 'p2') === false) {
            global $wpdb;
            $join .= " LEFT JOIN $wpdb->posts AS p2 ON ($wpdb->posts.post_parent = p2.ID) ";
        }
        
        return $join;
    }
    
    public function posts_where($where) {
        $post_id = (int) $_POST['post_id'];
        if ( ! $post = get_post( $post_id ) )
            wp_send_json_error();
        
        $options = $this->get_options();
        if (in_array($post->post_type, $options['post_types']))
            $where .= sprintf(" AND p2.post_type = '%s'", $post->post_type);
        else
            $where .= sprintf(" AND p2.post_type NOT IN ('%s')", implode("', '", $options['post_types']));
        
        return $where;
    }
    
}

new SeparateMediaLibrary;

?>
