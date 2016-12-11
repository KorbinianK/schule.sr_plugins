


<?php 
/*
Plugin Name: Slider for schule.sr
Version: 1.0
Plugin URI: 
Description: The descripion of your plugin.
Author: Korbinian Kasberger
Author URI: 
*/
require_once dirname( __FILE__ ) . '/assets/shortcode.php';
add_action( 'widgets_init', 'sr_slider_init' );
 
class sr_slider extends WP_Widget
{
    

	function __construct() {
			parent::__construct(
				'sr_slider',
				__( '#Slider Seite',  sr_slider ),
				array( 'description' => __( 'Seite für Frontpage slider.',  sr_slider ), )
			);

			add_action('admin_enqueue_scripts', array($this, 'scripts'));
		}

    public function scripts() {
	    	wp_enqueue_script('jquery');
            wp_enqueue_media();
            wp_enqueue_script('upload_media_widget', plugin_dir_url(__FILE__) . 'assets/js/upload.js', array('jquery'));
	    	
	    }
    // This function creates nice Facebook Page Like box in Header or Footer
    public function widget($args, $instance)
    {
     
			extract( $args );
			extract( $instance );
			echo $before_widget;

        ?>
        
           <?php echo do_shortcode( '[sr_slider img_src="'.$img_url.'" first_line="'.$first_line.'" second_line="'.$second_line.'" url="'.$url.'"]' ); ?>

 
        <?php echo $after_widget;
    }
    
    public function form($instance)
    {
        extract( $instance );
        $img_url = $instance['img_url'];
     
        if (isset($instance['first_line'])) {
         $first_line = $instance['first_line'];
        } else {
             $first_line ="";
        }
     if (isset($instance['second_line'])) {
         $second_line = $instance['second_line'];
        } else {
             $second_line ="";
        }
       if (isset($instance['url'])) {
         $url = $instance['url'];
        } else {
             $url ="";
        }
	?>


            <p><?php wp_dropdown_pages($pageIdArgs); ?></p>
            <p>
				<label for="<?php echo $this->get_field_id( 'first_line' ); ?>"><?php _e( 'Erste Zeile:', $instance['first_line'] ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'first_line' ); ?>" name="<?php echo $this->get_field_name( 'first_line' ); ?>" type="text" value="<?php if( isset( $first_line ) ) echo esc_attr( $first_line ); ?>">
			</p>
            <p>
				<label for="<?php echo $this->get_field_id( 'second_line' ); ?>"><?php _e( 'Zweite Zeile:', $instance['second_line'] ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'second_linetitle' ); ?>" name="<?php echo $this->get_field_name( 'second_line' ); ?>" type="text" value="<?php if( isset( $second_line ) ) echo esc_attr( $second_line ); ?>">
			</p>
			<p>
            	<label style="display: block;" for="<?php echo $this->get_field_name( 'img_url' ); ?>"><?php _e( 'Image:' ); ?></label>
	            <input style="display: none;" name="<?php echo $this->get_field_name( 'img_url' ); ?>" id="<?php echo $this->get_field_id( 'img_url' ); ?>" class="widefat "  value="<?php if( isset( $img_url ) ) echo esc_attr( $img_url ); ?>" />
	            <?php 
		            if( isset( $img_url ) ){
		            	$show_img = wp_get_attachment_image_src( $img_url, 'medium');
                        
						$show_img = $show_img[0];
						?>
						<img class="sr-image" id="<?php echo $this->get_field_id( 'img_url' ); ?>_sr_slider_images" src="<?php echo $show_img; ?>">
						<?php
		            } 
	            ?>
	            <input class="button button-primary" onclick="slider.processing( '<?php echo $this->id; ?>', '<?php echo $this->get_field_id( 'img_url' ); ?>' ); return false;" type="button" value="Bild auswählen" />
	        </p>
	         <p>
				<label for="<?php echo $this->get_field_id( 'url' ); ?>"><?php _e( 'Link:', $instance['url'] ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'url' ); ?>" name="<?php echo $this->get_field_name( 'url' ); ?>" type="text" value="<?php if( isset( $url ) ) echo esc_attr( $url ); ?>">
			</p>
</p>
 
<?php
    }
    
    function update($new_instance, $old_instance)
    {
    	$instance                           = array();
    
        $instance['first_line'] = ( ! empty( $new_instance['first_line'] ) ) ? strip_tags( $new_instance['first_line'] ) : '';
        $instance['second_line'] = ( ! empty( $new_instance['second_line'] ) ) ? strip_tags( $new_instance['second_line'] ) : '';
       	$instance['img_url'] = ( ! empty( $new_instance['img_url'] ) ) ? strip_tags( $new_instance['img_url'] ) : '';
        $instance['url'] = ( ! empty( $new_instance['url'] ) ) ? strip_tags( $new_instance['url'] ) : '';

    	return $instance;
    }
}
 
function sr_slider_init(){
    register_widget('sr_slider');
}



?>


