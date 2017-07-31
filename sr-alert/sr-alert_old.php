<?php 
/*
Plugin Name: Meldung Widget for schule.sr
Version: 1.0
Plugin URI: 
Description: The descripion of your plugin.
Author: Korbinian Kasberger
Author URI: 
*/
// require_once dirname( __FILE__ ) . '/assets/shortcode.php';
add_action( 'widgets_init', 'sr_alert_init' );
 
class sr_alert extends WP_Widget
{
    


/* Then to display the error message: */

	function __construct() {
			parent::__construct(
				'sr_alert',
				__( '# SR Meldung',  'sr_alert' ),
				array( 'description' => __( 'Wichtige Benachrichtigungen.',  'sr_alert' ), )
			);

			add_action('admin_enqueue_scripts', array($this, 'scripts'));
          
		}

    public function scripts() {
	    	// wp_enqueue_script('jquery');
            // wp_enqueue_media();
            // wp_enqueue_script('upload_media_widget', plugin_dir_url(__FILE__) . 'assets/js/upload.js', array('jquery'));
	    	
	    }
    
    public function widget($args, $instance) {
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		$alert_text = $instance['alert_text'];
		$class = $instance['class'];

		echo $before_widget;

	
		$before = '<div class="alert alert-'.$class.' alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>';
        
		$content = $before ."<strong>".$title."</strong> ".$alert_text."</div>"; 
       if($alert_text == ''){
           $content='';
       }

		echo $content;

		echo $after_widget;
	}
    
    public function form($instance)
    {
        extract( $instance );
       
     $title = '';
     if (isset($instance['title'])) {
			$title = esc_attr($instance['title']);
		}
        if (isset($instance['alert_text'])) {
         $alert_text = $instance['alert_text'];
        } else {
             $alert_text ="";
        }
    
	?>


        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> </label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
	
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id('alert_text') ); ?>"><?php _e('Text:', 'wp_widget_plugin'); ?></label><br/>
            <textarea style="width:100%;" id="<?php echo esc_attr( $this->get_field_id('alert_text') ); ?>" name="<?php echo esc_attr( $this->get_field_name('alert_text') ); ?>"><?php echo esc_html( $alert_text ); ?></textarea>
           
        </p>
        <label for="<?php echo $this->get_field_id('text'); ?>">Farbe: 
        <select class='widefat' id="<?php echo $this->get_field_id('class'); ?>"
                name="<?php echo $this->get_field_name('class'); ?>" type="text">
          <option value='danger'<?php echo ($class=='danger')?'selected':''; ?>>
            Rot
          </option>
          <option value='warning'<?php echo ($class=='warning')?'selected':''; ?>>
            Gelb
          </option> 
          <option value='success'<?php echo ($class=='success')?'selected':''; ?>>
            Grün
          </option> 
        </select>                
      </label>
</p>
 
<?php
    }
    
    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
    	$instance                           = array();
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['alert_text'] = ( ! empty( $new_instance['alert_text'] ) ) ?  strip_tags($new_instance['alert_text'])  : '';
        $instance['class'] = ( ! empty( $new_instance['class'] ) ) ? strip_tags( $new_instance['class'] ) : '';
      

    	return $instance;
    }
}
 
function sr_alert_init(){
    register_widget('sr_alert');
}
?>