<?php
/**
 * @package sr_event
 * @version 1.3
 */
/*
  Plugin Name: Event Widget for schule.sr
  Description: Zeigt einen Termin als Element an
  Version: 1.3
  Author: 
  Author URI: 
 */
add_action( 'widgets_init', 'register_sr_event' );
/**
 * sr_event_Widget Class
 */
class sr_event_Widget extends WP_Widget {
	
	public function __construct() {
		parent::__construct('sr_event', '# SR Termin', array(
			'classname' => 'sr_event',
			'description' => 'Zeigt Termin an'
		)
		);

		add_action('admin_enqueue_scripts', array($this, 'scripts'));
	}

 public function scripts() {
	    	wp_enqueue_script('jquery');
            wp_enqueue_script('jquery-ui-datepicker');
    		wp_enqueue_style('jquery-ui-css', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
			wp_enqueue_script('sr_event_widget', plugin_dir_url(__FILE__).'/event.js', array('jquery'));

	    }


	/** @see WP_Widget::widget */
	public function widget($args, $instance) {
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		$date = $instance['date'];
	

		echo $before_widget;

		
		echo' <div class="sr-event">';
		echo' 	<div class="sr-event-date">';
		echo '		<span class="sr-event-month">'.explode("-",$date)[1].'</span>
					<span class="sr-event-day">'.explode("-",$date)[0].'</span>';
		echo' 	</div>';

		echo '<div class="sr-event-text">';
		echo "  <h2 class='sr-event-headline'>".$instance['name']."</h2>";
		echo "  <p class='sr-event-desc'>".$instance['desc']."</p>";
		echo'</div>';
		echo'</div>';

		echo $after_widget;
	}


	/** @see WP_Widget::update */
	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['name']);
		$instance['name'] = strip_tags($new_instance['name']);
		$instance['desc'] = strip_tags($new_instance['desc']);
		$instance['date'] = strip_tags($new_instance['date']);
		return $instance;
	}

	/** @see WP_Widget::form */
	public function form($instance) {
		$name = '';
		if (isset($instance['title'])) {
			$title = esc_attr($instance['title']);
		}
		if (isset($instance['name'])) {
			$name = esc_attr($instance['name']);
		}
		if (isset($instance['desc'])) {
			$desc = esc_attr($instance['desc']);
		}
		if (isset($instance['date'])) {
			$date = esc_attr($instance['date']);
		}
	
?>
		<p>
		
		<input type="hidden"class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />

		<p><label for="<?php echo $this->get_field_id('name'); ?>"><?php _e('Name:'); ?> 
		<input class="widefat" id="<?php echo $this->get_field_id('name'); ?>" name="<?php echo $this->get_field_name('name'); ?>" type="text" value="<?php echo $name; ?>" />
		</label></p>
		<p><label for="<?php echo $this->get_field_id('desc'); ?>"><?php _e('Beschreibung:'); ?> 
		<input class="widefat" id="<?php echo $this->get_field_id('desc'); ?>" name="<?php echo $this->get_field_name('desc'); ?>" type="text" value="<?php echo $desc; ?>" />
		</label></p>
		<p><label for="<?php echo $this->get_field_id('date'); ?>"><?php _e('Datum:'); ?>
			<input class="widefat custom_date" id="<?php echo $this->get_field_id('date'); ?>" name="<?php echo $this->get_field_name('date'); ?>" type="date" value="<?php echo $date; ?>" />
		</label></p>

		
		
<?php
	}

}


function register_sr_event() {
	 return register_widget("sr_event_Widget"); 
	 }
