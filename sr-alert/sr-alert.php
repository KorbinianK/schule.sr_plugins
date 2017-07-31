<?php

/**
  * Plugin Name: Schule SR - Alert
  * Plugin URI: http://www.substring.io
  * Description: Zeigt einen Termin als Element an
  * Version: 2.0
  * Author: Korbinian Kasberger
  * Author URI: 
  * License: GPL2
 **/


add_action( 'init', 'sr_alert');
add_action( 'add_meta_boxes', 'add_alerts_metaboxes' );
add_action( 'save_post', 'sr_alert_save_date');
add_action( 'save_post', 'sr_alert_until_save_date');
add_action( 'save_post', 'sr_alert_save_class');
add_filter( 'manage_sr-alert_posts_columns', 'register_sr_alert_columns');
add_action( 'manage_posts_custom_column', 'handle_sr_alert_custom_columns', 10, 2 );
add_filter( 'manage_edit-sr-alert_sortable_columns', 'register_sr_alert_sortable_column');
add_action( 'pre_get_posts', 'sr_alert_orderby' );

function sr_alert_orderby( $query ) {
    if( ! is_admin() ){
        return;
    }
    if( isset($vars['orderby']) && 'sr_alert_date' == $vars['orderby'] ){
    $vars = array_merge( $vars, array(
      'meta_key' => 'sr_alert_date',
      'orderby'  => 'meta_value'
    ));
  }
  return $vars;
}


function register_sr_alert_sortable_column( $columns ){
  $columns['sr_alert_date'] = 'sr_alert_date';
  return $columns;
}


function register_sr_alert_columns($columns){
    $columns = array();
    $columns['title'] = 'Name';
    $columns['sr_alert_date'] = 'Anzeigen bis';
    $columns['description'] = 'Beschreibung';
    $columns['date'] = 'Erstellt am';
    return $columns; 
}

function handle_sr_alert_custom_columns($column, $post_id){
    switch ($column) {
        case 'sr_alert_date':
            $date = get_post_meta( $post_id,'_sr_alert_date_until_value_key', true );
            $originalDate = $date;
            $newDate = date("d. M. Y", strtotime($originalDate));
            echo $newDate;
            break;
    }
}


function add_alerts_metaboxes() {
     add_meta_box('sr-alert-class', 'Typ:', 'sr_alert_class_callback', 'sr-alert', 'side','high');
     add_meta_box('sr-alert-date_until', 'Anzeigen bis:', 'sr_alert_date_until_callback', 'sr-alert', 'side','high');
}


function sr_alert_class_callback($post) {
    wp_nonce_field( 'sr_alert_save_class', 'sr_alert_class_box_nonce');
    $value = get_post_meta( $post->ID, '_sr_alert_class_value_key', true );
    var_dump($value);
   ?>
    <label for="sr_alert_class_field">Aussehen</label>
    <select id="sr_alert_class_field" name="sr_alert_class_field">
        <option <?php echo ($value == "warning")    ?  "selected" : ''; ?> value="warning">Gelb</option>
        <option <?php echo ($value == "success")    ?  "selected" : ''; ?> value="success">Grün</option>
        <option <?php echo ($value == "danger")     ?  "selected" : ''; ?> value="danger">Rot</option>
        <option <?php echo ($value == "info")       ?  "selected" : ''; ?> value="info">Blau</option>
    </select>
 
    <?php
}
function sr_alert_date_until_callback($post) {
    wp_nonce_field( 'sr_alert_until_save_date', 'sr_alert_date_until_box_nonce');
    $newDate = '';
    $value = get_post_meta( $post->ID, '_sr_alert_date_until_value_key', true );
    if(isset($value)){
        $newDate = $value;
    }
    echo '<label for="sr_alert_date_until_field">Datum</label>';
    echo '<input type="date" id="sr_alert_date_until_field" name="sr_alert_date_until_field" value="'.$newDate .'" size="25" />';
}

function sr_alert_save_class($post_id){
    if(!isset($_POST['sr_alert_class_box_nonce'])){
        return;
    }
    if(!wp_verify_nonce( $_POST['sr_alert_class_box_nonce'], 'sr_alert_save_class' )){
        return;
    }
    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){
        return;
    }
    if(! current_user_can( 'edit_post',$post_id )){
        return;
    }
    if(!isset($_POST['sr_alert_class_field']) || $_POST['sr_alert_class_field'] == '0'){
        return;
    }
    $data = sanitize_text_field( $_POST['sr_alert_class_field'] );
  
    update_post_meta( $post_id, '_sr_alert_class_value_key', $data );
}





function sr_alert_until_save_date($post_id){
    if(!isset($_POST['sr_alert_date_until_box_nonce'])){
        return;
    }
    if(!wp_verify_nonce( $_POST['sr_alert_date_until_box_nonce'], 'sr_alert_until_save_date' )){
        return;
    }
    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){
        return;
    }
    if(! current_user_can( 'edit_post',$post_id )){
        return;
    }
    if(!isset($_POST['sr_alert_date_until_field'])|| $_POST['sr_alert_date_until_field'] == '0'){
        return;
    }
    $data = sanitize_text_field( $_POST['sr_alert_date_until_field'] );
  
    update_post_meta( $post_id, '_sr_alert_date_until_value_key', $data );
}

// Register Custom Post Type
function sr_alert()
{
    $labels = array(
        'name'                  => _x( 'Hinweis', 'Post Type General Name', 'sr-alert' ),
        'singular_name'         => _x( 'Hinweis', 'Post Type Singular Name', 'sr-alert' ),
        'menu_name'             => __( 'Hinweis', 'sr-alert' ),
        'name_admin_bar'        => __( 'Hinweis', 'sr-alert' ),
        'archives'              => __( 'Item Archives', 'sr-alert' ),
        'attributes'            => __( 'Item Attributes', 'sr-alert' ),
        'parent_item_colon'     => __( 'Parent Item:', 'sr-alert' ),
        'all_items'             => __( 'Alle Hinweise', 'sr-alert' ),
        'add_new_item'          => __( 'Neuen Hinweis erstellen', 'sr-alert' ),
        'add_new'               => __( 'Neuer Hinweis', 'sr-alert' ),
        'new_item'              => __( 'Neuer Hinweis', 'sr-alert' ),
        'edit_item'             => __( 'Edit Item', 'sr-alert' ),
        'update_item'           => __( 'Update Item', 'sr-alert' ),
        'view_item'             => __( 'View Item', 'sr-alert' ),
        'view_items'            => __( 'View Items', 'sr-alert' ),
        'search_items'          => __( 'Search Item', 'sr-alert' ),
        'not_found'             => __( 'Keine Hinweise gefunden', 'sr-alert' ),
        'not_found_in_trash'    => __( 'Keine Hinweise gefunden', 'sr-alert' ),
        'featured_image'        => __( 'Featured Image', 'sr-alert' ),
        'set_featured_image'    => __( 'Set featured image', 'sr-alert' ),
        'remove_featured_image' => __( 'Remove featured image', 'sr-alert' ),
        'use_featured_image'    => __( 'Use as featured image', 'sr-alert' ),
        'insert_into_item'      => __( 'Insert into item', 'sr-alert' ),
        'uploaded_to_this_item' => __( 'Uploaded to this item', 'sr-alert' ),
        'items_list'            => __( 'Items list', 'sr-alert' ),
        'items_list_navigation' => __( 'Items list navigation', 'sr-alert' ),
        'filter_items_list'     => __( 'Filter items list', 'sr-alert' ),

    );
    $args = array(
        'label'                 => __( 'Hinweis', 'sr-alert' ),
        'description'           => __( 'Hinweis Banner auf Frontpage', 'sr-alert' ),
        'labels'                => $labels,
        'supports'              => array('title','editor','metabox'),
        'taxonomies'            => array( '',),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => false,
        'publicly_queryable'    => false,
        'capability_type'       => 'page',
        'menu_icon'             => 'dashicons-warning',
    );
    register_post_type( 'sr-alert', $args );
}


function sr_alert_allowedtags() {
        return '<strong>'; 
    }

function sr_alert_exist(){
    $args = array(
        'post_type' => 'sr-alert',
        'meta_key' => '_sr_alert_date_until_value_key',
        'orderby' => 'meta_value', 
        'order' => 'ASC'
    );
  
    $query = new WP_query ( $args );
    if ( $query->have_posts() ) { 
         while ( $query->have_posts() ) : $query->the_post();
            $alert_date_until = get_post_meta( get_the_ID(), '_sr_alert_date_until_value_key', true);
            $timestamp1 = strtotime($today);
            if(isset($alert_date_until) && $alert_date_until != ''){
                $timestamp2 = strtotime($alert_date_until);
            }else{
                $timestamp2 = strtotime($timestamp1);
            }
            if($timestamp1 <= $timestamp2){
                return true;
            }
            return false;

         endwhile;
    }
}
// Display Frontend
function sr_alert_list() {
  
    $today = date('Y-m-d');
    $args = array(
        'post_type' => 'sr-alert',
        'meta_key' => '_sr_alert_date_until_value_key',
        'orderby' => 'meta_value', 
        'order' => 'ASC'
    );
  
    $query = new WP_query ( $args );
    if ( $query->have_posts() ) { ?>
 
        <?php while ( $query->have_posts() ) : $query->the_post(); /* start the loop */
            $alert_class = get_post_meta( get_the_ID(), '_sr_alert_class_value_key', true);
            if($alert_class == ''){
                $alert_class = 'warning';
            }
            $alert_date_until = get_post_meta( get_the_ID(), '_sr_alert_date_until_value_key', true);
            $timestamp1 = strtotime($today);
            if(isset($alert_date_until) && $alert_date_until != ''){
                $timestamp2 = strtotime($alert_date_until);
            }else{
                $timestamp2 = strtotime($timestamp1);
            }
            $post_id = get_the_ID();
            if($timestamp1 <= $timestamp2){
                ob_start();
                $the_content    = get_the_content($post_id );
                $title          = get_the_title( $post_id );
                ob_get_clean();  
                $content = '<div class="alert alert-'.$alert_class.' alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>';
		        $content .= "<strong>".$title."</strong> ".$the_content."</div>"; 
                echo $content;
            }
        ?>
        
        <?php endwhile; /* end the loop*/ ?>
        
        <?php rewind_posts();
    }
}