<?php

/**
  * Plugin Name: Schule SR - Termine
  * Plugin URI: http://www.substring.io
  * Description: Zeigt einen Termin als Element an
  * Version: 2.0
  * Author: Korbinian Kasberger
  * Author URI: 
  * License: GPL2
 **/


add_action( 'init', 'sr_event');
add_action( 'add_meta_boxes', 'add_events_metaboxes' );
add_action( 'save_post', 'sr_event_save_date');
add_filter( 'manage_sr-event_posts_columns', 'register_sr_event_columns');
add_action( 'manage_posts_custom_column', 'handle_sr_event_custom_columns', 10, 2 );

add_action( 'pre_get_posts', 'sr_event_orderby' );
function sr_event_orderby( $query ) {
    if( ! is_admin() ){
        return;
    }
    if( isset($vars['orderby']) && 'sr_date' == $vars['orderby'] ){
    $vars = array_merge( $vars, array(
      'meta_key' => 'sr_date',
      'orderby'  => 'meta_value'
    ));
  }
  return $vars;
}


add_filter('manage_edit-sr-event_sortable_columns', 'register_sr_event_sortable_column');
function register_sr_event_sortable_column( $columns ){
  $columns['sr_date'] = 'sr_date';
  return $columns;
}


function register_sr_event_columns($columns){
    $columns = array();
    $columns['title'] = 'Name';
    $columns['sr_date'] = 'Datum';
    $columns['description'] = 'Beschreibung';
    $columns['date'] = 'Erstellt am';
    return $columns; 
}

function handle_sr_event_custom_columns($column, $post_id){
    switch ($column) {
        case 'description':
            echo get_the_excerpt( $post_id );
            break;
        case 'sr_date':
            $date = get_post_meta( $post_id,'_sr_event_date_value_key', true );
            $originalDate = $date;
            $newDate = date("d.M.Y", strtotime($originalDate));
            echo $newDate;
            break;
    }
}


function add_events_metaboxes() {
     add_meta_box('sr-event-date', 'Datum', 'sr_event_date_callback', 'sr-event', 'side','high');
}

function sr_event_date_callback($post) {
    wp_nonce_field( 'sr_event_save_date', 'sr_event_date_box_nonce');
    $newDate = '';
    $value = get_post_meta( $post->ID, '_sr_event_date_value_key', true );
    echo '<label for="sr_event_date_field">Datum</label>';
    echo '<input type="date" id="sr_event_date_field" name="sr_event_date_field" value="'.esc_attr($value).'" size="25" />';
}

function sr_event_save_date($post_id){
    if(!isset($_POST['sr_event_date_box_nonce'])){
        return;
    }
    if(!wp_verify_nonce( $_POST['sr_event_date_box_nonce'], 'sr_event_save_date' )){
        return;
    }
    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){
        return;
    }
    if(! current_user_can( 'edit_post',$post_id )){
        return;
    }
    if(!isset($_POST['sr_event_date_field'])){
        return;
    }
    $data = sanitize_text_field( $_POST['sr_event_date_field'] );
  
    update_post_meta( $post_id, '_sr_event_date_value_key', $data );
}


// Register Custom Post Type
function sr_event()
{
    $labels = array(
        'name'                  => _x( 'Termine', 'Post Type General Name', 'sr-event' ),
        'singular_name'         => _x( 'Termin', 'Post Type Singular Name', 'sr-event' ),
        'menu_name'             => __( 'Termine', 'sr-event' ),
        'name_admin_bar'        => __( 'Termine', 'sr-event' ),
        'archives'              => __( 'Item Archives', 'sr-event' ),
        'attributes'            => __( 'Item Attributes', 'sr-event' ),
        'parent_item_colon'     => __( 'Parent Item:', 'sr-event' ),
        'all_items'             => __( 'All Items', 'sr-event' ),
        'add_new_item'          => __( 'Add New Item', 'sr-event' ),
        'add_new'               => __( 'Neuer Termin', 'sr-event' ),
        'new_item'              => __( 'Neuer Termin', 'sr-event' ),
        'edit_item'             => __( 'Edit Item', 'sr-event' ),
        'update_item'           => __( 'Update Item', 'sr-event' ),
        'view_item'             => __( 'View Item', 'sr-event' ),
        'view_items'            => __( 'View Items', 'sr-event' ),
        'search_items'          => __( 'Search Item', 'sr-event' ),
        'not_found'             => __( 'Keine Termine gefunden', 'sr-event' ),
        'not_found_in_trash'    => __( 'Keine Termine gefunden', 'sr-event' ),
        'featured_image'        => __( 'Featured Image', 'sr-event' ),
        'set_featured_image'    => __( 'Set featured image', 'sr-event' ),
        'remove_featured_image' => __( 'Remove featured image', 'sr-event' ),
        'use_featured_image'    => __( 'Use as featured image', 'sr-event' ),
        'insert_into_item'      => __( 'Insert into item', 'sr-event' ),
        'uploaded_to_this_item' => __( 'Uploaded to this item', 'sr-event' ),
        'items_list'            => __( 'Items list', 'sr-event' ),
        'items_list_navigation' => __( 'Items list navigation', 'sr-event' ),
        'filter_items_list'     => __( 'Filter items list', 'sr-event' ),

    );
    $args = array(
        'label'                 => __( 'Termin', 'sr-event' ),
        'description'           => __( 'Termin', 'sr-event' ),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail','metabox'),
        'taxonomies'            => array( 'category',),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'menu_icon'             => 'dashicons-calendar-alt'
    );
    register_post_type( 'sr-event', $args );
}

function prepend_post_type($title,$id){
    return $title;
}
function sr_event_allowedtags() {
        return '<img>,<div>,<strong>,<h1>,<blockquote>,<h2>,<script>,<style>,<br>,<em>,<i>,<ul>,<ol>,<li>,<a>,<p>,<img>,<video>,<audio>'; 
    }


function sr_event_excerpt($page_id,$word_count) {
            $event_excerpt = get_post_field('post_content', $page_id);
            $event_excerpt = strip_shortcodes( $event_excerpt );
            $event_excerpt = apply_filters('the_content', $event_excerpt);
            $event_excerpt = str_replace(']]>', ']]&gt;', $event_excerpt);
            $event_excerpt = strip_tags($event_excerpt, sr_event_allowedtags()); 
            $limit_reached = false;
            if(isset($word_count)){
                $excerpt_word_count = $word_count;
            }else{
                 $excerpt_word_count = 30;
            }
                $excerpt_length = apply_filters('excerpt_length', $excerpt_word_count); 
                $tokens = array();
                $excerptOutput = '';
                $count = 0;

                preg_match_all('/(<[^>]+>|[^<>\s]+)\s*/u', $event_excerpt, $tokens);

                foreach ($tokens[0] as $token) { 

                    if ($count >= $excerpt_word_count && preg_match('/[\,\;\?\.\!]\s*$/uS', $token)) { 
                        $excerptOutput .= trim($token);
                        $limit_reached = true;
                        break;
                    }
                    $count++;
                    $excerptOutput .= $token;
                }

                $event_excerpt = trim(force_balance_tags($excerptOutput));
                
                if($limit_reached){
                    $excerpt_end = ' <a href="'. esc_url( get_permalink($page_id) ) . '" class="btn btn-sm btn-primary text-muted">' . sprintf(__( 'WEITER LESEN &nbsp;&raquo;', 'srevent' )) . '</a>'; 
                    $excerpt_more = apply_filters('excerpt_more', ' ' . $excerpt_end); 
                }
               
                $event_excerpt .= $excerpt_end; 

            return $event_excerpt;   
    }


// Display Frontend
function sr_event_list() {
  
    $monthNamesShort = array('','Jan','Feb','Mär','Apr','Mai','Jun','Jul','Aug','Sep','Okt','Nov','Dez');
    $today = date('Y-m-d');
    $args = array(
            'post_type' => 'sr-event',
        'meta_key' => '_sr_event_date_value_key',
        'orderby' => 'meta_value', 
        'order' => 'ASC'
    );
    $firstContent = array();
    $secondContent = array();
    $firstHalfYear = array('9','10','11','12','1','2');
    
    $query = new WP_query ( $args );
    if ( $query->have_posts() ) { ?>
 
        <?php while ( $query->have_posts() ) : $query->the_post(); /* start the loop */
            $event_date = get_post_meta( get_the_ID(), '_sr_event_date_value_key', true);
            $timestamp1 = strtotime($today);
            $timestamp2 = strtotime($event_date);
            $month = explode("-",$event_date)[1];
            $day = explode("-",$event_date)[2];
            $month_text = $monthNamesShort[intval($month)];
            $php_date = DateTime::createFromFormat('d.m.Y', $event_date);
            $post_id = get_the_ID();
            ob_start();
            $auto_excerpt =  sr_event_excerpt(the_content(),6);
            ob_get_clean();  
           
            $content = '';
                $content .=' <div class="sr-event">';
                $content .=' 	<div class="sr-event-date">';
                $content .= '		<span class="sr-event-month">'.$month_text.'</span>
                                    <span class="sr-event-day">'.$day.'</span>';
                $content .=     '</div>';
                $content .=     '<div class="sr-event-text">';
                $content .=         "<h2 class='sr-event-headline'>";
                $content .=             "<a href='".get_the_permalink( $post_id)."' title='".esc_attr__( 'Permalink to ', 'compass' ).the_title_attribute( 'echo=0' )  ."' rel='bookmark'>". get_the_title( $post_id)."</a>";
                $content .=         "</h2>";
                $content .=         "<p class='sr-event-desc'>".$auto_excerpt."</p>";
                $content .=     '</div>';
                $content .='</div>';
            if(is_front_page() && $timestamp1 <= $timestamp2){
                if(in_array($month,$firstHalfYear)){
                    $firstContent[] = $content;
                }else{
                    $secondContent[] = $content;
                }
                // echo $content;
            }elseif(!is_front_page()){
                if ( has_post_thumbnail()) { 
                    $content .='<a href="'.get_the_permalink( $post_id)."'>";
                    $content .= get_the_post_thumbnail($post_id, 'medium', array(
                        'class' => 'event-picture aligncenter',
                        'alt'   => trim(strip_tags( $wp_postmeta->_wp_attachment_image_alt ))
                    ) ); 
                    $content .='</a>';
                } 
               if(in_array($month,$firstHalfYear)){
                    $firstContent[] = $content;
                }else{
                    $secondContent[] = $content;
                }
            }
            
        ?>
        <?php endwhile; /* end the loop*/ ?>
        <?php 
        
        if(sizeof( $firstContent)>0){
            
        if(!is_front_page()){?>
            <div class="head-line">
                <h2>1. Halbjahr</h2>
            </div>
            <?php }
            for ($i=0; $i < sizeof($firstContent) ; $i++) { 
                echo $firstContent[$i];
            }
        }
         if(sizeof( $secondContent)>0){
        if(!is_front_page()){?>
             <div class="head-line">
                <h2>2. Halbjahr</h2>
            </div>
        <?php }
            for ($i=0; $i < sizeof($secondContent) ; $i++) { 
                echo $secondContent[$i];
            }
        }
        
        ?>
        <?php rewind_posts();
    }
}